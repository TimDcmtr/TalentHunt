<?php
// On inclut la config et le modèle
include_once '../config/Database.php';
include_once '../models/User.php';

class UserController {
    
    private $db;
    private $user;

    public function __construct() {
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
public function register($data) {
        // 1. Validation des champs requis (HTML 'required')
        if(
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
        $this->user->lastname  = $data['lastname'];
        $this->user->email     = $data['email'];
        $this->user->password  = $data['password'];
        
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
            if($this->user->create()) {
                http_response_code(201);
                return json_encode(["message" => "Compte étudiant créé avec succès."]);
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
     * Récupère le profil complet d'un utilisateur par son ID
     * @param int $id L'ID de l'utilisateur
     */
    public function getUserProfile($id) {
        // On appelle la méthode du Modèle qui va hydrater $this->user
        if($this->user->getProfileById($id)) {
            
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
}
?>