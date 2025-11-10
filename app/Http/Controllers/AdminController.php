<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    /**
     * Display a listing of the resource.
     */
    public function appointments()
    {
        $appointments = Appointment::all();
        return view('admin.appointments', compact('appointments'));
    }

    /**
     * Display a listing of the resource.
     */
    public function messages()
    {
        $messages = ContactMessage::all();
        return view('admin.messages', compact('messages'));
    }
}
