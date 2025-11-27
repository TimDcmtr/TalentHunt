<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TechCorp - Profil Entreprise - TalentHub</title>
  <link rel="stylesheet" href="assets/css/variables.css">
  <link rel="stylesheet" href="assets/css/profil.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <?php require_once ROOT_PATH . 'app/helpers/Navbar.php'; ?>

  <?php 
  // Simulation données - À remplacer par requête DB
  $entreprise = [
    'id' => 1,
    'nom' => 'TechCorp',
    'logo' => '🚀',
    'taille' => '50-200 employés',
    'annee_creation' => 2018,
    'siege' => 'Paris, France',
    'website' => 'https://techcorp.com',
    'description_courte' => 'Scale-up innovante spécialisée dans le développement de solutions web et mobile.',
    'description' => 'TechCorp est une scale-up française en pleine croissance, spécialisée dans le développement de solutions web et mobile innovantes. Nous travaillons avec des clients du monde entier pour créer des expériences numériques exceptionnelles. Notre équipe passionnée est composée de développeurs, designers et product managers qui partagent la même vision : créer des produits qui font la différence.',
    'valeurs' => ['Innovation', 'Excellence', 'Collaboration', 'Transparence'],
    'secteur' => 'Technologies',
    'specialites' => ['web', 'mobile', 'cloud', 'data'],
    'email' => 'contact@techcorp.com',
    'telephone' => '+33 1 23 45 67 89',
    'linkedin' => 'https://linkedin.com/company/techcorp',
    'twitter' => 'https://twitter.com/techcorp',
    'offres_actives' => 12,
    'employes' => 150,
    'member_since' => 'Janvier 2024'
  ];

  $specialites_list = [
    'web' => 'Web Development',
    'mobile' => 'Mobile',
    'ai' => 'Intelligence Artificielle',
    'cloud' => 'Cloud',
    'security' => 'Cybersécurité',
    'data' => 'Data Science'
  ];
  ?>

  <main class="main-content">
    <div class="container">
      <div class="profil-layout company-layout">
        <!-- Sidebar -->
        <aside class="profil-sidebar">
          <!-- Logo Card -->
          <div class="company-logo-card glass-card">
            <div class="company-logo-xl"><?php echo $entreprise['logo']; ?></div>
            <h2 class="company-name"><?php echo $entreprise['nom']; ?></h2>
            <p class="company-tagline"><?php echo $entreprise['description_courte']; ?></p>

            <div class="company-stats">
              <div class="stat-item-small">
                <span class="stat-number"><?php echo $entreprise['offres_actives']; ?></span>
                <span class="stat-label">Offres actives</span>
              </div>
              <div class="stat-divider-small"></div>
              <div class="stat-item-small">
                <span class="stat-number"><?php echo $entreprise['employes']; ?></span>
                <span class="stat-label">Employés</span>
              </div>
            </div>

            <div class="profil-actions">
              <a href="#offres" class="btn-primary btn-full">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                  <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                </svg>
                Voir les offres
              </a>
              <button class="btn-secondary btn-full">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
                </svg>
                Suivre
              </button>
            </div>
          </div>

          <!-- Info Card -->
          <div class="quick-info-card glass-card">
            <h3>Informations</h3>
            
            <div class="info-item">
              <span class="info-icon">👥</span>
              <div>
                <p class="info-label">Taille</p>
                <p class="info-value"><?php echo $entreprise['taille']; ?></p>
              </div>
            </div>

            <div class="info-item">
              <span class="info-icon">📅</span>
              <div>
                <p class="info-label">Fondée en</p>
                <p class="info-value"><?php echo $entreprise['annee_creation']; ?></p>
              </div>
            </div>

            <div class="info-item">
              <span class="info-icon">📍</span>
              <div>
                <p class="info-label">Siège social</p>
                <p class="info-value"><?php echo $entreprise['siege']; ?></p>
              </div>
            </div>

            <div class="info-item">
              <span class="info-icon">🏢</span>
              <div>
                <p class="info-label">Secteur</p>
                <p class="info-value"><?php echo $entreprise['secteur']; ?></p>
              </div>
            </div>

            <div class="info-item">
              <span class="info-icon">🌐</span>
              <div>
                <p class="info-label">Site web</p>
                <p class="info-value">
                  <a href="<?php echo $entreprise['website']; ?>" target="_blank" class="link">
                    Visiter →
                  </a>
                </p>
              </div>
            </div>
          </div>

          <!-- Contact Card -->
          <div class="quick-info-card glass-card">
            <h3>Contact</h3>
            
            <div class="info-item">
              <span class="info-icon">📧</span>
              <div>
                <p class="info-label">Email</p>
                <p class="info-value"><?php echo $entreprise['email']; ?></p>
              </div>
            </div>

            <div class="info-item">
              <span class="info-icon">📱</span>
              <div>
                <p class="info-label">Téléphone</p>
                <p class="info-value"><?php echo $entreprise['telephone']; ?></p>
              </div>
            </div>

            <div class="social-links">
              <a href="<?php echo $entreprise['linkedin']; ?>" target="_blank" class="social-link" title="LinkedIn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                </svg>
              </a>
            </div>
          </div>
        </aside>

        <!-- Main Content -->
        <div class="profil-main">
          <!-- Description Section -->
          <section class="profil-section glass-card">
            <h3 class="section-title">À propos de <?php echo $entreprise['nom']; ?></h3>
            <p class="bio-text"><?php echo $entreprise['description']; ?></p>
          </section>

          <!-- Valeurs Section -->
          <section class="profil-section glass-card">
            <h3 class="section-title">Nos valeurs</h3>
            <div class="valeurs-grid">
              <?php foreach ($entreprise['valeurs'] as $valeur): ?>
                <div class="valeur-card">
                  <div class="valeur-icon">✨</div>
                  <h4><?php echo $valeur; ?></h4>
                </div>
              <?php endforeach; ?>
            </div>
          </section>

          <!-- Spécialités Section -->
          <section class="profil-section glass-card">
            <h3 class="section-title">Domaines d'expertise</h3>
            <div class="specialites-display">
              <?php foreach ($entreprise['specialites'] as $spec_key): ?>
                <?php if (isset($specialites_list[$spec_key])): ?>
                  <div class="specialite-badge">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <span><?php echo $specialites_list[$spec_key]; ?></span>
                  </div>
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
          </section>

          <!-- Offres Section -->
          <section id="offres" class="profil-section glass-card">
            <div class="section-header-inline">
              <h3 class="section-title">Offres actuelles</h3>
            </div>

            <div class="offres-preview">
              <?php
              $offres = [
                ['id' => 1, 'title' => 'Développeur Full Stack', 'type' => 'Stage', 'location' => 'Paris'],
                ['id' => 2, 'title' => 'Designer UI/UX', 'type' => 'Alternance', 'location' => 'Paris'],
                ['id' => 3, 'title' => 'Data Analyst', 'type' => 'CDI', 'location' => 'Remote']
              ];
              
              foreach ($offres as $offre): ?>
                <a href="/offre/<?php echo $offre['id']; ?>" class="offre-preview-card">
                  <h4><?php echo $offre['title']; ?></h4>
                  <div class="offre-preview-meta">
                    <span><?php echo $offre['type']; ?></span>
                    <span>•</span>
                    <span><?php echo $offre['location']; ?></span>
                  </div>
                </a>
              <?php endforeach; ?>
            </div>
          </section>
        </div>
      </div>
    </div>
  </main>

  <?php require_once ROOT_PATH . 'app/helpers/Footer.php'; ?>
  
  <script src="assets/js/navbar.js"></script>
  <script src="assets/js/profil.js"></script>
</body>
</html>