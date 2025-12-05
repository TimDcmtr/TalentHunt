<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mes Candidatures - TalentHub</title>
  <link rel="stylesheet" href="assets/css/variables.css">
  <link rel="stylesheet" href="assets/css/feed.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap"
    rel="stylesheet">
</head>

<body>
  <?php require_once ROOT_PATH . 'app/helpers/Navbar.php';
  require_once ROOT_PATH . 'app/controllers/ApplicationController.php';
  require_once ROOT_PATH . 'app/helpers/UserSession.php';
  ?>
  <?php
  requireLogin(); // Protection de la page
  
  $applicationController = new ApplicationController();
  $candidatures = json_decode($applicationController->getStudentApplications($currentUser['id']), true);

  $stats = [
    'total' => count($candidatures),
    'en_attente' => count(array_filter($candidatures, fn($c) => $c['status'] === 'pending')),
    'accepte' => count(array_filter($candidatures, fn($c) => $c['status'] === 'accepted'))
  ];
  ?>

  <main class="main-content">
    <div class="container">
      <!-- Header -->
      <div class="feed-header">
        <div>
          <h1>Mes Candidatures</h1>
          <p class="feed-subtitle">Suivez l'évolution de vos candidatures en temps réel</p>
        </div>
      </div>

      <!-- Stats -->
      <div class="feed-stats">
        <div class="feed-stat-card glass-card">
          <div class="stat-icon-small" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
              <polyline points="14 2 14 8 20 8" />
            </svg>
          </div>
          <div>
            <p class="stat-label-small">Total</p>
            <p class="stat-value-small"><?php echo $stats['total']; ?></p>
          </div>
        </div>

        <div class="feed-stat-card glass-card">
          <div class="stat-icon-small" style="background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10" />
              <polyline points="12 6 12 12 16 14" />
            </svg>
          </div>
          <div>
            <p class="stat-label-small">En attente</p>
            <p class="stat-value-small"><?php echo $stats['en_attente']; ?></p>
          </div>
        </div>

        <div class="feed-stat-card glass-card">
          <div class="stat-icon-small" style="background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="20 6 9 17 4 12" />
            </svg>
          </div>
          <div>
            <p class="stat-label-small">Acceptés</p>
            <p class="stat-value-small"><?php echo $stats['accepte']; ?></p>
          </div>
        </div>
      </div>

      <!-- Filters -->
      <div class="feed-filters glass-card">
        <div class="filter-tabs">
          <button class="filter-tab active" data-filter="all">
            Tous (<?php echo count($candidatures); ?>)
          </button>
          <button class="filter-tab" data-filter="pending">
            En attente
          </button>
          <button class="filter-tab" data-filter="accepted">
            Acceptés
          </button>
          <button class="filter-tab" data-filter="refused">
            Refusés
          </button>
        </div>
      </div>

      <!-- Candidatures List -->
      <div class="candidatures-list">
        <?php foreach ($candidatures as $cand): ?>
          <div class="candidature-card glass-card" data-status="<?php echo $cand['status']; ?>">
            <div class="candidature-header">
              <div class="candidature-company">
                <div class="company-logo-feed"><?php echo strtoupper(substr($cand['name'], 0, 2)); ?></div>
                <div class="candidature-info">
                  <h3><?php echo $cand['offre']; ?></h3>
                  <p><?php echo $cand['company']; ?></p>
                </div>
              </div>

              <span class="status-badge status-<?php echo $cand['status']; ?>">
                <?php echo $cand['status_label']; ?>
              </span>
            </div>

            <div class="candidature-timeline">
              <div class="timeline-item completed">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                  <p class="timeline-title">Candidature envoyée</p>
                  <p class="timeline-date"><?php echo $cand['date_candidature']; ?></p>
                </div>
              </div>

              <?php if ($cand['status'] !== 'pending'): ?>
                <div class="timeline-item completed">
                  <div class="timeline-dot"></div>
                  <div class="timeline-content">
                    <p class="timeline-title">
                      <?php
                      if ($cand['status'] === 'vue')
                        echo 'Vue par l\'entreprise';
                      elseif ($cand['status'] === 'entretien')
                        echo 'Entretien planifié';
                      elseif ($cand['status'] === 'accepte')
                        echo 'Candidature acceptée';
                      elseif ($cand['status'] === 'refuse')
                        echo 'Candidature refusée';
                      ?>
                    </p>
                    <?php if ($cand['date_reponse']): ?>
                      <p class="timeline-date"><?php echo $cand['date_reponse']; ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              <?php else: ?>
                <div class="timeline-item">
                  <div class="timeline-dot"></div>
                  <div class="timeline-content">
                    <p class="timeline-title">En attente de réponse</p>
                  </div>
                </div>
              <?php endif; ?>
            </div>

            <?php if ($cand['message']): ?>
              <div class="candidature-message">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                </svg>
                <p><?php echo $cand['message']; ?></p>
              </div>
            <?php endif; ?>

            <div class="candidature-actions">
              <a href="/offer?id=<?php echo $cand['offer_id']; ?>" class="btn-secondary btn-sm">
                Voir l'offre
              </a>

              <button class="btn-icon-text btn-delete" data-id="<?php echo $cand['id']; ?>">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="3 6 5 6 21 6" />
                  <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                </svg>
                Retirer
              </button>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Empty State -->
      <div class="empty-state" style="display: none;">
        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
          <polyline points="14 2 14 8 20 8" />
        </svg>
        <h3>Aucune candidature</h3>
        <p>Vous n'avez pas encore postulé à des offres</p>
        <a href="/offres" class="btn-primary">
          Parcourir les offres
        </a>
      </div>
    </div>
  </main>

  <?php require_once ROOT_PATH . 'app/helpers/Footer.php'; ?>

  <script src="assets/js/navbar.js"></script>
  <script src="assets/js/feed.js"></script>
</body>

</html>