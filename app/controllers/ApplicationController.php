<?php
require_once ROOT_PATH . 'app/config/db.php';
require_once ROOT_PATH . 'app/models/Application.php';
require_once ROOT_PATH . 'app/models/JobOffer.php';
require_once ROOT_PATH . 'app/models/Company.php';
require_once ROOT_PATH . 'app/models/User.php';

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
        // $data attend : user_id, job_offer_id
        if (!isset($data['user_id']) || !isset($data['job_offer_id'])) {
            http_response_code(400);
            return json_encode(["message" => "Données manquantes."]);
        }

        // 1. Vérifier doublon
        if ($this->application->checkExists($data['user_id'], $data['job_offer_id'])) {
            http_response_code(409); // Conflict
            return json_encode(["message" => "Vous avez déjà postulé à cette offre."]);
        }

        // 2. Récupérer l'ID de l'entreprise via l'offre (nécessaire pour la table application)
        if (!$this->jobModel->getOfferById($data['job_offer_id'])) {
            http_response_code(404);
            return json_encode(["message" => "Offre introuvable."]);
        }
        $companyId = $this->jobModel->company_id;

        // 3. Création
        $this->application->user_id = $data['user_id'];
        $this->application->job_offer_id = $data['job_offer_id'];
        $this->application->company_id = $companyId;

        if ($this->application->create()) {
            http_response_code(201);
            return json_encode(["message" => "Candidature envoyée avec succès."]);
        }

        http_response_code(503);
        return json_encode(["message" => "Erreur lors de l'envoi."]);
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
}
?>