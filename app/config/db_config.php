<?php
// Este archivo se puede usar como fallback o para configuraciones específicas si no se usa .env
// Sin embargo, recomendamos usar .env y la clase Database para la gestión de conexiones.

// Configuración de la base de datos (ejemplo, se recomienda usar variables de entorno)
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '5432');
define('DB_NAME', 'otorrinonet_db');
define('DB_USER', 'drviverosorl');
define('DB_PASS', 'your_strong_password');

// Opcional: Configuración de PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];