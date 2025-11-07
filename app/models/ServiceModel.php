<?php

namespace App\Models;

use App\Core\Database;
use PDO;

/**
 * Handles database operations related to services.
 */
class ServiceModel {
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
     * Gets all active services from the database.
     *
     * @return array An array of all active services.
     */
    public function getAllActiveServices() {
        try {
            $query = "SELECT nombre, descripcion, categoria, precio FROM services WHERE activo = TRUE ORDER BY categoria, nombre";
            $statement = $this->db->prepare($query);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error al obtener servicios: " . $e->getMessage());
            return [];
        }
    }
}