<?php
// Démarre la session (si elle n'est pas déjà démarrée)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Détruit toutes les variables de session
$_SESSION = array();

// Supprime le cookie de session
if (isset($_COOKIE[session_name()])) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
    // Aussi avec le chemin racine au cas où
    setcookie(session_name(), '', time() - 42000, '/');
}

// Détruit la session
session_unset();
session_destroy();

// Redirection
header("Location: " . BASE_URL . "login");
exit();
?>
