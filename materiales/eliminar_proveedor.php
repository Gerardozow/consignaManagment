<?php
require '../includes/auth.php';
require '../includes/db.php';

$id = $_GET['id'] ?? null;
$material_id = $_GET['material'] ?? null;

if ($id && $material_id) {
    $stmt = $pdo->prepare("DELETE FROM material_proveedores WHERE id = ?");
    $stmt->execute([$id]);

    $_SESSION['mensaje'] = [
        'icono' => 'success',
        'titulo' => 'Proveedor eliminado',
        'texto' => 'El proveedor fue desvinculado del material.'
    ];
}

header("Location: proveedores.php?material=$material_id");
exit;
