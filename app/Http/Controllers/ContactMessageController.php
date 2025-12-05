<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('contact');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'asunto' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Sanitize input to remove HTML tags
        $validated['name'] = strip_tags($validated['name']);
        $validated['email'] = strip_tags($validated['email']);
        $validated['phone'] = strip_tags($validated['phone']);
        $validated['asunto'] = strip_tags($validated['asunto']);
        $validated['message'] = strip_tags($validated['message']);

        ContactMessage::create($validated);

        return redirect()->route('contact.create')->with('success', 'Message sent successfully!');
    }
}
