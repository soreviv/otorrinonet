<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * Represents a singleton database connection.
 */
class Database
{
    /**
     * @var Database|null The single instance of the Database class.
     */
    private static $instance = null;

    /**
     * @var PDO The PDO connection object.
     */
    private $connection;

    /**
     * Private constructor to prevent direct creation of object.
     * Connects to the database using credentials from environment variables.
     * @throws PDOException If the database connection fails.
     */
    private function __construct()
    {
        $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
        $port = $_ENV['DB_PORT'] ?? '5432';
        $dbname = $_ENV['DB_DATABASE'] ?? 'otorrinonet_db';
        $username = $_ENV['DB_USERNAME'] ?? 'drviverosorl';
        $password = $_ENV['DB_PASSWORD'] ?? 'your_strong_password';

        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

        try {
            $this->connection = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            // En lugar de detener la aplicación, lanzamos la excepción
            // para que pueda ser manejada por un gestor de errores global.
            throw new PDOException("Error de conexión a la base de datos: " . $e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * Gets the single instance of the Database class.
     *
     * @return Database The single instance of the Database class.
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Gets the PDO connection object.
     *
     * @return PDO The PDO connection object.
     */
    public function getConnection()
    {
        return $this->connection;
    }
}