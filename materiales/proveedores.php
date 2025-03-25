<?php
require '../includes/auth.php';
require '../includes/db.php';
include '../includes/header.php';

$material_id = $_GET['material'] ?? null;
if (!$material_id) {
    $_SESSION['mensaje'] = [
        'icono' => 'error',
        'titulo' => 'Error',
        'texto' => 'Material no especificado.'
    ];
    header("Location: index.php");
    exit;
}

// Obtener info del material
$stmt = $pdo->prepare("SELECT * FROM materiales WHERE id = ?");
$stmt->execute([$material_id]);
$material = $stmt->fetch();

if (!$material) {
    $_SESSION['mensaje'] = [
        'icono' => 'error',
        'titulo' => 'No encontrado',
        'texto' => 'El material no existe.'
    ];
    header("Location: index.php");
    exit;
}

// Agregar proveedor
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $proveedor = trim($_POST['proveedor']);
    if ($proveedor) {
        $stmt = $pdo->prepare("INSERT INTO material_proveedores (material_id, proveedor) VALUES (?, ?)");
        $stmt->execute([$material_id, $proveedor]);

        $_SESSION['mensaje'] = [
            'icono' => 'success',
            'titulo' => 'Proveedor agregado',
            'texto' => 'El proveedor fue asignado correctamente.'
        ];
        header("Location: proveedores.php?material=$material_id");
        exit;
    }
}

// Obtener lista de proveedores actuales
$stmt = $pdo->prepare("SELECT * FROM material_proveedores WHERE material_id = ?");
$stmt->execute([$material_id]);
$proveedores = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h2>Proveedores de: <span class="text-primary"><?= htmlspecialchars($material['descripcion']) ?></span></h2>

    <form method="POST" class="row g-3 mb-4">
        <div class="col-md-10">
            <input type="text" name="proveedor" class="form-control" placeholder="Nombre del proveedor" required>
        </div>
        <div class="col-md-2">
            <button class="btn btn-success w-100">Agregar</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Proveedor</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($proveedores as $prov): ?>
                    <tr>
                        <td><?= htmlspecialchars($prov['proveedor']) ?></td>
                        <td>
                            <a href="eliminar_proveedor.php?id=<?= $prov['id'] ?>&material=<?= $material_id ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('¿Eliminar este proveedor?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($proveedores)): ?>
                    <tr><td colspan="2" class="text-center">Sin proveedores registrados.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
