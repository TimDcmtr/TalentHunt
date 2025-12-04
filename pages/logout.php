<?php
// Supprime le cookie authToken avec TOUS les paramètres possibles
setcookie('authToken', '', time() - 3600, '/');
setcookie('authToken', '', time() - 3600, '/', $_SERVER['HTTP_HOST']);
setcookie('authToken', '', time() - 3600, '/', '', false, true);

// Nettoyage session par sécurité
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION = array();
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Déconnexion...</title>
</head>
<body>
    <script>
        // SUPPRESSION FORCÉE DU COOKIE EN JAVASCRIPT (backup infaillible)
        document.cookie = 'authToken=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        document.cookie = 'authToken=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=' + window.location.hostname;
        
        // Redirection immédiate
        window.location.href = '<?php echo BASE_URL; ?>login';
    </script>
</body>
</html>
