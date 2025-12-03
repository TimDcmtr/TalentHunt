<?php
// api.php

// 1. Configuration des Headers (CORS & JSON)
// Permet à ton JS d'interagir sans bloquage et définit qu'on renvoie du JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// 2. Inclusion des fichiers nécessaires
// Adapte le chemin si tes dossiers sont ailleurs (ex: './app/controllers/...')
require_once ROOT_PATH . 'app/config/db.php';
require_once ROOT_PATH . 'app/controllers/UserController.php';
require_once ROOT_PATH . 'app/controllers/CompaniesController.php';

// 3. Récupération de l'action demandée via l'URL (ex: api.php?action=register)
$action = isset($_GET['action']) ? $_GET['action'] : '';

// 4. Récupération des données JSON envoyées par le JS
$json = file_get_contents("php://input");
$data = json_decode($json, true); // true pour avoir un tableau associatif

// 5. Instanciation du contrôleur
$controller = new UserController();
$controllerEN = new CompanyController();

// 6. Router (Switch)
switch ($action) {

    case 'register':
        // Le contrôleur attend un tableau de données
        if ($data) {
            echo $controller->register($data);
        } else {
            echo json_encode(["message" => "No data provided."]);
        }
        break;

    case 'registerEN':
        // Le contrôleur attend un tableau de données
        if ($data) {
            echo $controllerEN->register($data);
        } else {
            echo json_encode(["message" => "No data provided."]);
        }
        break;

    case 'login':
        // Le contrôleur attend email et password séparés
        if (isset($data['email']) && isset($data['password'])) {
            $result = $controller->login($data['email'], $data['password']);
            // login renvoie un tableau, on doit le convertir en JSON pour l'echo
            echo json_encode($result);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Email and password required."]);
        }
        break;

    case 'loginEN':
        // Le contrôleur attend email et password séparés
        if (isset($data['email']) && isset($data['password'])) {
            $result = $controllerEN->login($data['email'], $data['password']);
            // login renvoie un tableau, on doit le convertir en JSON pour l'echo
            echo json_encode($result);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Email and password required."]);
        }
        break;

    case 'profile':
        // Pour lire un profil (GET api.php?action=profile&id=1)
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if ($id) {
            echo $controller->getUserProfile($id);
        } else {
            echo json_encode(["message" => "No ID provided."]);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(["message" => "Action not found. Check your URL parameters."]);
        break;
}
?>