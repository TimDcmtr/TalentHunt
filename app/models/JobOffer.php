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
    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET 
                    company_id = :company_id,
                    title = :title,
                    location = :location,
                    type = :type,
                    remote = :remote,
                    salary = :salary,
                    duration = :duration,
                    start_date = :start_date,
                    description = :description,
                    tags = :tags,
                    missions = :missions,
                    requirements = :requirements,
                    benefits = :benefits,
                    created_at = NOW(),
                    views = 0,
                    applications_count = 0";

        $stmt = $this->conn->prepare($query);

        // Sanitize & Bind (Version simplifiée)
        $stmt->bindParam(":company_id", $this->company_id);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":location", $this->location);
        $stmt->bindParam(":type", $this->type);
        $stmt->bindParam(":remote", $this->remote);
        $stmt->bindParam(":salary", $this->salary);
        $stmt->bindParam(":duration", $this->duration);
        $stmt->bindParam(":start_date", $this->start_date);
        $stmt->bindParam(":description", $this->description);
        
        // JSON Encoding pour les tableaux
        $stmt->bindParam(":tags", json_encode($this->tags));
        $stmt->bindParam(":missions", json_encode($this->missions));
        $stmt->bindParam(":requirements", json_encode($this->requirements));
        $stmt->bindParam(":benefits", json_encode($this->benefits));

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
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
    private function timeElapsedString($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'an', 'm' => 'mois', 'w' => 'semaine',
            'd' => 'jour', 'h' => 'heure', 'i' => 'minute', 's' => 'seconde',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? 'Il y a ' . implode(', ', $string) : 'À l\'instant';
    }

    // --- DANS JobOffer.php ---

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