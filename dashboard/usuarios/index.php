<?php
include '../../includes/config.php';
if (!tiene_permiso('gestion_usuarios')) {
    header("Location: ../index.php");
    exit();
}

// Obtener todos los usuarios
$stmt = $conn->query("SELECT * FROM usuarios");
$usuarios = $stmt->fetchAll();
?>

<?php include '../../includes/header.php'; ?>

<div class="container-fluid">
    <h2>Gestión de Usuarios</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Permisos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?= $usuario['nombre'] ?></td>
                <td><?= $usuario['username'] ?></td>
                <td><?= $usuario['rol'] ?></td>
                <td>
                    <?php 
                    $permisos = obtener_permisos_usuario($usuario['id']);
                    echo implode(', ', $permisos);
                    ?>
                </td>
                <td>
                    <a href="editar.php?id=<?= $usuario['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../../includes/footer.php'; ?>