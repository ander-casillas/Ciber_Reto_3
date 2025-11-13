<?php
$page_title = 'Txiribitones - Mis tarjetas';
$active_page = 'tarjetas';
$page_header = 'Gestionar mis tarjetas';
$page_description = 'Revisa y gestiona tus tarjetas de crédito y débito';

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
            <h3>104096239426</h3>
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