<?php
require '../includes/auth.php';
require '../includes/db.php';
include '../includes/header.php';

// Filtros
$busqueda = $_GET['buscar'] ?? '';
$proveedor = $_GET['proveedor'] ?? '';
$ubicacion = $_GET['ubicacion'] ?? '';

// Consulta dinámica
$sql = "SELECT * FROM materiales WHERE activo = 1";
$params = [];

if (!empty($busqueda)) {
    $sql .= " AND (codigo LIKE ? OR descripcion LIKE ?)";
    $params[] = "%$busqueda%";
    $params[] = "%$busqueda%";
}

if (!empty($proveedor)) {
    $sql .= " AND proveedor = ?";
    $params[] = $proveedor;
}

if (!empty($ubicacion)) {
    $sql .= " AND ubicacion = ?";
    $params[] = $ubicacion;
}

$sql .= " ORDER BY descripcion ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$materiales = $stmt->fetchAll();

// Obtener listas únicas para filtros
$proveedores = $pdo->query("SELECT DISTINCT proveedor FROM materiales WHERE proveedor IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN);
$ubicaciones = $pdo->query("SELECT DISTINCT ubicacion FROM materiales WHERE ubicacion IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN);

?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Inventario</h2>
        <a href="agregar.php" class="btn btn-primary">Agregar Material</a>
    </div>

    <!-- Filtros -->
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="buscar" class="form-control" placeholder="Buscar código o descripción" value="<?= htmlspecialchars($busqueda) ?>">
        </div>
        <div class="col-md-3">
            <select name="proveedor" class="form-select">
                <option value="">Todos los proveedores</option>
                <?php foreach ($proveedores as $prov): ?>
                    <option value="<?= $prov ?>" <?= $proveedor === $prov ? 'selected' : '' ?>><?= $prov ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="ubicacion" class="form-select">
                <option value="">Todas las ubicaciones</option>
                <?php foreach ($ubicaciones as $ubi): ?>
                    <option value="<?= $ubi ?>" <?= $ubicacion === $ubi ? 'selected' : '' ?>><?= $ubi ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-dark w-100">Filtrar</button>
        </div>
    </form>

    <!-- Tabla -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Unidad</th>
                    <th>Ubicación</th>
                    <th>Proveedor</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($materiales) > 0): ?>
                    <?php foreach ($materiales as $m): ?>
                        <tr>
                            <td><?= htmlspecialchars($m['codigo']) ?></td>
                            <td><?= htmlspecialchars($m['descripcion']) ?></td>
                            <td><?= $m['cantidad'] ?></td>
                            <td><?= htmlspecialchars($m['unidad']) ?></td>
                            <td><?= htmlspecialchars($m['ubicacion']) ?></td>
                            <td><?= htmlspecialchars($m['proveedor']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No hay materiales que coincidan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
