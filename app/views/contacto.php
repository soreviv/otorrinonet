<?php require '_header.php'; ?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold mb-4"><?= htmlspecialchars($pageTitle ?? 'Contacto') ?></h1>
        <p class="text-xl">Estamos aquí para atenderte. Envíanos un mensaje</p>
    </div>
</section>

<!-- Mensajes de estado -->
<section class="py-8">
    <div class="container mx-auto px-4">
        <?php if (isset($status) && $status): ?>
            <div class="p-6 rounded-lg shadow-lg <?= $status['type'] === 'success' ? 'bg-green-100 border-l-4 border-green-500 text-green-700' : 'bg-red-100 border-l-4 border-red-500 text-red-700' ?>" role="alert">
                <p class="font-bold text-lg"><?= htmlspecialchars($status['message']) ?></p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Contacto Section -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Información de Contacto (HTML estático) -->
            <div>
                <!-- ... (toda la información de contacto se mantiene igual) ... -->
            </div>

            <!-- Formulario de Contacto -->
            <div>
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Envíanos un Mensaje</h2>
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <form method="POST" action="/contacto" class="space-y-6">
                        <?= App\Core\CSRF::getInputField() ?>
                        <!-- Nombre -->
                        <div>
                            <label for="nombre" class="block text-gray-700 font-medium mb-2">Nombre Completo <span class="text-red-600">*</span></label>
                            <input type="text" id="nombre" name="nombre" required value="<?= htmlspecialchars($old_data['nombre'] ?? '') ?>"
                                   class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 <?= isset($errors['nombre']) ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' ?>">
                            <?php if (isset($errors['nombre'])): ?><p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['nombre']) ?></p><?php endif; ?>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-gray-700 font-medium mb-2">Correo Electrónico <span class="text-red-600">*</span></label>
                            <input type="email" id="email" name="email" required value="<?= htmlspecialchars($old_data['email'] ?? '') ?>"
                                   class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 <?= isset($errors['email']) ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' ?>">
                            <?php if (isset($errors['email'])): ?><p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['email']) ?></p><?php endif; ?>
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label for="telefono" class="block text-gray-700 font-medium mb-2">Teléfono</label>
                            <input type="tel" id="telefono" name="telefono" value="<?= htmlspecialchars($old_data['telefono'] ?? '') ?>"
                                   class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 border-gray-300 focus:ring-blue-500">
                        </div>

                        <!-- Asunto -->
                        <div>
                            <label for="asunto" class="block text-gray-700 font-medium mb-2">Asunto <span class="text-red-600">*</span></label>
                            <input type="text" id="asunto" name="asunto" required value="<?= htmlspecialchars($old_data['asunto'] ?? '') ?>"
                                   class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 <?= isset($errors['asunto']) ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' ?>">
                            <?php if (isset($errors['asunto'])): ?><p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['asunto']) ?></p><?php endif; ?>
                        </div>

                        <!-- Mensaje -->
                        <div>
                            <label for="mensaje" class="block text-gray-700 font-medium mb-2">Mensaje <span class="text-red-600">*</span></label>
                            <textarea id="mensaje" name="mensaje" required rows="5"
                                      class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 <?= isset($errors['mensaje']) ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' ?>"><?= htmlspecialchars($old_data['mensaje'] ?? '') ?></textarea>
                            <?php if (isset($errors['mensaje'])): ?><p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['mensaje']) ?></p><?php endif; ?>
                        </div>

                        <!-- hCaptcha -->
                        <div class="h-captcha" data-sitekey="<?= htmlspecialchars($_ENV['HCAPTCHA_SITE_KEY'] ?? '') ?>"></div>
                        <?php if (isset($errors['hcaptcha'])): ?><p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['hcaptcha']) ?></p><?php endif; ?>

                        <!-- Botón Enviar -->
                        <div>
                            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-4 rounded-lg font-bold hover:from-blue-700 hover:to-indigo-700 transition">
                                Enviar Mensaje
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require '_footer.php'; ?>
