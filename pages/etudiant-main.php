<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rechercher des offres - TalentHub</title>
  <link rel="stylesheet" href="assets/css/variables.css">
  <link rel="stylesheet" href="assets/css/etudiant.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
</head>
<body>
  <?php require_once ROOT_PATH . 'app/helpers/Navbar.php'; ?>

  <main class="main-content">
    <div class="container">
      <div class="page-layout">
        <!-- Sidebar -->
        <aside class="sidebar glass-card">
          <div class="sidebar-header">
            <h3>Filtres TEST</h3>
            <button class="btn-reset">Réinitialiser</button>
          </div>

          <div class="filter-section">
            <label class="filter-label">Type de contrat</label>
            <div class="filter-options">
              <label class="filter-checkbox">
                <input type="checkbox" name="contract" value="stage">
                <span>Stage</span>
              </label>
              <label class="filter-checkbox">
                <input type="checkbox" name="contract" value="alternance">
                <span>Alternance</span>
              </label>
              <label class="filter-checkbox">
                <input type="checkbox" name="contract" value="cdd">
                <span>CDD</span>
              </label>
              <label class="filter-checkbox">
                <input type="checkbox" name="contract" value="cdi">
                <span>CDI</span>
              </label>
            </div>
          </div>

          <div class="filter-section">
            <label class="filter-label">Localisation</label>
            <input type="text" class="form-input" placeholder="Ville ou région">
          </div>

          <div class="filter-section">
            <label class="filter-label">Domaine</label>
            <select class="form-input">
              <option value="">Tous les domaines</option>
              <option value="dev">Développement</option>
              <option value="design">Design</option>
              <option value="marketing">Marketing</option>
              <option value="data">Data Science</option>
              <option value="business">Business</option>
            </select>
          </div>

          <div class="filter-section">
            <label class="filter-label">Télétravail</label>
            <div class="filter-options">
              <label class="filter-checkbox">
                <input type="checkbox" name="remote" value="full">
                <span>100% remote</span>
              </label>
              <label class="filter-checkbox">
                <input type="checkbox" name="remote" value="hybrid">
                <span>Hybride</span>
              </label>
              <label class="filter-checkbox">
                <input type="checkbox" name="remote" value="onsite">
                <span>Sur site</span>
              </label>
            </div>
          </div>

          <button class="btn-primary btn-full">Appliquer les filtres</button>
        </aside>

        <!-- Main Content -->
        <div class="content-area">
          <!-- Search Bar -->
          <div class="search-section glass-card">
            <div class="search-bar">
              <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/>
                <path d="m21 21-4.35-4.35"/>
              </svg>
              <input 
                type="text" 
                class="search-input" 
                placeholder="Rechercher un poste, une entreprise..."
              >
              <button class="btn-primary">Rechercher</button>
            </div>
          </div>

          <!-- Results Header -->
          <div class="results-header">
            <div class="results-info">
              <h2>Offres disponibles</h2>
              <span class="results-count">247 résultats</span>
            </div>
            <select class="sort-select">
              <option value="recent">Plus récentes</option>
              <option value="relevant">Plus pertinentes</option>
              <option value="salary">Salaire</option>
            </select>
          </div>

          <!-- Job Cards -->
          <div class="jobs-list">
            <?php
            // Exemple de données - À remplacer par la vraie DB
            $jobs = [
              [
                'id' => 1,
                'title' => 'Développeur Full Stack',
                'company' => 'TechCorp',
                'location' => 'Paris, France',
                'type' => 'Stage',
                'remote' => 'Hybride',
                'salary' => '1200-1500€',
                'logo' => '🚀',
                'tags' => ['React', 'Node.js', 'MongoDB']
              ],
              [
                'id' => 2,
                'title' => 'Designer UI/UX',
                'company' => 'DesignLab',
                'location' => 'Lyon, France',
                'type' => 'Alternance',
                'remote' => '100% remote',
                'salary' => '1500-1800€',
                'logo' => '🎨',
                'tags' => ['Figma', 'Adobe XD', 'Prototyping']
              ],
              [
                'id' => 3,
                'title' => 'Data Analyst',
                'company' => 'DataPro',
                'location' => 'Bordeaux, France',
                'type' => 'Stage',
                'remote' => 'Sur site',
                'salary' => '1000-1300€',
                'logo' => '📊',
                'tags' => ['Python', 'SQL', 'Tableau']
              ],
              [
                'id' => 4,
                'title' => 'Marketing Digital',
                'company' => 'MarketGenius',
                'location' => 'Toulouse, France',
                'type' => 'CDD',
                'remote' => 'Hybride',
                'salary' => '2000-2500€',
                'logo' => '📱',
                'tags' => ['SEO', 'Social Media', 'Analytics']
              ]
            ];

            foreach ($jobs as $job): ?>
              <div class="job-card glass-card">
                <div class="job-header">
                  <div class="job-logo"><?php echo $job['logo']; ?></div>
                  <div class="job-main-info">
                    <h3 class="job-title"><?php echo $job['title']; ?></h3>
                    <div class="job-company">
                      <?php echo $job['company']; ?>
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                      </svg>
                    </div>
                  </div>
                  <button class="btn-bookmark">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
                    </svg>
                  </button>
                </div>

                <div class="job-details">
                  <span class="job-detail">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                      <circle cx="12" cy="10" r="3"/>
                    </svg>
                    <?php echo $job['location']; ?>
                  </span>
                  <span class="job-detail">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                      <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                    </svg>
                    <?php echo $job['type']; ?>
                  </span>
                  <span class="job-detail">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                      <line x1="8" y1="21" x2="16" y2="21"/>
                      <line x1="12" y1="17" x2="12" y2="21"/>
                    </svg>
                    <?php echo $job['remote']; ?>
                  </span>
                </div>

                <div class="job-tags">
                  <?php foreach ($job['tags'] as $tag): ?>
                    <span class="tag"><?php echo $tag; ?></span>
                  <?php endforeach; ?>
                </div>

                <div class="job-footer">
                  <span class="job-salary"><?php echo $job['salary']; ?>/mois</span>
                  <a href="/offre/<?php echo $job['id']; ?>" class="btn-view">
                    Voir l'offre
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                  </a>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <!-- Pagination -->
          <div class="pagination">
            <button class="pagination-btn" disabled>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M15 18l-6-6 6-6"/>
              </svg>
            </button>
            <button class="pagination-btn active">1</button>
            <button class="pagination-btn">2</button>
            <button class="pagination-btn">3</button>
            <span class="pagination-dots">...</span>
            <button class="pagination-btn">10</button>
            <button class="pagination-btn">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 18l6-6-6-6"/>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php require_once ROOT_PATH . 'app/helpers/Footer.php'; ?>
  
  <script src="assets/js/navbar.js"></script>
  <script src="assets/js/etudiant.js"></script>
</body>
</html>