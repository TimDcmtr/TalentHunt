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
  <?php require_once ROOT_PATH . 'app/helpers/Navbar.php'; 
  require_once ROOT_PATH . 'app/controllers/JobOfferController.php';?>

  <?php
  // Récupérer l'URL actuelle sans paramètres
  $currentUrl = strtok($_SERVER["REQUEST_URI"], '?');
  
  $filters = [
    'type' => $_GET['contract'] ?? [],
    'location' => $_GET['location'] ?? '',
    'remote' => $_GET['remote'] ?? [],
    'search' => $_GET['search'] ?? ''
  ];
  $jobController = new JobOfferController();
  $allJobs = json_decode($jobController->findAllJobOffers($filters), true);
  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
  $limit = 2;
  $totalJobs = is_array($allJobs) ? count($allJobs) : 0;
  $totalPages = ceil($totalJobs / $limit);
  $offset = ($page - 1) * $limit;
  $jobs = array_slice($allJobs, $offset, $limit);
  ?>

  <main class="main-content">
    <div class="container">
      <div class="page-layout">
        <!-- Sidebar -->
        <aside class="sidebar glass-card">
          <form method="GET" action="<?php echo $currentUrl; ?>">
            <div class="sidebar-header">
              <h3>Filtres</h3>
              <a href="<?php echo $currentUrl; ?>" class="btn-reset">Réinitialiser</a>
            </div>

            <div class="filter-section">
              <label class="filter-label">Type de contrat</label>
              <div class="filter-options">
                <label class="filter-checkbox">
                  <input type="checkbox" name="contract[]" value="Stage"
                  <?php echo in_array('Stage', (array)$filters['type']) ? 'checked' : ''; ?>>
                  <span>Stage</span>
                </label>
                <label class="filter-checkbox">
                  <input type="checkbox" name="contract[]" value="Alternance"
                  <?php echo in_array('Alternance', (array)$filters['type']) ? 'checked' : ''; ?>>
                  <span>Alternance</span>
                </label>
                <label class="filter-checkbox">
                  <input type="checkbox" name="contract[]" value="CDD"
                  <?php echo in_array('CDD', (array)$filters['type']) ? 'checked' : ''; ?>>
                  <span>CDD</span>
                </label>
                <label class="filter-checkbox">
                  <input type="checkbox" name="contract[]" value="CDI"
                  <?php echo in_array('CDI', (array)$filters['type']) ? 'checked' : ''; ?>>
                  <span>CDI</span>
                </label>
              </div>
            </div>

            <div class="filter-section">
              <label class="filter-label">Localisation</label>
              <input type="text" name="location" class="form-input" placeholder="Ville ou région" 
              value="<?php echo htmlspecialchars($filters['location']); ?>">
            </div>

            <div class="filter-section">
              <label class="filter-label">Domaine</label>
              <select class="form-input" name="domaine">
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
                  <input type="checkbox" name="remote[]" value="100% remote"
                  <?php echo in_array('100% remote', (array)$filters['remote']) ? 'checked' : ''; ?>>
                  <span>100% remote</span>
                </label>
                <label class="filter-checkbox">
                  <input type="checkbox" name="remote[]" value="Hybride"
                  <?php echo in_array('Hybride', (array)$filters['remote']) ? 'checked' : ''; ?>>
                  <span>Hybride</span>
                </label>
                <label class="filter-checkbox">
                  <input type="checkbox" name="remote[]" value="Sur site"
                  <?php echo in_array('Sur site', (array)$filters['remote']) ? 'checked' : ''; ?>>
                  <span>Sur site</span>
                </label>
              </div>
            </div>

            <button type="submit" class="btn-primary btn-full">Appliquer les filtres</button>
          </form>
        </aside>

        <!-- Main Content -->
        <div class="content-area">
          <!-- Search Bar -->
          <div class="search-section glass-card">
            <form method="GET" action="<?php echo $currentUrl; ?>" class="search-bar">
              <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/>
                <path d="m21 21-4.35-4.35"/>
              </svg>
              <input 
                type="text" 
                name="search"
                class="search-input" 
                placeholder="Rechercher un poste ..."
                value="<?php echo htmlspecialchars($filters['search']); ?>"
              >
              <button type="submit" class="btn-primary">Rechercher</button>

              <?php 
              if (!empty($filters['type'])):
                foreach ((array)$filters['type'] as $type): ?>
                  <input type="hidden" name="contract[]" value="<?php echo htmlspecialchars($type); ?>">
                <?php endforeach;
              endif;
              
              if (!empty($filters['location'])): ?>
                <input type="hidden" name="location" value="<?php echo htmlspecialchars($filters['location']); ?>">
              <?php endif;
              
              if (!empty($filters['remote'])):
                foreach ((array)$filters['remote'] as $remote): ?>
                  <input type="hidden" name="remote[]" value="<?php echo htmlspecialchars($remote); ?>">
                <?php endforeach;
              endif;
              ?>
              <input type="hidden" name="page" value="1">
            </form>
          </div>

          <!-- Results Header -->
          <div class="results-header">
            <div class="results-info">
              <h2>Offres disponibles</h2>
              <span class="results-count"><?php echo $totalJobs; ?> résultats</span>
            </div>
          </div>

          <!-- Job Cards -->
          <div class="jobs-list">
            <?php
            if (is_array($jobs) && count($jobs) > 0):
              foreach ($jobs as $job): ?>
                <div class="job-card glass-card">
                  <div class="job-header">
                    <div class="job-logo"><?php echo strtoupper(substr($job['company'], 0, 2)); ?></div>
                    <div class="job-main-info">
                      <h3 class="job-title"><?php echo htmlspecialchars($job['title']); ?></h3>
                      <div class="job-company">
                        <?php echo htmlspecialchars($job['company']); ?>
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
                        ircle cx="12" cy="10" r="3"/>
                      </svg>
                      <?php echo htmlspecialchars($job['location']); ?>
                    </span>
                    <span class="job-detail">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                      </svg>
                      <?php echo htmlspecialchars($job['type']); ?>
                    </span>
                    <span class="job-detail">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                        <line x1="8" y1="21" x2="16" y2="21"/>
                        <line x1="12" y1="17" x2="12" y2="21"/>
                      </svg>
                      <?php echo htmlspecialchars($job['remote']); ?>
                    </span>
                  </div>

                  <div class="job-tags">
                    <?php 
                    if (is_array($job['tags'])):
                      foreach ($job['tags'] as $tag): ?>
                        <span class="tag"><?php echo htmlspecialchars($tag); ?></span>
                      <?php endforeach;
                    endif; ?>
                  </div>

                  <div class="job-footer">
                    <span class="job-salary"><?php echo htmlspecialchars($job['salary']); ?>/mois</span>
                    <a href="/offer?id=<?php echo $job['id']; ?>" class="btn-view">
                      Voir l'offre
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                      </svg>
                    </a>
                  </div>
                </div>
              <?php endforeach;
            else: ?>
              <p style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                Aucune offre ne correspond à vos critères de recherche.
              </p>
            <?php endif; ?>
          </div>

          <!-- Pagination -->
          <?php if ($totalPages > 1): ?>
          <div class="pagination">
            <?php
            $prevPage = max(1, $page - 1);
            $nextPage = min($totalPages, $page + 1);
            
            // Construire l'URL de base avec les filtres
            $baseParams = $_GET;
            unset($baseParams['page']);
            ?>
            
            <a href="<?php echo $currentUrl . '?' . http_build_query(array_merge($baseParams, ['page' => $prevPage])); ?>" 
              class="pagination-btn" <?php echo $page <= 1 ? 'style="pointer-events:none;opacity:0.5"' : ''; ?>>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M15 18l-6-6 6-6"/>
              </svg>
            </a>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
              <a href="<?php echo $currentUrl . '?' . http_build_query(array_merge($baseParams, ['page' => $i])); ?>" 
                class="pagination-btn <?php echo $i == $page ? 'active' : ''; ?>">
                <?php echo $i; ?>
              </a>
            <?php endfor; ?>

            <a href="<?php echo $currentUrl . '?' . http_build_query(array_merge($baseParams, ['page' => $nextPage])); ?>" 
              class="pagination-btn" <?php echo $page >= $totalPages ? 'style="pointer-events:none;opacity:0.5"' : ''; ?>>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 18l6-6-6-6"/>
              </svg>
            </a>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </main>

  <?php require_once ROOT_PATH . 'app/helpers/Footer.php'; ?>
  
  <script src="assets/js/navbar.js"></script>
  <script src="assets/js/etudiant.js"></script>
</body>
</html>
