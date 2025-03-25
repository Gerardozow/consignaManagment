<?php
session_start();

// Si ya está logueado, lo mandamos al dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
} else {
    // Si no ha iniciado sesión, lo llevamos al login
    header("Location: login.php");
    exit;
}
