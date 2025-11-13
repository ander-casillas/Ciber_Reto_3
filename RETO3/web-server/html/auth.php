// Codigo optimizado login.php
 
 
 
 
<?php
session_start();
 
// 🔹 Configuración LDAP
$ldap_host = "ldap://10.11.0.127:389";
$admin_dn  = "cn=admin,dc=txiribiton,dc=local";
$admin_pass = "admin123";
$base_users = "ou=Users,dc=txiribiton,dc=local";
 
// 🔹 Recoger datos del formulario
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');
if (!$username || !$password) die("❌ Usuario y contraseña no pueden estar vacíos.");
 
// 🔹 Conexión LDAP
$conn = ldap_connect($ldap_host);
ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($conn, LDAP_OPT_REFERRALS, 0);
if (!$conn) die("❌ No se pudo conectar al servidor LDAP.");
 
// 🔹 Bind admin para buscar DN del usuario
if (!@ldap_bind($conn, $admin_dn, $admin_pass)) die("❌ Falló la autenticación con admin.");
 
// 🔹 Buscar DN del usuario
$search = ldap_search($conn, $base_users, "(uid=$username)", ["dn"]);
$entries = ldap_get_entries($conn, $search);
if ($entries["count"] == 0) die("❌ Usuario no encontrado.");
$user_dn = $entries[0]["dn"];
 
// 🔹 Bind con el usuario para autenticar contraseña
if (!@ldap_bind($conn, $user_dn, $password)) die("❌ Usuario o contraseña incorrectos.");
 
// 🔹 Guardar datos del usuario en sesión
$_SESSION['username'] = $username;
$_SESSION['dn'] = $user_dn;
 
// 🔹 Mostrar información
echo "✅ ¡Login exitoso! Bienvenido $username.<br>";
echo "<a href='index.php'>Ir al panel</a>";
 
// 🔹 Cerrar conexión LDAP
ldap_unbind($conn);
?>