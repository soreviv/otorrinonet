@extends('layouts.app')

@section('title', 'Nuestros Servicios - OtorrinoNet')

@section('content')
<div class="container mx-auto px-4 py-16">
    <h1 class="text-4xl font-bold text-center mb-12">Nuestros Servicios</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Servicio 1 -->
        <div class="bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-4">Consulta Otorrinológica General</h2>
            <p class="mb-4">Evaluación y diagnóstico completo de padecimientos de oído, nariz y garganta para niños y adultos.</p>
            <ul class="list-disc list-inside text-gray-700">
                <li>Infecciones de oído (otitis)</li>
                <li>Amigdalitis y faringitis</li>
                <li>Rinitis alérgica</li>
                <li>Sinusitis</li>
            </ul>
        </div>

        <!-- Servicio 2 -->
        <div class="bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-4">Cirugías Especializadas</h2>
            <p class="mb-4">Procedimientos quirúrgicos con tecnología de vanguardia.</p>
            <ul class="list-disc list-inside text-gray-700">
                <li>Amigdalectomía (extracción de amígdalas)</li>
                <li>Adenoidectomía</li>
                <li>Septoplastia (corrección de tabique desviado)</li>
                <li>Turbinoplastia</li>
            </ul>
        </div>

        <!-- Servicio 3 -->
        <div class="bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-4">Audiología y Vértigo</h2>
            <p class="mb-4">Diagnóstico y tratamiento de problemas de audición y equilibrio.</p>
            <ul class="list-disc list-inside text-gray-700">
                <li>Audiometrías</li>
                <li>Tratamiento de tinnitus (zumbido en oídos)</li>
                <li>Rehabilitación vestibular para vértigo</li>
                <li>Limpieza de oídos (lavado ótico)</li>
            </ul>
        </div>

        <!-- Servicio 4 -->
        <div class="bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-4">Trastornos de la Voz</h2>
            <p class="mb-4">Evaluación de cuerdas vocales y problemas de la voz.</p>
            <ul class="list-disc list-inside text-gray-700">
                <li>Laringoscopía flexible</li>
                <li>Disfonía</li>
                <li>Pólipos y nódulos vocales</li>
            </ul>
        </div>
    </div>

    <div class="text-center mt-12">
        <a href="{{ route('appointment.create') }}" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-full font-bold hover:bg-blue-700 transition duration-300">
            Agendar una Cita
        </a>
    </div>
</div>
@endsection
