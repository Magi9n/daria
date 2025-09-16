<?php
// Seguridad mínima: permitir sólo llamadas desde localhost

// Cargar WP
require_once __DIR__ . '/wp-load.php';

// Parámetros: usuario y nueva contraseña
$user_login = 'tempadmin';
$new_pass = 'TempPass123!';  // <- Cambia aquí si quieres otra

$user = get_user_by('login', $user_login);
if (!$user) {
    echo "User not found: $user_login\n";
    exit;
}

// Forzar el cambio de contraseña
wp_set_password($new_pass, $user->ID);

echo "Password reset for user {$user_login} to '{$new_pass}'. Delete this file immediately after use.\n";
