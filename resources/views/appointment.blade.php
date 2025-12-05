@extends('layouts.app')

@section('title', 'Agendar Cita')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8 text-center">Agendar Cita</h1>
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="flex flex-col md:flex-row gap-8">
            <!-- Calendar Column -->
            <div class="md:w-1/2 bg-white shadow-md rounded p-4">
                <h2 class="text-xl font-bold mb-4">Seleccionar Fecha</h2>
                <div id="calendar"></div>
                <div id="slots-container" class="mt-6 hidden">
                    <h3 class="text-lg font-semibold mb-3">Horarios Disponibles</h3>
                    <div id="slots-grid" class="grid grid-cols-3 gap-2">
                        <!-- Slots will be injected here via JS -->
                    </div>
                </div>
            </div>

            <!-- Form Column -->
            <div class="md:w-1/2">
                <form action="{{ route('appointment.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8">
                    @csrf
                    <!-- Hidden inputs for date and time (required removed as per request, validated in controller) -->
                    <input type="hidden" id="date" name="date">
                    <input type="hidden" id="time" name="time">

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Nombre</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" type="text" placeholder="Tu Nombre" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="phone">Teléfono</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="phone" name="phone" type="text" placeholder="Tu Número de Teléfono" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="tipo_consulta">Tipo de Consulta</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="tipo_consulta" name="tipo_consulta" type="text" placeholder="Tipo de Consulta" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="motivo">Motivo</label>
                        <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="motivo" name="motivo" placeholder="Motivo de la cita" rows="5" required></textarea>
                    </div>

                    <div class="mb-4">
                        <p class="text-gray-700 text-sm font-bold mb-2">Cita Seleccionada:</p>
                        <p id="selected-appointment-display" class="text-gray-600 italic">Por favor selecciona una fecha y hora del calendario.</p>
                    </div>

                    <div class="flex items-center justify-between">
                        <button id="submit-btn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline disabled:opacity-50 disabled:cursor-not-allowed" type="submit" disabled>
                            Agendar Cita
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
