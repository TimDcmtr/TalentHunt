<?php
// On suppose que ROOT_PATH est défini dans ton index ou config
// Si ce n'est pas le cas, ajuste les chemins (ex: include_once '../controllers/...')
require_once ROOT_PATH . 'app/controllers/UserController.php';

// Variable globale qui sera accessible dans ta page HTML
$currentUser = null;
$isAuthenticated = false;

// 1. On regarde si le cookie 'authToken' existe
if (isset($_COOKIE['authToken'])) {
    
    // 2. On instancie le contrôleur
    $controller = new UserController();
    
    // 3. On vérifie le token via la méthode qu'on a créée précédemment
    $userFromToken = $controller->getUserFromToken($_COOKIE['authToken']);

    if (!isset($userFromToken['role']) && $userFromToken['role'] == 'student') {
        // SUCCÈS : Le token est valide
        $currentUser = $userFromToken;
        $isAuthenticated = true;
    }
}

// Rediriger si pas connecté
function requireLogin() {
    global $isAuthenticated;
    if (!$isAuthenticated) {
        header("Location: /login");
        exit();
    }
}
?>