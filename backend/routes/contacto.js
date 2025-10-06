const express = require('express');
const router = express.Router();
const contactoController = require('../controllers/contactoController');

// Ruta para crear un nuevo mensaje de contacto
// POST /api/contacto
router.post('/', contactoController.createMensaje);

module.exports = router;