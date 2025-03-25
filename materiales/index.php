<?php
require '../includes/auth.php';
require '../includes/db.php';
include '../includes/header.php';

$buscar = $_GET['buscar'] ?? '';

// Buscar por código o descripción
$sql = "SELECT * FROM materiales WHERE (codigo LIKE ? OR descripcion LIKE ?) ORDER BY descripcion";
$stmt = $pdo->prepare($sql);
$stmt->execute(["%$buscar%", "%$buscar%"]);
$materiales = $stmt->fetchAll();
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Materiales</h2>
        <a href="agregar.php" class="btn btn-primary">Agregar Material</a>
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-10">
            <input type="text" name="buscar" class="form-control" placeholder="Buscar por código o descripción" value="<?= htmlspecialchars($buscar) ?>">
        </div>
        <div class="col-md-2">
            <button class="btn btn-dark w-100">Buscar</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th>Unidad</th>
                    <th>Activo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($materiales) > 0): ?>
                    <?php foreach ($materiales as $m): ?>
                        <tr>
                            <td><?= htmlspecialchars($m['codigo']) ?></td>
                            <td><?= htmlspecialchars($m['descripcion']) ?></td>
                            <td><?= htmlspecialchars($m['unidad']) ?></td>
                            <td>
                                <span class="badge bg-<?= $m['activo'] ? 'success' : 'secondary' ?>">
                                    <?= $m['activo'] ? 'Activo' : 'Inactivo' ?>
                                </span>
                            </td>
                            <td>
                                <a href="editar.php?id=<?= $m['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                <a href="proveedores.php?material=<?= $m['id'] ?>" class="btn btn-sm btn-info">Proveedores</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No hay materiales registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
