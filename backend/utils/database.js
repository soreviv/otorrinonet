const db = require('../config/db');

const initDatabase = async () => {
  try {
    // --- Tabla de Citas ---
    // 1. Asegurar que la tabla base exista con su nombre original para no romper instalaciones existentes.
    const createCitasTableQuery = `
      CREATE TABLE IF NOT EXISTS citas (
        id SERIAL PRIMARY KEY,
        nombre_paciente VARCHAR(255) NOT NULL,
        fecha_cita TIMESTAMP NOT NULL,
        motivo TEXT,
        fecha_creacion TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP
      );
    `;
    await db.query(createCitasTableQuery);
    console.log('Tabla "citas" base verificada/creada.');

    // 2. Modificar la tabla para la nueva estructura de forma idempotente (segura para re-ejecutar)
    // Renombrar 'nombre_paciente' a 'nombre' si la columna original todavía existe
    const renameRes = await db.query("SELECT 1 FROM information_schema.columns WHERE table_name = 'citas' AND column_name = 'nombre_paciente'");
    if (renameRes.rowCount > 0) {
        await db.query('ALTER TABLE citas RENAME COLUMN nombre_paciente TO nombre;');
        console.log('Columna "nombre_paciente" renombrada a "nombre".');
    }

    // Añadir las nuevas columnas solo si no existen
    await db.query('ALTER TABLE citas ADD COLUMN IF NOT EXISTS apellido VARCHAR(255);');
    await db.query('ALTER TABLE citas ADD COLUMN IF NOT EXISTS telefono VARCHAR(50);');
    await db.query('ALTER TABLE citas ADD COLUMN IF NOT EXISTS email VARCHAR(255);');
    console.log('Columnas "apellido", "telefono", y "email" verificadas/añadidas a "citas".');

    // --- Tabla de Mensajes de Contacto ---
    // Crear la tabla para los mensajes del formulario de contacto
    const createContactoTableQuery = `
      CREATE TABLE IF NOT EXISTS mensajes_contacto (
        id SERIAL PRIMARY KEY,
        nombre VARCHAR(255) NOT NULL,
        apellido VARCHAR(255),
        telefono VARCHAR(50) NOT NULL,
        email VARCHAR(255) NOT NULL,
        mensaje TEXT NOT NULL,
        leido BOOLEAN DEFAULT FALSE,
        fecha_creacion TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP
      );
    `;
    await db.query(createContactoTableQuery);
    console.log('Tabla "mensajes_contacto" verificada/creada exitosamente.');

  } catch (err) {
    console.error('Error al inicializar la base de datos:', err.stack);
    process.exit(1); // Termina el proceso si no se puede inicializar la DB
  }
};

module.exports = { initDatabase };