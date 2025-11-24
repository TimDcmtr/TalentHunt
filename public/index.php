<?php

$request = $_SERVER['REQUEST_URI'];
$viewDir = '/pages/';

switch ($request) {
    case '':
    case '/':
        require '../pages/accueil.php';
        break;

    case '/views/users':
        require __DIR__ . $viewDir . 'users.php';
        break;

    case '/contact':
        require __DIR__ . $viewDir . 'contact.php';
        break;

    default:
        http_response_code(404);
        require __DIR__ . $viewDir . '404.php';
}