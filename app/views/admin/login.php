<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Administración') ?></title>
    <link href="/assets/css/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="w-full max-w-md">
        <div class="bg-white shadow-md rounded-lg p-8">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Iniciar Sesión</h2>

            <?php if (isset($status) && $status['type'] === 'error'): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?= htmlspecialchars($status['message']) ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="/admin/login">
                <?= App\Core\CSRF::getInputField() ?>
                <div class="mb-4">
                    <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Usuario:</label>
                    <input type="text" id="username" name="username" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Contraseña:</label>
                    <input type="password" id="password" name="password" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Entrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>