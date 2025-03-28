<?php
require '../includes/auth.php';
require '../includes/db.php';
include '../includes/header.php';

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = strtoupper(trim($_POST['codigo']));
    $descripcion = trim($_POST['descripcion']);
    $cantidad = (int) $_POST['cantidad'];
    $unidad = $_POST['unidad'] ?? 'pz';
    $ubicacion = trim($_POST['ubicacion']);
    $proveedor = trim($_POST['proveedor']);

    if (!$codigo || !$descripcion) {
        $_SESSION['mensaje'] = [
            'icono' => 'error',
            'titulo' => 'Faltan datos',
            'texto' => 'Código y descripción son obligatorios.'
        ];
    } else {
        // Verificar si ya existe el código
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM materiales WHERE codigo = ?");
        $stmt->execute([$codigo]);
        if ($stmt->fetchColumn() > 0) {
            $_SESSION['mensaje'] = [
                'icono' => 'warning',
                'titulo' => 'Código duplicado',
                'texto' => 'Este código ya existe en el sistema.'
            ];
        } else {
            // Insertar
            $stmt = $pdo->prepare("INSERT INTO materiales (codigo, descripcion, cantidad, unidad, ubicacion, proveedor)
                                   VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$codigo, $descripcion, $cantidad, $unidad, $ubicacion, $proveedor]);

            $_SESSION['mensaje'] = [
                'icono' => 'success',
                'titulo' => 'Material agregado',
                'texto' => 'El material se registró correctamente.'
            ];

            header("Location: index.php");
            exit;
        }
    }
}
?>

<div class="container mt-4">
    <h2>Agregar Nuevo Material</h2>

    <form method="POST" class="row g-3">
        <div class="col-md-4">
            <label>Código</label>
            <input type="text" name="codigo" class="form-control" required>
        </div>
        <div class="col-md-8">
            <label>Descripción</label>
            <input type="text" name="descripcion" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label>Cantidad</label>
            <input type="number" name="cantidad" class="form-control" value="0" min="0" required>
        </div>
        <div class="col-md-3">
            <label>Unidad</label>
            <input type="text" name="unidad" class="form-control" value="pz">
        </div>
        <div class="col-md-3">
            <label>Ubicación</label>
            <input type="text" name="ubicacion" class="form-control">
        </div>
        <div class="col-md-3">
            <label>Proveedor</label>
            <input type="text" name="proveedor" class="form-control">
        </div>
        <div class="col-12 text-end">
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
            <button class="btn btn-success">Guardar</button>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
