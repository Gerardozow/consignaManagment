<?php
include 'includes/config.php';
requireAuth();

// Manejar búsqueda
$search = $_GET['search'] ?? '';

$sql = "SELECT i.*, u.nombre as ubicacion 
        FROM inventario i
        LEFT JOIN ubicaciones u ON i.ubicacion_id = u.id
        WHERE numero_parte LIKE ? OR descripcion LIKE ?";
$params = ["%$search%", "%$search%"];

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$inventario = $stmt->fetchAll();
?>

<?php include 'includes/header.php'; ?>

<div class="d-flex justify-content-between mb-3">
    <h2>Inventario en Consignación</h2>
    <div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoItem">
            <i class="bi bi-plus-circle"></i> Nuevo Item
        </button>
    </div>
</div>

<div class="mb-3">
    <form method="GET">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Buscar...">
            <button class="btn btn-outline-secondary" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </div>
    </form>
</div>

<table class="table table-hover table-striped">
    <thead class="table-dark">
        <tr>
            <th>N° Parte</th>
            <th>Descripción</th>
            <th>Cantidad</th>
            <th>Ubicación</th>
            <th>Matrícula</th>
            <th>Fecha Sunset</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($inventario as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['numero_parte']) ?></td>
            <td><?= htmlspecialchars($item['descripcion']) ?></td>
            <td><?= $item['cantidad'] ?></td>
            <td><?= $item['ubicacion'] ?? 'Sin ubicación' ?></td>
            <td><?= htmlspecialchars($item['matricula']) ?></td>
            <td><?= $item['fecha_sunset'] ?></td>
            <td>
                <button class="btn btn-sm btn-warning" onclick="editarItem(<?= $item['id'] ?>)">
                    <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="eliminarItem(<?= $item['id'] ?>)">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Modal Nuevo Item -->
<div class="modal fade" id="nuevoItem">
    <!-- Contenido del modal similar al de edición -->
</div>

<?php include 'includes/footer.php'; ?>