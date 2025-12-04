<?php
class Application
{
    private $conn;
    private $table_name = "applications";

    public $id;
    public $user_id;
    public $job_offer_id;
    public $company_id;
    public $status;
    public $message;
    public $created_at;
    public $updated_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // --- CRÉER UNE CANDIDATURE ---
    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET 
                    user_id = :user_id, 
                    job_offer_id = :job_offer_id, 
                    company_id = :company_id, 
                    status = 'pending',
                    created_at = NOW()";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":job_offer_id", $this->job_offer_id);
        $stmt->bindParam(":company_id", $this->company_id);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // --- LISTE POUR UN ÉTUDIANT (Mes candidatures) ---
    public function getAllByUser($user_id)
    {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE user_id = :user_id 
                  ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        return $stmt;
    }

    // --- LISTE POUR UNE ENTREPRISE (Candidats reçus) ---
    public function getAllByCompany($company_id)
    {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE company_id = :company_id 
                  ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":company_id", $company_id);
        $stmt->execute();
        return $stmt;
    }

    // --- VÉRIFIER SI DÉJÀ POSTULÉ ---
    public function checkExists($user_id, $job_offer_id)
    {
        $query = "SELECT id FROM " . $this->table_name . " 
                  WHERE user_id = :user_id AND job_offer_id = :job_offer_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":job_offer_id", $job_offer_id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function getAllByOffer($job_offer_id)
    {
        $query = "SELECT * FROM " . $this->table_name . " 
              WHERE job_offer_id = :job_offer_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":job_offer_id", $job_offer_id);
        $stmt->execute();

        // On retourne le tableau complet directement (fetchAll)
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($application_id, $user_id)
    {
        // On vérifie d'abord que la candidature appartient bien à l'utilisateur
        // pour éviter qu'un utilisateur supprime la candidature d'un autre
        $checkQuery = "SELECT id FROM " . $this->table_name . " WHERE id = :id AND user_id = :user_id";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(':id', $application_id);
        $checkStmt->bindParam(':user_id', $user_id);
        $checkStmt->execute();

        if ($checkStmt->rowCount() === 0) {
            return false; // Pas trouvé ou pas autorisé
        }

        // Suppression réelle
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $application_id);

        return $stmt->execute();
    }

    public function updateStatus($application_id, $company_id, $status)
    {
        // 1. Vérification d'appartenance (Sécurité)
        $checkQuery = "SELECT id FROM " . $this->table_name . " 
                       WHERE id = :id AND company_id = :company_id";

        $stmtCheck = $this->conn->prepare($checkQuery);
        $stmtCheck->bindParam(':id', $application_id);
        $stmtCheck->bindParam(':company_id', $company_id);
        $stmtCheck->execute();

        if ($stmtCheck->rowCount() === 0) {
            return false; // Pas trouvé ou pas à vous
        }

        // 2. Mise à jour
        $query = "UPDATE " . $this->table_name . " 
                  SET status = :status, updated_at = NOW() 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // On s'assure que le statut est valide
        $allowed = ['accepted', 'rejected', 'entretien'];
        if (!in_array($status, $allowed))
            return false;

        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $application_id);

        return $stmt->execute();
    }
}
?>