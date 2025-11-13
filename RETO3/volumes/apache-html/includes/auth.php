<?php
require_once __DIR__ . '/session_bootstrap.php';

/**
 * Obliga a estar logueado y con token correcto
 */
function require_login(): void {
    // 1) Debe existir usuario en la sesión
    if (empty($_SESSION['username'])) {
        header('Location: /pagina/index.php'); // ajusta la ruta si hace falta
        exit;
    }

    // 2) (Opcional) Comprobación del token paralelo APP_TOKEN
    if (
        !isset($_COOKIE['APP_TOKEN']) ||
        !isset($_SESSION['app_token']) ||
        $_COOKIE['APP_TOKEN'] !== $_SESSION['app_token']
    ) {
        // Token inconsistente ➜ forzar logout
        session_unset();
        session_destroy();
        header('Location: /pagina/index.php');
        exit;
    }
}

/**
 * Devuelve un array de grupos "normalizados" para el usuario actual.
 * - Si en la sesión hay DNs tipo: cn=empleado,ou=groups,...
 *   extraemos el CN → "empleado".
 */
function get_normalized_groups(): array {
    if (empty($_SESSION['groups']) || !is_array($_SESSION['groups'])) {
        return [];
    }

    $normalized = [];

    foreach ($_SESSION['groups'] as $g) {
        // Si es un DN tipo cn=empleado,ou=...
        if (preg_match('/^cn=([^,]+)/i', $g, $m)) {
            $normalized[] = strtolower($m[1]); // "empleado"
        } else {
            // Si ya viene simple, lo dejamos tal cual
            $normalized[] = strtolower($g);
        }
    }

    return $normalized;
}

/**
 * Comprueba si el usuario actual tiene un rol concreto
 * (empleado, administrador, etc.)
 */
function user_has_role(string $role): bool {
    $role = strtolower($role);
    $groups = get_normalized_groups();

    return in_array($role, $groups, true);
}

/**
 * Obliga a que el usuario tenga alguno de los roles dados.
 * El rol 'administrador' SIEMPRE tiene acceso a todo.
 */
function require_role($allowed_roles): void {
    if (!is_array($allowed_roles)) {
        $allowed_roles = [$allowed_roles];
    }

    // Normalizamos roles permitidos
    $allowed_roles = array_map('strtolower', $allowed_roles);

    // Si es admin, pasa siempre
    if (user_has_role('administrador')) {
        return;
    }

    // Si tiene alguno de los roles permitidos, también pasa
    foreach ($allowed_roles as $role) {
        if (user_has_role($role)) {
            return;
        }
    }

    // Si llega aquí, no tiene permiso
    http_response_code(403);
    echo "❌ Acceso denegado. No tienes permisos suficientes para ver esta página.";
    exit;
}
