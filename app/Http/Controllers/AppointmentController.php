<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
<<<<<<< HEAD
use Carbon\Carbon;
=======
>>>>>>> origin/main

class AppointmentController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('appointment');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'tipo_consulta' => 'required|string|max:255',
            'motivo' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
        ]);

        Appointment::create($validated);

        // Invalidate cache for this date
        Cache::forget('appointment_slots_' . $validated['date']);

        return redirect()->route('appointment.create')->with('success', 'Appointment booked successfully!');
    }

    /**
     * Get available slots for a given date.
     */
    public function getAvailableSlots(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
        ]);

        $date = $validated['date'];

        $slots = Cache::remember('appointment_slots_' . $date, 300, function () use ($date) {
            $dayOfWeek = date('N', strtotime($date)); // 1 (Mon) - 7 (Sun)

<<<<<<< HEAD
            $allowedDays = config('appointments.allowed_days');
=======
            // Configuration: Mon (1), Tue (2), Wed (3)
            // Time: 16:00 to 19:30
            $allowedDays = [1, 2, 3];
>>>>>>> origin/main

            if (!in_array($dayOfWeek, $allowedDays)) {
                return [];
            }

<<<<<<< HEAD
            $startConfig = config('appointments.start_time');
            $endConfig = config('appointments.end_time');
            $durationMinutes = config('appointments.slot_duration_minutes');

            $startTime = Carbon::parse("$date $startConfig");
            $endTime = Carbon::parse("$date $endConfig");

            $slots = [];
            $current = $startTime->copy();

            // Fetch existing appointments for the date
            $existingAppointments = Appointment::where('date', $date)
                ->pluck('time')
                ->map(function ($time) {
                    return Carbon::parse($time)->format('H:i');
                })
                ->toArray();

            // Iterate while the current slot start time allows for a full duration before end time
            while ($current->lte($endTime->copy()->subMinutes($durationMinutes))) {
                $slotTime = $current->format('H:i');
=======
            $startTime = strtotime("$date 16:00");
            $endTime = strtotime("$date 19:30");
            $duration = 30 * 60; // 30 minutes

            $slots = [];
            $current = $startTime;

            // Fetch existing appointments for the date
            $existingAppointments = Appointment::where('date', $date)
                ->pluck('time') // Assuming 'time' is stored as "HH:MM" or "HH:MM:SS"
                ->map(function ($time) {
                    return date('H:i', strtotime($time));
                })
                ->toArray();

            while ($current < $endTime) {
                $slotTime = date('H:i', $current);
>>>>>>> origin/main

                // Check availability
                if (!in_array($slotTime, $existingAppointments)) {
                    $slots[] = $slotTime;
                }

<<<<<<< HEAD
                $current->addMinutes($durationMinutes);
=======
                $current += $duration;
>>>>>>> origin/main
            }

            return $slots;
        });

        return response()->json(['slots' => $slots]);
    }
}
