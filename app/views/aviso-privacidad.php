<?php require '_header.php'; ?>

<!-- Privacy Notice Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold mb-4"><?= htmlspecialchars($pageTitle ?? 'Aviso de Privacidad') ?></h1>
        <p class="text-xl">Información sobre el tratamiento de sus datos personales</p>
    </div>
</section>

<!-- Privacy Notice Content -->
<section class="py-16">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">1. Responsable del Tratamiento de Datos Personales</h2>
                <p class="text-gray-700 leading-relaxed">Dr. Alejandro Viveros Domínguez, con cédula profesional 6277305 y especialidad 10148701, con domicilio en Buenavista 20, Col. Lindavista, Gustavo A. Madero, Ciudad de México, CDMX 07750, es el responsable del tratamiento de sus datos personales.</p>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">2. Datos Personales que Recopilamos</h2>
                <p class="text-gray-700 leading-relaxed mb-4">Para prestar nuestros servicios médicos, recopilamos los siguientes datos personales:</p>
                <ul class="space-y-2 text-gray-700">
                    <li class="flex items-start"><span class="text-blue-600 mr-2">•</span><strong>Datos de identificación:</strong> Nombre completo, fecha de nacimiento, sexo, nacionalidad</li>
                    <li class="flex items-start"><span class="text-blue-600 mr-2">•</span><strong>Datos de contacto:</strong> Domicilio, teléfono, correo electrónico</li>
                    <li class="flex items-start"><span class="text-blue-600 mr-2">•</span><strong>Datos médicos:</strong> Historia clínica, diagnósticos, tratamientos, medicamentos</li>
                    <li class="flex items-start"><span class="text-blue-600 mr-2">•</span><strong>Datos de facturación:</strong> Información fiscal para emisión de comprobantes</li>
                    <li class="flex items-start"><span class="text-blue-600 mr-2">•</span><strong>Datos de citas:</strong> Fechas y horarios de consultas, tipos de servicio</li>
                </ul>
            </div>

            <!-- ... (resto del contenido estático) ... -->

            <div class="bg-blue-100 border-l-4 border-blue-600 p-6 rounded">
                <p class="text-sm text-blue-800"><strong>Última actualización:</strong> 23 de Octubre de 2025</p>
                <p class="text-sm text-blue-800 mt-2">Este aviso cumple con la Ley Federal de Protección de Datos Personales en Posesión de los Particulares y su Reglamento.</p>
            </div>
        </div>
    </div>
</section>

<?php require '_footer.php'; ?>
