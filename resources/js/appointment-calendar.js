import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import axios from 'axios';

document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    const slotsContainer = document.getElementById('slots-container');
    const slotsGrid = document.getElementById('slots-grid');
    const dateInput = document.getElementById('date');
    const timeInput = document.getElementById('time');
    const displayEl = document.getElementById('selected-appointment-display');
    const submitBtn = document.getElementById('submit-btn');

    const calendar = new Calendar(calendarEl, {
        plugins: [ dayGridPlugin, interactionPlugin ],
        initialView: 'dayGridMonth',
        selectable: true,
        validRange: {
            start: new Date().toISOString().split('T')[0] // Disable past dates
        },
        dateClick: function(info) {
            // Highlight selected date visually in calendar (optional or handled by FullCalendar selection)
            handleDateSelection(info.dateStr);
        }
    });

    calendar.render();

    /**
     * Maneja la selección de una fecha en el calendario y carga los intervalos de tiempo disponibles para esa fecha.
     *
     * Restablece la zona de slots y el formulario, actualiza la vista con la fecha seleccionada, desactiva el botón de envío
     * y solicita al servidor los horarios disponibles; al recibir la respuesta renderiza los slots o muestra un mensaje de error.
     * @param {string} dateStr - Fecha seleccionada en formato ISO simple `YYYY-MM-DD`.
     */
    function handleDateSelection(dateStr) {
        // Reset slots and form
        slotsGrid.innerHTML = '<p class="text-gray-500 col-span-3">Loading slots...</p>';
        slotsContainer.classList.remove('hidden');
        timeInput.value = '';
        dateInput.value = dateStr;
        updateDisplay(dateStr, null);
        submitBtn.disabled = true;

        // Fetch slots
        axios.get(window.slotsRoute, {
            params: { date: dateStr }
        })
        .then(response => {
            const slots = response.data.slots;
            renderSlots(slots, dateStr);
        })
        .catch(error => {
            console.error('Error fetching slots:', error);
            slotsGrid.innerHTML = '<p class="text-red-500 col-span-3">Error loading slots. Please try again.</p>';
        });
    }

    /**
     * Muestra en la interfaz los botones correspondientes a los huecos horarios disponibles para una fecha.
     *
     * Si no hay huecos, muestra un mensaje indicándolo; si los hay, crea un botón seleccionable por cada hora
     * que permite elegir esa hora para la fecha provista.
     *
     * @param {string[]} slots - Array de horas disponibles (por ejemplo "09:30", "14:00").
     * @param {string} dateStr - Fecha asociada a los huecos en formato ISO `YYYY-MM-DD`.
     */
    function renderSlots(slots, dateStr) {
        slotsGrid.innerHTML = '';

        if (slots.length === 0) {
            slotsGrid.innerHTML = '<p class="text-gray-500 col-span-3">No available slots for this date.</p>';
            return;
        }

        slots.forEach(time => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'py-2 px-4 bg-gray-100 hover:bg-blue-100 text-gray-800 font-semibold rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors';
            btn.textContent = time;
            btn.onclick = () => selectSlot(btn, dateStr, time);
            slotsGrid.appendChild(btn);
        });
    }

    /**
     * Marca un hueco horario como seleccionado y actualiza el estado del formulario y la interfaz.
     *
     * Actualiza visualmente el botón de hueco seleccionado, establece el valor del campo de tiempo,
     * habilita el botón de envío y actualiza el resumen mostrado con la fecha y hora elegidas.
     *
     * @param {HTMLButtonElement} btn - El botón del hueco que se ha seleccionado.
     * @param {string} dateStr - Fecha seleccionada en formato de cadena (por ejemplo, "YYYY-MM-DD").
     * @param {string} time - Hora seleccionada en formato de cadena (por ejemplo, "HH:mm" o etiqueta visible).
     */
    function selectSlot(btn, dateStr, time) {
        // Remove active class from all buttons
        const allBtns = slotsGrid.querySelectorAll('button');
        allBtns.forEach(b => {
            b.classList.remove('bg-blue-500', 'text-white', 'hover:bg-blue-600');
            b.classList.add('bg-gray-100', 'text-gray-800', 'hover:bg-blue-100');
        });

        // Add active class to clicked button
        btn.classList.remove('bg-gray-100', 'text-gray-800', 'hover:bg-blue-100');
        btn.classList.add('bg-blue-500', 'text-white', 'hover:bg-blue-600');

        // Update form inputs
        timeInput.value = time;
        submitBtn.disabled = false;

        updateDisplay(dateStr, time);
    }

    /**
     * Actualiza el texto y el estilo del elemento de resumen según la fecha y la hora seleccionadas.
     * @param {string|null|undefined} date - Fecha seleccionada en formato legible por el usuario; si no hay fecha, proporcionar `null` o `undefined`.
     * @param {string|null|undefined} time - Hora seleccionada en formato legible por el usuario; si no hay hora, proporcionar `null` o `undefined`.
     */
    function updateDisplay(date, time) {
        if (date && time) {
            displayEl.textContent = `${date} at ${time}`;
            displayEl.classList.remove('italic', 'text-gray-600');
            displayEl.classList.add('font-bold', 'text-blue-600');
        } else if (date) {
            displayEl.textContent = `${date} (Select a time)`;
            displayEl.classList.add('italic', 'text-gray-600');
            displayEl.classList.remove('font-bold', 'text-blue-600');
        } else {
            displayEl.textContent = 'Please select a date and time from the calendar.';
            displayEl.classList.add('italic', 'text-gray-600');
            displayEl.classList.remove('font-bold', 'text-blue-600');
        }
    }
});