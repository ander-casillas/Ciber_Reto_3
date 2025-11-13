<?php

require_once __DIR__ . '/includes/auth.php';
require_login();
require_role(['administrador', 'panchito', 'empleado']); // solo el admin puede entrar aquí

$page_title = 'Txiribitones - Panel Principal';
$active_page = 'inicio';
$page_header = 'Bienvenido a Txiribitones';
$page_description = 'Revisa tus estadísticas de proyectos hoy';

ob_start();
?>
<div class="card-container">
    <div class="card">
        <div class="card-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="card-info">
            <h3>12</h3>
            <p>Tareas Completadas</p>
        </div>
    </div>
    <div class="card">
        <div class="card-icon">
            <i class="fas fa-clock"></i>
        </div>
        <div class="card-info">
            <h3>10</h3>
            <p>Tareas Pendientes</p>
        </div>
    </div>
    <div class="card">
        <div class="card-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="card-info">
            <h3>7</h3>
            <p>Proyectos Activos</p>
        </div>
    </div>
</div>
<?php
$page_content = ob_get_clean();

include 'base.php';
?>
