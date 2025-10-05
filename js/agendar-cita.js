
document.addEventListener('DOMContentLoaded', () => {
    const calendarContainer = document.getElementById('calendar-container');
    const slotsContainer = document.getElementById('slots-container');
    const bookingFormContainer = document.getElementById('booking-form-container');
    const confirmationMessage = document.getElementById('confirmation-message');

    let today = new Date();
    let currentMonth = today.getMonth();
    let currentYear = today.getFullYear();
    let selectedDate = null;
    let selectedSlot = null;

    function renderCalendar(month, year) {
        calendarContainer.innerHTML = ''; // Limpiar calendario
        slotsContainer.innerHTML = '<p>Seleccione una fecha para ver los horarios disponibles.</p>';
        bookingFormContainer.style.display = 'none';
        confirmationMessage.style.display = 'none';

        const firstDay = new Date(year, month).getDay();
        const daysInMonth = 32 - new Date(year, month, 32).getDate();

        let header = document.createElement('div');
        header.classList.add('calendar-header');
        header.innerHTML = `
            <button id="prev-month">&lt;</button>
            <h2>${new Date(year, month).toLocaleString('es-ES', { month: 'long', year: 'numeric' })}</h2>
            <button id="next-month">&gt;</button>
        `;
        calendarContainer.appendChild(header);

        let daysGrid = document.createElement('div');
        daysGrid.classList.add('calendar-grid');
        const daysOfWeek = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
        daysOfWeek.forEach(day => {
            let dayLabel = document.createElement('div');
            dayLabel.classList.add('calendar-day-label');
            dayLabel.innerText = day;
            daysGrid.appendChild(dayLabel);
        });

        for (let i = 0; i < firstDay; i++) {
            let emptyCell = document.createElement('div');
            daysGrid.appendChild(emptyCell);
        }

        for (let i = 1; i <= daysInMonth; i++) {
            let dayCell = document.createElement('div');
            dayCell.classList.add('calendar-day');
            dayCell.innerText = i;

            const cellDate = new Date(year, month, i);
            if (cellDate.setHours(0,0,0,0) < today.setHours(0,0,0,0)) {
                dayCell.classList.add('past-day');
            } else {
                dayCell.addEventListener('click', () => {
                    document.querySelectorAll('.calendar-day.selected').forEach(d => d.classList.remove('selected'));
                    dayCell.classList.add('selected');
                    selectedDate = new Date(year, month, i);
                    fetchAvailability(selectedDate);
                });
            }
            daysGrid.appendChild(dayCell);
        }

        calendarContainer.appendChild(daysGrid);

        document.getElementById('prev-month').addEventListener('click', () => {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            renderCalendar(currentMonth, currentYear);
        });

        document.getElementById('next-month').addEventListener('click', () => {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            renderCalendar(currentMonth, currentYear);
        });
    }

    async function fetchAvailability(date) {
        slotsContainer.innerHTML = '<p>Cargando horarios...</p>';
        bookingFormContainer.style.display = 'none';

        // Corrección: Formatear la fecha en la zona horaria local para evitar errores de un día.
        const year = date.getFullYear();
        const month = (date.getMonth() + 1).toString().padStart(2, '0'); // Meses son 0-indexados
        const day = date.getDate().toString().padStart(2, '0');
        const dateString = `${year}-${month}-${day}`; // Formato YYYY-MM-DD

        try {
            const response = await fetch(`/api/availability?date=${dateString}`);
            if (!response.ok) throw new Error('Error al cargar la disponibilidad');
            const data = await response.json();
            renderSlots(data.availableSlots);
        } catch (error) {
            console.error(error);
            slotsContainer.innerHTML = '<p class="error">No se pudo cargar la disponibilidad. Intente de nuevo.</p>';
        }
    }

    function renderSlots(slots) {
        slotsContainer.innerHTML = '';
        if (slots.length === 0) {
            slotsContainer.innerHTML = '<p>No hay horarios disponibles para este día.</p>';
            return;
        }

        let title = document.createElement('h3');
        title.innerText = `Horarios para el ${selectedDate.toLocaleDateString('es-ES', { weekday: 'long', day: 'numeric', month: 'long' })}`;
        slotsContainer.appendChild(title);

        let slotsGrid = document.createElement('div');
        slotsGrid.classList.add('slots-grid');

        slots.forEach(slotISO => {
            const slotDate = new Date(slotISO);
            let slotButton = document.createElement('button');
            slotButton.classList.add('slot');
            slotButton.innerText = slotDate.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit', hour12: false });
            slotButton.addEventListener('click', () => {
                selectedSlot = slotISO;
                document.querySelectorAll('.slot.selected').forEach(s => s.classList.remove('selected'));
                slotButton.classList.add('selected');
                showBookingForm();
            });
            slotsGrid.appendChild(slotButton);
        });
        slotsContainer.appendChild(slotsGrid);
    }

    function showBookingForm() {
        bookingFormContainer.style.display = 'block';
        document.getElementById('booking-time').innerText = new Date(selectedSlot).toLocaleString('es-ES', {
            weekday: 'long', day: 'numeric', month: 'long', hour: '2-digit', minute: '2-digit'
        });
    }

    document.getElementById('booking-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const name = document.getElementById('name').value;
        const reason = document.getElementById('reason').value;

        if (!name || !selectedSlot) {
            alert('Por favor, complete su nombre y seleccione un horario.');
            return;
        }

        try {
            const response = await fetch('/api/citas', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ 
                    nombre_paciente: name, 
                    fecha_cita: selectedSlot, 
                    motivo: reason 
                })
            });

            if (response.status === 409) {
                throw new Error('El horario seleccionado acaba de ser ocupado. Por favor, elija otro.');
            }
            if (!response.ok) {
                throw new Error('Error al agendar la cita.');
            }

            const newCita = await response.json();
            showConfirmation(newCita);

        } catch (error) {
            console.error(error);
            alert(error.message);
            // Opcional: volver a cargar la disponibilidad para reflejar el cambio
            fetchAvailability(selectedDate);
        }
    });

    function showConfirmation(cita) {
        calendarContainer.innerHTML = '';
        slotsContainer.innerHTML = '';
        bookingFormContainer.style.display = 'none';
        confirmationMessage.style.display = 'block';
        document.getElementById('conf-name').innerText = cita.nombre_paciente;
        document.getElementById('conf-time').innerText = new Date(cita.fecha_cita).toLocaleString('es-ES', {
            weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'
        });
    }

    renderCalendar(currentMonth, currentYear);
});
