<?php
function tienePermiso($pdo, $usuario_id, $permiso_nombre) {
    // Verifica si el usuario tiene un permiso explícito
    $stmt = $pdo->prepare("
        SELECT permitido FROM usuario_permisos 
        JOIN permisos ON permisos.id = usuario_permisos.permiso_id 
        WHERE usuario_id = ? AND permisos.nombre = ?
    ");
    $stmt->execute([$usuario_id, $permiso_nombre]);
    $permiso = $stmt->fetchColumn();

    if ($permiso !== false) {
        return $permiso == 1;
    }

    // Si no hay permiso explícito, verificar por rol
    $stmt = $pdo->prepare("SELECT rol FROM usuarios WHERE id = ?");
    $stmt->execute([$usuario_id]);
    $rol = $stmt->fetchColumn();

    $permisos_por_rol = [
        'Administrador' => ['ver_dashboard', 'editar_inventario', 'ver_reportes', 'configuracion'],
        'Supervisor'    => ['ver_dashboard', 'ver_reportes'],
        'Usuario'       => ['ver_dashboard']
    ];

    return in_array($permiso_nombre, $permisos_por_rol[$rol] ?? []);
}
