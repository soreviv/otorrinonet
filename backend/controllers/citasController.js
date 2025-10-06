const db = require('../config/db');
const { verifyHCaptcha } = require('../utils/hcaptcha');

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
  // Extraer los datos del formulario y el token de hCaptcha
  const { name: nombre, apellido, telefono, email, fecha_cita, motivo } = req.body; // Corregido: mapear 'name' a 'nombre'
  const hCaptchaToken = req.body['h-captcha-response'];

  // 1. Verificar el token de hCaptcha
  const isHuman = await verifyHCaptcha(hCaptchaToken);
  if (!isHuman) {
    return res.status(403).json({ error: 'Falló la verificación de hCaptcha. Inténtelo de nuevo.' });
  }

  // 2. Validar los campos obligatorios
  if (!nombre || !telefono || !email || !fecha_cita) {
    return res.status(400).json({ error: 'Los campos nombre, teléfono, email y fecha de la cita son obligatorios.' });
  }

  try {
    // 3. Validación para evitar doble agendamiento (double booking)
    const checkQuery = 'SELECT id FROM citas WHERE fecha_cita = $1';
    const existingCita = await db.query(checkQuery, [fecha_cita]);

    if (existingCita.rows.length > 0) {
      return res.status(409).json({ error: 'El horario seleccionado ya no está disponible.' });
    }

    // 4. Insertar la nueva cita con los campos actualizados
    const insertQuery = `
      INSERT INTO citas (nombre, apellido, telefono, email, fecha_cita, motivo)
      VALUES ($1, $2, $3, $4, $5, $6)
      RETURNING *;
    `;
    const values = [nombre, apellido || null, telefono, email, fecha_cita, motivo || null];
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