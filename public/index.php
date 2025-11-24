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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TalentHub | <?php echo $page_title; ?></title>
    
    <!-- Liens vers les styles globaux et variables -->
    <link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/variables.css">

    <!-- Chargement conditionnel des styles spécifiques à la page -->
    <?php 
    // On essaie de charger le CSS correspondant au nom de la route
    $css_file_name = str_replace('-', '_', $route) . '.css';
    $specific_css_path = ASSETS_PATH . 'css/' . $css_file_name;
    
    // Pour simplifier, nous chargeons quelques styles spécifiques par défaut ici.
    // Il faudrait affiner cette logique pour correspondre exactement à votre structure.
    $load_css = '';
    
    if ($route === 'accueil') {
        $load_css = 'accueil.css';
    } elseif ($route === 'login') {
        $load_css = 'auth.css';
    } elseif ($route === 'etudiant-main') {
        $load_css = 'etudiant.css';
    } elseif ($route === 'config-etudiant') {
        $load_css = 'config.css';
    } elseif ($route === 'pro-main') {
        $load_css = 'pro.css';
    }
    
    if (!empty($load_css)) {
        echo '<link rel="stylesheet" href="' . ASSETS_PATH . 'css/' . $load_css . '">';
    }
    
    // Ajoutez également config.css si pertinent pour certaines pages
    if (strpos($route, 'config') !== false) {
         echo '<link rel="stylesheet" href="' . ASSETS_PATH . 'css/config.css">';
    }
    ?>

</head>
<body>

    <main id="content">
        <?php 
        // Inclusion du contenu de la page demandée
        require_once $page_file; 
        ?>
    </main>
    
    <!-- Chargement des scripts JS -->
    <script src="<?php echo ASSETS_PATH; ?>js/navbar.js"></script>
    
    <?php
    // Chargement conditionnel des scripts JS spécifiques à la page
    $load_js = '';
    if ($route === 'login') {
        $load_js = 'auth.js';
    } elseif ($route === 'etudiant-main') {
        $load_js = 'etudiant.js';
    } elseif ($route === 'config-etudiant') {
        $load_js = 'config.js';
    } elseif ($route === 'pro-main') {
        $load_js = 'pro.js';
    }

    if (!empty($load_js)) {
        echo '<script src="' . ASSETS_PATH . 'js/' . $load_js . '"></script>';
    }
    ?>

</body>
</html>