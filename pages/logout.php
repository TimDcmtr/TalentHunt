<?php
// Démarrer la session si pas déjà fait
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Supprimer toutes les variables de session
$_SESSION = array();

// Supprimer le cookie de session
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Détruire la session
session_destroy();

// Supprimer le cookie authToken
setcookie('authToken', '', time() - 3600, '/');
setcookie('authToken', '', time() - 3600, '/', $_SERVER['HTTP_HOST'] ?? '');

// Supprimer aussi companyAuthToken si applicable
setcookie('companyAuthToken', '', time() - 3600, '/');
setcookie('companyAuthToken', '', time() - 3600, '/', $_SERVER['HTTP_HOST'] ?? '');

// Redirection immédiate côté serveur
header('Location: /login');
exit();
?>
