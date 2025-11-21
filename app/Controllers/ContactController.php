<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Validator;
use App\Models\ContactMessageModel;

/**
 * Handles the contact form.
 */
class ContactController extends BaseController
{
    /**
     * Displays the contact form.
     * @return void
     */
    public function create()
    {
        $status = $_SESSION['status'] ?? null;
        $errors = $_SESSION['errors'] ?? [];
        $old_data = $_SESSION['old_data'] ?? [];

        unset($_SESSION['status'], $_SESSION['errors'], $_SESSION['old_data']);

        $data = [
            'pageTitle' => 'Contacto',
            'status' => $status,
            'errors' => $errors,
            'old_data' => $old_data
        ];

        echo $this->renderView('contacto', $data);
    }

    /**
     * Processes the contact form submission.
     * @return void
     */
    public function store()
    {
        $request = new Request();
        $data = $request->allPost();

        if (!\App\Core\CSRF::validateToken($data['csrf_token'] ?? '')) {
            $_SESSION['status'] = ['type' => 'error', 'message' => 'Error de seguridad. Inténtalo de nuevo.'];
            header('Location: /contacto');
            exit;
        }

        $validator = new Validator();
        $validator->validate($data, [
            'nombre' => 'required',
            'email' => 'required|email',
            'asunto' => 'required',
            'mensaje' => 'required',
        ]);

        $errors = $validator->getErrors();

        if (!$this->validateHCaptcha($data['h-captcha-response'] ?? '')) {
            $errors['hcaptcha'] = 'Por favor, completa la verificación de seguridad.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_data'] = $data;
            $_SESSION['status'] = ['type' => 'error', 'message' => 'Por favor, corrige los errores en el formulario.'];

            header('Location: /contacto');
            exit;
        }

        $contactMessageModel = new ContactMessageModel();
        if ($contactMessageModel->create($data)) {
            $_SESSION['status'] = ['type' => 'success', 'message' => '¡Gracias por tu mensaje! Nos pondremos en contacto contigo pronto.'];
        } else {
            $_SESSION['status'] = ['type' => 'error', 'message' => 'Hubo un error al enviar tu mensaje. Por favor, inténtalo de nuevo.'];
            $_SESSION['old_data'] = $data;
        }

        header('Location: /contacto');
        exit;
    }

    /**
     * Validates the hCaptcha response.
     *
     * @param string $response The hCaptcha response from the form.
     * @return bool True if the response is valid, false otherwise.
     */
    private function validateHCaptcha(string $response) {
        if (empty($response)) return false;
        $secret = $_ENV['HCAPTCHA_SECRET_KEY'] ?? '';
        if (empty($secret)) {
            error_log("hCaptcha secret key is not set.");
            return false;
        }
        $data = ['secret' => $secret, 'response' => $response];
        $options = ['http' => ['header' => "Content-type: application/x-www-form-urlencoded\r\n", 'method' => 'POST', 'content' => http_build_query($data)]];
        $context = stream_context_create($options);
        $verify = file_get_contents('https://hcaptcha.com/siteverify', false, $context);
        $captchaSuccess = json_decode($verify);
        return $captchaSuccess->success ?? false;
    }
}