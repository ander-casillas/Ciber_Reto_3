<?php
// Cargar el archivo .env
$env_path = '/var/www/.env';
if (file_exists($env_path)) {
    $env_vars = parse_ini_file($env_path, false, INI_SCANNER_RAW);
    foreach ($env_vars as $key => $value) {
        putenv("$key=$value");
    }
} else {
    die("❌ No se encontró el archivo .env");
}

// Ahora puedes usar las variables con getenv()
$ldap_host    = getenv('LDAP_HOST');
$admin_dn     = getenv('LDAP_ADMIN_DN');
$admin_pass   = getenv('LDAP_ADMIN_PASS');
$base_users   = getenv('LDAP_BASE_USERS');
$base_groups  = getenv('LDAP_BASE_GROUPS');
?>
