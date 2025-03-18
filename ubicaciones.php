<?php
include 'includes/config.php';
requireAuth();

// Obtener ubicaciones
$stmt = $pdo->query("SELECT * FROM ubicaciones");
$ubicaciones = $stmt->fetchAll();
?>

<?php include 'includes/header.php'; ?>

<h2 class="mb-4">Gestión de Ubicaciones</h2>

<button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#nuevaUbicacion">
    <i class="bi bi-plus-circle"></i> Nueva Ubicación
</button>

<table class="table table-hover">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($ubicaciones as $ubicacion): ?>
        <tr>
            <td><?= htmlspecialchars($ubicacion['nombre']) ?></td>
            <td><?= htmlspecialchars($ubicacion['descripcion']) ?></td>
            <td>
                <button class="btn btn-sm btn-warning">
                    <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-danger">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Modal Nueva Ubicación -->
<div class="modal fade" id="nuevaUbicacion">
    <!-- Contenido del modal -->
</div>

<?php include 'includes/footer.php'; ?>