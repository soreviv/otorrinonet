<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class AppointmentConfigTest extends TestCase
{
    use RefreshDatabase;

    public function test_slots_use_config()
    {
        Config::set('appointments.allowed_days', [1, 2, 3, 4, 5]); // Mon-Fri
        Config::set('appointments.start_time', '09:00');
        Config::set('appointments.end_time', '10:00');
        Config::set('appointments.slot_duration_minutes', 30);

        $date = '2025-10-24'; // A Friday

        // Mock Cache to ensure we run the closure
        Cache::shouldReceive('remember')
            ->once()
            ->andReturnUsing(function ($key, $ttl, $closure) {
                return $closure();
            });

        $response = $this->get(route('appointment.slots', ['date' => $date]));

        // Expected slots: 09:00, 09:30. (10:00 is end time, so 09:30 is last slot that ends at 10:00)
        $response->assertStatus(200);
        $response->assertJson(['slots' => ['09:00', '09:30']]);
    }

    public function test_slots_respect_end_time_boundary()
    {
        Config::set('appointments.allowed_days', [1]);
        Config::set('appointments.start_time', '09:00');
        Config::set('appointments.end_time', '09:30'); // Only one slot fits (09:00-09:30)
        Config::set('appointments.slot_duration_minutes', 30);

        $date = '2025-10-20'; // Monday

         // Mock Cache
         Cache::shouldReceive('remember')
            ->once()
            ->andReturnUsing(function ($key, $ttl, $closure) {
                return $closure();
            });

        $response = $this->get(route('appointment.slots', ['date' => $date]));

        $response->assertStatus(200);
        $response->assertJson(['slots' => ['09:00']]);
    }
}
