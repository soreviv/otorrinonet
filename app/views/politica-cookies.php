<?php require '_header.php'; ?>

<!-- Cookie Policy Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold mb-4"><?= htmlspecialchars($pageTitle ?? 'Política de Cookies') ?></h1>
        <p class="text-xl">Información sobre el uso de cookies en nuestro sitio web</p>
    </div>
</section>

<!-- Cookie Policy Content -->
<section class="py-16">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">1. ¿Qué son las Cookies?</h2>
                <p class="text-gray-700 leading-relaxed">Una cookie es un pequeño archivo de texto que un sitio web almacena en su navegador cuando lo visita. Las cookies permiten que el sitio web recuerde sus acciones y preferencias (como inicio de sesión, idioma, tamaño de fuente y otras preferencias de visualización) durante un período de tiempo, para que no tenga que volver a introducirlas cada vez que regrese al sitio o navegue de una página a otra.</p>
            </div>

            <!-- ... (más contenido estático sobre cookies) ... -->

            <div class="bg-blue-100 border-l-4 border-blue-600 p-6 rounded">
                <p class="text-sm text-blue-800"><strong>Última actualización:</strong> 23 de Octubre de 2025</p>
            </div>
        </div>
    </div>
</section>

<?php require '_footer.php'; ?>
