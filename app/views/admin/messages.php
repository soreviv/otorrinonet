<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Administración') ?></title>
    <link href="/assets/css/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen bg-gray-200">
        <!-- Sidebar -->
        <?php include '_sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow">
                <div class="container mx-auto px-6 py-4">
                    <h1 class="text-xl font-bold text-gray-800">Mensajes de Contacto</h1>
                </div>
            </header>
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200 p-6">
                <div class="container mx-auto">
                    <div class="bg-white shadow-md rounded my-6">
                        <table class="min-w-full leading-normal">
                            <thead>
                                <tr>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Remitente</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fecha</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Asunto</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Estado</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($messages as $message): ?>
                                    <tr>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap"><?= htmlspecialchars($message['nombre']) ?></p>
                                            <p class="text-gray-600 whitespace-no-wrap"><?= htmlspecialchars($message['email']) ?></p>
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap"><?= htmlspecialchars($message['fecha_envio']) ?></p>
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap"><?= htmlspecialchars($message['asunto']) ?></p>
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <form action="/admin/messages/status" method="POST" class="inline-flex">
                                                <input type="hidden" name="id" value="<?= $message['id'] ?>">
                                                <select name="status" class="appearance-none bg-transparent border-none text-gray-900 js-autosubmit">
                                                    <option value="nuevo" <?= $message['status'] === 'nuevo' ? 'selected' : '' ?>>Nuevo</option>
                                                    <option value="leido" <?= $message['status'] === 'leido' ? 'selected' : '' ?>>Leído</option>
                                                    <option value="respondido" <?= $message['status'] === 'respondido' ? 'selected' : '' ?>>Respondido</option>
                                                    <option value="archivado" <?= $message['status'] === 'archivado' ? 'selected' : '' ?>>Archivado</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                                            <!-- Aquí irán los botones de acción -->
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>