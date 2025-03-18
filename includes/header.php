<?php include '../config.php'; ?>
<!DOCTYPE html>
<html lang="es" data-bs-theme="<?= $theme ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de Inventario en Consignación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="../assets/css/styles.css" rel="stylesheet">
    <link href="../assets/css/theme.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">Inventario Consignación</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Inventario</a></li>
                <li class="nav-item"><a class="nav-link" href="ubicaciones.php">Ubicaciones</a></li>
                <li class="nav-item"><a class="nav-link" href="movimientos.php">Movimientos</a></li>
            </ul>
            <div class="d-flex">
                <button class="btn btn-outline-secondary me-2" id="theme-toggle">
                    <i class="bi <?= $theme === 'dark' ? 'bi-sun' : 'bi-moon' ?>"></i>
                </button>
                <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
            </div>
        </div>
    </div>
</nav>
<div class="container mt-4">