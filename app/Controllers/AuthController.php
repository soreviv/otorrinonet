<?php

namespace App\Controllers;

use App\Core\Request;
use App\Models\UserModel;

/**
 * Handles user authentication, including login and logout.
 */
class AuthController extends BaseController {
    /**
     * Displays the login form.
     * @return void
     */
    public function showLoginForm() {
        $status = $_SESSION['status'] ?? null;
        unset($_SESSION['status']);

        $data = [
            'pageTitle' => 'Iniciar Sesión - Administración',
            'status' => $status
        ];

        echo $this->renderView('admin/login', $data);
    }

    /**
     * Processes the login form submission.
     * @return void
     */
    public function login()
    {
        $request = new Request();
        $data = $request->allPost();

        if (!\App\Core\CSRF::validateToken($data['csrf_token'] ?? '')) {
            $_SESSION['status'] = ['type' => 'error', 'message' => 'Error de seguridad. Inténtalo de nuevo.'];
            header('Location: /admin/login');
            exit;
        }

        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        $userModel = new UserModel();
        $user = $userModel->findByUsername($username);

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);

            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'rol' => $user['rol']
            ];

            header('Location: /admin/dashboard');
            exit;
        }

        $_SESSION['status'] = ['type' => 'error', 'message' => 'Usuario o contraseña incorrectos.'];
        header('Location: /admin/login');
        exit;
    }

    /**
     * Logs the user out.
     * @return void
     */
    public function logout() {
        $_SESSION = [];
        session_destroy();

        header('Location: /admin/login');
        exit;
    }
}