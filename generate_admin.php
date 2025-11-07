<?php

require __DIR__ . '/otorrinonet/app/core/Database.php';

// --- Configuración ---
$username = 'admin';
$password = 'Admin123!';
// ---------------------

$hash = password_hash($password, PASSWORD_BCRYPT);

echo "--------------------------------------------------------------------
";
echo "Script para crear/actualizar usuario administrador
";
echo "--------------------------------------------------------------------
";
echo "Usuario: " . $username . "
";
echo "Password: " . $password . "
";
echo "Hash Generado: " . $hash . "

";

echo "Ejecuta el siguiente comando SQL en tu base de datos PostgreSQL:
";
echo "--------------------------------------------------------------------

";

$sql = "-- Primero, intenta eliminar al usuario por si ya existe con un hash incorrecto.
";
$sql .= "DELETE FROM users WHERE username = '" . $username . "';

";
$sql .= "-- Luego, inserta el nuevo usuario con el hash correcto.
";
$sql .= "INSERT INTO users (username, email, password_hash, nombre_completo, rol) VALUES ('" . $username . "', 'admin@otorrinonet.com', '" . $hash . "', 'Administrador del Sistema', 'admin');

";
$sql .= "-- Si prefieres actualizar en lugar de borrar y crear, usa este comando:
";
$sql .= "-- UPDATE users SET password_hash = '" . $hash . "' WHERE username = '" . $username . "';
";

echo $sql;

echo "--------------------------------------------------------------------
";

?>