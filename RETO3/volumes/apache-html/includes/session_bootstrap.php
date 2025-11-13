<?php
// Nombre de cookie de sesión
session_name('TXIRIBI_SESSID');

// Cookies seguras (ajusta secure=true si usas HTTPS)
$cookieParams = [
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Lax',
];
session_set_cookie_params($cookieParams);

// Endurecer la sesión
ini_set('session.use_only_cookies', '1');
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/* -----------------------------
   ⏱ Expiración por inactividad
------------------------------ */
$session_timeout = 900; // 15 min, ajusta a tu gusto

if (isset($_SESSION['LAST_ACTIVITY'])) {
    $inactive = time() - $_SESSION['LAST_ACTIVITY'];
    if ($inactive > $session_timeout) {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        // limpiar APP_TOKEN por si existiese
        setcookie('APP_TOKEN', '', time() - 42000, '/', '', false, true);
        session_destroy();
        header("Location: /pagina/index.php?expired=1");
        exit;
    }
}
$_SESSION['LAST_ACTIVITY'] = time();

/* -----------------------------------
   ⏳ (Opcional) Vida máxima de sesión
------------------------------------ */

$max_session_lifetime = 3600; // 1 hora
if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} elseif (time() - $_SESSION['CREATED'] > $max_session_lifetime) {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    setcookie('APP_TOKEN', '', time() - 42000, '/', '', false, true);
    session_destroy();
    header("Location: /pagina/index.php?expired=1");
    exit;
}


// 🔐 Importante: NO crear token aquí.
// Solo reflejamos cookie APP_TOKEN si ya hay login y token en la sesión.
if (!empty($_SESSION['username']) && !empty($_SESSION['app_token'])) {
    setcookie('APP_TOKEN', $_SESSION['app_token'], [
        'expires'  => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => false, // true si HTTPS
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
} else {
    // Si no hay login, nos aseguramos de no dejar cookie colgando
    setcookie('APP_TOKEN', '', time() - 42000, '/', '', false, true);
}
