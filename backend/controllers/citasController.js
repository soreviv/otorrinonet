
const db = require('../config/db');

const getAllCitas = async (req, res) => {
  try {
    const result = await db.query('SELECT * FROM citas ORDER BY fecha_cita DESC');
    res.status(200).json(result.rows);
  } catch (err) {
    console.error('Error al obtener las citas:', err.stack);
    res.status(500).json({ error: 'Error interno del servidor' });
  }
};

const createCita = async (req, res) => {
  const { nombre_paciente, fecha_cita, motivo } = req.body;

  if (!nombre_paciente || !fecha_cita) {
    return res.status(400).json({ error: 'Los campos nombre_paciente y fecha_cita son obligatorios.' });
  }

  try {
    // --- Validación para evitar doble agendamiento (double booking) ---
    const checkQuery = 'SELECT id FROM citas WHERE fecha_cita = $1';
    const existingCita = await db.query(checkQuery, [fecha_cita]);

    if (existingCita.rows.length > 0) {
      return res.status(409).json({ error: 'El horario seleccionado ya no está disponible.' });
    }

    // --- Insertar la nueva cita ---
    const insertQuery = `
      INSERT INTO citas (nombre_paciente, fecha_cita, motivo)
      VALUES ($1, $2, $3)
      RETURNING *;
    `;
    const values = [nombre_paciente, fecha_cita, motivo || null];
    const result = await db.query(insertQuery, values);

    res.status(201).json(result.rows[0]);

  } catch (err) {
    console.error('Error al crear la cita:', err.stack);
    res.status(500).json({ error: 'Error interno del servidor' });
  }
};

module.exports = {
  getAllCitas,
  createCita,
};
