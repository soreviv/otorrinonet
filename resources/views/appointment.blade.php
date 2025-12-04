@extends('layouts.app')

@section('title', 'Book an Appointment')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8 text-center">Book an Appointment</h1>
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="flex flex-col md:flex-row gap-8">
            <!-- Calendar Column -->
            <div class="md:w-1/2 bg-white shadow-md rounded p-4">
                <h2 class="text-xl font-bold mb-4">Select a Date</h2>
                <div id="calendar"></div>
                <div id="slots-container" class="mt-6 hidden">
                    <h3 class="text-lg font-semibold mb-3">Available Slots</h3>
                    <div id="slots-grid" class="grid grid-cols-3 gap-2">
                        <!-- Slots will be injected here via JS -->
                    </div>
                </div>
            </div>

            <!-- Form Column -->
            <div class="md:w-1/2">
                <form action="{{ route('appointment.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8">
                    @csrf
                    <!-- Hidden inputs for date and time -->
                    <input type="hidden" id="date" name="date" required>
                    <input type="hidden" id="time" name="time" required>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Name</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" type="text" placeholder="Your Name" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="phone">Phone</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="phone" name="phone" type="text" placeholder="Your Phone Number" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="tipo_consulta">Consultation Type</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="tipo_consulta" name="tipo_consulta" type="text" placeholder="Consultation Type" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="motivo">Reason</label>
                        <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="motivo" name="motivo" placeholder="Reason for your appointment" rows="5" required></textarea>
                    </div>

                    <div class="mb-4">
                        <p class="text-gray-700 text-sm font-bold mb-2">Selected Appointment:</p>
                        <p id="selected-appointment-display" class="text-gray-600 italic">Please select a date and time from the calendar.</p>
                    </div>

                    <div class="flex items-center justify-between">
                        <button id="submit-btn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline disabled:opacity-50 disabled:cursor-not-allowed" type="submit" disabled>
                            Book Appointment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Pass route to JS -->
    <script>
        window.slotsRoute = "{{ route('appointment.slots') }}";
    </script>
@endsection
