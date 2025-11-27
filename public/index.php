<?php

declare(strict_types=1);

// 1. Définition des chemins absolus
// On remonte d'un niveau pour sortir de /public/ et aller vers la racine
define('ROOT_PATH', dirname(__DIR__));
define('ASSETS_PATH', 'assets/');
define('PAGES_PATH', ROOT_PATH . '/pages');

// 2. Récupération de l'URL demandée
// On utilise parse_url pour ignorer les paramètres GET (?id=123)
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// 3. Nettoyage de l'URL
// On enlève les slashs de début et fin pour avoir une chaine propre
$uri = trim($request, '/');

// 4. Routage de base
if ($uri === '' || $uri === 'index.php') {
    $uri = 'home'; // Page par défaut (accueil)
}

// 5. Construction du chemin du fichier cible
$targetFile = PAGES_PATH . '/' . $uri . '.php';

// 6. SÉCURITÉ : Vérification et Inclusion
// realpath() résout les chemins relatifs (../) et retourne false si le fichier n'existe pas
$realPath = realpath($targetFile);

// On vérifie deux choses :
// A. Le fichier existe ($realPath n'est pas false)
// B. Le fichier est bien DANS le dossier /pages/ (protection contre l'accès à /etc/passwd ou config)
if ($realPath && strpos($realPath, PAGES_PATH) === 0 && file_exists($realPath)) {
    
    // (Optionnel) Ici tu pourrais inclure un header.php
    require $realPath;
    // (Optionnel) Ici tu pourrais inclure un footer.php

} else {
    // 7. Gestion de l'erreur 404
    http_response_code(404);
    
    $errorPage = PAGES_PATH . '/404.php';
    if (file_exists($errorPage)) {
        require $errorPage;
    } else {
        echo "<h1>Erreur 404 : Page introuvable</h1>";
    }
}