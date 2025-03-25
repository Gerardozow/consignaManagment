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

// Registrar o actualizar inventario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ubicacion = trim($_POST['ubicacion']);
    $matricula = strtoupper(trim($_POST['matricula']));
    $cantidad = (int) $_POST['cantidad'];

    if (!$ubicacion || !$matricula || $cantidad < 0) {
        $_SESSION['mensaje'] = [
            'icono' => 'error',
            'titulo' => 'Datos inválidos',
            'texto' => 'Todos los campos son obligatorios y la cantidad debe ser válida.'
        ];
    } else {
        // Verificar si ya existe ese registro exacto
        $stmt = $pdo->prepare("SELECT id FROM inventario_material WHERE material_id = ? AND ubicacion = ? AND matricula = ?");
        $stmt->execute([$material_id, $ubicacion, $matricula]);
        $registro = $stmt->fetch();

        if ($registro) {
            // Actualizar cantidad
            $stmt = $pdo->prepare("UPDATE inventario_material SET cantidad = cantidad + ? WHERE id = ?");
            $stmt->execute([$cantidad, $registro['id']]);
            $mensaje = "Cantidad actualizada.";
        } else {
            // Insertar nuevo registro
            $stmt = $pdo->prepare("INSERT INTO inventario_material (material_id, ubicacion, matricula, cantidad) VALUES (?, ?, ?, ?)");
            $stmt->execute([$material_id, $ubicacion, $matricula, $cantidad]);
            $mensaje = "Registro agregado.";
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
// Manejar movimiento de material
if (isset($_POST['accion']) && $_POST['accion'] === 'mover') {
    $ubicacion_origen = trim($_POST['origen']);
    $ubicacion_destino = trim($_POST['destino']);
    $matricula = strtoupper(trim($_POST['matricula']));
    $cantidad = (int) $_POST['cantidad_mover'];

    if (!$ubicacion_origen || !$ubicacion_destino || !$matricula || $cantidad <= 0) {
        $_SESSION['mensaje'] = [
            'icono' => 'error',
            'titulo' => 'Datos incompletos',
            'texto' => 'Todos los campos del movimiento son obligatorios.'
        ];
    } else {
        // Verificar existencia en origen
        $stmt = $pdo->prepare("SELECT id, cantidad FROM inventario_material WHERE material_id = ? AND ubicacion = ? AND matricula = ?");
        $stmt->execute([$material_id, $ubicacion_origen, $matricula]);
        $origen = $stmt->fetch();

        if (!$origen || $origen['cantidad'] < $cantidad) {
            $_SESSION['mensaje'] = [
                'icono' => 'warning',
                'titulo' => 'Stock insuficiente',
                'texto' => 'No hay suficiente inventario en la ubicación de origen.'
            ];
        } else {
            // Descontar de origen
            $stmt = $pdo->prepare("UPDATE inventario_material SET cantidad = cantidad - ? WHERE id = ?");
            $stmt->execute([$cantidad, $origen['id']]);

            // Sumar en destino (crear o actualizar)
            $stmt = $pdo->prepare("SELECT id FROM inventario_material WHERE material_id = ? AND ubicacion = ? AND matricula = ?");
            $stmt->execute([$material_id, $ubicacion_destino, $matricula]);
            $destino = $stmt->fetch();

            if ($destino) {
                $stmt = $pdo->prepare("UPDATE inventario_material SET cantidad = cantidad + ? WHERE id = ?");
                $stmt->execute([$cantidad, $destino['id']]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO inventario_material (material_id, ubicacion, matricula, cantidad) VALUES (?, ?, ?, ?)");
                $stmt->execute([$material_id, $ubicacion_destino, $matricula, $cantidad]);
            }

            $_SESSION['mensaje'] = [
                'icono' => 'success',
                'titulo' => 'Movimiento realizado',
                'texto' => "Se movieron $cantidad unidades de '$ubicacion_origen' a '$ubicacion_destino'."
            ];
        }

        header("Location: ubicaciones.php?material=$material_id");
        exit;
    }
}

// Obtener inventario actual por matrícula y ubicación
$stmt = $pdo->prepare("SELECT * FROM inventario_material WHERE material_id = ? ORDER BY ubicacion, matricula");
$stmt->execute([$material_id]);
$inventario = $stmt->fetchAll();

// Calcular total general
$total = array_sum(array_column($inventario, 'cantidad'));
?>

<div class="container mt-4">
    <h2>Inventario por Matrícula: <span class="text-primary"><?= htmlspecialchars($material['descripcion']) ?></span></h2>

    <!-- Formulario de registro -->
    <form method="POST" class="row g-3 mb-4 needs-validation" novalidate>
        <div class="col-md-4">
            <input type="text" name="ubicacion" class="form-control" placeholder="Ubicación" required>
            <div class="invalid-feedback">Ubicación requerida.</div>
        </div>
        <div class="col-md-4">
            <input type="text" name="matricula" class="form-control" placeholder="Matrícula" required>
            <div class="invalid-feedback">Matrícula requerida.</div>
        </div>
        <div class="col-md-2">
            <input type="number" name="cantidad" class="form-control" placeholder="Cantidad" min="0" required>
            <div class="invalid-feedback">Cantidad válida requerida.</div>
        </div>
        <div class="col-md-2">
            <button class="btn btn-success w-100">Registrar</button>
        </div>
    </form>

    <hr class="my-4">
        <h4>Mover Material entre Ubicaciones</h4>

        <form method="POST" class="row g-3 needs-validation" novalidate>
            <input type="hidden" name="accion" value="mover">

            <div class="col-md-3">
                <label>Ubicación Origen</label>
                <input type="text" name="origen" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label>Ubicación Destino</label>
                <input type="text" name="destino" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label>Matrícula</label>
                <input type="text" name="matricula" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label>Cantidad</label>
                <input type="number" name="cantidad_mover" class="form-control" min="1" required>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button class="btn btn-primary w-100">Mover</button>
            </div>
        </form>

    <!-- Tabla de inventario -->
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Ubicación</th>
                    <th>Matrícula</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($inventario as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['ubicacion']) ?></td>
                        <td><?= htmlspecialchars($item['matricula']) ?></td>
                        <td><?= $item['cantidad'] ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($inventario)): ?>
                    <tr><td colspan="3" class="text-center">Sin inventario registrado.</td></tr>
                <?php else: ?>
                    <tr class="table-light fw-bold">
                        <td colspan="2">Total</td>
                        <td><?= $total ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Validación JS -->
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
