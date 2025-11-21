<?php

namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

/**
 * Handles database operations related to appointments.
 */
class AppointmentModel {
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
     * Saves a new appointment to the database.
     *
     * @param array $data The appointment data.
     * @return bool True if the appointment was saved successfully, false otherwise.
     */
    public function create(array $data) {
        $fields = [
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'telefono' => $data['telefono'],
            'fecha_cita' => $data['fecha_cita'],
            'hora_cita' => $data['hora_cita'],
            'tipo_consulta' => $data['tipo_consulta'],
            'motivo' => $data['motivo']
        ];

        $columns = implode(', ', array_keys($fields));
        $placeholders = ':' . implode(', :', array_keys($fields));
        $query = "INSERT INTO appointments ($columns) VALUES ($placeholders)";

        try {
            $statement = $this->db->prepare($query);

            foreach ($fields as $key => $value) {
                $statement->bindValue(":$key", $value);
            }

            return $statement->execute();
        } catch (PDOException $e) {
            error_log("Error al crear la cita: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Checks if a time slot is available.
     *
     * @param string $fecha The date of the appointment.
     * @param string $hora The time of the appointment.
     * @return bool True if the time slot is available, false otherwise.
     */
    public function isTimeSlotAvailable(string $fecha, string $hora): bool {
        $query = "SELECT COUNT(*) FROM appointments
                  WHERE fecha_cita = :fecha_cita
                  AND hora_cita = :hora_cita
                  AND status != 'cancelada'";

        try {
            $statement = $this->db->prepare($query);
            $statement->execute([
                ':fecha_cita' => $fecha,
                ':hora_cita' => $hora
            ]);

            return $statement->fetchColumn() == 0;
        } catch (PDOException $e) {
            error_log("Error al verificar disponibilidad: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Gets all appointments, ordered by date and time.
     *
     * @return array An array of all appointments.
     */
    public function getAllAppointments() {
        $query = "SELECT * FROM appointments ORDER BY fecha_cita DESC, hora_cita ASC";
        try {
            $statement = $this->db->prepare($query);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener todas las citas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Updates the status of an appointment.
     *
     * @param int $id The ID of the appointment.
     * @param string $status The new status of the appointment.
     * @return bool True if the status was updated successfully, false otherwise.
     */
    public function updateStatus(int $id, string $status): bool {
        $query = "UPDATE appointments SET status = :status WHERE id = :id";
        try {
            $statement = $this->db->prepare($query);
            return $statement->execute([':status' => $status, ':id' => $id]);
        } catch (PDOException $e) {
            error_log("Error al actualizar el estado de la cita: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Gets the appointments for a specific date.
     *
     * @param string $date The date in Y-m-d format.
     * @return array An array of appointments for the specified date.
     */
    public function getAppointmentsForDate(string $date) {
        $query = "SELECT hora_cita, nombre, tipo_consulta, status
                  FROM appointments
                  WHERE fecha_cita = :date
                  ORDER BY hora_cita ASC";
        try {
            $statement = $this->db->prepare($query);
            $statement->execute([':date' => $date]);
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener las citas para la fecha {$date}: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Gets the number of pending appointments.
     *
     * @return int The number of pending appointments.
     */
    public function getPendingAppointmentsCount() {
        $query = "SELECT COUNT(*) FROM appointments WHERE status = 'pendiente'";
        try {
            $statement = $this->db->prepare($query);
            $statement->execute();
            $count = $statement->fetchColumn();
            return $count !== false ? (int)$count : 0;
        } catch (PDOException $e) {
            error_log("Error al contar las citas pendientes: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Calculates the available time slots for a specific date.
     *
     * @param string $date The date in Y-m-d format.
     * @return array An array of available time slots.
     */
    public function getAvailableSlotsForDate(string $date) {
        try {
            $dayOfWeek = date('w', strtotime($date));

            $scheduleQuery = "SELECT hora_inicio, hora_fin FROM schedule_config WHERE dia_semana = :dayOfWeek AND activo = TRUE";
            $scheduleStmt = $this->db->prepare($scheduleQuery);
            $scheduleStmt->execute([':dayOfWeek' => $dayOfWeek]);
            $schedules = $scheduleStmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($schedules)) {
                return [];
            }

            $appointmentsQuery = "SELECT hora_cita FROM appointments WHERE fecha_cita = :date AND status != 'cancelada'";
            $appointmentsStmt = $this->db->prepare($appointmentsQuery);
            $appointmentsStmt->execute([':date' => $date]);
            $bookedSlots = $appointmentsStmt->fetchAll(PDO::FETCH_COLUMN, 0);

            $availableSlots = [];
            $slotDuration = 30;

            foreach ($schedules as $schedule) {
                $start = new \DateTime($schedule['hora_inicio']);
                $end = new \DateTime($schedule['hora_fin']);

                while ($start < $end) {
                    $slot = $start->format('H:i:s');
                    if (!in_array($slot, $bookedSlots)) {
                        $availableSlots[] = $start->format('H:i');
                    }
                    $start->add(new \DateInterval('PT' . $slotDuration . 'M'));
                }
            }

            return $availableSlots;
        } catch (\Exception $e) {
            error_log("Error al calcular horarios disponibles: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Gets the count of appointments for the last N days.
     *
     * @param int $days The number of days to look back.
     * @return array An associative array with dates as keys and counts as values.
     */
    public function getAppointmentCountsForLastDays(int $days = 7) {
        $counts = [];
        $date = new \DateTime();
        $date->modify('-' . ($days - 1) . ' days');

        for ($i = 0; $i < $days; $i++) {
            $d = $date->format('Y-m-d');
            $counts[$d] = 0;
            $date->modify('+1 day');
        }

        $startDate = array_key_first($counts);
        $endDate = array_key_last($counts);

        $query = "SELECT DATE(fecha_cita) as appointment_date, COUNT(*) as count
                  FROM appointments
                  WHERE fecha_cita BETWEEN :startDate AND :endDate
                  GROUP BY DATE(fecha_cita)";

        try {
            $statement = $this->db->prepare($query);
            $statement->execute([':startDate' => $startDate, ':endDate' => $endDate]);
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);

            foreach ($results as $row) {
                $counts[$row['appointment_date']] = (int)$row['count'];
            }

            return $counts;
        } catch (PDOException $e) {
            error_log("Error al obtener el recuento de citas por día: " . $e->getMessage());
            return $counts; // Return initialized counts on error
        }
    }
}