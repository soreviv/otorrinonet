<?php
// Incluir el header. Las variables como $pageTitle, $status, $errors y $old_data
// son pasadas desde AppointmentController->create().
require '_header.php';
?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold mb-4"><?= htmlspecialchars($pageTitle ?? 'Agendar Cita') ?></h1>
        <p class="text-xl">Selecciona la fecha y hora que mejor te convenga</p>
    </div>
</section>

<!-- Sección para mostrar mensajes de estado (éxito o error) -->
<section class="py-8">
    <div class="container mx-auto px-4">
        <?php if (isset($status) && $status): ?>
            <?php if ($status['type'] === 'success'): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-6 rounded-lg shadow-lg" role="alert">
                    <p class="font-bold text-lg"><?= htmlspecialchars($status['message']) ?></p>
                </div>
            <?php else: ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-6 rounded-lg shadow-lg" role="alert">
                    <p class="font-bold text-lg"><?= htmlspecialchars($status['message']) ?></p>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<!-- Agendamiento de Citas -->
<section class="pb-16">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Calendario -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">1. Selecciona Fecha y Hora</h2>
                    <div id="calendar" class="mb-6"></div>
                    <div id="available-times" class="hidden">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Horarios Disponibles</h3>
                        <p class="text-gray-600 mb-4">Fecha seleccionada: <span id="selected-date" class="font-bold"></span></p>
                        <div id="time-slots" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3"></div>
                    </div>
                </div>
            </div>

            <!-- Formulario de Cita -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">2. Datos del Paciente</h2>

                    <form method="POST" action="/agendar-cita" id="appointment-form" class="space-y-4">
                        <?= App\Core\CSRF::getInputField() ?>
                        <input type="hidden" name="fecha_cita" id="fecha_cita" value="<?= htmlspecialchars($old_data['fecha_cita'] ?? '') ?>">
                        <input type="hidden" name="hora_cita" id="hora_cita" value="<?= htmlspecialchars($old_data['hora_cita'] ?? '') ?>">
                        
                        <!-- Nombre -->
                        <div>
                            <label for="nombre" class="block text-gray-700 font-medium mb-2">Nombre Completo <span class="text-red-600">*</span></label>
                            <input type="text" id="nombre" name="nombre" required value="<?= htmlspecialchars($old_data['nombre'] ?? '') ?>"
                                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 <?= isset($errors['nombre']) ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' ?>"
                                   placeholder="Tu nombre completo">
                            <?php if (isset($errors['nombre'])): ?>
                                <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['nombre']) ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-gray-700 font-medium mb-2">Email <span class="text-red-600">*</span></label>
                            <input type="email" id="email" name="email" required value="<?= htmlspecialchars($old_data['email'] ?? '') ?>"
                                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 <?= isset($errors['email']) ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' ?>"
                                   placeholder="correo@ejemplo.com">
                            <?php if (isset($errors['email'])): ?>
                                <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['email']) ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label for="telefono" class="block text-gray-700 font-medium mb-2">Teléfono <span class="text-red-600">*</span></label>
                            <input type="tel" id="telefono" name="telefono" required value="<?= htmlspecialchars($old_data['telefono'] ?? '') ?>"
                                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 <?= isset($errors['telefono']) ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' ?>"
                                   placeholder="55 1234-5678">
                            <?php if (isset($errors['telefono'])): ?>
                                <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['telefono']) ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Tipo de Consulta -->
                        <div>
                            <label for="tipo_consulta" class="block text-gray-700 font-medium mb-2">Tipo de Consulta <span class="text-red-600">*</span></label>
                            <select id="tipo_consulta" name="tipo_consulta" required
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 <?= isset($errors['tipo_consulta']) ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' ?>">
                                <option value="">Seleccione...</option>
                                <option value="primera_vez" <?= ($old_data['tipo_consulta'] ?? '') === 'primera_vez' ? 'selected' : '' ?>>Primera Vez</option>
                                <option value="seguimiento" <?= ($old_data['tipo_consulta'] ?? '') === 'seguimiento' ? 'selected' : '' ?>>Seguimiento</option>
                                <option value="urgencia" <?= ($old_data['tipo_consulta'] ?? '') === 'urgencia' ? 'selected' : '' ?>>Urgencia</option>
                                <option value="valoracion_cirugia" <?= ($old_data['tipo_consulta'] ?? '') === 'valoracion_cirugia' ? 'selected' : '' ?>>Valoración para Cirugía</option>
                            </select>
                            <?php if (isset($errors['tipo_consulta'])): ?>
                                <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['tipo_consulta']) ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Motivo -->
                        <div>
                            <label for="motivo" class="block text-gray-700 font-medium mb-2">Motivo de Consulta</label>
                            <textarea id="motivo" name="motivo" rows="4"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Describe brevemente tu motivo de consulta..."><?= htmlspecialchars($old_data['motivo'] ?? '') ?></textarea>
                        </div>

                        <!-- Resumen de Cita -->
                        <div id="appointment-summary" class="hidden bg-blue-50 p-4 rounded-lg">
                            <h3 class="font-bold text-blue-800 mb-2">Resumen de tu Cita</h3>
                            <div class="text-sm text-blue-700 space-y-1">
                                <p><strong>Fecha:</strong> <span id="summary-date"></span></p>
                                <p><strong>Hora:</strong> <span id="summary-time"></span></p>
                            </div>
                        </div>

                        <!-- hCaptcha -->
                        <div class="h-captcha" data-sitekey="<?= htmlspecialchars($_ENV['HCAPTCHA_SITE_KEY'] ?? '') ?>"></div>
                        <?php if (isset($errors['hcaptcha'])): ?>
                            <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['hcaptcha']) ?></p>
                        <?php endif; ?>

                        <!-- Botón Enviar -->
                        <div>
                            <button type="submit" id="submit-btn" disabled class="w-full bg-gray-400 text-white py-3 rounded-lg font-bold cursor-not-allowed transition duration-300">
                                Selecciona Fecha y Hora
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const availableTimesDiv = document.getElementById('available-times');
    const timeSlotsDiv = document.getElementById('time-slots');
    const selectedDateSpan = document.getElementById('selected-date');
    const fechaCitaInput = document.getElementById('fecha_cita');
    const horaCitaInput = document.getElementById('hora_cita');
    const submitBtn = document.getElementById('submit-btn');
    const appointmentSummary = document.getElementById('appointment-summary');
    const summaryDate = document.getElementById('summary-date');
    const summaryTime = document.getElementById('summary-time');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth' },
        buttonText: { today: 'Hoy' },
        validRange: { start: new Date().toISOString().split('T')[0] },
        dateClick: function(info) {
            const dayOfWeek = new Date(info.dateStr + 'T00:00:00').getDay();
            if (dayOfWeek === 6 || dayOfWeek === 0) { return; }
            fetchAvailableTimes(info.dateStr);
        },
        dayCellClassNames: function(arg) {
            const dayOfWeek = arg.date.getDay();
            if (dayOfWeek === 0 || dayOfWeek === 6) { return ['bg-gray-100', 'cursor-not-allowed']; }
            return [];
        }
    });
    calendar.render();

    async function fetchAvailableTimes(dateStr, preselectedTime = null) {
        selectedDateSpan.textContent = formatDate(dateStr);
        fechaCitaInput.value = dateStr;
        horaCitaInput.value = preselectedTime || '';
        updateSubmitButton();

        availableTimesDiv.classList.remove('hidden');
        timeSlotsDiv.innerHTML = '<p class="text-gray-500">Cargando horarios...</p>';

        try {
            const response = await fetch(`/api/available-times?date=${dateStr}`);
            if (!response.ok) {
                throw new Error('Error al cargar los horarios.');
            }
            const data = await response.json();

            timeSlotsDiv.innerHTML = '';
            if (data.error) {
                throw new Error(data.error);
            }

            if (data.slots.length === 0) {
                timeSlotsDiv.innerHTML = '<p class="text-red-500">No hay horarios disponibles para este día.</p>';
                return;
            }

            data.slots.forEach(time => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'px-4 py-3 border-2 border-blue-300 text-blue-700 rounded-lg hover:bg-blue-600 hover:text-white transition duration-200';
                btn.textContent = time;
                btn.onclick = () => selectTime(btn, time, dateStr);
                timeSlotsDiv.appendChild(btn);

                if (preselectedTime === time) {
                    selectTime(btn, time, dateStr);
                }
            });
        } catch (error) {
            console.error(error);
            timeSlotsDiv.innerHTML = `<p class="text-red-500">${error.message}</p>`;
        }
    }

    function selectTime(button, time, date) {
        document.querySelectorAll('#time-slots button').forEach(btn => {
            btn.classList.remove('bg-blue-600', 'text-white');
        });
        button.classList.add('bg-blue-600', 'text-white');

        horaCitaInput.value = time;
        summaryDate.textContent = formatDate(date);
        summaryTime.textContent = time;
        appointmentSummary.classList.remove('hidden');
        updateSubmitButton();
    }

    function updateSubmitButton() {
        if (fechaCitaInput.value && horaCitaInput.value) {
            submitBtn.disabled = false;
            submitBtn.className = 'w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 rounded-lg font-bold cursor-pointer transition';
            submitBtn.textContent = 'Confirmar Cita';
        } else {
            submitBtn.disabled = true;
            submitBtn.className = 'w-full bg-gray-400 text-white py-3 rounded-lg font-bold cursor-not-allowed transition';
            submitBtn.textContent = 'Selecciona Fecha y Hora';
        }
    }

    function formatDate(dateStr) {
        return new Date(dateStr + 'T00:00:00').toLocaleDateString('es-MX', {
            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
        });
    }

    if (fechaCitaInput.value) {
        fetchAvailableTimes(fechaCitaInput.value, horaCitaInput.value);
    }
});
</script>

<?php require '_footer.php'; ?>
