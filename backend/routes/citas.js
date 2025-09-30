
const express = require('express');
const router = express.Router();
const citasController = require('../controllers/citasController');

// Definir las rutas para las citas
// GET /api/citas
router.get('/', citasController.getAllCitas);

// POST /api/citas
router.post('/', citasController.createCita);

module.exports = router;
