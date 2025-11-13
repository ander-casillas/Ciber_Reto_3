<?php
require_once __DIR__ . '/../includes/session_bootstrap.php';

// Borrar variables y destruir sesión
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
// (Opcional) borrar cookie APP_TOKEN
setcookie('APP_TOKEN', '', time() - 42000, '/');

session_destroy();

// Redirigir al inicio
header("Location: ../index.php");
exit;
?>