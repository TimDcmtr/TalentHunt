<?php
// On inclut la config et le modèle
require_once ROOT_PATH . 'app/config/db.php';
require_once ROOT_PATH . 'app/models/Company.php';

class CompanyController
{
    private $db;
    private $company;
    private $secret_key = "MA_SUPER_CLE_SECRETE_TRES_LONGUE_ET_ALEATOIRE"; // Idéalement, à mettre dans un .env

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->company = new Company($this->db);
    }

    /**
     * Inscription de l'entreprise
     */
    public function register($data)
    {
        // 1. Validation des champs requis
        if (
            !isset($data['name']) ||
            !isset($data['email']) ||
            !isset($data['password'])
        ) {
            http_response_code(400);
            return json_encode(["message" => "Veuillez remplir tous les champs obligatoires."]);
        }

        // 2. MAPPING : HTML -> Modèle (DB English)
        $this->company->name = $data['name'];
        $this->company->email = $data['email'];
        $this->company->password = $data['password'];

        // Champs optionnels ou mappés
        // HTML: name="siege" -> DB: headquarters
        $this->company->headquarters = $data['region'] ?? null;
        
        // HTML: name="site_web" -> DB: website
        $this->company->website = $data['site_web'] ?? '(Aucun site)';
        
        // HTML: name="secteur" -> DB: sector
        $this->company->sector = $data['domain'] ?? null;
        
        // HTML: name="tel" -> DB: phone
        $this->company->phone = $data['tel'] ?? null;

        // 3. Création
        try {
            if ($this->company->create()) {
                http_response_code(201);
                return json_encode(["message" => "Compte entreprise créé avec succès."]);
            } else {
                http_response_code(503);
                return json_encode(["message" => "Impossible de créer le compte. L'email existe peut-être déjà."]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            return json_encode(["message" => "Erreur serveur : " . $e->getMessage()]);
        }
    }

    /**
     * Login Entreprise
     */
    public function login($email, $password) {
        if(empty($email) || empty($password)) {
            http_response_code(400);
            return json_encode(["message" => "Email et mot de passe requis."]);
        }

        $this->company->email = $email;

        if($this->company->findByEmail()) {
            if(password_verify($password, $this->company->password)) {
                
                // Payload JWT spécifique Entreprise
                $payload = [
                    'iss' => "TalentHub",
                    'iat' => time(),
                    'exp' => time() + (60 * 60 * 24),
                    'data' => [
                        'id' => $this->company->id,
                        'email' => $this->company->email,
                        'name' => $this->company->name,
                        'role' => 'company' // Distinction importante
                    ]
                ];

                $jwt = $this->generateJWT($payload);

                http_response_code(200);
                return json_encode([
                    "message" => "Connexion réussie.",
                    "status" => true,
                    "token" => $jwt,
                    "user" => [ // On garde la clé "user" pour la compatibilité front, ou on change en "company"
                        "id" => $this->company->id,
                        "name" => $this->company->name,
                        "type" => "company"
                    ]
                ]);
            }
        }

        http_response_code(401);
        return json_encode(["message" => "Email ou mot de passe incorrect."]);
    }

    /**
     * Récupère le profil complet d'une entreprise
     */
    public function getCompanyProfile($id)
    {
        if ($this->company->getProfileById($id)) {

            // Mapping vers JSON
            $company_arr = [
                "id" => $this->company->id,
                "name" => $this->company->name,
                "logo" => $this->company->logo,
                
                // Infos Business
                "headquarters" => $this->company->headquarters,
                "website" => $this->company->website,
                "sector" => $this->company->sector,
                "size_range" => $this->company->size_range,
                "founded_year" => $this->company->founded_year,
                "employee_count" => $this->company->employee_count,

                // Description & Culture
                "short_description" => $this->company->short_description,
                "description" => $this->company->description,
                "core_values" => $this->company->core_values, // Array
                "specialties" => $this->company->specialties, // Array

                // Contact
                "email" => $this->company->email,
                "phone" => $this->company->phone,
                "linkedin" => $this->company->linkedin,
                "twitter" => $this->company->twitter,

                // Stats
                "active_offers" => $this->company->active_offers,
                "member_since" => $this->company->member_since
            ];

            http_response_code(200);
            return json_encode($company_arr);

        } else {
            http_response_code(404);
            return json_encode(["message" => "Company not found."]);
        }
    }

    // ==========================================
    // MÉTHODES PRIVÉES JWT (Identiques à User)
    // ==========================================

    private function generateJWT($payload) {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $base64UrlHeader = $this->base64UrlEncode($header);
        $base64UrlPayload = $this->base64UrlEncode(json_encode($payload));
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->secret_key, true);
        $base64UrlSignature = $this->base64UrlEncode($signature);
        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    private function base64UrlEncode($data) {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    private function base64UrlDecode($data) {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $data .= str_repeat('=', $padlen);
        }
        return base64_decode(str_replace(['-', '_'], ['+', '/'], $data));
    }

    // Récupérer l'entreprise via le Token (pour l'authentification auto)
    public function getCompanyFromToken($jwt) {
        $tokenParts = explode('.', $jwt);
        if (count($tokenParts) !== 3) return false;

        list($header64, $payload64, $signature64) = $tokenParts;

        $validSignature = hash_hmac('sha256', $header64 . "." . $payload64, $this->secret_key, true);
        $validSignature64 = $this->base64UrlEncode($validSignature);

        if (!hash_equals($validSignature64, $signature64)) return false;

        $payload = json_decode($this->base64UrlDecode($payload64), true);
        if ($payload === null || (isset($payload['exp']) && $payload['exp'] < time())) return false;

        // Vérification que c'est bien une entreprise
        if (isset($payload['data']['id']) && isset($payload['data']['role']) && $payload['data']['role'] === 'company') {
            $companyId = $payload['data']['id'];
            if ($this->company->getProfileById($companyId)) {
                return [
                    "id" => $this->company->id,
                    "name" => $this->company->name,
                    "email" => $this->company->email,
                    "role" => "company",
                    "logo" => $this->company->logo
                ];
            }
        }
        return false;
    }
}
?>