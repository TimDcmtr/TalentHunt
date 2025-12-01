<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TalentHub - Connecter talents et opportunités</title>
  <link rel="stylesheet" href="assets/css/variables.css">
  <link rel="stylesheet" href="assets/css/accueil.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
</head>
<body>
  <?php
  require_once ROOT_PATH . 'app/helpers/Navbar.php';
  require_once ROOT_PATH . 'app/helpers/UserSession.php';

  requireLogin();
  
  ?>

  <main class="main-content">
    <!-- Hero Section -->
    <section class="hero">
      <div class="hero-background">
        <div class="gradient-orb orb-1"></div>
        <div class="gradient-orb orb-2"></div>
        <div class="gradient-orb orb-3"></div>
      </div>
      
      <div class="container">
        <div class="hero-content fade-in">
          <h1 class="hero-title">
            <?php echo $currentUser['firstname'] ?>, Trouvez votre
            <span class="gradient-text">opportunité parfaite</span>
          </h1>
          <p class="hero-subtitle">
            TalentHub connecte les meilleurs étudiants avec les entreprises innovantes.
            Votre carrière commence ici.
          </p>
          
          <div class="hero-cta">
            <a href="/login?tab=register" class="btn-primary btn-large">
              Commencer gratuitement
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M5 12h14M12 5l7 7-7 7"/>
              </svg>
            </a>
          </div>

          <div class="hero-stats">
            <div class="stat-item">
              <span class="stat-number">10K+</span>
              <span class="stat-label">Étudiants</span>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
              <span class="stat-number">500+</span>
              <span class="stat-label">Entreprises</span>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
              <span class="stat-number">2K+</span>
              <span class="stat-label">Offres actives</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="features">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">Pourquoi choisir TalentHub ?</h2>
          <p class="section-subtitle">Une plateforme conçue pour faciliter votre recherche</p>
        </div>

        <div class="features-grid">
          <div class="feature-card glass-card">
            <div class="feature-icon">
              <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                <line x1="12" y1="22.08" x2="12" y2="12"/>
              </svg>
            </div>
            <h3 class="feature-title">Offres ciblées</h3>
            <p class="feature-description">
              Des milliers d'opportunités correspondant parfaitement à votre profil et vos ambitions.
            </p>
          </div>

          <div class="feature-card glass-card">
            <div class="feature-icon">
              <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
              </svg>
            </div>
            <h3 class="feature-title">Réseau professionnel</h3>
            <p class="feature-description">
              Connectez-vous directement avec des recruteurs et professionnels de votre secteur.
            </p>
          </div>

          <div class="feature-card glass-card">
            <div class="feature-icon">
              <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
              </svg>
            </div>
            <h3 class="feature-title">Candidature rapide</h3>
            <p class="feature-description">
              Postulez en quelques clics grâce à votre profil optimisé et votre CV intégré.
            </p>
          </div>

          <div class="feature-card glass-card">
            <div class="feature-icon">
              <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 6v6l4 2"/>
              </svg>
            </div>
            <h3 class="feature-title">Suivi en temps réel</h3>
            <p class="feature-description">
              Suivez l'évolution de vos candidatures et recevez des notifications instantanées.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
      <div class="container">
        <div class="cta-card glass-card">
          <div class="cta-content">
            <h2 class="cta-title">Prêt à recruter de nouveaux talents ?</h2>
            <p class="cta-description">
              Rejoignez des milliers d’entreprises qui ont trouvé leurs meilleurs talents grâce à TalentHub.
            </p>
            <div class="cta-buttons">
              <a href="/login?tab=registerEN" class="btn-primary btn-large">Créer mon compte entreprise</a>
            </div>
          </div>
          <div class="cta-visual">
            <div class="floating-card">
              <div class="mini-card">
                <div class="mini-icon"></div>
                <div class="mini-content">
                  <div class="mini-line"></div>
                  <div class="mini-line short"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php require_once ROOT_PATH . 'app/helpers/Footer.php'; ?>
  
  <script src="public/assets/js/navbar.js"></script>
</body>
</html>