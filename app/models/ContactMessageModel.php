<?php

namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

/**
 * Handles database operations related to contact messages.
 */
class ContactMessageModel {
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
     * Saves a new contact message to the database.
     *
     * @param array $data The message data.
     * @return bool True if the message was saved successfully, false otherwise.
     */
    public function create(array $data) {
        $fields = [
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'telefono' => $data['telefono'],
            'asunto' => $data['asunto'],
            'mensaje' => $data['mensaje']
        ];

        $columns = implode(', ', array_keys($fields));
        $placeholders = ':' . implode(', :', array_keys($fields));
        $query = "INSERT INTO contact_messages ($columns) VALUES ($placeholders)";

        try {
            $statement = $this->db->prepare($query);
            foreach ($fields as $key => $value) {
                $statement->bindValue(":$key", $value);
            }
            return $statement->execute();
        } catch (PDOException $e) {
            error_log("Error al guardar mensaje de contacto: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Gets all contact messages, ordered by submission date.
     *
     * @return array An array of all contact messages.
     */
    public function getAllMessages() {
        $query = "SELECT * FROM contact_messages ORDER BY fecha_envio DESC";
        try {
            $statement = $this->db->prepare($query);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener todos los mensajes: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Updates the status of a message.
     *
     * @param int $id The ID of the message.
     * @param string $status The new status of the message.
     * @return bool True if the status was updated successfully, false otherwise.
     */
    public function updateStatus(int $id, string $status): bool {
        $query = "UPDATE contact_messages SET status = :status WHERE id = :id";
        try {
            $statement = $this->db->prepare($query);
            return $statement->execute([':status' => $status, ':id' => $id]);
        } catch (PDOException $e) {
            error_log("Error al actualizar el estado del mensaje: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Gets the number of unread messages.
     *
     * @return int The number of unread messages.
     */
    public function getUnreadMessagesCount() {
        $query = "SELECT COUNT(*) FROM contact_messages WHERE status = 'nuevo'";
        try {
            $statement = $this->db->prepare($query);
            $statement->execute();
            $count = $statement->fetchColumn();
            return $count !== false ? (int)$count : 0;
        } catch (PDOException $e) {
            error_log("Error al contar los mensajes no leídos: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Gets the most recent unread messages.
     *
     * @param int $limit The maximum number of messages to retrieve.
     * @return array An array of the most recent unread messages.
     */
    public function getRecentUnreadMessages(int $limit = 5) {
        $query = "SELECT id, nombre, asunto, fecha_envio
                  FROM contact_messages
                  WHERE status = 'nuevo'
                  ORDER BY fecha_envio DESC
                  LIMIT :limit";
        try {
            $statement = $this->db->prepare($query);
            $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener mensajes recientes no leídos: " . $e->getMessage());
            return [];
        }
    }
}