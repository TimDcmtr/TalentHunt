<?php
// api.php (VERSION DE TEST)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// On capture tout ce qui arrive
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? 'AUCUNE ACTION';
$input = file_get_contents("php://input");

// On renvoie 200 OK avec les infos de debug
http_response_code(200);

echo json_encode([
    "status" => "Le fichier api.php est bien atteint !",
    "method_recue" => $method,
    "action_recue" => $action,
    "json_recu" => $input
]);
?>