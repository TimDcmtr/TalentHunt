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

    public function findAllJobOffersCompany($id_company)
    {
        // 1. Récupérer les infos de l'entreprise (Nom & Logo) pour les injecter dans les offres
        // Cela évite de faire une requête SQL "Company" pour chaque offre trouvée
        $companyData = ['name' => 'Unknown', 'logo' => '❓'];

        if ($this->companyModel->getProfileById($id_company)) {
            $companyData = [
                'name' => $this->companyModel->name,
                'logo' => $this->companyModel->logo
            ];
        }

        // 2. Récupérer les offres brutes depuis la BDD
        $stmt = $this->job->getAllByCompany($id_company);
        $offers_arr = [];

        // 3. Boucle et formatage (Similaire à getJobDetails mais en boucle)
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            // Calcul du "Il y a X jours" (On peut utiliser une fonction helper ou le faire ici)
            // Note: Pour faire propre, rend la méthode timeElapsedString du modèle 'public' ou 'static'
            // Ici je refais une logique simple pour l'exemple :
            $created = new DateTime($row['created_at']);
            $now = new DateTime();
            $interval = $now->diff($created);
            $posted_ago = "Il y a " . $interval->d . " jours";
            if ($interval->d == 0)
                $posted_ago = "Aujourd'hui";

            $item = [
                'id' => $row['id'],
                'title' => $row['title'],

                // Infos Entreprise (Injectées)
                'company' => $companyData['name'],
                'company_logo' => $companyData['logo'],

                'location' => $row['location'],
                'type' => $row['type'],
                'remote' => $row['remote'],
                'salary' => $row['salary'],
                'duration' => $row['duration'],
                'start_date' => date("d/m/Y", strtotime($row['start_date'])),
                'posted' => $posted_ago,
                'views' => (int) ($row['views'] ?? 0),
                'applications' => (int) ($row['applications_count'] ?? 0),

                // Décodage JSON pour la liste (si besoin d'afficher les tags dans la liste)
                'tags' => json_decode($row['tags'] ?? '[]', true),

                // On n'envoie généralement pas la description complète/missions/benefits 
                // dans une liste pour alléger le JSON, mais tu peux les ajouter si tu veux :
                // 'description' => $row['description']
            ];

            array_push($offers_arr, $item);
        }

        // 4. Renvoi du JSON
        http_response_code(200);
        return json_encode($offers_arr);
    }
    /**
     * Récupérer TOUTES les offres (toutes entreprises)
     */
    public function findAllJobOffers($filters = [])
    {
        $query = "SELECT jo.*, c.name as company_name, c.logo as company_logo 
                  FROM job_offers jo 
                  LEFT JOIN companies c ON jo.company_id = c.id 
                  WHERE 1=1";

        $params = [];

        if (!empty($filters['search'])) {
            $query .= " AND jo.title LIKE :search";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['type'])) {
            $types = is_array($filters['type']) ? $filters['type'] : [$filters['type']];
            $placeholders = [];
            foreach ($types as $i => $type) {
                $key = ":type$i";
                $placeholders[] = $key;
                $params[$key] = $type;
            }
            $query .= " AND jo.type IN (" . implode(',', $placeholders) . ")";
        }

        if (!empty($filters['location'])) {
            $query .= " AND jo.location LIKE :location";
            $params[':location'] = '%' . $filters['location'] . '%';
        }

        if (!empty($filters['remote'])) {
            $remotes = is_array($filters['remote']) ? $filters['remote'] : [$filters['remote']];
            $placeholders = [];
            foreach ($remotes as $i => $remote) {
                $key = ":remote$i";
                $placeholders[] = $key;
                $params[$key] = $remote;
            }
            $query .= " AND jo.remote IN (" . implode(',', $placeholders) . ")";
        }

        $query .= " ORDER BY jo.created_at DESC";

        $stmt = $this->db->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();

        $offers_arr = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $created = new DateTime($row['created_at']);
            $now = new DateTime();
            $interval = $now->diff($created);
            $posted_ago = "Il y a " . $interval->d . " jours";
            if ($interval->d == 0)
                $posted_ago = "Aujourd'hui";

            $item = [
                'id' => $row['id'],
                'title' => $row['title'],

                'company' => $row['company_name'] ?? 'Entreprise inconnue',
                'company_logo' => $row['company_logo'] ?? '❓',

                'location' => $row['location'],
                'type' => $row['type'],
                'remote' => $row['remote'],
                'salary' => $row['salary'],
                'duration' => $row['duration'],
                'start_date' => date("d/m/Y", strtotime($row['start_date'])),
                'posted' => $posted_ago,
                'views' => (int) ($row['views'] ?? 0),
                'applications' => (int) ($row['applications_count'] ?? 0),

                'tags' => json_decode($row['tags'] ?? '[]', true),
            ];

            array_push($offers_arr, $item);
        }

        http_response_code(200);
        return json_encode($offers_arr);
    }
    /**
     * Incrémenter les vues d'une offre
     */
    public function incrementViewsForOffer($id)
    {
        return $this->job->incrementViews($id);
    }

    /**
     * Créer une nouvelle offre
     */
    public function createOffer($data)
    {
        // Validation minimale
        if (!isset($data['company_id']) || !isset($data['title'])) {
            http_response_code(400);
            return json_encode(["message" => "Titre manquant."]);
        }

        $this->job->company_id = $data['company_id'];
        $this->job->title = $data['title'];
        $this->job->location = $data['location'] ?? 'Non spécifié';
        $this->job->type = $data['contract_type'] ?? 'CDI'; // Mapping name HTML
        $this->job->remote = $data['remote'] ?? 'Sur site';
        $this->job->duration = $data['duration'] ?? null;

        // Gestion Salaire (Min - Max)
        $min = $data['salary_min'] ?? '';
        $max = $data['salary_max'] ?? '';
        if ($min && $max)
            $this->job->salary = "$min - $max €";
        elseif ($min)
            $this->job->salary = "À partir de $min €";
        else
            $this->job->salary = "Non communiqué";

        // Date de début
        if (!empty($data['start_date'])) {
            $this->job->start_date = $data['start_date']; // Format YYYY-MM-DD direct du input date
        } else {
            $this->job->start_date = date('Y-m-d', strtotime('+1 month')); // Par défaut +1 mois
        }

        $this->job->description = $data['description'] ?? '';

        // Gestion des tags (Input texte séparé par virgules -> Array)
        if (isset($data['tags']) && is_string($data['tags'])) {
            // "React, Node" -> ["React", "Node"]
            $this->job->tags = array_map('trim', explode(',', $data['tags']));
        } else {
            $this->job->tags = [];
        }

        // Tableaux dynamiques (Missions, Requirements, Benefits)
        // Le JS enverra des tableaux JSON directement
        $this->job->missions = $data['missions'] ?? [];
        $this->job->requirements = $data['requirements'] ?? [];
        $this->job->benefits = $data['benefits'] ?? [];

        if ($this->job->create()) {
            http_response_code(201);
            return json_encode([
                "message" => "Offre publiée avec succès !",
                "id" => $this->job->id
            ]);
        }

        http_response_code(503);
        return json_encode(["message" => "Erreur lors de la publication."]);
    }
}
?>