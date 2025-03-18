<?php
include 'includes/config.php';

if (isset($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validaciones
        if (empty($nombre) || empty($email) || empty($password)) {
            throw new Exception('Todos los campos son requeridos');
        }

        if ($password !== $confirm_password) {
            throw new Exception('Las contraseñas no coinciden');
        }

        if (strlen($password) < 8) {
            throw new Exception('La contraseña debe tener al menos 8 caracteres');
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $email, $hashed_password]);

        $_SESSION['success'] = "Registro exitoso. Por favor inicia sesión.";
        header('Location: login.php');
        exit;

    } catch (PDOException $e) {
        if ($e->errorInfo[1] === 1062) {
            $error = "El email ya está registrado";
        } else {
            $error = "Error en el registro: " . $e->getMessage();
        }
        error_log("Error de registro: " . $e->getMessage());
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!-- Resto del formulario de registro... -->