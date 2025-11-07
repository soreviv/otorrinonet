<?php

namespace App\Controllers;

/**
 * Handles the display of legal pages such as the privacy policy, cookie policy, and terms and conditions.
 */
class LegalController extends BaseController {
    /**
     * Displays the Privacy Policy page.
     * @return void
     */
    public function privacyPolicy() {
        $data = [
            'pageTitle' => 'Aviso de Privacidad'
        ];

        echo $this->renderView('aviso-privacidad', $data);
    }

    /**
     * Displays the Cookie Policy page.
     * @return void
     */
    public function cookiePolicy() {
        $data = [
            'pageTitle' => 'Política de Cookies'
        ];

        echo $this->renderView('politica-cookies', $data);
    }

    /**
     * Displays the Terms and Conditions page.
     * @return void
     */
    public function termsAndConditions() {
        $data = [
            'pageTitle' => 'Términos y Condiciones'
        ];

        echo $this->renderView('terminos-condiciones', $data);
    }
}