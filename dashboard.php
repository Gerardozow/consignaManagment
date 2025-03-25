<?php
require 'includes/auth.php';
require 'includes/db.php';
require 'includes/permisos.php';

if (!tienePermiso($pdo, $_SESSION['user_id'], 'ver_dashboard')) {
    echo "<div class='alert alert-danger'>No tienes permiso para ver el dashboard.</div>";
    exit;
}

include 'includes/header.php';
?>

<div class="container mt-5">
    <h1>Bienvenido al Dashboard</h1>
    <p>Aquí podrás gestionar tus materiales en consigna.</p>
</div>

<?php include 'includes/footer.php'; ?>
