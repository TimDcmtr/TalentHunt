<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jean Dupont - Profil Étudiant - TalentHub</title>
  <link rel="stylesheet" href="assets/css/variables.css">
  <link rel="stylesheet" href="assets/css/profil.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap"
    rel="stylesheet">
</head>

<body>
  <?php require_once ROOT_PATH . 'app/helpers/Navbar.php';
  require_once ROOT_PATH . 'app/controllers/UserController.php';
  ?>

  <?php
  $id = $_GET['id'];
  $userController = new UserController();
  $etudiant = $userController->getUserProfile($id);


  $categories_list = [
    'dev' => ['icon' => '💻', 'label' => 'Développement'],
    'design' => ['icon' => '🎨', 'label' => 'Design'],
    'marketing' => ['icon' => '📱', 'label' => 'Marketing'],
    'data' => ['icon' => '📊', 'label' => 'Data Science'],
    'business' => ['icon' => '💼', 'label' => 'Business'],
    'finance' => ['icon' => '💰', 'label' => 'Finance']
  ];

  $types_contrat = [
    'stage' => 'Stage',
    'alternance' => 'Alternance',
    'cdd' => 'CDD',
    'cdi' => 'CDI'
  ];

  $modes_travail = [
    'onsite' => ['icon' => '🏢', 'label' => 'Sur site'],
    'hybrid' => ['icon' => '🔄', 'label' => 'Hybride'],
    'remote' => ['icon' => '🌍', 'label' => '100% remote']
  ];
  ?>

  <?php if (!$id): ?>
    <h1> Erreur de Connexion </h1>
  <?php else: ?>
  <main class="main-content">
    <div class="container">
      <div class="profil-layout">
        <!-- Sidebar -->
        <aside class="profil-sidebar">
          <!-- Avatar Card -->
          <div class="avatar-card glass-card">
            <div class="avatar-header">
              <div class="avatar-xl"><?php echo $etudiant['avatar_initials']; ?></div>
            </div>

            <h2 class="profil-name"><?php echo $etudiant['firstname'] . ' ' . $etudiant['lastname']; ?></h2>
            <p class="profil-title"><?php echo $etudiant['domaine']; ?></p>
            <p class="profil-location">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                <circle cx="12" cy="10" r="3" />
              </svg>
              <?php echo $etudiant['location']; ?>
            </p>

            <div class="profil-stats">
              <div class="stat-item-small">
                <span class="stat-number"><?php echo $etudiant['candidatures']; ?></span>
                <span class="stat-label">Candidatures</span>
              </div>
            </div>

            <div class="profil-actions">

              <button class="btn-secondary btn-full">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z" />
                </svg>
                Partager
              </button>

            </div>

            <div class="profil-meta">
              <p>Membre depuis <?php echo $etudiant['member_since']; ?></p>
            </div>
          </div>

          <!-- Quick Info Card -->
          <div class="quick-info-card glass-card">
            <h3>Informations rapides</h3>

            <div class="info-item">
              <span class="info-icon">🎓</span>
              <div>
                <p class="info-label">École</p>
                <p class="info-value"><?php echo $etudiant['school']; ?></p>
              </div>
            </div>

            <div class="info-item">
              <span class="info-icon">📧</span>
              <div>
                <p class="info-label">Email</p>
                <p class="info-value"><?php echo $etudiant['email']; ?></p>
              </div>
            </div>

            <div class="info-item">
              <span class="info-icon">📱</span>
              <div>
                <p class="info-label">Téléphone</p>
                <p class="info-value"><?php echo $etudiant['phone']; ?></p>
              </div>
            </div>

            <div class="info-item">
              <span class="info-icon">💰</span>
              <div>
                <p class="info-label">Salaire souhaité</p>
                <p class="info-value">À partir de <?php echo $etudiant['min_salary']; ?>€/mois</p>
              </div>
            </div>
          </div>
        </aside>

        <!-- Main Content -->
        <div class="profil-main">
          <!-- About Section -->
          <section class="profil-section glass-card">
            <h3 class="section-title">À propos</h3>
            <p class="bio-text"><?php echo $etudiant['bio']; ?></p>
          </section>

          <!-- Categories Section -->
          <section class="profil-section glass-card">
            <h3 class="section-title">Domaines d'intérêt</h3>
            <div class="categories-display">
              <?php foreach ($etudiant['categories'] as $cat_key): ?>
                <?php if (isset($categories_list[$cat_key])): ?>
                  <div class="category-badge">
                    <span class="category-icon"><?php echo $categories_list[$cat_key]['icon']; ?></span>
                    <span class="category-label"><?php echo $categories_list[$cat_key]['label']; ?></span>
                  </div>
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
          </section>

          <!-- Competences Section -->
          <section class="profil-section glass-card">
            <h3 class="section-title">Compétences techniques</h3>
            <div class="competences-list">
              <?php foreach ($etudiant['skills'] as $comp): ?>
                <span class="competence-tag"><?php echo $comp; ?></span>
              <?php endforeach; ?>
            </div>
          </section>

          <!-- Recherche Section -->
          <section class="profil-section glass-card">
            <h3 class="section-title">Recherche actuelle</h3>

            <div class="recherche-info">
              <div class="recherche-item">
                <h4>Type de contrat</h4>
                <div class="recherche-value">
                  <div class="type-badge type-<?php echo $etudiant['search_type']; ?>">
                    <?php echo $types_contrat[$etudiant['search_type']]; ?>
                  </div>
                </div>
              </div>

              <div class="recherche-item">
                <h4>Mode de travail</h4>
                <div class="recherche-value">
                  <?php foreach ($etudiant['work_mode'] as $mode): ?>
                    <div class="mode-badge">
                      <span><?php echo $modes_travail[$mode]['icon']; ?></span>
                      <span><?php echo $modes_travail[$mode]['label']; ?></span>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </section>

          <!-- CV Section -->
          <?php if ($etudiant['cv_uploaded']): ?>
            <section class="profil-section glass-card">
              <h3 class="section-title">Curriculum Vitae</h3>

              <div class="cv-display">
                <div class="cv-file-display">
                  <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                    <polyline points="14 2 14 8 20 8" />
                  </svg>
                  <div class="cv-info">
                    <h4><?php echo $etudiant['cv_filename']; ?></h4>
                    <p>PDF • Dernière mise à jour il y a 2 jours</p>
                  </div>
                </div>
                <a href="/download-cv/<?php echo $etudiant['id']; ?>" class="btn-primary">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                    <polyline points="7 10 12 15 17 10" />
                    <line x1="12" y1="15" x2="12" y2="3" />
                  </svg>
                  Télécharger
                </a>
              </div>
            </section>
          <?php endif; ?>

          <!-- Disponibilité Section -->
          <section class="profil-section glass-card">
            <h3 class="section-title">Disponibilité</h3>
            <div class="disponibilite-card">
              <div class="disponibilite-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <circle cx="12" cy="12" r="10" />
                  <polyline points="12 6 12 12 16 14" />
                </svg>
              </div>
              <div class="disponibilite-info">
                <h4>Disponible immédiatement</h4>
                <p>Prêt à commencer dès que possible</p>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </main>
  <?php endif; ?>

  <?php require_once ROOT_PATH . 'app/helpers/Footer.php'; ?>

  <script src="assets/js/navbar.js"></script>
  <script src="assets/js/profil.js"></script>
</body>

</html>