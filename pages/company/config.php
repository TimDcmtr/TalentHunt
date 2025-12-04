<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Configuration Entreprise - TalentHub</title>
  <link rel="stylesheet" href="/assets/css/variables.css">
  <link rel="stylesheet" href="/assets/css/config.css">
  <link rel="stylesheet" href="/assets/css/entreprise-config.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
</head>

<body>
  <?php 
  require_once ROOT_PATH . 'app/helpers/Navbar.php';
  require_once ROOT_PATH . 'app/helpers/CompanySession.php';
  require_once ROOT_PATH . 'app/controllers/CompaniesController.php';
  ?>

  <?php
  // Récupération des données
  // $currentCompany contient l'ID ou le tableau issu de la session (dépend de ton helper)
  // On s'assure d'avoir l'ID
  $id = is_array($currentCompany) ? $currentCompany['id'] : $currentCompany;
  
  $companyController = new CompanyController();
  // On récupère le JSON et on le décode en tableau associatif
  $companyJson = $companyController->getCompanyProfile($id);
  $company = json_decode($companyJson, true);
  
  requireCompanyLogin();
  ?>

  <main class="main-content">
    <div class="container">
      <div class="config-layout">
        <aside class="config-sidebar glass-card">
          <div class="profile-preview">
            <div class="avatar-large company-avatar">
                <?= strtoupper(substr($company['name'] ?? 'C', 0, 2)); ?>
            </div>
            <h3><?= htmlspecialchars($company['name'] ?? 'Mon Entreprise') ?></h3>
          </div>

          <nav class="config-nav">
            <a href="#infos" class="nav-item active">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="7" width="20" height="14" rx="2" ry="2" />
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
              </svg>
              Informations
            </a>
            <a href="#description" class="nav-item">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                <polyline points="14 2 14 8 20 8" />
              </svg>
              Description
            </a>
            <a href="#secteur" class="nav-item">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7" />
                <rect x="14" y="3" width="7" height="7" />
                <rect x="14" y="14" width="7" height="7" />
                <rect x="3" y="14" width="7" height="7" />
              </svg>
              Secteur
            </a>
            <a href="#contact" class="nav-item">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
              </svg>
              Contact
            </a>
          </nav>
        </aside>

        <div class="config-content">
          
          <section id="infos" class="config-section glass-card active">
            <div class="section-header">
              <h2>Informations de l'entreprise</h2>
              <p>Les informations de base visibles par les étudiants</p>
            </div>

            <form class="config-form" method="POST" action="/api?action=updateCompany">
              <input type="hidden" name="id" value="<?= $company['id'] ?>">
              <input type="hidden" name="section" value="infos">

              <div class="form-group">
                <label for="company_name" class="form-label">Nom de l'entreprise *</label>
                <input type="text" id="company_name" name="name" class="form-input"
                  value="<?= htmlspecialchars($company['name'] ?? '') ?>" required>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="company_size" class="form-label">Taille de l'entreprise *</label>
                  <select id="company_size" name="size_range" class="form-input" required>
                    <option value="">Sélectionnez</option>
                    <?php 
                      $sizes = ["1-10", "11-50", "50-200", "201-500", "500+"];
                      $currentSize = $company['size_range'] ?? '';
                      foreach($sizes as $size): 
                    ?>
                        <option value="<?= $size ?>" <?= ($currentSize == $size) ? 'selected' : '' ?>>
                            <?= $size ?> employés
                        </option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="founded_year" class="form-label">Année de création</label>
                  <input type="number" id="founded_year" name="founded_year" class="form-input" placeholder="2020"
                    min="1800" max="2025" value="<?= htmlspecialchars($company['founded_year'] ?? '') ?>">
                </div>
              </div>

              <div class="form-group">
                <label for="headquarters" class="form-label">Siège social *</label>
                <input type="text" id="headquarters" name="headquarters" class="form-input" placeholder="Paris, France" 
                    value="<?= htmlspecialchars($company['headquarters'] ?? '') ?>" required>
              </div>

              <div class="form-group">
                <label for="website" class="form-label">Site web</label>
                <input type="url" id="website" name="website" class="form-input" placeholder="https://..." 
                    value="<?= htmlspecialchars($company['website'] ?? '') ?>">
              </div>

              <div class="form-actions">
                <button type="submit" class="btn-primary">Enregistrer</button>
              </div>
            </form>
          </section>

          <section id="description" class="config-section glass-card">
            <div class="section-header">
              <h2>Description et culture</h2>
              <p>Présentez votre entreprise aux futurs candidats</p>
            </div>

            <form class="config-form" method="POST" action="/api/updateCompany">
              <input type="hidden" name="id" value="<?= $company['id'] ?>">
              <input type="hidden" name="section" value="description">

              <div class="form-group">
                <label for="short_description" class="form-label">Description courte (visible en aperçu)</label>
                <textarea id="short_description" name="short_description" class="form-textarea" rows="3"
                  placeholder="Une phrase accrocheuse sur votre entreprise..." maxlength="200"><?= htmlspecialchars($company['short_description'] ?? '') ?></textarea>
                <small class="char-count">0/200</small>
              </div>

              <div class="form-group">
                <label for="long_description" class="form-label">Description complète</label>
                <textarea id="long_description" name="description" class="form-textarea" rows="8"
                  placeholder="Présentez votre entreprise, votre mission, vos valeurs..."><?= htmlspecialchars($company['description'] ?? '') ?></textarea>
              </div>

              <div class="form-group">
                <label class="form-label">Valeurs de l'entreprise</label>
                <div id="values-container" class="values-grid">
                  
                  <?php 
                    // On récupère les valeurs, qui sont un array (json_decode true dans le controller/model)
                    $values = $company['core_values'] ?? [];
                    if (!is_array($values)) $values = []; // Sécurité
                    
                    // S'il n'y a pas de valeurs, on affiche un champ vide par défaut
                    if(empty($values)) { $values = ['']; }

                    foreach($values as $val): 
                  ?>
                      <div class="value-card">
                        <input type="text" name="core_values[]" class="value-input" placeholder="Ex: Innovation"
                          value="<?= htmlspecialchars($val) ?>">
                        <button type="button" class="btn-remove-value">×</button>
                      </div>
                  <?php endforeach; ?>

                </div>
                <button type="button" class="btn-add btn-secondary" id="addValue">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                  </svg>
                  Ajouter une valeur
                </button>
              </div>

              <div class="form-actions">
                <button type="submit" class="btn-primary">Enregistrer</button>
              </div>
            </form>
          </section>

          <section id="secteur" class="config-section glass-card">
            <div class="section-header">
              <h2>Secteur d'activité</h2>
              <p>Aidez les étudiants à vous trouver</p>
            </div>

            <form class="config-form" method="POST" action="/api/updateCompany">
              <input type="hidden" name="id" value="<?= $company['id'] ?>">
              <input type="hidden" name="section" value="sector">

              <div class="form-group">
                <label for="industry" class="form-label">Secteur principal *</label>
                <select id="industry" name="sector" class="form-input" required>
                  <option value="">Sélectionnez</option>
                  <?php 
                    $sectors = ["Technologies", "Finance", "Santé", "Commerce", "Éducation", "Conseil", "Industrie", "Services"];
                    $currentSector = $company['sector'] ?? '';
                    foreach($sectors as $sec):
                  ?>
                    <option value="<?= $sec ?>" <?= ($currentSector == $sec) ? 'selected' : '' ?>>
                        <?= $sec ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label">Spécialités</label>
                <div class="specialties-grid">
                  <?php 
                    // Liste de toutes les spécialités possibles (Hardcodée pour l'affichage, ou venant de la BDD idéalement)
                    $allSpecialties = [
                        'web' => 'Web Development',
                        'mobile' => 'Mobile',
                        'ai' => 'Intelligence Artificielle',
                        'cloud' => 'Cloud',
                        'security' => 'Cybersécurité',
                        'data' => 'Data Science',
                        'marketing' => 'Marketing Digital'
                    ];

                    // Les spécialités de l'entreprise
                    $mySpecs = $company['specialties'] ?? [];
                    if(!is_array($mySpecs)) $mySpecs = [];

                    foreach($allSpecialties as $key => $label):
                        $isChecked = in_array($key, $mySpecs) ? 'checked' : '';
                  ?>
                      <label class="specialty-card">
                        <input type="checkbox" name="specialties[]" value="<?= $key ?>" <?= $isChecked ?>>
                        <span><?= $label ?></span>
                      </label>
                  <?php endforeach; ?>
                </div>
              </div>

              <div class="form-actions">
                <button type="submit" class="btn-primary">Enregistrer</button>
              </div>
            </form>
          </section>

          <section id="contact" class="config-section glass-card">
            <div class="section-header">
              <h2>Informations de contact</h2>
              <p>Comment les candidats peuvent vous joindre</p>
            </div>

            <form class="config-form" method="POST" action="/api/updateCompany">
              <input type="hidden" name="id" value="<?= $company['id'] ?>">
              <input type="hidden" name="section" value="contact">

              <div class="form-group">
                <label for="contact_email" class="form-label">Email de contact *</label>
                <input type="email" id="contact_email" name="email" class="form-input"
                  value="<?= htmlspecialchars($company['email'] ?? '') ?>" required>
              </div>

              <div class="form-group">
                <label for="phone" class="form-label">Téléphone</label>
                <input type="tel" id="phone" name="phone" class="form-input" 
                    value="<?= htmlspecialchars($company['phone'] ?? '') ?>">
              </div>

              <div class="form-group">
                <label class="form-label">Réseaux sociaux</label>
                <div class="social-inputs">
                  <div class="social-input-group">
                    <span class="social-icon">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" /></svg>
                    </span>
                    <input type="url" name="linkedin" class="form-input" placeholder="LinkedIn URL"
                        value="<?= htmlspecialchars($company['linkedin'] ?? '') ?>">
                  </div>
                </div>
              </div>

              <div class="form-actions">
                <button type="submit" class="btn-primary">Enregistrer</button>
              </div>
            </form>
          </section>

        </div>
      </div>
    </div>
  </main>

  <?php require_once ROOT_PATH . 'app/helpers/Footer.php'; ?>

  <script src="/assets/js/navbar.js"></script>
  <script src="/assets/js/config.js"></script>
  <script src="/assets/js/entreprise-config.js"></script>
</body>
</html>