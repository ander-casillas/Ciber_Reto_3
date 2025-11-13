<?php

require_once __DIR__ . '/includes/session_bootstrap.php';
require_once __DIR__ . '/includes/auth.php';

$is_logged_in = isset($_SESSION['username']);
$user_name = $is_logged_in ? $_SESSION['username'] : '';
$user_role = $is_logged_in ? implode(', ', $_SESSION['groups'] ?? []) : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Txiribitones'; ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="logo">
                <i class="fa-solid fa-building-columns"></i>
            </div>
            <nav class="menu">
    <ul>
        <?php if ($is_logged_in && user_has_role('administrador')): ?>
            <!-- ADMINISTRADOR: puede ver todo -->
            <li class="<?php echo (isset($active_page) && $active_page == 'inicio') ? 'active' : ''; ?>">
                <a href="index.php">
                    <i class="fas fa-home"></i>
                    <span>Inicio</span>
                </a>
            </li>
            <li class="<?php echo (isset($active_page) && $active_page == 'tarjetas') ? 'active' : ''; ?>">
                <a href="tarjetas.php">
                    <i class="fa-solid fa-credit-card"></i>
                    <span>Tarjetas</span>
                </a>
            </li>
            <li class="<?php echo (isset($active_page) && $active_page == 'gastos') ? 'active' : ''; ?>">
                <a href="gastos.php">
                    <i class="fas fa-wallet"></i>
                    <span>Gastos</span>
                </a>
            </li>
            <li class="<?php echo (isset($active_page) && $active_page == 'analisis') ? 'active' : ''; ?>">
                <a href="analisis.php">
                    <i class="fas fa-chart-line"></i>
                    <span>Análisis</span>
                </a>
            </li>

        <?php elseif ($is_logged_in && user_has_role('empleado')): ?>
            <!-- EMPLEADO: solo Tarjetas -->
             <li class="<?php echo (isset($active_page) && $active_page == 'inicio') ? 'active' : ''; ?>">
                <a href="index.php">
                    <i class="fas fa-home"></i>
                    <span>Inicio</span>
                </a>
            </li>
            <li class="<?php echo (isset($active_page) && $active_page == 'tarjetas') ? 'active' : ''; ?>">
                <a href="tarjetas.php">
                    <i class="fa-solid fa-credit-card"></i>
                    <span>Tarjetas</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>

            <div class="profile">
                <?php if ($is_logged_in): ?>
                    <div class="avatar">
                        <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=100&q=80" alt="Perfil">
                    </div>
                    <div class="user-info">
                        <h3><?php echo htmlspecialchars($user_name); ?></h3>
                        <p><?php echo htmlspecialchars($user_role); ?></p>
                    </div>
                    <a href="pagina/logout.php" class="logout-button" title="Cerrar Sesión">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                <?php else: ?>
                    <a href="pagina/index.php" class="login-button">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Iniciar Sesión</span>
                    </a>
                <?php endif; ?>
            </div>
        </aside>
        <main class="content">
            <?php if (isset($page_header)): ?>
                <header>
                    <h1><?php echo $page_header; ?></h1>
                    <?php if (isset($page_description)): ?>
                        <p><?php echo $page_description; ?></p>
                    <?php endif; ?>
                </header>
            <?php endif; ?>

            <!-- Contenido específico de la página -->
            <?php echo isset($page_content) ? $page_content : ''; ?>
        </main>
    </div>
    <script>
document.addEventListener('DOMContentLoaded', () => {
    const menuItems = document.querySelectorAll('.menu li');

    menuItems.forEach(item => {
        item.addEventListener('click', () => {
            menuItems.forEach(i => i.classList.remove('active'));
            item.classList.add('active');

            const icon = item.querySelector('i');
            icon.style.transform = 'scale(1.2)';
            setTimeout(() => {
                icon.style.transform = 'scale(1)';
            }, 200);
        });
    });

    menuItems.forEach(item => {
        item.addEventListener('mouseenter', (e) => {
            const highlight = document.createElement('div');
            highlight.classList.add('highlight');
            highlight.style.position = 'absolute';
            highlight.style.top = '0';
            highlight.style.left = '0';
            highlight.style.width = '100%';
            highlight.style.height = '100%';
            highlight.style.borderRadius = '16px';
            highlight.style.background = 'radial-gradient(circle at ' + (e.offsetX) + 'px ' + (e.offsetY) + 'px, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 70%)';
            highlight.style.pointerEvents = 'none';

            item.appendChild(highlight);

            setTimeout(() => {
                highlight.style.opacity = '0';
                setTimeout(() => {
                    item.removeChild(highlight);
                }, 300);
            }, 500);
        });
    });

    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const rotateY = (x / rect.width - 0.5) * 10;
            const rotateX = (y / rect.height - 0.5) * -10;

            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.05)`;
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0) scale(1)';
            card.style.transition = 'transform 0.5s ease';
        });
    });
});
    </script>
</body>
</html>
