<?php
session_start();

// Redireccionar según estado de autenticación
if (isset($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
} else {
    header('Location: login.php');
}
exit;