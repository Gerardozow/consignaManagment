<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';

// Manejo básico de rutas
$request = strtok($_SERVER['REQUEST_URI'], '?');

switch ($request) {
    case '/':
    case '/index.php':
        if (is_logged_in()) {
            header('Location: /pages/dashboard.php');
        } else {
            header('Location: /pages/login.php');
        }
        break;
        
    default:
        if (file_exists(__DIR__ . $request)) {
            require __DIR__ . $request;
        } else {
            http_response_code(404);
            include __DIR__ . '/pages/404.php';
        }
        break;
}

exit;