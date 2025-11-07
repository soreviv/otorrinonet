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
                    <h1 class="text-xl font-bold text-gray-800">Citas Agendadas</h1>
                </div>
            </header>
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200 p-6">
                <div class="container mx-auto">
                    <div class="bg-white shadow-md rounded my-6">
                        <table class="min-w-full leading-normal">
                            <thead>
                                <tr>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Paciente</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fecha / Hora</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Contacto</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tipo</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Estado</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($appointments as $appointment): ?>
                                    <tr>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap"><?= htmlspecialchars($appointment['nombre']) ?></p>
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap"><?= htmlspecialchars($appointment['fecha_cita']) ?></p>
                                            <p class="text-gray-600 whitespace-no-wrap"><?= htmlspecialchars($appointment['hora_cita']) ?></p>
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap"><?= htmlspecialchars($appointment['email']) ?></p>
                                            <p class="text-gray-600 whitespace-no-wrap"><?= htmlspecialchars($appointment['telefono']) ?></p>
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap"><?= htmlspecialchars($appointment['tipo_consulta']) ?></p>
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <form action="/admin/appointments/status" method="POST" class="inline-flex">
                                                <input type="hidden" name="id" value="<?= $appointment['id'] ?>">
                                                <select name="status" class="appearance-none bg-transparent border-none text-gray-900 js-autosubmit">
                                                    <option value="pendiente" <?= $appointment['status'] === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                                    <option value="confirmada" <?= $appointment['status'] === 'confirmada' ? 'selected' : '' ?>>Confirmada</option>
                                                    <option value="completada" <?= $appointment['status'] === 'completada' ? 'selected' : '' ?>>Completada</option>
                                                    <option value="cancelada" <?= $appointment['status'] === 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                                                    <option value="no_asistio" <?= $appointment['status'] === 'no_asistio' ? 'selected' : '' ?>>No Asistió</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                                            <!-- Botones de acción adicionales -->
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