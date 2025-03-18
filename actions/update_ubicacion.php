<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
        $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);

        $stmt = $pdo->prepare("UPDATE ubicaciones SET nombre = ?, descripcion = ? WHERE id = ?");
        $stmt->execute([$nombre, $descripcion, $id]);
        
        $_SESSION['success'] = "Ubicación actualizada exitosamente";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error al actualizar ubicación: " . $e->getMessage();
    }
    
    header('Location: ../ubicaciones.php');
    exit;
}