@extends('layouts.app')

@section('title', 'Welcome to OtorrinoNet')

@section('content')
    <div class="flex justify-center items-center h-screen">
        <div class="text-center">
            <h1 class="text-4xl font-bold">OtorrinoNet</h1>
            <p class="mt-4 text-lg">Your trusted partner for ear, nose, and throat health.</p>
            <div class="mt-8">
                <a href="{{ route('appointment.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded">Book an Appointment</a>
                <a href="{{ route('contact.create') }}" class="ml-4 px-4 py-2 bg-gray-500 text-white rounded">Contact Us</a>
            </div>
        </div>
    </div>
@endsection
