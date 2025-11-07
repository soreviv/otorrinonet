<?php

namespace App\Controllers;

/**
 * Handles the home page.
 */
class HomeController extends BaseController {
    /**
     * Displays the home page.
     * @return void
     */
    public function index() {
        $data = [
            'pageTitle' => 'Bienvenido a OtorrinoNet',
            'welcomeMessage' => 'La mejor atención para tu salud auditiva, nasal y de garganta.'
        ];

        echo $this->renderView('home', $data);
    }
}