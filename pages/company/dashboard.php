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
  <?php require_once ROOT_PATH . 'app/helpers/Navbar.php'; ?>

  <main class="main-content">
    <div class="container">
      <!-- Dashboard Header -->
      <div class="dashboard-header">
        <div class="header-content">
          <h1>Espace Entreprise</h1>
          <p class="header-subtitle">Gérez vos offres et trouvez les meilleurs talents</p>
        </div>
        <a href="#publier-offre" class="btn-primary">
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
            <p class="stat-value">12</p>
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
            <p class="stat-value">247</p>
          </div>
        </div>

        <div class="stat-card glass-card">
          <div class="stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
            </svg>
          </div>
          <div class="stat-info">
            <p class="stat-label">Taux de réponse</p>
            <p class="stat-value">89%</p>
          </div>
        </div>

        <div class="stat-card glass-card">
          <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
          </div>
          <div class="stat-info">
            <p class="stat-label">Profils sauvegardés</p>
            <p class="stat-value">34</p>
            <p class="stat-change">En attente</p>
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
            $offers = [
              [
                'id' => 1,
                'title' => 'Développeur Full Stack',
                'applications' => 34,
                'views' => 256,
                'status' => 'active',
                'posted' => 'Il y a 2 jours'
              ],
              [
                'id' => 2,
                'title' => 'Designer UI/UX',
                'applications' => 28,
                'views' => 189,
                'status' => 'active',
                'posted' => 'Il y a 5 jours'
              ],
              [
                'id' => 3,
                'title' => 'Data Analyst',
                'applications' => 12,
                'views' => 98,
                'status' => 'draft',
                'posted' => 'Brouillon'
              ]
            ];

            foreach ($offers as $offer): ?>
              <div class="offer-item">
                <div class="offer-main">
                  <h3><?php echo $offer['title']; ?></h3>
                  <div class="offer-meta">
                    <span class="status-badge status-<?php echo $offer['status']; ?>">
                      <?php echo $offer['status'] === 'active' ? 'Active' : 'Brouillon'; ?>
                    </span>
                    <span class="offer-date"><?php echo $offer['posted']; ?></span>
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
            $applications = [
              [
                'name' => 'Marie Durant',
                'position' => 'Développeur Full Stack',
                'avatar' => 'MD',
                'time' => 'Il y a 2h',
                'match' => 95
              ],
              [
                'name' => 'Thomas Martin',
                'position' => 'Designer UI/UX',
                'avatar' => 'TM',
                'time' => 'Il y a 5h',
                'match' => 88
              ],
              [
                'name' => 'Sophie Bernard',
                'position' => 'Data Analyst',
                'avatar' => 'SB',
                'time' => 'Hier',
                'match' => 92
              ],
              [
                'name' => 'Lucas Petit',
                'position' => 'Développeur Full Stack',
                'avatar' => 'LP',
                'time' => 'Hier',
                'match' => 85
              ]
            ];

            foreach ($applications as $app): ?>
              <div class="application-item">
                <div class="app-avatar"><?php echo $app['avatar']; ?></div>
                <div class="app-info">
                  <h4><?php echo $app['name']; ?></h4>
                  <p><?php echo $app['position']; ?></p>
                  <span class="app-time"><?php echo $app['time']; ?></span>
                </div>
                <div class="app-match">
                  <div class="match-circle" style="--match: <?php echo $app['match']; ?>">
                    <svg width="50" height="50" viewBox="0 0 50 50">
                      <circle cx="25" cy="25" r="20" fill="none" stroke="var(--bg-tertiary)" stroke-width="4"/>
                      <circle cx="25" cy="25" r="20" fill="none" stroke="var(--primary)" stroke-width="4" 
                              stroke-dasharray="<?php echo 2 * 3.14159 * 20; ?>"
                              stroke-dashoffset="<?php echo 2 * 3.14159 * 20 * (1 - $app['match'] / 100); ?>"
                              stroke-linecap="round"
                              transform="rotate(-90 25 25)"/>
                    </svg>
                    <span><?php echo $app['match']; ?>%</span>
                  </div>
                </div>
                <a href="/candidature/<?php echo $app['name']; ?>" class="btn-secondary btn-sm">Voir le profil</a>
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