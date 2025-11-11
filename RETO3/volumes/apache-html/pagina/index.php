<?php
session_start();
require_once './Conf/config.php'; // Asegúrate de tener tus variables LDAP ahí

// Si ya hay sesión iniciada ➜ muestra el panel
if (isset($_SESSION['username'])) {
    echo "<h2>Bienvenido, {$_SESSION['username']} ✅</h2>";
    echo "<p>Grupos: " . (!empty($_SESSION['groups']) ? implode(', ', $_SESSION['groups']) : 'Ninguno') . "</p>";
    echo "<a href='logout.php'>Cerrar sesión</a>";
    exit;
}

// Si el usuario pide logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Si el formulario fue enviado ➜ procesa el login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!$username || !$password) {
        $error = "❌ Usuario y contraseña no pueden estar vacíos.";
    } else {
        // 🔹 Conexión LDAP
        $conn = ldap_connect($ldap_host);
        ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($conn, LDAP_OPT_REFERRALS, 0);

        if (!$conn) {
            $error = "❌ No se pudo conectar al servidor LDAP.";
        } elseif (!@ldap_bind($conn, $admin_dn, $admin_pass)) {
            $error = "❌ Falló la autenticación con admin.";
        } else {
            // 🔹 Buscar DN del usuario
            $search = ldap_search($conn, $base_users, "(uid=$username)", ["dn"]);
            $entries = ldap_get_entries($conn, $search);

            if ($entries["count"] == 0) {
                $error = "❌ Usuario no encontrado.";
            } else {
                $user_dn = $entries[0]["dn"];

                // 🔹 Intentar login con usuario
                if (@ldap_bind($conn, $user_dn, $password)) {
                    $_SESSION['username'] = $username;
                    $_SESSION['dn'] = $user_dn;

                    // 🔹 Buscar grupos
                    if (@ldap_bind($conn, $admin_dn, $admin_pass)) {
                        $group_search = ldap_search($conn, $base_groups, "(memberUid=$username)", ["cn"]);
                        $user_groups = [];
                        if ($group_search) {
                            $group_entries = ldap_get_entries($conn, $group_search);
                            for ($i = 0; $i < $group_entries["count"]; $i++) {
                                $user_groups[] = $group_entries[$i]["cn"][0];
                            }
                        }
                        $_SESSION['groups'] = $user_groups;
                    }

                    ldap_unbind($conn);
                    header("Location: ../index.php");
                    exit;
                } else {
                    $error = "❌ Usuario o contraseña incorrectos.";
                }
            }
        }
        ldap_unbind($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login LDAP</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="login-page">
    <div class="login-container">
        <h2>Iniciar sesión (LDAP)</h2>
        <form method="POST" class="login-form">
            <label>Usuario:</label>
            <input type="text" name="username" placeholder="Ingresa tu usuario" required>
            <label>Contraseña:</label>
            <input type="password" name="password" placeholder="Ingresa tu contraseña" required>
            <input type="submit" value="Ingresar">
        </form>
        <?php if (isset($error)): ?>
            <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
