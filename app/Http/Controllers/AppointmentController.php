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
     * Almacena una nueva cita creada a partir de los datos de la petición.
     *
     * Valida los campos obligatorios y crea un registro de Appointment con los datos validados.
     *
     * @return \Illuminate\Http\RedirectResponse Redirección a la ruta `appointment.create` con un mensaje de éxito.
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
     * Devuelve las franjas horarias disponibles de 30 minutos para una fecha dada.
     *
     * Genera intervalos entre las 16:00 y las 19:30 (cada 30 minutos) y excluye los horarios ya reservados
     * para la fecha solicitada. Solo considera fechas que caigan en lunes, martes o miércoles; para otras fechas
     * devuelve un array vacío.
     *
     * @return \Illuminate\Http\JsonResponse JSON con la clave `slots` cuyo valor es un array de cadenas en formato `HH:MM`
     *                                     que representan las franjas disponibles (puede ser un array vacío).
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