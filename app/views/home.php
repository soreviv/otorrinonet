<?php
// Incluir el header
require '_header.php';
?>

<!-- Hero Section -->
<section class="bg-blue-600 text-white py-20">
    <div class="container mx-auto px-4 text-center">
        <!-- Título y mensaje de bienvenida ahora vienen del controlador -->
        <h2 class="text-4xl font-bold mb-4"><?= htmlspecialchars($pageTitle ?? 'Bienvenido a OtorrinoNet') ?></h2>
        <p class="text-xl mb-8"><?= htmlspecialchars($welcomeMessage ?? 'Especialista en Otorrinolaringología') ?></p>
        <a href="/agendar-cita" class="bg-white text-blue-600 px-6 py-3 rounded-full font-bold hover:bg-gray-100 transition duration-300">Agendar Cita</a>
    </div>
</section>

<!-- Acerca del Doctor -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Acerca del Doctor</h2>
        <div class="flex flex-col md:flex-row items-center">
            <!-- Foto del doctor (placeholder) -->
            <div class="md:w-1/3 mb-8 md:mb-0 flex justify-center">
                <img src="https://placehold.co/256x256/E2E8F0/4A5568/png?text=Dr.+Viveros" alt="Fotografía profesional del Dr. Alejandro Viveros, especialista en otorrinolaringología" class="rounded-full w-64 h-64 object-cover border-4 border-blue-200">
            </div>
            <div class="md:w-2/3 md:pl-8">
                <h3 class="text-2xl font-bold mb-4">Dr. Alejandro Viveros Domínguez</h3>
                <p class="mb-4">Médico especialista en Otorrinolaringología y Cirugía de Cabeza y Cuello con más de 10 años de experiencia.</p>
                <p class="mb-4">Cédula profesional: 6277305</p>
                <p class="mb-4">Cédula de especialidad: 10148701</p>
                <p class="mb-4">Adscripción hospitalaria: [Nombre del hospital]</p>
                <p>Registro COFEPRIS: [Número de registro]</p>
            </div>
        </div>
    </div>
</section>

<!-- Vista Previa de Servicios -->
<section class="bg-gray-100 py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Nuestros Servicios</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <img src="https://placehold.co/80x80/DBEAFE/3B82F6/png?text=Consulta" alt="Icono de un estetoscopio representando una consulta médica" class="mx-auto mb-4">
                <h3 class="text-xl font-bold mb-2">Consulta Otorrinológica</h3>
                <p>Evaluación completa del oído, nariz y garganta.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <img src="https://placehold.co/80x80/DBEAFE/3B82F6/png?text=Cirugía" alt="Icono de un bisturí representando procedimientos quirúrgicos" class="mx-auto mb-4">
                <h3 class="text-xl font-bold mb-2">Cirugías Especializadas</h3>
                <p>Amigdalectomía, septoplastia y más.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <img src="https://placehold.co/80x80/DBEAFE/3B82F6/png?text=Tratamiento" alt="Icono de un frasco de medicina representando tratamientos especializados" class="mx-auto mb-4">
                <h3 class="text-xl font-bold mb-2">Tratamientos Especializados</h3>
                <p>Tratamiento para vértigo, tinnitus y terapia de voz.</p>
            </div>
        </div>
        <div class="text-center mt-8">
            <a href="/servicios" class="text-blue-600 hover:text-blue-800 font-bold">Ver todos los servicios &rarr;</a>
        </div>
    </div>
</section>

<!-- Información de Contacto y Ubicación -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Contacto y Ubicación</h2>
        <div class="flex flex-col md:flex-row">
            <div class="md:w-1/2 mb-8 md:mb-0">
                <h3 class="text-2xl font-bold mb-4">Información de Contacto</h3>
                <p class="mb-2">Dirección: Buenavista 20, Col. Lindavista, Gustavo A. Madero, CDMX</p>
                <p class="mb-2">Teléfono: +52 55 1234-5678</p>
                <p class="mb-2">Email: contacto@otorrinonet.com</p>
                <p>Horarios: Lunes a Miércoles 16:00-20:00, Jueves-Viernes 10:00-13:00</p>
            </div>
            <div class="md:w-1/2">
                <h3 class="text-2xl font-bold mb-4">Ubicación</h3>
                <!-- Mapa de Google Maps (placeholder) -->
                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-64 flex items-center justify-center">
                    <span>Mapa de Google Maps</span>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// Incluir el footer
require '_footer.php';
?>