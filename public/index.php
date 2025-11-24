<?php
// ===============================================
// DÉFINITION DES CHEMINS ET CONSTANTES
// ===============================================

// Chemin absolu vers le dossier racine du projet (talenthub/)
// Utile pour inclure les fichiers PHP sans se soucier de l'emplacement actuel.
define('ROOT_PATH', dirname(__DIR__) . '/');

// Chemin relatif pour les assets (CSS/JS) côté client
// /public/assets/
define('ASSETS_PATH', 'assets/');

// ===============================================
// INCLUSION DES HELPERS/COMPOSANTS
// ===============================================

// Inclusion des composants (Navbar, Footer, etc.)
require_once ROOT_PATH . 'app/helpers/Navbar.php';
require_once ROOT_PATH . 'app/helpers/Footer.php';

// ===============================================
// ROUTAGE SIMPLE
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

// Vérifier si le fichier de la page existe
if (file_exists($page_file)) {
    // La page existe, on prépare le titre (simple capitalisation ici)
    $page_title = ucfirst(str_replace('-', ' ', $route));
} else {
    // Page non trouvée (404)
    http_response_code(404);
    $page_file = ROOT_PATH . 'pages/404.php'; // Assurez-vous d'avoir une page 404.php
    $page_title = 'Page non trouvée';
}

// ===============================================
// TEMPLATE PRINCIPAL
// ===============================================


// Inclusion du contenu de la page demandée
require_once $page_file;