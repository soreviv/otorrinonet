<?php
// Incluir el header
require '_header.php';

// --- Lógica para preparar los datos ---

// Agrupar los servicios por categoría para una fácil visualización.
$groupedServices = [];
if (isset($services) && is_array($services)) {
    foreach ($services as $service) {
        $groupedServices[$service['categoria']][] = $service;
    }
}

// Mapeo para estilos y iconos de cada categoría, manteniendo el diseño original.
$categoryStyles = [
    'consulta' => [
        'bg_color' => 'bg-blue-100',
        'text_color' => 'text-blue-600',
        'icon' => '<svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>'
    ],
    'cirugia' => [
        'bg_color' => 'bg-indigo-100',
        'text_color' => 'text-indigo-600',
        'icon' => '<svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>'
    ],
    'estetica' => [
        'bg_color' => 'bg-purple-100',
        'text_color' => 'text-purple-600',
        'icon' => '<svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
    ],
    'tratamiento' => [
        'bg_color' => 'bg-green-100',
        'text_color' => 'text-green-600',
        'icon' => '<svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
    ],
    'otros' => [
        'bg_color' => 'bg-orange-100',
        'text_color' => 'text-orange-600',
        'icon' => '<svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>'
    ]
];

// Función para formatear el nombre de la categoría para mostrarlo.
function formatCategoryName($category) {
    return ucwords(str_replace('_', ' ', $category));
}
?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <!-- El título ahora viene del controlador -->
        <h1 class="text-4xl font-bold mb-4"><?= htmlspecialchars($pageTitle ?? 'Nuestros Servicios') ?></h1>
        <p class="text-xl">Atención médica especializada en Otorrinolaringología y Cirugía de Cabeza y Cuello</p>
    </div>
</section>

<!-- Servicios Section -->
<section class="py-16">
    <div class="container mx-auto px-4">

        <?php if (empty($groupedServices)): ?>
            <div class="text-center text-gray-600">
                <p>No hay servicios disponibles en este momento. Por favor, inténtelo más tarde.</p>
            </div>
        <?php else: ?>
            <!-- Iteramos sobre cada categoría de servicios -->
            <?php foreach ($groupedServices as $category => $serviceList): ?>
                <?php $style = $categoryStyles[$category] ?? $categoryStyles['otros']; ?>
                <div class="mb-16">
                    <div class="flex items-center mb-6">
                        <div class="<?= $style['bg_color'] ?> p-4 rounded-full mr-4">
                            <?= $style['icon'] ?>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-800"><?= htmlspecialchars(formatCategoryName($category)) ?></h2>
                    </div>

                    <!-- Grid para los servicios de esta categoría -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Iteramos sobre cada servicio en la categoría actual -->
                        <?php foreach ($serviceList as $service): ?>
                            <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow flex flex-col">
                                <h3 class="font-bold text-xl text-gray-800 mb-3"><?= htmlspecialchars($service['nombre']) ?></h3>
                                <p class="text-gray-600 mb-4 flex-grow"><?= htmlspecialchars($service['descripcion']) ?></p>
                                <?php if (isset($service['precio']) && $service['precio'] > 0): ?>
                                    <p class="text-lg font-semibold text-blue-600 mt-auto">
                                        $<?= number_format($service['precio'], 2) ?> MXN
                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Call to Action (se mantiene igual) -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow-xl p-8 mt-16 text-center text-white">
            <h2 class="text-3xl font-bold mb-4">¿Necesitas una Consulta?</h2>
            <p class="text-xl mb-6">Agenda tu cita y recibe atención médica especializada</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="/agendar-cita" class="bg-white text-blue-600 px-8 py-3 rounded-full font-bold hover:bg-gray-100 transition duration-300">
                    Agendar Cita
                </a>
                <a href="/contacto" class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-full font-bold hover:bg-white hover:text-blue-600 transition duration-300">
                    Contactar
                </a>
            </div>
        </div>
    </div>
</section>

<?php
// Incluir el footer
require '_footer.php';
?>