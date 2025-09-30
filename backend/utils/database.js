
const db = require('../config/db');

const initDatabase = async () => {
  try {
    const createTableQuery = `
      CREATE TABLE IF NOT EXISTS citas (
        id SERIAL PRIMARY KEY,
        nombre_paciente VARCHAR(255) NOT NULL,
        fecha_cita TIMESTAMP NOT NULL,
        motivo TEXT,
        fecha_creacion TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP
      );
    `;
    await db.query(createTableQuery);
    console.log('Tabla "citas" verificada/creada exitosamente.');
  } catch (err) {
    console.error('Error al inicializar la base de datos:', err.stack);
    process.exit(1); // Termina el proceso si no se puede inicializar la DB
  }
};

module.exports = { initDatabase };
