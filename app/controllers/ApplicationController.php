<?php
require_once ROOT_PATH . 'app/config/db.php';
require_once ROOT_PATH . 'app/models/Application.php';
require_once ROOT_PATH . 'app/models/JobOffer.php';
require_once ROOT_PATH . 'app/models/Company.php';
require_once ROOT_PATH . 'app/models/User.php';
MESSAGE = "Données manquantes.";
class ApplicationController
{
    private $db;
    private $application;
    private $jobModel;
    private $companyModel;
    private $userModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();

        $this->application = new Application($this->db);
        $this->jobModel = new JobOffer($this->db);
        $this->companyModel = new Company($this->db);
        $this->userModel = new User($this->db);
    }

    /**
     * Postuler à une offre
     */
    public function apply($data)
    {
        // 1. Validation : On vérifie que 'offre_id' (envoyé par le JS) est bien là
        if (!isset($data['user_id']) || !isset($data['offre_id'])) {
            http_response_code(400);
            return json_encode([
                "message" => MESSAGE,
                "debug" => $data // Pour voir ce qui manque si besoin
            ]);
        }

        // 2. Vérifier doublon (On utilise bien $data['offre_id'])
        if ($this->application->checkExists($data['user_id'], $data['offre_id'])) {
            http_response_code(409); // Conflict
            return json_encode(["message" => "Vous avez déjà postulé à cette offre."]);
        }

        // 3. Récupérer l'offre (On utilise bien $data['offre_id'])
        if (!$this->jobModel->getOfferById($data['offre_id'])) {
            http_response_code(404);
            return json_encode(["message" => "Offre introuvable."]);
        }

        $companyId = $this->jobModel->company_id;

        // 4. Préparation du Modèle Application
        // Ici on mappe la donnée reçue ('offre_id') vers la propriété du modèle ('job_offer_id')
        $this->application->user_id = $data['user_id'];
        $this->application->job_offer_id = $data['offre_id']; // <-- C'est ici que la conversion se fait
        $this->application->company_id = $companyId;

        // Gestion du message
        $message = $data['cover_letter'] ?? 'Candidature simplifiée (One-Click)';
        if (!empty($data['availability'])) {
            $message .= " | Dispo: " . $data['availability'];
        }
        $this->application->message = $message;

        // 5. Enregistrement en BDD
        if ($this->application->create()) {
            http_response_code(201);
            return json_encode([
                "message" => "Candidature envoyée avec succès.",
                "status" => "success"
            ]);
        }

        http_response_code(503);
        return json_encode(["message" => "Erreur serveur lors de l'enregistrement."]);
    }
    /**
     * A. Pour l'ÉTUDIANT : Voir mes candidatures
     * Retourne : Offre, Entreprise, Logo, Statut...
     */
    public function getStudentApplications($userId)
    {
        $stmt = $this->application->getAllByUser($userId);
        $apps_arr = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            // 1. Récupérer infos Entreprise (Nom, Logo) via l'ID stocké
            $companyName = "Unknown";
            $companyLogo = "";
            if ($this->companyModel->getProfileById($row['company_id'])) {
                $companyName = $this->companyModel->name;
                $companyLogo = $this->companyModel->logo;
            }

            // 2. Récupérer infos Offre (Titre)
            $jobTitle = "Offre supprimée";
            if ($this->jobModel->getOfferById($row['job_offer_id'])) {
                $jobTitle = $this->jobModel->title;
            }

            // 3. Formatage Date & Status
            $dateCandidature = date("d/m/Y", strtotime($row['created_at']));
            $dateReponse = $row['updated_at'] ? date("d/m/Y", strtotime($row['updated_at'])) : null;

            // Mapping statut -> Label lisible
            $statusLabels = [
                'pending' => 'En attente',
                'accepted' => 'Acceptée',
                'rejected' => 'Refusée',
                'archived' => 'Archivée'
            ];
            $statusLabel = $statusLabels[$row['status']] ?? 'Inconnu';

            // 4. Construction de l'objet final demandé
            $item = [
                'id' => $row['id'],
                'userId' => $row['user_id'],
                'offre' => $jobTitle,
                'offer_id' => $row['job_offer_id'],
                'company' => $companyName,
                'logo' => $companyLogo,
                'status' => $row['status'],
                'status_label' => $statusLabel,
                'date_candidature' => $dateCandidature,
                'date_reponse' => $dateReponse,
                'message' => $row['message']
            ];

            array_push($apps_arr, $item);
        }

        http_response_code(200);
        return json_encode($apps_arr);
    }

    /**
     * B. Pour l'ENTREPRISE : Voir les candidats reçus
     * Retourne : Nom du candidat, Offre concernée, CV, Statut...
     */
    public function getCompanyApplications($companyId)
    {
        $stmt = $this->application->getAllByCompany($companyId);
        $apps_arr = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            // 1. Récupérer infos Candidat (Nom, Prénom, CV)
            $candidateName = "Utilisateur supprimé";
            $cvFilename = null;
            if ($this->userModel->getProfileById($row['user_id'])) {
                $candidateName = $this->userModel->firstname . ' ' . $this->userModel->lastname;
                $candidateId = $this->userModel->id;
                $candidateAvatar = $this->userModel->avatar_initials;
                $candidateBio = $this->userModel->bio;
            }

            // 2. Récupérer Titre de l'offre
            $jobTitle = "Offre supprimée";
            if ($this->jobModel->getOfferById($row['job_offer_id'])) {
                $jobTitle = $this->jobModel->title;
            }

            // 3. Status
            $statusLabels = [
                'pending' => 'À traiter',
                'accepted' => 'Accepté',
                'rejected' => 'Refusé',
                'archived' => 'Archivé'
            ];

            $item = [
                'id' => $row['id'],
                'candidate_name' => $candidateName,
                'candidate_id' => $candidateId,
                'candidate_avatar' => $candidateAvatar,
                'candidate_bio' => $candidateBio,
                'cv_filename' => $cvFilename, // Pour faire un lien de téléchargement
                'offre' => $jobTitle,
                'status' => $row['status'],
                'status_label' => $statusLabels[$row['status']] ?? $row['status'],
                'date_candidature' => date("d/m/Y", strtotime($row['created_at'])),
                'message_envoye' => $row['message'] // Le message que l'entreprise a envoyé (si reponse)
            ];

            array_push($apps_arr, $item);
        }

        http_response_code(200);
        return json_encode($apps_arr);
    }

    public function getOfferApplications($offerId)
    {
        // Retourne un tableau d'applications
        return $this->application->getAllByOffer($offerId);
    }

    public function withdraw($data)
    {
        if (!isset($data['application_id']) || !isset($data['user_id'])) {
            http_response_code(400);
            return json_encode(["message" => MESSAGE]);
        }

        if ($this->application->delete($data['application_id'], $data['user_id'])) {
            http_response_code(200);
            return json_encode(["message" => "Candidature retirée avec succès."]);
        } else {
            http_response_code(403); // Forbidden ou Not Found
            return json_encode(["message" => "Impossible de retirer cette candidature (introuvable ou non autorisé)."]);
        }
    }

    public function updateStatus($data)
    {
        if (!isset($data['application_id']) || !isset($data['company_id']) || !isset($data['status'])) {
            http_response_code(400);
            return json_encode(["message" => MESSAGE]);
        }

        if ($this->application->updateStatus($data['application_id'], $data['company_id'], $data['status'])) {
            
            // Petit bonus : Message de succès personnalisé
            $msg = ($data['status'] === 'accepted') ? "Candidat accepté !" : "Candidat refusé.";
            
            http_response_code(200);
            return json_encode(["message" => $msg, "new_status" => $data['status']]);
        } else {
            http_response_code(403);
            return json_encode(["message" => "Action non autorisée ou candidature introuvable."]);
        }
    }
}
?>