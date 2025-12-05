<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Appointment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class AppointmentSlotsTest extends TestCase
{
    use RefreshDatabase;

    public function test_slots_are_cached()
    {
        $date = '2025-10-20'; // A Monday

        Cache::shouldReceive('remember')
            ->once()
            ->with('appointment_slots_' . $date, 300, \Closure::class)
            ->andReturn(['16:00', '16:30']);

        $response = $this->get(route('appointment.slots', ['date' => $date]));

        $response->assertStatus(200);
        $response->assertJson(['slots' => ['16:00', '16:30']]);
    }

    public function test_cache_is_cleared_on_appointment_store()
    {
        $date = '2025-10-20';

        Cache::shouldReceive('forget')
            ->once()
            ->with('appointment_slots_' . $date);

        $response = $this->post(route('appointment.store'), [
            'name' => 'John Doe',
            'phone' => '1234567890',
            'tipo_consulta' => 'General',
            'motivo' => 'Checkup',
            'date' => $date,
            'time' => '16:00',
        ]);

        $response->assertRedirect();
    }
}
