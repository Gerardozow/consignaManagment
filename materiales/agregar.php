<?php
require '../includes/auth.php';
require '../includes/db.php';
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = strtoupper(trim($_POST['codigo']));
    $descripcion = trim($_POST['descripcion']);
    $unidad = trim($_POST['unidad']) ?: 'pz';

    if (!$codigo || !$descripcion) {
        $_SESSION['mensaje'] = [
            'icono' => 'error',
            'titulo' => 'Datos incompletos',
            'texto' => 'Código y descripción son obligatorios.'
        ];
    } else {
        // Verificar duplicado
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM materiales WHERE codigo = ?");
        $stmt->execute([$codigo]);
        if ($stmt->fetchColumn() > 0) {
            $_SESSION['mensaje'] = [
                'icono' => 'warning',
                'titulo' => 'Código duplicado',
                'texto' => 'Este código ya está registrado.'
            ];
        } else {
            $stmt = $pdo->prepare("INSERT INTO materiales (codigo, descripcion, unidad) VALUES (?, ?, ?)");
            $stmt->execute([$codigo, $descripcion, $unidad]);

            $_SESSION['mensaje'] = [
                'icono' => 'success',
                'titulo' => 'Material creado',
                'texto' => 'El material fue registrado correctamente.'
            ];
            header("Location: index.php");
            exit;
        }
    }
}
?>

<div class="container mt-4">
    <h2>Registrar Nuevo Material</h2>

    <form method="POST" class="row g-3 needs-validation" novalidate>
        <div class="col-md-4">
            <label class="form-label">Código *</label>
            <input type="text" name="codigo" class="form-control" required>
            <div class="invalid-feedback">Este campo es obligatorio.</div>
        </div>
        <div class="col-md-6">
            <label class="form-label">Descripción *</label>
            <input type="text" name="descripcion" class="form-control" required>
            <div class="invalid-feedback">Este campo es obligatorio.</div>
        </div>
        <div class="col-md-2">
            <label class="form-label">Unidad</label>
            <input type="text" name="unidad" class="form-control" value="pz">
        </div>
        <div class="col-12 text-end">
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-success">Guardar</button>
        </div>
    </form>
</div>

<!-- Validación con Bootstrap 5 -->
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
