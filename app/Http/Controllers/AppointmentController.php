<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

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
     * Valida los datos de entrada, crea una nueva cita y redirige al formulario con un mensaje de éxito.
     *
     * Valida los campos `name`, `phone`, `tipo_consulta`, `motivo`, `date` y `time`, persiste la cita usando
     * asignación masiva y añade un mensaje flash de éxito antes de redirigir a la ruta `appointment.create`.
     *
     * @return \Illuminate\Http\RedirectResponse Respuesta que redirige a la ruta `appointment.create` con un flash `success` cuyo valor es "Appointment booked successfully!".
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

        return redirect()->route('appointment.create')->with('success', 'Appointment booked successfully!');
    }

    /**
     * Obtiene los huecos de cita disponibles para una fecha dada.
     *
     * Valida que el parámetro `date` esté presente y sea una fecha, limita la búsqueda a los días
     * lunes, martes y miércoles, y devuelve los intervalos de 30 minutos entre las 16:00 y las 19:30
     * que no estén ocupados por citas existentes.
     *
     * @param \Illuminate\Http\Request $request Request que debe incluir el campo `date` con una fecha válida.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con la clave `slots` que contiene un array de cadenas en formato `HH:MM` con los horarios disponibles.
     */
    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $date = $request->input('date');
        $dayOfWeek = date('N', strtotime($date)); // 1 (Mon) - 7 (Sun)

        // Configuration: Mon (1), Tue (2), Wed (3)
        // Time: 16:00 to 19:30
        $allowedDays = [1, 2, 3];

        if (!in_array($dayOfWeek, $allowedDays)) {
            return response()->json(['slots' => []]);
        }

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

            // Check availability
            if (!in_array($slotTime, $existingAppointments)) {
                $slots[] = $slotTime;
            }

            $current += $duration;
        }

        return response()->json(['slots' => $slots]);
    }
}