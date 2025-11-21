<?php

namespace App\Core;

use Exception;

/**
 * Handles routing of HTTP requests to controllers and actions.
 */
class Router {
    /**
     * @var array Stores all registered routes.
     */
    protected $routes = [
        'GET' => [],
        'POST' => []
    ];

    /**
     * Loads the routes from a file.
     *
     * @param string $file The path to the file containing the routes.
     * @return static An instance of the Router class.
     */
    public static function load($file)
    {
        $router = new static;
        require $file;
        return $router;
    }

    /**
     * Registers a GET route.
     *
     * @param string $uri The URI for the route.
     * @param string $handler The handler for the route, which can be a 'Controller@method' string or a view file.
     * @return void
     */
    public function get($uri, $handler)
    {
        $this->routes['GET'][$uri] = $handler;
    }

    /**
     * Registers a POST route.
     *
     * @param string $uri The URI for the route.
     * @param string $handler The handler for the route, in the format 'Controller@method'.
     * @return void
     */
    public function post($uri, $handler)
    {
        $this->routes['POST'][$uri] = $handler;
    }

    /**
     * Directs the request to the appropriate route and controller.
     *
     * @param string $uri The requested URI.
     * @param string $requestType The HTTP request type (GET or POST).
     * @return mixed The result of the controller action.
     */
    public function direct($uri, $requestType)
    {
        if (array_key_exists($uri, $this->routes[$requestType])) {
            $handler = $this->routes[$requestType][$uri];

            if (strpos($handler, '@') !== false) {
                return $this->callAction(
                    ...explode('@', $handler)
                );
            }

            return $this->loadView($handler);
        }

        $this->abort();
    }

    /**
     * Calls a method on a controller.
     *
     * @param string $controller The name of the controller.
     * @param string $action The name of the action (method).
     * @return mixed The result of the controller action.
     * @throws Exception If the controller or action is not found.
     */
    protected function callAction($controller, $action)
    {
        $controller = "App\\Controllers\\{$controller}";

        if (!class_exists($controller)) {
            throw new Exception("Controlador no encontrado: {$controller}");
        }

        $controllerInstance = new $controller;

        if (!method_exists($controllerInstance, $action)) {
            throw new Exception(
                "El método {$action} no está definido en el controlador {$controller}."
            );
        }

        return $controllerInstance->$action();
    }

    /**
     * Loads a view file.
     * (Note: This is legacy logic and its use is not recommended).
     *
     * @param string $view The name of the view file.
     * @return void
     * @throws Exception If the view file is not found.
     */
    protected function loadView($view)
    {
        $viewPath = __DIR__ . "/../views/{$view}";
        if (file_exists($viewPath)) {
            require $viewPath;
            return;
        }

        throw new Exception("Vista no encontrada: {$viewPath}");
    }

    /**
     * Aborts the request and shows an error page.
     *
     * @param int $code The HTTP status code to use.
     * @return void
     */
    protected function abort($code = 404)
    {
        http_response_code($code);

        echo "Error {$code}: Página no encontrada";

        die();
    }
}