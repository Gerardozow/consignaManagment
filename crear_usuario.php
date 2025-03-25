<?php
require 'includes/auth.php';
require 'includes/db.php';
include 'includes/header.php';

// Solo un administrador debe poder acceder
$stmt = $pdo->prepare("SELECT rol FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$rol = $stmt->fetchColumn();

if ($rol !== 'Administrador') {
    echo "<div class='container mt-5 alert alert-danger'>No tienes permiso para crear usuarios.</div>";
    include 'includes/footer.php';
    exit;
}

// Si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $password = $_POST['password'];
    $rol = $_POST['rol'];

    // Verifica si el usuario ya existe
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    if ($stmt->fetchColumn() > 0) {
        $mensaje = "<div class='alert alert-warning'>El usuario ya existe.</div>";
    } else {
        // Encriptar la contraseña
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, password, rol) VALUES (?, ?, ?)");
        $stmt->execute([$usuario, $hash, $rol]);

        $mensaje = "<div class='alert alert-success'>Usuario creado correctamente.</div>";
    }
}
?>

<div class="container mt-5">
    <h2>Crear Usuario</h2>
    <?php if (isset($mensaje)) echo $mensaje; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Usuario</label>
            <input type="text" name="usuario" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Rol</label>
            <select name="rol" class="form-select" required>
                <option value="Administrador">Administrador</option>
                <option value="Supervisor">Supervisor</option>
                <option value="Usuario">Usuario</option>
            </select>
        </div>
        <button class="btn btn-primary">Crear Usuario</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
