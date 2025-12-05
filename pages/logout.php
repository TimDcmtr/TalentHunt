<?php
// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Supprimer TOUTES les variables de session
$_SESSION = array();

// Détruire le cookie de session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Détruire la session
session_destroy();

// Liste de tous les cookies à supprimer
$cookiesToDelete = ['authToken', 'companyAuthToken'];

// Supprimer avec TOUTES les combinaisons possibles
foreach ($cookiesToDelete as $cookieName) {
    // Méthode 1: Path racine
    setcookie($cookieName, '', time() - 3600, '/');
    
    // Méthode 2: Avec domaine
    if (isset($_SERVER['HTTP_HOST'])) {
        setcookie($cookieName, '', time() - 3600, '/', $_SERVER['HTTP_HOST']);
    }
    
    // Méthode 3: Sans domaine mais avec sécurité
    setcookie($cookieName, '', time() - 3600, '/', '', false, true);
}

// Redirection
header('Location: /');
exit();
?>
