<?php

namespace App\Core;

/**
 * Encapsulates the HTTP request information.
 *
 * Provides a clean, testable way to access request data like the URI,
 * method, and input from $_GET and $_POST, abstracting away the direct
 * use of superglobals.
 */
class Request
{
    /**
     * The request URI.
     * @var string
     */
    protected $uri;

    /**
     * The request method (GET, POST, etc.).
     * @var string
     */
    protected $method;

    /**
     * The parsed GET parameters.
     * @var array
     */
    protected $get;

    /**
     * The parsed POST parameters.
     * @var array
     */
    protected $post;

    /**
     * Constructor.
     *
     * Initializes the Request object by parsing the superglobals.
     */
    public function __construct()
    {
        $this->uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->get = $_GET;
        $this->post = $_POST;
    }

    /**
     * Get the request URI.
     *
     * @return string
     */
    public function uri(): string
    {
        return $this->uri;
    }

    /**
     * Get the request method.
     *
     * @return string
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * Get a specific value from the POST data.
     *
     * @param string $key The key of the POST data to retrieve.
     * @param mixed $default The default value to return if the key is not found.
     * @return mixed
     */
    public function post(string $key, $default = null)
    {
        return $this->post[$key] ?? $default;
    }

    /**
     * Get all POST data.
     *
     * @return array
     */
    public function allPost(): array
    {
        return $this->post;
    }
}
