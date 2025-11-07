<?php

namespace App\Controllers;

use App\Models\AppointmentModel;
use App\Models\ContactMessageModel;

/**
 * Handles the administration dashboard and related actions.
 */
class AdminController extends BaseController {
    /**
     * The constructor checks if the user is authenticated
     * before allowing access to any method of this controller.
     */
    public function __construct() {
        if (!isset($_SESSION['user'])) {
            header('Location: /admin/login');
            exit;
        }
    }

    /**
     * Displays the main dashboard of the administration panel.
     * @return void
     */
    public function dashboard() {
        $appointmentModel = new AppointmentModel();
        $contactMessageModel = new ContactMessageModel();

        $today = date('Y-m-d');

        $data = [
            'pageTitle' => 'Dashboard - Administración',
            'appointmentsToday' => $appointmentModel->getAppointmentsForDate($today),
            'pendingAppointmentsCount' => $appointmentModel->getPendingAppointmentsCount(),
            'unreadMessagesCount' => $contactMessageModel->getUnreadMessagesCount(),
            'appointmentCounts' => $appointmentModel->getAppointmentCountsForLastDays(7),
            'recentMessages' => $contactMessageModel->getRecentUnreadMessages(5),
        ];

        echo $this->renderView('admin/dashboard', $data);
    }

    /**
     * Displays the list of appointments.
     * @return void
     */
    public function listAppointments() {
        $appointmentModel = new AppointmentModel();
        $appointments = $appointmentModel->getAllAppointments();

        $data = [
            'pageTitle' => 'Citas Agendadas - Administración',
            'appointments' => $appointments
        ];

        echo $this->renderView('admin/appointments', $data);
    }

    /**
     * Displays the list of contact messages.
     * @return void
     */
    public function listMessages() {
        $contactMessageModel = new ContactMessageModel();
        $messages = $contactMessageModel->getAllMessages();

        $data = [
            'pageTitle' => 'Mensajes de Contacto - Administración',
            'messages' => $messages
        ];

        echo $this->renderView('admin/messages', $data);
    }

    /**
     * Updates the status of an appointment.
     * @return void
     */
    public function updateAppointmentStatus() {
        $id = $_POST['id'] ?? null;
        $status = $_POST['status'] ?? null;

        if ($id && $status) {
            $appointmentModel = new AppointmentModel();
            $appointmentModel->updateStatus((int)$id, $status);
        }

        header('Location: /admin/appointments');
        exit;
    }

    /**
     * Updates the status of a contact message.
     * @return void
     */
    public function updateMessageStatus() {
        $id = $_POST['id'] ?? null;
        $status = $_POST['status'] ?? null;

        if ($id && $status) {
            $contactMessageModel = new ContactMessageModel();
            $contactMessageModel->updateStatus((int)$id, $status);
        }

        header('Location: /admin/messages');
        exit;
    }
}