<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AppointmentViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_appointment_page_loads_in_spanish()
    {
        $response = $this->get(route('appointment.create'));

        $response->assertStatus(200);
        $response->assertSee('Agendar Cita');
        $response->assertSee('Nombre');
        $response->assertSee('Teléfono');
    }

    public function test_welcome_page_has_variables()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Hospital Ángeles Lindavista');
        $response->assertSee('123456789');
    }
}
