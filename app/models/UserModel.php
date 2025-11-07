<?php

namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

/**
 * Handles database operations related to users.
 */
class UserModel {
    /**
     * @var PDO The database connection object.
     */
    private $db;

    /**
     * The constructor gets the database connection instance.
     */
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Finds a user by their username.
     *
     * @param string $username The username to search for.
     * @return array|false The user's data if found, or false if not.
     */
    public function findByUsername(string $username) {
        $query = "SELECT id, username, password_hash, rol FROM users WHERE username = :username AND activo = TRUE";

        try {
            $statement = $this->db->prepare($query);
            $statement->execute([':username' => $username]);
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al buscar usuario: " . $e->getMessage());
            return false;
        }
    }
}