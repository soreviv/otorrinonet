<?php

/**
 * Punto de entrada principal de la aplicación OtorrinoNet.
 *
 * Este archivo maneja la inicialización de la aplicación, la carga de dependencias,
 * la configuración del entorno y el enrutamiento de las peticiones HTTP.
 */

// Iniciar la sesión para poder usar variables $_SESSION.
session_start();

// Cargar el autoloader de Composer para gestionar las dependencias.
require_once __DIR__ . '/../vendor/autoload.php';

// Importar clases con su namespace para una mejor legibilidad.
use App\Core\Request;
use App\Core\Router;
use Dotenv\Dotenv; // Importar la clase Dotenv

try {
    // Cargar variables de entorno desde el archivo .env.
    $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();

    // Crear una instancia de la clase Request para encapsular la información de la petición.
    $request = new Request();

    // Cargar las definiciones de rutas de la aplicación.
    $router = Router::load(__DIR__ . '/../app/routes.php');

    // Dirigir la petición al controlador y método correspondiente.
    $router->direct($request->uri(), $request->method());
} catch (Throwable $e) {
    // Captura cualquier error o excepción que ocurra durante la ejecución de la aplicación.
    // En un entorno de producción, se debería mostrar una página de error genérica
    // y registrar el error detallado en un log.
    http_response_code(500);
    error_log('Critical Application Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() . "\n" . $e->getTraceAsString());

    // Determinar si estamos en modo de depuración (por ejemplo, a través de una variable de entorno).
    $isDevelopment = ($_ENV['APP_ENV'] ?? 'production') === 'development';

    if ($isDevelopment) {
        // Mostrar detalles del error en desarrollo.
        echo '<h1>Error 500 - Internal Server Error</h1>';
        echo '<p>Se ha producido un error crítico en la aplicación.</p>';
        echo '<pre><strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</pre>';
        echo '<pre><strong>Archivo:</strong> ' . htmlspecialchars($e->getFile()) . ' en la línea ' . $e->getLine() . '</pre>';
        echo '<pre><strong>Stack Trace:</strong><br>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    } else {
        // Mostrar un mensaje de error genérico en producción.
        echo '<h1>Error 500 - Internal Server Error</h1>';
        echo '<p>Lo sentimos, algo salió mal. Por favor, inténtelo de nuevo más tarde.</p>';
    }
}
