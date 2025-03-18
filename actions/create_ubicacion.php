<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
        $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);

        $stmt = $pdo->prepare("INSERT INTO ubicaciones (nombre, descripcion) VALUES (?, ?)");
        $stmt->execute([$nombre, $descripcion]);
        
        $_SESSION['success'] = "Ubicación creada exitosamente";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error al crear ubicación: " . $e->getMessage();
    }
    
    header('Location: ../ubicaciones.php');
    exit;
}