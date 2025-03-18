<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$movimientos = $pdo->query("
    SELECT m.*, i.numero_parte, u.nombre as usuario, 
           u_ant.nombre as ubicacion_anterior,
           u_nueva.nombre as ubicacion_nueva
    FROM movimientos m
    JOIN inventario i ON m.inventario_id = i.id
    JOIN usuarios u ON m.usuario_id = u.id
    LEFT JOIN ubicaciones u_ant ON m.ubicacion_anterior = u_ant.id
    LEFT JOIN ubicaciones u_nueva ON m.ubicacion_nueva = u_nueva.id
    ORDER BY m.fecha_movimiento DESC
")->fetchAll();

include __DIR__ . '/../includes/header.php';
?>

<div class="card shadow">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="bi bi-clock-history"></i> Historial de Movimientos</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>N° Parte</th>
                        <th>Cantidad</th>
                        <th>Ubicación</th>
                        <th>Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($movimientos as $mov): ?>
                    <tr>
                        <td><?= date('d/m/Y H:i', strtotime($mov['fecha_movimiento'])) ?></td>
                        <td><?= $mov['numero_parte'] ?></td>
                        <td>
                            <span class="text-danger"><?= $mov['cantidad_anterior'] ?></span> → 
                            <span class="text-success"><?= $mov['cantidad_nueva'] ?></span>
                        </td>
                        <td>
                            <?= $mov['ubicacion_anterior'] ?? 'N/A' ?> → 
                            <?= $mov['ubicacion_nueva'] ?? 'N/A' ?>
                        </td>
                        <td><?= $mov['usuario'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>