const db = require('../config/db');
const { verifyHCaptcha } = require('../utils/hcaptcha');

const createMensaje = async (req, res) => {
  // Extraer los datos del formulario y el token de hCaptcha
  const { name: nombre, apellido, telefono, email, mensaje } = req.body; // Corregido: mapear 'name' a 'nombre'
  const hCaptchaToken = req.body['h-captcha-response'];

  // 1. Verificar el token de hCaptcha
  const isHuman = await verifyHCaptcha(hCaptchaToken);
  if (!isHuman) {
    return res.status(403).json({ error: 'Falló la verificación de hCaptcha. Inténtelo de nuevo.' });
  }

  // 2. Validar los campos obligatorios
  if (!nombre || !telefono || !email || !mensaje) {
    return res.status(400).json({ error: 'Los campos nombre, teléfono, email y mensaje son obligatorios.' });
  }

  try {
    // 3. Insertar el nuevo mensaje en la base de datos
    const insertQuery = `
      INSERT INTO mensajes_contacto (nombre, apellido, telefono, email, mensaje)
      VALUES ($1, $2, $3, $4, $5)
      RETURNING id, nombre, fecha_creacion;
    `;
    const values = [nombre, apellido || null, telefono, email, mensaje];
    const result = await db.query(insertQuery, values);

    // Enviar una respuesta de éxito con datos mínimos
    res.status(201).json({
      message: 'Gracias por su mensaje. Nos pondremos en contacto con usted pronto.',
      data: result.rows[0]
    });

  } catch (err) {
    console.error('Error al guardar el mensaje de contacto:', err.stack);
    res.status(500).json({ error: 'Error interno del servidor al procesar su mensaje.' });
  }
};

module.exports = {
  createMensaje,
};