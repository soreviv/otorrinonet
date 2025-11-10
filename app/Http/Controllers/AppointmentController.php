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
     * Persist a new appointment from validated request data and redirect to the creation form with a success message.
     *
     * @return \Illuminate\Http\RedirectResponse Redirects to the 'appointment.create' route with a `'success'` flash message on successful creation.
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
}