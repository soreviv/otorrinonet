<?php

// 1. Carga el autoloader de Composer. Esto permite cargar todas las clases del framework
// y las dependencias (vendor).
require __DIR__.'/../vendor/autoload.php';

// 2. Arranca la aplicación (Application).
// Laravel utiliza un Factory Pattern para crear la instancia de la aplicación ($app).
$app = require_once __DIR__.'/../bootstrap/app.php';

// 3. Obtiene el Kernel HTTP (el corazón de la aplicación).
// El Kernel maneja la petición web, pasa la petición a través de los middlewares
// y llama al router para encontrar la acción del controlador correcta.
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// 4. Maneja la Petición y obtiene la Respuesta.
// El Kernel toma la Petición HTTP global (Symfony Request) y devuelve la Respuesta HTTP.
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
)->send();

// 5. Finaliza la Petición.
// Esto realiza cualquier tarea de limpieza o tareas posteriores a la respuesta (post-response)
// definidas por el Kernel.
$kernel->terminate($request, $response);
// 1. Carga el autoloader de Composer. Esto permite cargar todas las clases del framework
// y las dependencias (vendor).
