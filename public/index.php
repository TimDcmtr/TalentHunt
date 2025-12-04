<?php

define('ROOT_PATH', dirname(__DIR__) . '/');
define('ASSETS_PATH', 'assets/');
define('PAGES_PATH', ROOT_PATH . 'pages/');

// 1. DÉFINITION DE LA CONSTANTE (Indispensable)
$script_dir = dirname($_SERVER['SCRIPT_NAME']);
$url_calculee = rtrim($script_dir, '/\\') . '/';

// On stocke ça dans une CONSTANTE nommée 'BASE_URL'
define('BASE_URL', $url_calculee);

// ===============================================
// ROUTAGE AVANCÉ
// ===============================================

// 1. Récupérer l'URI demandée
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// 2. Nettoyer l'URI (Méthode robuste)
// On calcule le chemin du dossier contenant index.php (ex: /mon-site/public)
$script_dir = dirname($_SERVER['SCRIPT_NAME']);

// On retire ce préfixe de l'URL demandée pour avoir le chemin relatif
// Si le script est à la racine, $script_dir vaut "/" ou "\", on gère ces cas
if ($script_dir === '/' || $script_dir === '\\') {
    $route = trim($request_uri, '/');
} else {
    // On retire le dossier d'installation de l'URL
    $route = trim(str_replace($script_dir, '', $request_uri), '/');
}

// Route de déconnexion (À PLACER AVANT LA GESTION DU CAS RACINE)
if ($route === 'logout') {
    require_once ROOT_PATH . 'app/pages/logout.php';
    exit();
}

// Cas de la racine
if (empty($route) || $route === 'index.php') {
    $route = 'home';
}

// 3. Construction du chemin cible
// Cela fonctionne pour "home", mais aussi "admin/dashboard" ou "user/compte/params"
$target_file = PAGES_PATH . $route . '.php';

// ===============================================
// SÉCURITÉ & VÉRIFICATION
// ===============================================

// realpath() retourne le chemin absolu canonique (résout les ../) ou false si introuvable
$real_path = realpath($target_file);

// A. On vérifie que le fichier existe ($real_path n'est pas false)
// B. On vérifie que le fichier final commence bien par le dossier PAGES_PATH
//    (Ceci empêche les attaques de type Directory Traversal vers ../../etc/passwd)
if ($real_path && strpos($real_path, realpath(PAGES_PATH)) === 0) {
    
    $page_file = $real_path;
    
    // Titre dynamique : on prend la dernière partie du chemin (ex: dashboard)
    $filename = basename($route); 
    $page_title = ucfirst(str_replace('-', ' ', $filename));

} else {
    // 404 Not Found
    http_response_code(404);
    $page_file = PAGES_PATH . '404.php';
    $page_title = 'Page non trouvée';
    
    // Sécurité supplémentaire : si même la 404 n'existe pas
    if (!file_exists($page_file)) {
        die("Erreur critique : Page 404 manquante.");
    }
}

// ===============================================
// TEMPLATE PRINCIPAL
// ===============================================

// 1. On démarre l'enregistrement de la sortie
ob_start();

// 2. On inclut la page (son HTML est capturé en mémoire, pas affiché)
require_once $page_file;

// 3. On récupère tout le contenu HTML dans une variable
$html_content = ob_get_clean();

// 4. On prépare la balise <base>
$base_tag = "\n\t<base href=\"" . BASE_URL . "\">";

// 5. On injecte la balise juste après l'ouverture du <head>
// On utilise str_replace pour trouver <head> et le remplacer par <head> + <base>
// Si la page n'a pas de <head>, rien ne sera cassé (mais le CSS ne marchera pas)
$final_html = str_replace('<head>', '<head>' . $base_tag, $html_content);

// 6. On affiche le résultat final au navigateur
echo $final_html;
