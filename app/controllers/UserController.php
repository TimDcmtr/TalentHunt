<?php
// On inclut la config et le modèle
require_once ROOT_PATH . 'app/config/db.php';
require_once ROOT_PATH . 'app/models/User.php';

class UserController
{

    private $db;
    private $user;
    private $secret_key = "MA_SUPER_CLE_SECRETE_TRES_LONGUE_ET_ALEATOIRE";

    public function __construct()
    {
        // Initialisation de la connexion BDD
        $database = new Database();
        $this->db = $database->getConnection();

        // Initialisation du Modèle User
        $this->user = new User($this->db);
    }

    /**
     * Gère l'inscription de l'utilisateur
     * @param array $data Les données POST (souvent venant d'un formulaire ou JSON)
     */
    public function register($data)
    {
        // 1. Validation des champs requis (HTML 'required')
        if (
            !isset($data['firstname']) ||
            !isset($data['lastname']) ||
            !isset($data['email']) ||
            !isset($data['password'])
        ) {
            http_response_code(400);
            return json_encode(["message" => "Veuillez remplir tous les champs obligatoires."]);
        }

        // 2. MAPPING : HTML (français) vers Modèle (Anglais/DB)
        // C'est ici qu'on fait correspondre tes 'name="..."' HTML avec la BDD

        $this->user->firstname = $data['firstname'];
        $this->user->lastname = $data['lastname'];
        $this->user->email = $data['email'];
        $this->user->password = $data['password'];

        // Mapping spécifique
        // HTML: name="tel" -> DB: phone
        $this->user->phone = $data['tel'] ?? null;

        // HTML: name="school" -> DB: school
        $this->user->school = $data['school'] ?? null;

        // HTML: name="region" -> DB: location
        $this->user->location = $data['region'] ?? null;

        // HTML: name="domain" -> DB: field_of_study
        $this->user->field_of_study = $data['domain'] ?? null;

        // 3. Création
        try {
            if ($this->user->create()) {
                http_response_code(201);
                return json_encode(["message" => "Compte étudiant créé avec succès."]);
            } else {
                http_response_code(503);
                return json_encode(["message" => "Impossible de créer le compte. L'email existe peut-être déjà."]);
            }
        } catch (Exception $e) {
            error_log("Erreur Register Company : " . $e->getMessage());

            http_response_code(500);
            return json_encode(["message" => "Erreur serveur : " . $e->getMessage()]);
        }
    }

    /**
     * Récupère le profil complet d'un utilisateur par son ID
     * @param int $id L'ID de l'utilisateur
     */
    public function getUserProfile($id)
    {
        // On appelle la méthode du Modèle qui va hydrater $this->user
        if ($this->user->getProfileById($id)) {

            // On prépare le tableau de réponse (Mapping)
            // On transforme les propriétés de l'objet en un tableau propre pour le JSON
            $user_arr = [
                "id" => $this->user->id,

                // Info Personnelle
                "firstname" => $this->user->firstname,
                "lastname" => $this->user->lastname,
                "email" => $this->user->email,
                "phone" => $this->user->phone,
                "avatar_initials" => $this->user->avatar_initials, // Champ calculé

                // Info Académique & Loc
                "school" => $this->user->school,
                "location" => $this->user->location,
                "field_of_study" => $this->user->field_of_study,

                // Info Profil Étendu
                "bio" => $this->user->bio,
                "categories" => $this->user->categories, // Déjà un array PHP grâce au Model
                "search_type" => $this->user->search_type,
                "work_mode" => $this->user->work_mode,   // Déjà un array PHP
                "min_salary" => $this->user->min_salary,

                // Info CV & Stats
                "cv_uploaded" => $this->user->cv_uploaded, // Booléen
                "cv_filename" => $this->user->cv_filename,
                "member_since" => $this->user->member_since, // Formaté "November 2024"
                "application_count" => $this->user->application_count,

                // Compétences
                "skills" => $this->user->skills // Déjà un array PHP
            ];

            http_response_code(200); // OK
            return json_encode($user_arr);

        } else {
            http_response_code(404); // Not Found
            return json_encode(["message" => "User not found."]);
        }

    }

    public function login($email, $password)
    {
        // 1. Validation basique
        if (empty($email) || empty($password)) {
            http_response_code(400);
            return json_encode(["message" => "Email et mot de passe requis."]);
        }

        $this->user->email = $email;

        // 2. Vérification existence utilisateur
        if ($this->user->findByEmail()) {

            // 3. Vérification du hash du mot de passe
            if (password_verify($password, $this->user->password)) {

                // 4. Génération du Token (JWT)
                // Payload : les infos qu'on veut stocker dans le jeton
                $payload = [
                    'iss' => "TalentHub", // Emetteur
                    'iat' => time(),      // Date de création (Issued At)
                    'exp' => time() + (60 * 60 * 24), // Expiration (24h)
                    'data' => [
                        'id' => $this->user->id,
                        'email' => $this->user->email,
                        'name' => $this->user->firstname . ' ' . $this->user->lastname
                    ]
                ];

                $jwt = $this->generateJWT($payload);

                http_response_code(200);
                return json_encode([
                    "message" => "Connexion réussie.",
                    "status" => true,
                    "token" => $jwt, // LE SÉSAME !
                    "user" => [
                        "id" => $this->user->id,
                        "firstname" => $this->user->firstname,
                        "lastname" => $this->user->lastname
                    ]
                ]);
            }
        }

        // Si on arrive ici, c'est que l'email n'existe pas ou le mot de passe est faux
        http_response_code(401); // Unauthorized
        return json_encode(["message" => "Email ou mot de passe incorrect."]);
    }

    // ... (Ta fonction getUserProfile est ici) ...

    // ==========================================
    // MÉTHODES PRIVÉES POUR LE TOKEN (JWT)
    // ==========================================

    /**
     * Génère un JSON Web Token signé
     */
    private function generateJWT($payload)
    {
        // 1. Header
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

        // 2. Encodage Base64Url
        $base64UrlHeader = $this->base64UrlEncode($header);
        $base64UrlPayload = $this->base64UrlEncode(json_encode($payload));

        // 3. Signature
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->secret_key, true);
        $base64UrlSignature = $this->base64UrlEncode($signature);

        // 4. Token final
        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    /**
     * Encodage Base64 adapté aux URLs (Standard JWT)
     */
    private function base64UrlEncode($data)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    public function getUserFromToken($jwt)
    {
        // 1. Découpage du Token (Header . Payload . Signature)
        $tokenParts = explode('.', $jwt);

        if (count($tokenParts) !== 3) {
            return false; // Format invalide
        }

        list($header64, $payload64, $signature64) = $tokenParts;

        // 2. Vérification de la Signature
        // On recrée la signature avec NOTRE clé secrète et les données reçues
        $validSignature = hash_hmac('sha256', $header64 . "." . $payload64, $this->secret_key, true);
        $validSignature64 = $this->base64UrlEncode($validSignature);

        // On compare la signature calculée avec celle du token (hash_equals protège des attaques temporelles)
        if (!hash_equals($validSignature64, $signature64)) {
            return false; // Signature falsifiée !
        }

        // 3. Décodage du Payload
        $payloadJson = $this->base64UrlDecode($payload64);
        $payload = json_decode($payloadJson, true);

        if ($payload === null) {
            return false; // JSON corrompu
        }

        // 4. Vérification de l'expiration (exp)
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return false; // Token expiré
        }

        // 5. Récupération des données fraîches en BDD
        // Le token contient l'ID dans $payload['data']['id'] (voir fonction login)
        if (isset($payload['data']['id'])) {
            $userId = $payload['data']['id'];
            $role = $payload['data']['role'] ?? 'student';

            // On utilise la méthode existante du modèle qui nettoie déjà le mot de passe
            // et formate les données (JSON decode des compétences, etc.)
            if ($this->user->getProfileById($userId)) {
                return [
                    "id" => $this->user->id,
                    "firstname" => $this->user->firstname,
                    "lastname" => $this->user->lastname,
                    "email" => $this->user->email,
                    "phone" => $this->user->phone,
                    "school" => $this->user->school,
                    "location" => $this->user->location,
                    "field_of_study" => $this->user->field_of_study,
                    "bio" => $this->user->bio,
                    "role" => $role, // Simplification
                    // ... Ajoute les autres champs dont tu as besoin
                    "avatar_initials" => $this->user->avatar_initials
                ];
            }
        }

        return false; // Utilisateur introuvable en BDD (supprimé entre temps ?)
    }

    /**
     * Décodage Base64Url (Inverse de base64UrlEncode)
     */
    private function base64UrlDecode($data)
    {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $data .= str_repeat('=', $padlen);
        }
        return base64_decode(str_replace(['-', '_'], ['+', '/'], $data));
    }

    public function updateUserProfile($data)
    {
        if (!isset($data['id']) || !isset($data['section'])) {
            http_response_code(400);
            return json_encode(["message" => "Données manquantes."]);
        }

        $this->user->id = $data['id'];
        $success = false;

        try {
            switch ($data['section']) {
                case 'infos':
                    $this->user->firstname = $data['firstname'];
                    $this->user->lastname = $data['lastname'];
                    $this->user->email = $data['email'];
                    $this->user->phone = $data['phone'];
                    $this->user->school = $data['school'];
                    $this->user->location = $data['region']; // Mapping region -> location
                    $this->user->field_of_study = $data['domain']; // Mapping domain -> field_of_study
                    $this->user->bio = $data['bio'];

                    $success = $this->user->updateInfos();
                    break;

                case 'categories':
                    // Checkboxes -> Array
                    $this->user->categories = $data['category'] ?? [];
                    $success = $this->user->updateCategories();
                    break;

                case 'search_type':
                    $this->user->search_type = $data['search_type'];
                    $success = $this->user->updateSearchType();
                    break;

                case 'preferences':
                    // Checkboxes -> Array
                    $this->user->work_mode = $data['remote'] ?? [];
                    $this->user->min_salary = (int) ($data['salary_min'] ?? 0);
                    $success = $this->user->updatePreferences();
                    break;

                default:
                    http_response_code(400);
                    return json_encode(["message" => "Section inconnue."]);
            }

            if ($success) {
                http_response_code(200);
                return json_encode(["message" => "Mise à jour réussie.", "section" => $data['section']]);
            } else {
                http_response_code(503);
                return json_encode(["message" => "Erreur lors de la mise à jour."]);
            }

        } catch (Exception $e) {
            http_response_code(500);
            return json_encode(["message" => "Erreur serveur : " . $e->getMessage()]);
        }
    }

    public function uploadCV($data, $files)
    {
        if (!isset($data['id']) || !isset($files['cv_file'])) {
            http_response_code(400);
            return json_encode(["message" => "Fichier ou ID manquant."]);
        }

        $userId = $data['id'];
        $file = $files['cv_file'];

        // 1. Vérifications d'usage
        if ($file['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            return json_encode(["message" => "Erreur lors du transfert du fichier."]);
        }

        // Vérification taille (Ex: max 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            http_response_code(400);
            return json_encode(["message" => "Le fichier est trop volumineux (Max 5MB)."]);
        }

        // Vérification extension
        $allowed = ['pdf', 'doc', 'docx'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            http_response_code(400);
            return json_encode(["message" => "Format non accepté (PDF, DOC, DOCX uniquement)."]);
        }

        // 2. Préparation du stockage
        // Création du nom unique : cv_IDUSER_TIMESTAMP.ext
        $filename = "cv_" . $userId . "_" . time() . "." . $ext;
        
        // Chemin absolu vers le dossier uploads (Assure-toi que ce dossier existe !)
        $targetDir = ROOT_PATH . 'public/uploads/cv/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        $targetFile = $targetDir . $filename;

        // 3. Déplacement du fichier
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            
            // 4. Mise à jour en BDD
            $this->user->id = $userId;
            $this->user->cv_filename = $filename;

            if ($this->user->updateCV()) {
                http_response_code(200);
                return json_encode([
                    "message" => "CV téléchargé avec succès.",
                    "filename" => $filename,
                    "url" => "/uploads/cv/" . $filename // Pour l'affichage direct
                ]);
            } else {
                // Si erreur BDD, on supprime le fichier pour ne pas polluer
                unlink($targetFile);
                http_response_code(503);
                return json_encode(["message" => "Erreur lors de la sauvegarde en base."]);
            }

        } else {
            http_response_code(500);
            return json_encode(["message" => "Erreur lors de l'enregistrement du fichier sur le serveur."]);
        }
    }
}
?>