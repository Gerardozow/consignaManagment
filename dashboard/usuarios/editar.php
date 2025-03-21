<?php
include '../../includes/config.php';
if (!tiene_permiso('gestion_usuarios')) {
    header("Location: ../index.php");
    exit();
}

// Obtener usuario
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$_GET['id']]);
$usuario = $stmt->fetch();

// Todos los permisos disponibles
$todos_permisos = [
    'editar_materiales',
    'eliminar_materiales',
    'ver_reportes',
    'exportar_datos'
];

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Actualizar rol
    $rol = $_POST['rol'];
    $stmt = $conn->prepare("UPDATE usuarios SET rol = ? WHERE id = ?");
    $stmt->execute([$rol, $_GET['id']]);
    
    // Actualizar permisos
    $conn->prepare("DELETE FROM usuario_permisos WHERE usuario_id = ?")->execute([$_GET['id']]);
    
    if (!empty($_POST['permisos'])) {
        foreach ($_POST['permisos'] as $permiso) {
            $stmt = $conn->prepare("INSERT INTO usuario_permisos (usuario_id, permiso) VALUES (?, ?)");
            $stmt->execute([$_GET['id'], $permiso]);
        }
    }
    
    header("Location: index.php");
    exit();
}
?>

<?php include '../../includes/header.php'; ?>

<div class="container-fluid">
    <h2>Editar Usuario: <?= $usuario['nombre'] ?></h2>
    
    <form method="post">
        <div class="mb-3">
            <label>Rol</label>
            <select name="rol" class="form-select">
                <option value="Administrador" <?= $usuario['rol'] == 'Administrador' ? 'selected' : '' ?>>Administrador</option>
                <option value="Supervisor" <?= $usuario['rol'] == 'Supervisor' ? 'selected' : '' ?>>Supervisor</option>
                <option value="Usuario" <?= $usuario['rol'] == 'Usuario' ? 'selected' : '' ?>>Usuario</option>
            </select>
        </div>
        
        <div class="mb-3">
            <label>Permisos Adicionales</label>
            <?php 
            $permisos_usuario = obtener_permisos_usuario($usuario['id']);
            foreach ($todos_permisos as $permiso): 
            ?>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="permisos[]" 
                    value="<?= $permiso ?>" <?= in_array($permiso, $permisos_usuario) ? 'checked' : '' ?>>
                <label class="form-check-label"><?= str_replace('_', ' ', ucfirst($permiso)) ?></label>
            </div>
            <?php endforeach; ?>
        </div>
        
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>