<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Espace Entreprise - TalentHub</title>
  <link rel="stylesheet" href="/assets/css/variables.css">
  <link rel="stylesheet" href="/assets/css/pro.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap"
    rel="stylesheet">
</head>

<body>
  <?php require_once ROOT_PATH . 'app/helpers/Navbar.php';
  require_once ROOT_PATH . 'app/controllers/JobOfferController.php';
  require_once ROOT_PATH . 'app/controllers/ApplicationController.php';
  require_once ROOT_PATH . 'app/helpers/CompanySession.php';
  ?>

  <?php
  $id = $currentCompany;

  $offersController = new JobOfferController();
  $offers = json_decode($offersController->findAllJobOffersCompany($id), true);
  $totalOffres = is_array($offers) ? count($offers) : 0;

  $applicationController = new ApplicationController();
  $applications = json_decode($applicationController->getCompanyApplications($id), true);
  $totalApplications = is_array($applications) ? count($applications) : 0;
  $candidates = $applicationController->getOfferApplications($id);

  requireCompanyLogin();
  if (isset($_POST['delete_offer_id'])) {
    $offerId = $_POST['delete_offer_id'];

    $offersController = new JobOfferController();
    $offersController->deleteOffer($offerId);

    // Recharger la page pour voir les changements
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
  }
  ?>

  <main class="main-content">
    <div class="container">
      <!-- Dashboard Header -->
      <div class="dashboard-header">
        <div class="header-content">
          <h1>Espace Entreprise</h1>
          <p class="header-subtitle">Gérez vos offres et trouvez les meilleurs talents : <?= $id ?></p>
        </div>
        <a href="/company/new-offer" class="btn-primary">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
          </svg>
          Publier une offre
        </a>
      </div>

      <!-- Stats Cards -->
      <div class="stats-grid">
        <div class="stat-card glass-card">
          <div class="stat-icon" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="2" y="7" width="20" height="14" rx="2" ry="2" />
              <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
            </svg>
          </div>
          <div class="stat-info">
            <p class="stat-label">Offres actives</p>
            <p class="stat-value"><?php echo $totalOffres; ?></p>
          </div>
        </div>


        <div class="stat-card glass-card">
          <div class="stat-icon" style="background: linear-gradient(135deg, #ec4899 0%, #f43f5e 100%);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
              <circle cx="9" cy="7" r="4" />
              <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
              <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
          </div>
          <div class="stat-info">
            <p class="stat-label">Candidatures reçues</p>
            <p class="stat-value"><?php echo $totalApplications; ?></p>
          </div>
        </div>
      </div>

      <!-- Quick Actions & Recent Activity -->
      <div class="dashboard-grid">
        <!-- My Offers -->
        <section class="dashboard-section glass-card">
          <div class="section-header-inline">
            <h2>Mes offres</h2>
          </div>

          <div class="offers-list">
            <?php
            foreach ($offers as $offer): ?>
              <div class="offer-item">
                <div class="offer-main">
                  <h3><?php echo $offer['title']; ?></h3>
                  <div class="offer-meta">
                    <span class="status-badge status-active">
                      ACTIVE
                    </span>
                  </div>
                </div>
                <div class="offer-stats">
                  <div class="offer-stat">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                      <circle cx="12" cy="12" r="3" />
                    </svg>
                    <span><?php echo $offer['views']; ?></span>
                  </div>
                  <div class="offer-stat">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                      <circle cx="9" cy="7" r="4" />
                    </svg>
                    <span><?php echo count($candidates) ?></span>
                  </div>
                </div>
                <div class="offer-actions">
                  <form method="POST" action="" style="display: inline;">
                    <input type="hidden" name="delete_offer_id" value="<?php echo $offer['id']; ?>">
                    <button type="submit" class="btn-icon" title="Supprimer"
                      onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette offre ?');">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 7l16 0" />
                        <path d="M10 11l0 6" />
                        <path d="M14 11l0 6" />
                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                      </svg>
                    </button>
                  </form>

                  <a href="/offer?id=<?php echo $offer['id']; ?>" class="btn-icon" title="Voir l'offre">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                      <circle cx="12" cy="12" r="3" />
                    </svg>
                  </a>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </section>

        <!-- Recent Applications -->
        <section class="dashboard-section glass-card">
          <div class="section-header-inline">
            <h2>Candidatures récentes</h2>
          </div>

          <div class="applications-list">
            <?php
            // S'il n'y a pas de candidatures
            if (empty($applications)) {
              echo '<p style="padding:20px; text-align:center; color:#666;">Aucune candidature pour le moment.</p>';
            } else {
              foreach ($applications as $app):
                // On détermine la classe CSS selon le statut
                $statusClass = '';
                if ($app['status'] == 'accepted')
                  $statusClass = 'status-success';
                if ($app['status'] == 'rejected')
                  $statusClass = 'status-error';
                ?>
                <div class="application-item" id="app-card-<?= $app['id'] ?>">

                  <div class="app-avatar">
                    <!-- Initiales du candidat -->
                    <?= strtoupper(substr($app['candidate_name'], 0, 2)) ?>
                  </div>

                  <div class="app-info">
                    <h4><?= htmlspecialchars($app['candidate_name']) ?></h4>
                    <p class="app-position">Pour : <?= htmlspecialchars($app['offre']) ?></p>
                    <span class="status-badge-small <?= $statusClass ?>" id="status-label-<?= $app['id'] ?>">
                      <?= $app['status_label'] ?>
                    </span>
                  </div>

                  <div class="app-actions">
                    <!-- Bouton Voir Profil -->
                    <a href="/student/profil?id=<?= $app['id'] ?>" class="btn-icon" title="Voir le profil">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                        <circle cx="12" cy="12" r="3" />
                      </svg>
                    </a>

                    <!-- Bouton CV (Si dispo) -->
                    <?php if (!empty($app['cv_filename'])): ?>
                      <a href="/uploads/cv/<?= $app['cv_filename'] ?>" target="_blank" class="btn-icon"
                        title="Télécharger CV">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                          <polyline points="7 10 12 15 17 10" />
                          <line x1="12" y1="15" x2="12" y2="3" />
                        </svg>
                      </a>
                    <?php endif; ?>

                    <!-- Actions (Affichées seulement si en attente) -->
                    <div class="decision-buttons" id="actions-<?= $app['id'] ?>"
                      style="<?= ($app['status'] !== 'pending') ? 'display:none;' : '' ?>">

                      <button class="btn-icon btn-accept" onclick="updateApplication(<?= $app['id'] ?>, 'accepted')"
                        title="Accepter">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2">
                          <polyline points="20 6 9 17 4 12" />
                        </svg>
                      </button>

                      <button class="btn-icon btn-refuse" onclick="updateApplication(<?= $app['id'] ?>, 'rejected')"
                        title="Refuser">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2">
                          <line x1="18" y1="6" x2="6" y2="18" />
                          <line x1="6" y1="6" x2="18" y2="18" />
                        </svg>
                      </button>

                    </div>
                  </div>
                </div>
              <?php endforeach;
            } ?>
          </div>
        </section>
      </div>
    </div>
  </main>
  <?php require_once ROOT_PATH . 'app/helpers/Footer.php'; ?>

  <script src="assets/js/navbar.js"></script>
  <script src="assets/js/pro.js"></script>
</body>

</html>