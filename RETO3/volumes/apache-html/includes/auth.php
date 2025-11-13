<?php
require_once __DIR__ . '/session_bootstrap.php';

function require_login(): void {
    // 1) Debe existir usuario en la sesión
    if (empty($_SESSION['username'])) {
        header('Location: /pagina/index.php');
        exit;
    }

    // 2) (Opcional) Si usas cookie APP_TOKEN paralela, comprueba que coincide:
    if (!isset($_COOKIE['APP_TOKEN']) || $_COOKIE['APP_TOKEN'] !== $_SESSION['app_token']) {
        // Token inconsistente ➜ forzar logout
        session_unset();
        session_destroy();
        header('Location: /pagina/index.php');
        exit;
    }
}
