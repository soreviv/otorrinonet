@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Admin Dashboard</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white shadow-md rounded p-6">
                <h2 class="text-xl font-bold mb-4">Appointments</h2>
                <a href="{{ route('admin.appointments') }}" class="text-blue-500 hover:underline">View Appointments</a>
            </div>
            <div class="bg-white shadow-md rounded p-6">
                <h2 class="text-xl font-bold mb-4">Contact Messages</h2>
                <a href="{{ route('admin.messages') }}" class="text-blue-500 hover:underline">View Messages</a>
            </div>
        </div>
    </div>
@endsection
