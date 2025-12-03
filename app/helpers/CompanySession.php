<?php
// On s'assure que ROOT_PATH est défini (à inclure dans tes pages)
require_once ROOT_PATH . 'app/controllers/CompaniesController.php';

// Variables globales accessibles dans tes pages "Entreprise"
$currentCompany = null;
$isCompanyAuthenticated = false;

// 1. On regarde si le cookie 'authToken' existe
if (isset($_COOKIE['authToken'])) {
    
    // 2. On instancie le contrôleur Entreprise
    $controller = new CompanyController();
    
    // 3. On vérifie le token
    // Cette méthode (définie plus tôt) vérifie la signature ET si le role === 'company'
    $companyFromToken = $controller->getCompanyFromToken($_COOKIE['authToken']);

    if ($companyFromToken) {
        // SUCCÈS : C'est bien une entreprise connectée
        $currentCompany = $companyFromToken['id'];
        $isCompanyAuthenticated = true;
    } else {
        // ÉCHEC : Token invalide, expiré, ou c'est un token "Student" qui essaie d'accéder à une page Entreprise
        // On ne supprime le cookie que si on est sûr que c'est invalide. 
        // Dans le doute, pour une sécu simple, on nettoie.
        setcookie('authToken', '', time() - 3600, "/"); 
    }
}

// Fonction pour protéger les pages du dashboard Entreprise
function requireCompanyLogin() {
    global $isCompanyAuthenticated;
    if (!$isCompanyAuthenticated) {
        // Redirection vers la page de connexion (idéalement login entreprise si distincte)
        header("Location: /login"); 
        exit();
    }
}
?>