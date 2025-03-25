<?php
require '../includes/auth.php';
require '../includes/db.php';
include '../includes/header.php';

$buscar = $_GET['buscar'] ?? '';

// Consulta principal
$sql = "
    SELECT m.id, m.codigo, m.descripcion, m.unidad, 
           SUM(COALESCE(i.cantidad, 0)) AS total
    FROM materiales m
    LEFT JOIN inventario_material i ON m.id = i.material_id
    WHERE m.codigo LIKE ? OR m.descripcion LIKE ?
    GROUP BY m.id, m.codigo, m.descripcion, m.unidad
    ORDER BY m.descripcion
";

$stmt = $pdo->prepare($sql);
$stmt->execute(["%$buscar%", "%$buscar%"]);
$materiales = $stmt->fetchAll();
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Inventario General</h2>
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
                    <th>Total Inventario</th>
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
                            <td><?= $m['total'] ?></td>
                            <td>
                                <a href="ubicaciones.php?material=<?= $m['id'] ?>" class="btn btn-sm btn-primary">Ver ubicaciones</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center">No se encontraron resultados.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
