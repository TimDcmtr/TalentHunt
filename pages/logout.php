<?php
// Supprime le cookie authToken (CRITIQUE)
setcookie('authToken', '', time() - 3600, '/');
setcookie('authToken', '', time() - 3600, '/', '', false, true);

// Supprime aussi toute session PHP par sécurité
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION = array();

// Supprime le cookie de session PHP
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

session_destroy();

// Redirection
header("Location: " . BASE_URL . "login");
exit();
?>
