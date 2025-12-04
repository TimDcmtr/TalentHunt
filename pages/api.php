<?php
// api.php

// 1. Configuration des Headers (CORS & JSON)
// Permet à ton JS d'interagir sans bloquage et définit qu'on renvoie du JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// 2. Inclusion des fichiers nécessaires
// Adapte le chemin si tes dossiers sont ailleurs (ex: './app/controllers/...')
require_once ROOT_PATH . 'app/config/db.php';
require_once ROOT_PATH . 'app/controllers/UserController.php';
require_once ROOT_PATH . 'app/controllers/CompaniesController.php';

// 3. Récupération de l'action demandée via l'URL (ex: api.php?action=register)
$action = isset($_GET['action']) ? $_GET['action'] : '';

// 4. Récupération des données JSON envoyées par le JS
$json = file_get_contents("php://input");
$data = json_decode($json, true); // true pour avoir un tableau associatif

// 5. Instanciation du contrôleur
$controller = new UserController();
$controllerEN = new CompanyController();

// 6. Router (Switch)
switch ($action) {

    case 'register':
        // Le contrôleur attend un tableau de données
        if ($data) {
            echo $controller->register($data);
        } else {
            echo json_encode(["message" => "No data provided."]);
        }
        break;

    case 'registerEN':
        // Le contrôleur attend un tableau de données
        if ($data) {
            echo $controllerEN->register($data);
        } else {
            echo json_encode(["message" => "No data provided."]);
        }
        break;

    case 'login':
        // Le contrôleur attend email et password séparés
        if (isset($data['email']) && isset($data['password'])) {
            $result = $controller->login($data['email'], $data['password']);
            // login renvoie un tableau, on doit le convertir en JSON pour l'echo
            echo json_encode($result);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Email and password required."]);
        }
        break;

    case 'loginEN':
        // Le contrôleur attend email et password séparés
        if (isset($data['email']) && isset($data['password'])) {
            $result = $controllerEN->login($data['email'], $data['password']);
            // login renvoie un tableau, on doit le convertir en JSON pour l'echo
            echo json_encode($result);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Email and password required."]);
        }
        break;

    case 'profile':
        // Pour lire un profil (GET api.php?action=profile&id=1)
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if ($id) {
            echo $controller->getUserProfile($id);
        } else {
            echo json_encode(["message" => "No ID provided."]);
        }
        break;

    case 'update_student':
        // 1. Auth check
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';
        $jwt = str_replace("Bearer ", "", $authHeader);

        $userController = new UserController();
        // getUserFromToken doit retourner l'utilisateur si token valide
        $loggedUser = $userController->getUserFromToken($jwt);

        if (!$loggedUser) {
            http_response_code(401);
            echo json_encode(["message" => "Non autorisé."]);
            exit;
        }

        // 2. Data
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data)
            $data = $_POST;

        // 3. Force ID
        $data['id'] = $loggedUser['id'];

        echo $userController->updateUserProfile($data);
        break;

    case 'updateCompany':
        // 1. Sécurité : Vérifier le token
        $headers = getallheaders(); // Ou ta fonction fallback
        $authHeader = $headers['Authorization'] ?? '';
        $jwt = str_replace("Bearer ", "", $authHeader);

        $companyController = new CompanyController();
        $loggedCompany = $companyController->getCompanyFromToken($jwt);

        if (!$loggedCompany) {
            http_response_code(401);
            echo json_encode(["message" => "Non autorisé."]);
            exit;
        }

        // 2. Récupérer les données POST
        // Note: Si tu envoies un FormData classique (pas JSON), utilise $_POST
        // Si tu envoies du JSON via fetch, utilise php://input

        // Option A: Si tu utilises le JS 'sendAuthRequest' (JSON)
        $data = json_decode(file_get_contents("php://input"), true);

        // Option B: Si tu utilises des formulaires classiques sans JS
        // $data = $_POST;

        if (!$data) {
            // Fallback mixte
            $data = $_POST;
            // Si c'est du JSON, on merge les tableaux pour récupérer values[] et specialties[]
            if (empty($data))
                $data = json_decode(file_get_contents("php://input"), true);
        }

        // 3. Sécurité : On force l'ID à être celui du token
        // On ignore l'ID envoyé dans le formulaire pour empêcher de modifier une autre entreprise
        $data['id'] = $loggedCompany['id'];

        echo $companyController->updateCompanyProfile($data);
        break;

    default:
        http_response_code(404);
        echo json_encode(["message" => "Action not found. Check your URL parameters."]);
        break;

    case 'upload_cv':
        // 1. Auth check (Copier-coller habituel)
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';
        $jwt = str_replace("Bearer ", "", $authHeader);

        $userController = new UserController();
        $loggedUser = $userController->getUserFromToken($jwt);

        if (!$loggedUser) {
            http_response_code(401);
            echo json_encode(["message" => "Non autorisé."]);
            exit;
        }

        // DEBUG TEMPORAIRE
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST) && empty($_FILES) && $_SERVER['CONTENT_LENGTH'] > 0) {
            die(json_encode(["message" => "ERREUR CRITIQUE : Le fichier dépasse post_max_size dans php.ini"]));
        }

        // 2. Préparation des données
        // Pour l'upload, on utilise $_POST et $_FILES standard
        $data = $_POST;
        $data['id'] = $loggedUser['id']; // Sécurité : on force l'ID

        // 3. Appel Controller avec les fichiers
        echo $userController->uploadCV($data, $_FILES);
        break;

    case 'apply':
        // 1. SÉCURITÉ : Récupération et vérification du Token
        $headers = null;
        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
        } else {
            $headers = []; // Fallback si nécessaire
            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) == 'HTTP_') {
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }
        }

        $authHeader = $headers['Authorization'] ?? '';
        $jwt = str_replace("Bearer ", "", $authHeader);

        require_once ROOT_PATH . 'app/controllers/UserController.php';
        $userController = new UserController();
        $user = $userController->getUserFromToken($jwt);

        if (!$user) {
            http_response_code(401);
            echo json_encode(["message" => "Session expirée ou invalide. Veuillez vous reconnecter."]);
            exit;
        }

        // 2. Récupération des données JSON envoyées par le JS
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);

        if (!$data) {
            http_response_code(400);
            echo json_encode(["message" => "Données invalides."]);
            exit;
        }

        // 3. Injection sécurisée de l'ID utilisateur
        $data['user_id'] = $user['id'];

        // 4. Appel du Controller
        require_once ROOT_PATH . 'app/controllers/ApplicationController.php';
        $appController = new ApplicationController();
        echo $appController->apply($data);
        break;

    case 'create_offer':
        // 1. Auth check (Entreprise uniquement)
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';
        $jwt = str_replace("Bearer ", "", $authHeader);

        require_once ROOT_PATH . 'app/controllers/CompaniesController.php';
        $companyCtrl = new CompanyController();
        $company = $companyCtrl->getCompanyFromToken($jwt);

        if (!$company) {
            http_response_code(401);
            echo json_encode(["message" => "Non autorisé."]);
            exit;
        }

        // 2. Data
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data) {
            http_response_code(400);
            echo json_encode(["message" => "Données invalides."]);
            exit;
        }

        // 3. Force Company ID
        $data['company_id'] = $company['id'];

        // 4. Controller
        require_once ROOT_PATH . 'app/controllers/JobOfferController.php';
        $offerCtrl = new JobOfferController();
        echo $offerCtrl->createOffer($data);
        break;
}
?>