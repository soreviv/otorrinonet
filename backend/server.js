
require('dotenv').config();
const express = require('express');
const cors = require('cors');
const helmet = require('helmet');

const { initDatabase } = require('./utils/database');
const citasRoutes = require('./routes/citas');
const availabilityRoutes = require('./routes/availability');

const app = express();
const PORT = process.env.PORT || 3000;

// Middlewares de seguridad y configuración
app.use(helmet());
app.use(cors());
app.use(express.json()); // Para parsear body de peticiones como JSON

// Rutas de la API
app.use('/api/citas', citasRoutes);
app.use('/api/availability', availabilityRoutes);

// Ruta raíz de bienvenida
app.get('/', (req, res) => {
  res.send('Backend de OtorrinoNet funcionando.');
});

// Función para iniciar el servidor
const startServer = async () => {
  try {
    // Asegurarse de que la base de datos y las tablas están listas
    await initDatabase();

    app.listen(PORT, () => {
      console.log(`Servidor escuchando en el puerto ${PORT}`);
    });
  } catch (error) {
    console.error('No se pudo iniciar el servidor:', error);
    process.exit(1);
  }
};

// Iniciar el servidor
startServer();
