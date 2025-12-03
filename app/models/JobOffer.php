<?php
class JobOffer
{
    private $conn;
    private $table_name = "job_offers";

    // --- Properties ---
    public $id;
    public $company_id; // La clé étrangère
    public $title;
    public $location;
    public $type;
    public $remote;
    public $salary;
    public $duration;
    public $start_date;     // YYYY-MM-DD
    public $created_at;     // Datetime
    public $views;
    public $applications_count;

    // Arrays (JSON)
    public $tags;
    public $description;
    public $missions;
    public $requirements;
    public $benefits;

    // Computed property for "Il y a X jours"
    public $posted_ago;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // --- CREATE ---
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
        if($min && $max) $this->job->salary = "$min - $max €";
        elseif($min) $this->job->salary = "À partir de $min €";
        else $this->job->salary = "Non communiqué";

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

    // --- READ ONE ---
    public function getOfferById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Hydratation
            $this->id = $row['id'];
            $this->company_id = $row['company_id']; // IMPORTANT
            $this->title = $row['title'];
            $this->location = $row['location'];
            $this->type = $row['type'];
            $this->remote = $row['remote'];
            $this->salary = $row['salary'];
            $this->duration = $row['duration'];
            $this->start_date = $row['start_date'];
            $this->views = (int) $row['views'];
            $this->applications_count = (int) $row['applications_count'];
            $this->description = $row['description'];

            // Date "Posted ago" logic
            $this->created_at = $row['created_at'];
            $this->posted_ago = $this->timeElapsedString($row['created_at']);

            // JSON Decoding
            $this->tags = json_decode($row['tags'] ?? '[]', true);
            $this->missions = json_decode($row['missions'] ?? '[]', true);
            $this->requirements = json_decode($row['requirements'] ?? '[]', true);
            $this->benefits = json_decode($row['benefits'] ?? '[]', true);

            return true;
        }
        return false;
    }

    // Helper pour "Il y a X jours"
    // DANS app/models/JobOffer.php

    private function timeElapsedString($datetime, $full = false)
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        // CORRECTION : On utilise une variable locale au lieu de toucher à $diff
        $weeks = floor($diff->d / 7);
        $days = $diff->d - ($weeks * 7);

        $string = array(
            'y' => 'an',
            'm' => 'mois',
            'w' => 'semaine',
            'd' => 'jour',
            'h' => 'heure',
            'i' => 'minute',
            's' => 'seconde',
        );

        foreach ($string as $k => &$v) {
            // On gère le cas spécial des semaines (w) et des jours (d) manuellement
            if ($k === 'w') {
                $value = $weeks;
            } elseif ($k === 'd') {
                $value = $days;
            } else {
                $value = $diff->$k;
            }

            if ($value) {
                $v = $value . ' ' . $v . ($value > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full)
            $string = array_slice($string, 0, 1);
        return $string ? 'Il y a ' . implode(', ', $string) : 'À l\'instant';
    }
    /**
     * Incrémenter le nombre de vues
     */
    public function incrementViews($id)
    {
        $query = "UPDATE job_offers SET views = views + 1 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }


    /**
     * Récupère toutes les offres d'une entreprise spécifique
     */
    public function getAllByCompany($company_id)
    {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE company_id = :company_id 
                  ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':company_id', $company_id);
        $stmt->execute();

        return $stmt; // On retourne l'objet PDOStatement pour boucler dessus dans le controller
    }

}
?>