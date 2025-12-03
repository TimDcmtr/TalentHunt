<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Espace Entreprise - TalentHub</title>
  <link rel="stylesheet" href="assets/css/variables.css">
  <link rel="stylesheet" href="assets/css/pro.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
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

      requireCompanyLogin();
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
              <line x1="12" y1="5" x2="12" y2="19"/>
              <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Publier une offre
          </a>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
          <div class="stat-card glass-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
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
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
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
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                      </svg>
                      <span><?php echo $offer['views']; ?></span>
                    </div>
                    <div class="offer-stat">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                      </svg>
                      <span><?php echo $offer['applications']; ?></span>
                    </div>
                  </div>
                  <div class="offer-actions">
                    <a href="/offre/<?php echo $offer['id']; ?>/edit" class="btn-icon" title="Modifier">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                      </svg>
                    </a>
                    <a href="/offre/<?php echo $offer['id']; ?>/candidatures" class="btn-icon" title="Voir les candidatures">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
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
              <h2>Candidatures</h2>
            </div>

            <div class="applications-list">
              <?php
              $applicationsTests = [
                [
                  'name' => 'Marie Durant',
                  'position' => 'Développeur Full Stack',
                  'avatar' => 'MD',
                ],
                [
                  'name' => 'Thomas Martin',
                  'position' => 'Designer UI/UX',
                  'avatar' => 'TM',
                ],
                [
                  'name' => 'Sophie Bernard',
                  'position' => 'Data Analyst',
                  'avatar' => 'SB',
                ],
                [
                  'name' => 'Lucas Petit',
                  'position' => 'Développeur Full Stack',
                  'avatar' => 'LP',
                ]
              ];

              foreach ($applications as $app): ?>
                <div class="application-item">
                  <div class="app-avatar"><?php echo $app['avatar']; ?></div>
                  <div class="app-info">
                    <h4><?php echo $app['candidate_name']; ?></h4>
                    <p><?php echo $app['candidate_bio']; ?></p>
                  </div>
                  <a href="/student/profil?id=<?php echo $app['candidate_id']; ?>" class="btn-secondary btn-sm">Voir le profil</a>
                </div>
              <?php endforeach; ?>
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