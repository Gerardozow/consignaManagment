<?php
session_start();
$host = "localhost";
$dbname = "inventario_consigna";
$username = "tu_usuario";
$password = "tu_password";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}


function tiene_permiso($permiso_requerido) {
    global $conn;
    
    // 1. Verificar permisos directos del usuario
    $stmt = $conn->prepare("SELECT * FROM usuario_permisos WHERE usuario_id = ? AND permiso = ?");
    $stmt->execute([$_SESSION['user_id'], $permiso_requerido]);
    if ($stmt->rowCount() > 0) return true;
    
    // 2. Verificar permisos por rol
    $rol = $_SESSION['rol'];
    
    // Permisos base por rol
    $permisos_por_rol = [
        'Administrador' => ['gestion_usuarios', 'gestion_materiales', 'reportes'],
        'Supervisor' => ['editar_materiales', 'ver_reportes'],
        'Usuario' => ['ver_dashboard']
    ];
    
    return in_array($permiso_requerido, $permisos_por_rol[$rol]);
}

function obtener_permisos_usuario($usuario_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT permiso FROM usuario_permisos WHERE usuario_id = ?");
    $stmt->execute([$usuario_id]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
}
?>