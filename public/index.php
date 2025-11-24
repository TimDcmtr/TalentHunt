<?php
// ===============================================
// DÉFINITION DES CHEMINS ET CONSTANTES
// ===============================================

// Chemin absolu vers le dossier racine du projet (talenthub/).
// Ceci est essentiel pour la sécurité, car cela permet d'inclure
// les fichiers de l'application (comme /pages/) sans dépendre du répertoire courant.
define('ROOT_PATH', dirname(__DIR__) . '/');

// L'ancienne constante ASSETS_PATH n'est plus nécessaire ici puisque les pages 
// sont maintenant responsables d'inclure leurs propres assets.
// // define('ASSETS_PATH', 'assets/');

// ===============================================
// INCLUSION DES HELPERS/COMPOSANTS
// Ces fichiers sont inclus pour rendre leurs fonctions ou classes disponibles
// dans toutes les pages (si les pages en ont besoin).
// ===============================================

require_once ROOT_PATH . 'app/helpers/Navbar.php';
require_once ROOT_PATH . 'app/helpers/Footer.php';

// ===============================================
// ROUTAGE MINIMALISTE
// ===============================================

// 1. Récupérer l'URI demandée (ex: /login, /etudiant-main, /accueil)
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// 2. Nettoyer et normaliser l'URI
// Retire le chemin du répertoire 'public' s'il est présent
$public_dir_name = basename(dirname($_SERVER['SCRIPT_NAME']));
$route = trim(str_replace('/' . $public_dir_name, '', $request_uri), '/');

// Cas de la racine (URI vide)
if (empty($route) || $route === 'index.php') {
    $route = 'accueil';
}

// 3. Définition du fichier de page à charger
$page_file = ROOT_PATH . 'pages/' . $route . '.php';

// ===============================================
// INCLUSION DE LA PAGE (CONTENU BRUT)
// ===============================================

// Vérifier si le fichier de la page existe
if (file_exists($page_file)) {
    // La page existe : on l'importe directement.
    // Cette page doit fournir TOUTE la structure HTML.
    require_once $page_file; 
} else {
    // Page non trouvée (404)
    http_response_code(404);
    // On assume qu'une page 404.php existe et inclut sa propre structure HTML complète.
    require_once ROOT_PATH . 'pages/404.php'; 
}