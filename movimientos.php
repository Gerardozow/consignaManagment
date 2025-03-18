<?php
include 'includes/config.php';
requireAuth();

$sql = "SELECT m.*, i.numero_parte, u.nombre as usuario 
        FROM movimientos m
        JOIN inventario i ON m.inventario_id = i.id
        JOIN usuarios u ON m.usuario_id = u.id
        ORDER BY m.fecha_movimiento DESC";

$movimientos = $pdo->query($sql)->fetchAll();
?>

<?php include 'includes/header.php'; ?>

<h2 class="mb-4">Historial de Movimientos</h2>

<table class="table table-hover">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>N° Parte</th>
            <th>Cambio Cantidad</th>
            <th>Ubicación</th>
            <th>Usuario</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($movimientos as $mov): ?>
        <tr>
            <td><?= $mov['fecha_movimiento'] ?></td>
            <td><?= $mov['numero_parte'] ?></td>
            <td>
                <span class="text-danger"><?= $mov['cantidad_anterior'] ?></span> → 
                <span class="text-success"><?= $mov['cantidad_nueva'] ?></span>
            </td>
            <td>
                <?= obtenerNombreUbicacion($mov['ubicacion_anterior']) ?> → 
                <?= obtenerNombreUbicacion($mov['ubicacion_nueva']) ?>
            </td>
            <td><?= $mov['usuario'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>