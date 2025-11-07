<?php require '_header.php'; ?>

<!-- Terms and Conditions Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold mb-4"><?= htmlspecialchars($pageTitle ?? 'Términos y Condiciones') ?></h1>
        <p class="text-xl">Reglas para el uso de nuestro sitio web y servicios</p>
    </div>
</section>

<!-- Terms and Conditions Content -->
<section class="py-16">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">1. Aceptación de los Términos</h2>
                <p class="text-gray-700 leading-relaxed">Al acceder y utilizar este sitio web, usted acepta estar sujeto a estos Términos y Condiciones de uso, todas las leyes y regulaciones aplicables, y acepta que es responsable del cumplimiento de las leyes locales aplicables. Si no está de acuerdo con alguno de estos términos, se le prohíbe usar o acceder a este sitio.</p>
            </div>

            <!-- ... (más contenido estático sobre términos y condiciones) ... -->

            <div class="bg-blue-100 border-l-4 border-blue-600 p-6 rounded">
                <p class="text-sm text-blue-800"><strong>Última actualización:</strong> 23 de Octubre de 2025</p>
            </div>
        </div>
    </div>
</section>

<?php require '_footer.php'; ?>
