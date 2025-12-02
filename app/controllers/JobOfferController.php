<?php
require_once ROOT_PATH . 'app/config/db.php';
require_once ROOT_PATH . 'app/models/JobOffer.php';
require_once ROOT_PATH . 'app/models/Company.php'; // On a besoin du modèle Company

class JobOfferController
{
    private $db;
    private $job;
    private $companyModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        
        $this->job = new JobOffer($this->db);
        $this->companyModel = new Company($this->db);
    }

    /**
     * Récupérer une offre complète (Offre + Infos Entreprise)
     */
    public function getJobDetails($id)
    {
        // 1. Récupérer les données brutes de l'offre
        if ($this->job->getOfferById($id)) {

            // 2. Récupérer les données de l'entreprise associée
            // On utilise l'ID stocké dans l'offre ($this->job->company_id)
            $companyData = [];
            if ($this->companyModel->getProfileById($this->job->company_id)) {
                $companyData = [
                    'name' => $this->companyModel->name,
                    'logo' => $this->companyModel->logo
                ];
            } else {
                // Fallback si l'entreprise a été supprimée
                $companyData = ['name' => 'Entreprise inconnue', 'logo' => '❓'];
            }

            // 3. Formatage de la date de début (YYYY-MM-DD -> dd/mm/yyyy)
            $startDateFormatted = date("d/m/Y", strtotime($this->job->start_date));

            // 4. Construction de la réponse fusionnée
            $response = [
                'id' => $this->job->id,
                'title' => $this->job->title,
                
                // --- Infos injectées depuis le CompanyModel ---
                'company' => $companyData['name'],
                'company_logo' => $companyData['logo'],
                // ---------------------------------------------

                'location' => $this->job->location,
                'type' => $this->job->type,
                'remote' => $this->job->remote,
                'salary' => $this->job->salary,
                'duration' => $this->job->duration,
                'start_date' => $startDateFormatted,
                'posted' => $this->job->posted_ago, // Champ calculé dans le modèle
                'views' => $this->job->views,
                'applications' => $this->job->applications_count,
                
                'tags' => $this->job->tags,
                'description' => $this->job->description,
                'missions' => $this->job->missions,
                'requirements' => $this->job->requirements,
                'benefits' => $this->job->benefits
            ];

            http_response_code(200);
            return json_encode($response);

        } else {
            http_response_code(404);
            return json_encode(["message" => "Offre non trouvée."]);
        }
    }

    /**
     * Créer une nouvelle offre
     */
    public function createOffer($data)
    {
        // $data contient le POST body, on attend un 'company_id' (ex: via le token JWT de l'entreprise)
        
        if (!isset($data['company_id']) || !isset($data['title'])) {
            http_response_code(400);
            return json_encode(["message" => "Données manquantes."]);
        }

        $this->job->company_id = $data['company_id'];
        $this->job->title = $data['title'];
        $this->job->location = $data['location'] ?? null;
        $this->job->type = $data['type'] ?? 'Stage';
        $this->job->remote = $data['remote'] ?? 'Sur site';
        $this->job->salary = $data['salary'] ?? null;
        $this->job->duration = $data['duration'] ?? null;
        
        // Conversion format date "01/03/2025" -> "2025-03-01" pour SQL
        if (isset($data['start_date'])) {
            $dateObj = DateTime::createFromFormat('d/m/Y', $data['start_date']);
            $this->job->start_date = $dateObj ? $dateObj->format('Y-m-d') : null;
        }

        $this->job->description = $data['description'] ?? '';
        
        // Tableaux
        $this->job->tags = $data['tags'] ?? [];
        $this->job->missions = $data['missions'] ?? [];
        $this->job->requirements = $data['requirements'] ?? [];
        $this->job->benefits = $data['benefits'] ?? [];

        if ($this->job->create()) {
            http_response_code(201);
            return json_encode(["message" => "Offre publiée avec succès.", "id" => $this->job->id]);
        }
        
        http_response_code(503);
        return json_encode(["message" => "Erreur lors de la publication."]);
    }
}
?>