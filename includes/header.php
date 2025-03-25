<?php
$base_url = '/consigna'; // cambia esto si cambia el nombre de la carpeta
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario Consigna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= $base_url ?>/dashboard.php">Inventario Consigna</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContenido" aria-controls="navbarContenido" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContenido">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <!-- Categoría: Materiales -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="menuMateriales" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Materiales
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="menuMateriales">
                        <li><a class="dropdown-item" href="<?= $base_url ?>/materiales/index.php">Ver Materiales</a></li>
                        <li><a class="dropdown-item" href="<?= $base_url ?>/materiales/agregar.php">Agregar Material</a></li>
                    </ul>
                </li>
                <!-- Categoría: Inventario -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="menuInventario" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Inventario
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="menuInventario">
                        <li><a class="dropdown-item" href="<?= $base_url ?>/inventario/index.php">Ver Inventario</a></li>
                        <li><a class="dropdown-item" href="../inventario/agregar.php">Agregar Material</a></li>
                        <li><a class="dropdown-item" href="../inventario/movimientos.php">Movimientos</a></li>
                        <li><a class="dropdown-item" href="../inventario/conteo.php">Conteo</a></li>
                    </ul>
                </li>

                <!-- Categoría: Reportes -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="menuReportes" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Reportes
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="menuReportes">
                        <li><a class="dropdown-item" href="#">Movimientos</a></li>
                        <li><a class="dropdown-item" href="#">Exportar Excel</a></li>
                    </ul>
                </li>

                <!-- Categoría: Administración -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="menuAdmin" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Administración
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="menuAdmin">
                        <li><a class="dropdown-item" href="crear_usuario.php">Crear Usuario</a></li>
                        <li><a class="dropdown-item" href="#">Permisos</a></li>
                        <li><a class="dropdown-item" href="#">Configuración</a></li>
                    </ul>
                </li>
            </ul>

            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="btn btn-outline-light btn-sm" href="logout.php">Cerrar sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
