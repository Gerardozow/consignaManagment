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

// Insertar nueva ubicación/cantidad
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ubicacion = trim($_POST['ubicacion']);
    $cantidad = (int) $_POST['cantidad'];

    if (!$ubicacion || $cantidad < 0) {
        $_SESSION['mensaje'] = [
            'icono' => 'error',
            'titulo' => 'Datos inválidos',
            'texto' => 'Debes proporcionar una ubicación y cantidad válida.'
        ];
    } else {
        // Verificar si ya existe esa ubicación
        $stmt = $pdo->prepare("SELECT id FROM inventario_material WHERE material_id = ? AND ubicacion = ?");
        $stmt->execute([$material_id, $ubicacion]);
        $registro = $stmt->fetch();

        if ($registro) {
            // Actualizar cantidad
            $stmt = $pdo->prepare("UPDATE inventario_material SET cantidad = cantidad + ? WHERE id = ?");
            $stmt->execute([$cantidad, $registro['id']]);

            $mensaje = "Cantidad actualizada en la ubicación.";
        } else {
            // Insertar nueva ubicación
            $stmt = $pdo->prepare("INSERT INTO inventario_material (material_id, ubicacion, cantidad) VALUES (?, ?, ?)");
            $stmt->execute([$material_id, $ubicacion, $cantidad]);

            $mensaje = "Ubicación registrada con inventario.";
        }

        $_SESSION['mensaje'] = [
            'icono' => 'success',
            'titulo' => 'Inventario actualizado',
            'texto' => $mensaje
        ];
        header("Location: inventario.php?material=$material_id");
        exit;
    }
}

// Obtener inventario por ubicación
$stmt = $pdo->prepare("SELECT * FROM inventario_material WHERE material_id = ?");
$stmt->execute([$material_id]);
$inventario = $stmt->fetchAll();

// Calcular total
$total = array_sum(array_column($inventario, 'cantidad'));
?>

<div class="container mt-4">
    <h2>Inventario de: <span class="text-primary"><?= htmlspecialchars($material['descripcion']) ?></span></h2>

    <form method="POST" class="row g-3 mb-4">
        <div class="col-md-6">
            <input type="text" name="ubicacion" class="form-control" placeholder="Ubicación" required>
        </div>
        <div class="col-md-3">
            <input type="number" name="cantidad" class="form-control" placeholder="Cantidad a agregar" min="0" required>
        </div>
        <div class="col-md-3">
            <button class="btn btn-success w-100">Registrar</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Ubicación</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($inventario as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['ubicacion']) ?></td>
                        <td><?= $item['cantidad'] ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($inventario)): ?>
                    <tr><td colspan="2" class="text-center">Sin inventario registrado.</td></tr>
                <?php else: ?>
                    <tr class="table-light fw-bold">
                        <td>Total</td>
                        <td><?= $total ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
