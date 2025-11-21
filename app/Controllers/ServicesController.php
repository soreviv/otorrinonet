<?php

namespace App\Controllers;

use App\Models\ServiceModel;

/**
 * Handles the display of the services page.
 */
class ServicesController extends BaseController {
    /**
     * Displays the services page.
     * @return void
     */
    public function index() {
        $serviceModel = new ServiceModel();
        $services = $serviceModel->getAllActiveServices();

        $data = [
            'pageTitle' => 'Nuestros Servicios',
            'services' => $services
        ];

        echo $this->renderView('servicios', $data);
    }
}