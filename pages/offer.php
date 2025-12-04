<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail de l'offre - TalentHub</title>
    <link rel="stylesheet" href="assets/css/variables.css">
    <link rel="stylesheet" href="assets/css/offre-detail.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
</head>

<body>

    <?php require_once ROOT_PATH . 'app/helpers/Navbar.php';
      require_once ROOT_PATH . 'app/controllers/JobOfferController.php';
      require_once ROOT_PATH . 'app/controllers/ApplicationController.php';
    ?>
    <?php if (!isset($_GET['id'])): ?>
        <h1> Erreur de Connexion </h1>
    <?php else: ?>

    <?php 
    $id = (int)$_GET['id'];
    $OffreController = new JobOfferController();
    $OffreController->incrementViewsForOffer($id);
    $offreDb = $OffreController->getJobDetails($id);
    $offre = json_decode($offreDb, true);
    
    $applicationController = new ApplicationController();
    $candidates = $applicationController->getOfferApplications($id);
    ?>

    <main class="main-content">
        <div class="container">
            <div class="offre-layout">
                <div class="offre-main">
                    <nav class="breadcrumb">
                        <a href="/offres">Offres</a>
                        <span>/</span>
                        <span><?php echo $offre['title']; ?></span>
                    </nav>

                    <div class="offre-header glass-card">
                        <div class="company-logo-large"><?php echo strtoupper(substr($offre['company'], 0, 2)); ?></div>
                        <div class="offre-header-content">
                            <h1><?php echo $offre['title']; ?></h1>
                            <div class="company-info">
                                <h2><?php echo $offre['company']; ?></h2>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" />
                                </svg>
                            </div>
                            <div class="offre-meta">
                                <span class="meta-item">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                        <circle cx="12" cy="10" r="3" />
                                    </svg>
                                    <?php echo $offre['location']; ?>
                                </span>
                                <span class="meta-item">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2" />
                                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                                    </svg>
                                    <?php echo $offre['type']; ?>
                                </span>
                                <span class="meta-item">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2" />
                                        <line x1="8" y1="21" x2="16" y2="21" />
                                        <line x1="12" y1="17" x2="12" y2="21" />
                                    </svg>
                                    <?php echo $offre['remote']; ?>
                                </span>
                                <span class="meta-item">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12 16 14" />
                                    </svg>
                                    <?php echo $offre['duration']; ?>
                                </span>
                            </div>
                            <div class="offre-tags">
                                <?php foreach ($offre['tags'] as $tag): ?>
                                    <span class="tag"><?php echo $tag; ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <section class="offre-section glass-card">
                        <h3>Description du poste</h3>
                        <p><?php echo $offre['description']; ?></p>
                    </section>

                    <section class="offre-section glass-card">
                        <h3>Vos missions</h3>
                        <ul class="offre-list">
                            <?php foreach ($offre['missions'] as $mission): ?>
                                <li><?php echo $mission; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </section>

                    <section class="offre-section glass-card">
                        <h3>Profil recherché</h3>
                        <ul class="offre-list">
                            <?php foreach ($offre['requirements'] as $req): ?>
                                <li><?php echo $req; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </section>

                    <section class="offre-section glass-card">
                        <h3>Avantages</h3>
                        <div class="benefits-grid">
                            <?php foreach ($offre['benefits'] as $benefit): ?>
                                <div class="benefit-item">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                    <span><?php echo $benefit; ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                </div>

                <aside class="offre-sidebar">
                    <div class="apply-card glass-card sticky-card">
                        <div class="salary-info">
                            <span class="salary-label">Rémunération</span>
                            <span class="salary-value"><?php echo $offre['salary']; ?>/mois</span>
                        </div>

                        <div class="apply-info">
                            <div class="info-item">
                                <span class="info-label">Date de début</span>
                                <span class="info-value"><?php echo $offre['start_date']; ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Durée</span>
                                <span class="info-value"><?php echo $offre['duration']; ?></span>
                            </div>
                        </div>

                        <button class="btn-primary btn-full" id="applyBtn" data-offre-id="<?php echo $offre['id']; ?>">
                            Postuler maintenant
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14M12 5l7 7-7 7" />
                            </svg>
                        </button>

                        <div class="offre-stats-sidebar">
                            <div class="stat-sidebar">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <span><?php echo $offre['views']; ?> vues</span>
                            </div>
                            <div class="stat-sidebar">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                </svg>
                                <span><?php echo count($candidates); ?> candidatures</span>
                            </div>
                        </div>

                        <p class="posted-date">Publiée <?php echo $offre['posted']; ?></p>
                    </div>

                    <div class="company-card glass-card">
                        <h4>À propos de l'entreprise</h4>
                        <div class="company-header-small">
                            <div class="company-logo-small"><?php echo strtoupper(substr($offre['company'], 0, 2)); ?></div>
                            <div>
                                <h5><?php echo $offre['company']; ?></h5>
                                <p>Tech • 50-200 employés</p>
                            </div>
                        </div>
                        <p class="company-description">
                            TechCorp est une scale-up innovante spécialisée dans le développement de solutions web et mobile.
                        </p>
                        <a href="/company/profil?id=<?php echo $offre['company_id']; ?>" class="link">
                            Voir le profil complet →
                        </a>
                    </div>
                </aside>
            </div>
        </div>
    </main>

    <?php endif; ?>
    <?php require_once ROOT_PATH . 'app/helpers/Footer.php'; ?>

    <script src="assets/js/navbar.js"></script>
    <script src="assets/js/offre-detail.js"></script>
</body>
</html>