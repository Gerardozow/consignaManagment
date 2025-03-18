<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

// CRUD de Ubicaciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['crear'])) {
        // Crear nueva ubicación
    } elseif (isset($_POST['editar'])) {
        // Editar ubicación existente
    } elseif (isset($_POST['eliminar'])) {
        // Eliminar ubicación
    }
}

$ubicaciones = $pdo->query("SELECT * FROM ubicaciones ORDER BY nombre")->fetchAll();

include __DIR__ . '/../includes/header.php';
?>

<div class="card shadow">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Gestión de Ubicaciones</h5>
    </div>
    <div class="card-body">
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalUbicacion">
            <i class="bi bi-plus-circle"></i> Nueva Ubicación
        </button>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
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
                            <button class="btn btn-sm btn-warning" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalUbicacion"
                                    data-id="<?= $ubicacion['id'] ?>"
                                    data-nombre="<?= $ubicacion['nombre'] ?>"
                                    data-descripcion="<?= $ubicacion['descripcion'] ?>">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalEliminar"
                                    data-id="<?= $ubicacion['id'] ?>">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Ubicación -->
<div class="modal fade" id="modalUbicacion" tabindex="-1">
    <!-- Contenido del modal -->
</div>

<!-- Modal Eliminar -->
<div class="modal fade" id="modalEliminar" tabindex="-1">
    <!-- Contenido del modal de eliminación -->
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>