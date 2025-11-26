<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Développeur Full Stack - TalentHub</title>
    <link rel="stylesheet" href="assets/css/variables.css">
    <link rel="stylesheet" href="assets/css/offre-detail.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap"
        rel="stylesheet">
</head>

<body>

    <?php require_once ROOT_PATH . 'app/helpers/Navbar.php'; ?>
    <?php

    // Simulation données - À remplacer par requête DB
    $offre = [
        'id' => 1,
        'title' => 'Développeur Full Stack',
        'company' => 'TechCorp',
        'company_logo' => '🚀',
        'location' => 'Paris, France',
        'type' => 'Stage',
        'remote' => 'Hybride',
        'salary' => '1200-1500€',
        'duration' => '6 mois',
        'start_date' => '01/03/2025',
        'posted' => 'Il y a 2 jours',
        'views' => 256,
        'applications' => 34,
        'tags' => ['React', 'Node.js', 'MongoDB', 'TypeScript', 'Docker'],
        'description' => 'Nous recherchons un développeur Full Stack passionné pour rejoindre notre équipe dynamique. Vous participerez au développement de solutions web innovantes et travaillerez sur des projets variés.',
        'missions' => [
            'Développer et maintenir des applications web avec React et Node.js',
            'Participer à la conception d\'architectures techniques',
            'Collaborer avec l\'équipe produit et design',
            'Écrire du code propre et maintenable',
            'Participer aux code reviews'
        ],
        'requirements' => [
            'Formation Bac+3/5 en informatique',
            'Maîtrise de JavaScript/TypeScript',
            'Expérience avec React et Node.js',
            'Connaissance des bases de données (SQL/NoSQL)',
            'Bon niveau d\'anglais technique'
        ],
        'benefits' => [
            'Télétravail flexible (2j/semaine)',
            'Équipement fourni (MacBook Pro)',
            'Tickets restaurant',
            'Mutuelle premium',
            'Formation continue',
            'Événements d\'équipe réguliers'
        ]
    ];
    ?>

    <main class="main-content">
        <div class="container">
            <div class="offre-layout">
                <!-- Main Content -->
                <div class="offre-main">
                    <!-- Breadcrumb -->
                    <nav class="breadcrumb">
                        <a href="/offres">Offres</a>
                        <span>/</span>
                        <span><?php echo $offre['title']; ?></span>
                    </nav>

                    <!-- Header -->
                    <div class="offre-header glass-card">
                        <div class="company-logo-large"><?php echo $offre['company_logo']; ?></div>
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
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                        <circle cx="12" cy="10" r="3" />
                                    </svg>
                                    <?php echo $offre['location']; ?>
                                </span>
                                <span class="meta-item">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2" />
                                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                                    </svg>
                                    <?php echo $offre['type']; ?>
                                </span>
                                <span class="meta-item">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2" />
                                        <line x1="8" y1="21" x2="16" y2="21" />
                                        <line x1="12" y1="17" x2="12" y2="21" />
                                    </svg>
                                    <?php echo $offre['remote']; ?>
                                </span>
                                <span class="meta-item">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
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

                    <!-- Description -->
                    <section class="offre-section glass-card">
                        <h3>Description du poste</h3>
                        <p><?php echo $offre['description']; ?></p>
                    </section>

                    <!-- Missions -->
                    <section class="offre-section glass-card">
                        <h3>Vos missions</h3>
                        <ul class="offre-list">
                            <?php foreach ($offre['missions'] as $mission): ?>
                                <li><?php echo $mission; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </section>

                    <!-- Requirements -->
                    <section class="offre-section glass-card">
                        <h3>Profil recherché</h3>
                        <ul class="offre-list">
                            <?php foreach ($offre['requirements'] as $req): ?>
                                <li><?php echo $req; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </section>

                    <!-- Benefits -->
                    <section class="offre-section glass-card">
                        <h3>Avantages</h3>
                        <div class="benefits-grid">
                            <?php foreach ($offre['benefits'] as $benefit): ?>
                                <div class="benefit-item">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                    <span><?php echo $benefit; ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>

                    <!-- Similar Offers -->
                    <section class="similar-offers">
                        <h3>Offres similaires</h3>
                        <div class="similar-grid">
                            <?php for ($i = 0; $i < 3; $i++): ?>
                                <a href="/offre/<?php echo $i + 2; ?>" class="similar-card glass-card">
                                    <div class="similar-logo">🎨</div>
                                    <h4>Designer UI/UX</h4>
                                    <p>DesignLab • Lyon</p>
                                    <span class="similar-salary">1500-1800€</span>
                                </a>
                            <?php endfor; ?>
                        </div>
                    </section>
                </div>

                <!-- Sidebar -->
                <aside class="offre-sidebar">
                    <!-- Apply Card -->
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

                        <button class="btn-primary btn-full" id="applyBtn">
                            Postuler à cette offre
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M5 12h14M12 5l7 7-7 7" />
                            </svg>
                        </button>

                        <button class="btn-secondary btn-full">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z" />
                            </svg>
                            Sauvegarder
                        </button>

                        <div class="offre-stats-sidebar">
                            <div class="stat-sidebar">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <span><?php echo $offre['views']; ?> vues</span>
                            </div>
                            <div class="stat-sidebar">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                </svg>
                                <span><?php echo $offre['applications']; ?> candidatures</span>
                            </div>
                        </div>

                        <p class="posted-date">Publiée <?php echo $offre['posted']; ?></p>
                    </div>

                    <!-- Company Card -->
                    <div class="company-card glass-card">
                        <h4>À propos de l'entreprise</h4>
                        <div class="company-header-small">
                            <div class="company-logo-small"><?php echo $offre['company_logo']; ?></div>
                            <div>
                                <h5><?php echo $offre['company']; ?></h5>
                                <p>Tech • 50-200 employés</p>
                            </div>
                        </div>
                        <p class="company-description">
                            TechCorp est une scale-up innovante spécialisée dans le développement de solutions web et
                            mobile.
                        </p>
                        <a href="/entreprise/<?php echo $offre['company']; ?>" class="link">
                            Voir le profil complet →
                        </a>
                    </div>
                </aside>
            </div>
        </div>
    </main>

    <!-- Application Modal -->
    <div id="applicationModal" class="modal-overlay" style="display: none;">
        <div class="modal-content glass-card">
            <div class="modal-header">
                <h2>Postuler à l'offre</h2>
                <button class="btn-icon close-modal">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="/candidature/submit" class="application-form">
                <input type="hidden" name="offre_id" value="<?php echo $offre['id']; ?>">

                <div class="form-group">
                    <label class="form-label">CV à utiliser</label>
                    <select name="cv_id" class="form-input" required>
                        <option value="1">CV_Jean_Dupont.pdf</option>
                        <option value="2">CV_Jean_Dupont_EN.pdf</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Lettre de motivation</label>
                    <textarea name="cover_letter" class="form-textarea" rows="6"
                        placeholder="Expliquez pourquoi ce poste vous intéresse..." required></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Disponibilité</label>
                    <input type="date" name="availability" class="form-input" required>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-secondary close-modal">Annuler</button>
                    <button type="submit" class="btn-primary">
                        Envoyer ma candidature
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php require_once ROOT_PATH . 'app/helpers/Navbar.php'; ?>

    <script src="assets/js/navbar.js"></script>
    <script src="assets/js/offre-detail.js"></script>
</body>

</html>