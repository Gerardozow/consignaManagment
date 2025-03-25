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
    header("Location: ../materiales/index.php");
    exit;
}

// Obtener información del material
$stmt = $pdo->prepare("SELECT * FROM materiales WHERE id = ?");
$stmt->execute([$material_id]);
$material = $stmt->fetch();

if (!$material) {
    $_SESSION['mensaje'] = [
        'icono' => 'error',
        'titulo' => 'No encontrado',
        'texto' => 'El material no existe.'
    ];
    header("Location: ../materiales/index.php");
    exit;
}

// Registrar o actualizar inventario por ubicación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ubicacion = trim($_POST['ubicacion']);
    $cantidad = (int) $_POST['cantidad'];

    if (!$ubicacion || $cantidad < 0) {
        $_SESSION['mensaje'] = [
            'icono' => 'error',
            'titulo' => 'Datos inválidos',
            'texto' => 'Debes ingresar una ubicación y una cantidad válida.'
        ];
    } else {
        // Verificar si ya existe inventario en esa ubicación
        $stmt = $pdo->prepare("SELECT id FROM inventario_material WHERE material_id = ? AND ubicacion = ?");
        $stmt->execute([$material_id, $ubicacion]);
        $registro = $stmt->fetch();

        if ($registro) {
            // Actualizar cantidad (suma)
            $stmt = $pdo->prepare("UPDATE inventario_material SET cantidad = cantidad + ? WHERE id = ?");
            $stmt->execute([$cantidad, $registro['id']]);
            $mensaje = "Cantidad actualizada.";
        } else {
            // Crear nuevo registro
            $stmt = $pdo->prepare("INSERT INTO inventario_material (material_id, ubicacion, cantidad) VALUES (?, ?, ?)");
            $stmt->execute([$material_id, $ubicacion, $cantidad]);
            $mensaje = "Ubicación registrada.";
        }

        $_SESSION['mensaje'] = [
            'icono' => 'success',
            'titulo' => 'Inventario actualizado',
            'texto' => $mensaje
        ];
        header("Location: ubicaciones.php?material=$material_id");
        exit;
    }
}

// Obtener inventario actual
$stmt = $pdo->prepare("SELECT * FROM inventario_material WHERE material_id = ?");
$stmt->execute([$material_id]);
$inventario = $stmt->fetchAll();

$total = array_sum(array_column($inventario, 'cantidad'));
?>

<div class="container mt-4">
    <h2>Inventario por ubicación: <span class="text-primary"><?= htmlspecialchars($material['descripcion']) ?></span></h2>

    <!-- Formulario de registro -->
    <form method="POST" class="row g-3 mb-4 needs-validation" novalidate>
        <div class="col-md-6">
            <input type="text" name="ubicacion" class="form-control" placeholder="Ubicación" required>
            <div class="invalid-feedback">Campo requerido.</div>
        </div>
        <div class="col-md-3">
            <input type="number" name="cantidad" class="form-control" placeholder="Cantidad" min="0" required>
            <div class="invalid-feedback">Cantidad válida requerida.</div>
        </div>
        <div class="col-md-3">
            <button class="btn btn-success w-100">Registrar</button>
        </div>
    </form>

    <!-- Tabla de ubicaciones -->
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

<!-- Validación con Bootstrap -->
<script>
(() => {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>

<?php include '../includes/footer.php'; ?>
