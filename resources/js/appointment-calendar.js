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
