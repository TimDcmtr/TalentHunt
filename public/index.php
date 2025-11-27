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

// Sécurité : Empêcher de remonter dans les dossiers (Directory Traversal)
// On interdit les ".." dans l'URL
if (strpos($route, '..') !== false) {
    http_response_code(400);
    die('Requête invalide.');
}


// Chemin de base des pages
$pages_path = ROOT_PATH . 'pages/';

// On prépare deux chemins possibles :
// 1. Le fichier direct : pages/company/dashboard.php
$target_file = $pages_path . $route . '.php';

// 2. Le dossier avec index : pages/company/dashboard/index.php
$target_index = $pages_path . $route . '/index.php';

// Vérification de l'existence
if (file_exists($target_file)) {
    $page_file = $target_file;
    // Titre basé sur le nom du fichier final
    $page_title = ucfirst(basename($route)); 
} 
elseif (is_dir($pages_path . $route) && file_exists($target_index)) {
    $page_file = $target_index;
    // Titre basé sur le nom du dossier
    $page_title = ucfirst(basename($route));
} 
else {
    // Page non trouvée (404)
    http_response_code(404);
    $page_file = $pages_path . '404.php';
    $page_title = 'Page non trouvée';
}
// ===============================================
// TEMPLATE PRINCIPAL
// ===============================================


// Inclusion du contenu de la page demandée
require_once $page_file;