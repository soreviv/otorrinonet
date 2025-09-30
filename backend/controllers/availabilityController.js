
const db = require('../config/db');

// --- Definición de Horarios ---
const workingHours = {
  1: { start: 16, end: 19 }, // Lunes 4pm-7pm (la hora final es el inicio del último slot)
  2: { start: 16, end: 19 }, // Martes 4pm-7pm
  3: { start: 16, end: 19 }, // Miércoles 4pm-7pm
  4: { start: 10, end: 13 }, // Jueves 10am-1pm
  5: { start: 10, end: 13 }, // Viernes 10am-1pm
};
const appointmentDuration = 60; // en minutos

const getAvailability = async (req, res) => {
  const { date } = req.query; // Espera una fecha en formato YYYY-MM-DD

  if (!date) {
    return res.status(400).json({ error: 'Se requiere una fecha en formato YYYY-MM-DD.' });
  }

  try {
    const targetDate = new Date(date);
    const dayOfWeek = targetDate.getUTCDay(); // Domingo=0, Lunes=1, etc.

    // Validar si es un día laboral
    if (!workingHours[dayOfWeek]) {
      return res.json({ availableSlots: [] }); // No es día laboral, no hay horarios
    }

    // --- Generar todos los slots posibles para el día ---
    const daySchedule = workingHours[dayOfWeek];
    const allSlots = [];
    for (let hour = daySchedule.start; hour < daySchedule.end; hour++) {
        const slot = new Date(targetDate);
        slot.setUTCHours(hour, 0, 0, 0);
        allSlots.push(slot);
    }

    // --- Obtener citas ya agendadas para ese día ---
    const bookedAppointmentsQuery = `
      SELECT fecha_cita FROM citas 
      WHERE DATE(fecha_cita) = $1;
    `;
    const result = await db.query(bookedAppointmentsQuery, [date]);
    const bookedTimes = result.rows.map(row => new Date(row.fecha_cita).getTime());

    // --- Filtrar los slots disponibles ---
    const availableSlots = allSlots.filter(slot => {
        // No permitir agendar en el pasado
        if (slot.getTime() < new Date().getTime()) {
            return false;
        }
        // Verificar si el slot está en la lista de citas agendadas
        return !bookedTimes.includes(slot.getTime());
    });

    // Formatear para la respuesta
    const formattedSlots = availableSlots.map(slot => {
        return slot.toISOString(); // Enviar en formato ISO 8601
    });

    res.status(200).json({ availableSlots: formattedSlots });

  } catch (err) {
    console.error('Error al calcular la disponibilidad:', err.stack);
    res.status(500).json({ error: 'Error interno del servidor' });
  }
};

module.exports = { getAvailability };
