<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Configuration du profil - TalentHub</title>
  <link rel="stylesheet" href="/assets/css/variables.css">
  <link rel="stylesheet" href="/assets/css/config.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap"
    rel="stylesheet">
</head>

<body>
  <?php
  require_once ROOT_PATH . 'app/helpers/Navbar.php';
  // On suppose que UserSession.php existe (similaire à CompanySession)
  // require_once ROOT_PATH . 'app/helpers/UserSession.php'; 
  require_once ROOT_PATH . 'app/controllers/UserController.php';

  // Simulation Session pour l'exemple (si tu as UserSession.php, utilise-le)
  // $id = $currentUser['id'];
  
  // Pour l'exemple, on récupère via un Token ou une Session existante
  // Ici, je récupère les données fraîches depuis la BDD
  if (isset($_COOKIE['authToken'])) {
    $uCtrl = new UserController();
    $userRaw = $uCtrl->getUserFromToken($_COOKIE['authToken']); // Renvoie un array
    $user = $userRaw; // Déjà un tableau associatif dans ton UserController
  } else {
    header('Location: /login');
    exit;
  }
  ?>

  <main class="main-content">
    <div class="container">
      <div class="config-layout">
        <aside class="config-sidebar glass-card">
          <div class="profile-preview">
            <div class="avatar-large">
              <span><?= strtoupper(substr($user['firstname'] ?? 'U', 0, 1) . substr($user['lastname'] ?? '', 0, 1)) ?></span>
            </div>
            <h3><?= htmlspecialchars(($user['firstname'] ?? '') . ' ' . ($user['lastname'] ?? '')) ?></h3>
          </div>

          <nav class="config-nav">
            <a href="#infos" class="nav-item active">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                <circle cx="12" cy="7" r="4" />
              </svg>
              Informations
            </a>
            <a href="#categories" class="nav-item">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7" />
                <rect x="14" y="3" width="7" height="7" />
                <rect x="14" y="14" width="7" height="7" />
                <rect x="3" y="14" width="7" height="7" />
              </svg>
              Catégories
            </a>
            <a href="#type-recherche" class="nav-item">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.35-4.35" />
              </svg>
              Type de recherche
            </a>
            <a href="#cv" class="nav-item">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                <polyline points="14 2 14 8 20 8" />
              </svg>
              CV
            </a>
            <a href="#recherche-travail" class="nav-item">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="7" width="20" height="14" rx="2" ry="2" />
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
              </svg>
              Préférences
            </a>
          </nav>
        </aside>

        <div class="config-content">

          <section id="infos" class="config-section glass-card active">
            <div class="section-header">
              <h2>Informations personnelles</h2>
              <p>Complétez vos informations pour améliorer votre visibilité</p>
            </div>

            <form class="config-form" method="POST">
              <input type="hidden" name="section" value="infos">

              <div class="form-row">
                <div class="form-group">
                  <label for="firstname" class="form-label">Prénom *</label>
                  <input type="text" id="firstname" name="firstname" class="form-input"
                    value="<?= htmlspecialchars($user['firstname'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                  <label for="lastname" class="form-label">Nom *</label>
                  <input type="text" id="lastname" name="lastname" class="form-input"
                    value="<?= htmlspecialchars($user['lastname'] ?? '') ?>" required>
                </div>
              </div>

              <div class="form-group">
                <label for="email" class="form-label">Email *</label>
                <input type="email" id="email" name="email" class="form-input"
                  value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
              </div>

              <div class="form-group">
                <label for="phone" class="form-label">Téléphone</label>
                <input type="tel" id="phone" name="phone" class="form-input"
                  value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="+33...">
              </div>

              <div class="form-group">
                <label for="school" class="form-label">École / Université *</label>
                <input type="text" id="school" name="school" class="form-input"
                  value="<?= htmlspecialchars($user['school'] ?? '') ?>" required>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="region" class="form-label">Région *</label>
                  <select id="region" name="region" class="form-input" required>
                    <option value="">Sélectionnez</option>
                    <?php
                    $regions = [
                      'idf' => 'Île-de-France',
                      'aura' => 'Auvergne-Rhône-Alpes',
                      'paca' => 'Provence-Alpes-Côte d\'Azur',
                      'occitanie' => 'Occitanie'
                    ];
                    $curReg = $user['location'] ?? '';
                    foreach ($regions as $k => $v):
                      ?>
                      <option value="<?= $k ?>" <?= ($curReg == $k || $curReg == $v) ? 'selected' : '' ?>><?= $v ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="domain" class="form-label">Domaine d'études *</label>
                  <input type="text" id="domain" name="domain" class="form-input"
                    value="<?= htmlspecialchars($user['field_of_study'] ?? '') ?>" required>
                </div>
              </div>

              <div class="form-group">
                <label for="bio" class="form-label">Bio / Présentation</label>
                <textarea id="bio" name="bio" class="form-textarea" rows="4"
                  placeholder="Parlez-nous de vous..."><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
              </div>

              <div class="form-actions">
                <button type="submit" class="btn-primary">Enregistrer les modifications</button>
              </div>
            </form>
          </section>

          <section id="categories" class="config-section glass-card">
            <div class="section-header">
              <h2>Catégories d'intérêt</h2>
              <p>Sélectionnez les domaines qui vous intéressent</p>
            </div>

            <form class="config-form" method="POST">
              <input type="hidden" name="section" value="categories">

              <div class="categories-grid">
                <?php
                $userCats = $user['categories'] ?? [];
                if (!is_array($userCats))
                  $userCats = [];

                $catsList = [
                  'dev' => ['icon' => '💻', 'name' => 'Développement'],
                  'design' => ['icon' => '🎨', 'name' => 'Design'],
                  'marketing' => ['icon' => '📱', 'name' => 'Marketing'],
                  'data' => ['icon' => '📊', 'name' => 'Data Science'],
                  'business' => ['icon' => '💼', 'name' => 'Business'],
                  'finance' => ['icon' => '💰', 'name' => 'Finance']
                ];

                foreach ($catsList as $key => $info):
                  $checked = in_array($key, $userCats) ? 'checked' : '';
                  ?>
                  <label class="category-card">
                    <input type="checkbox" name="category[]" value="<?= $key ?>" <?= $checked ?>>
                    <div class="category-content">
                      <div class="category-icon"><?= $info['icon'] ?></div>
                      <span class="category-name"><?= $info['name'] ?></span>
                    </div>
                  </label>
                <?php endforeach; ?>
              </div>

              <div class="form-actions">
                <button type="submit" class="btn-primary">Enregistrer les catégories</button>
              </div>
            </form>
          </section>

          <section id="type-recherche" class="config-section glass-card">
            <div class="section-header">
              <h2>Type de recherche</h2>
              <p>Indiquez le type de poste que vous recherchez</p>
            </div>

            <form class="config-form" method="POST">
              <input type="hidden" name="section" value="search_type">

              <div class="search-types">
                <?php
                $curSearch = $user['search_type'] ?? 'stage';
                $types = [
                  'stage' => 'Stage',
                  'alternance' => 'Alternance',
                  'cdd' => 'CDD',
                  'cdi' => 'CDI'
                ];
                foreach ($types as $val => $label):
                  $checked = ($curSearch == $val) ? 'checked' : '';
                  ?>
                  <label class="search-type-card">
                    <input type="radio" name="search_type" value="<?= $val ?>" <?= $checked ?>>
                    <div class="search-type-content">
                      <h4><?= $label ?></h4>
                      <p>Selectionnez ce type</p>
                    </div>
                  </label>
                <?php endforeach; ?>
              </div>

              <div class="form-actions" style="margin-top: 20px;">
                <button type="submit" class="btn-primary">Enregistrer</button>
              </div>
            </form>
          </section>

          <section id="cv" class="config-section glass-card">
            <div class="section-header">
              <h2>Curriculum Vitae</h2>
              <p>Importez votre CV (PDF, DOCX)</p>
            </div>

            <form class="config-form" method="POST" enctype="multipart/form-data" data-api-action="upload_cv">

              <div class="cv-upload-area">
                <input type="file" id="cv-file" name="cv_file" accept=".pdf,.doc,.docx" hidden required>

                <label for="cv-file" class="cv-upload-zone" id="drop-zone">
                  <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                    <polyline points="17 8 12 3 7 8" />
                    <line x1="12" y1="3" x2="12" y2="15" />
                  </svg>
                  <h4 id="file-label">Cliquez pour importer votre CV</h4>
                  <span class="file-types">PDF, DOC, DOCX (max 5MB)</span>
                </label>
              </div>

              <?php if (!empty($user['cv_filename'])): ?>
                <div class="cv-current" style="margin-top: 20px;">
                  <div class="cv-file-card">
                    <div class="cv-file-info">
                      <h4>CV Actuel : <?= htmlspecialchars($user['cv_filename']) ?></h4>
                      <a href="/uploads/cv/<?= htmlspecialchars($user['cv_filename']) ?>" target="_blank"
                        class="link">Voir le fichier</a>
                    </div>
                  </div>
                </div>
              <?php endif; ?>

              <div class="form-actions">
                <button type="submit" class="btn-primary">Uploader le CV</button>
              </div>
            </form>
          </section>

          <script>
            document.getElementById('cv-file').addEventListener('change', function (e) {
              if (this.files && this.files[0]) {
                document.getElementById('file-label').textContent = this.files[0].name;
              }
            });
          </script>


          <section id="recherche-travail" class="config-section glass-card">
            <div class="section-header">
              <h2>Préférences de recherche</h2>
              <p>Personnalisez vos critères de recherche</p>
            </div>

            <form class="config-form" method="POST">
              <input type="hidden" name="section" value="preferences">

              <div class="form-group">
                <label class="form-label">Mode de travail préféré</label>
                <div class="checkbox-group">
                  <?php
                  $modes = $user['work_mode'] ?? [];
                  if (!is_array($modes))
                    $modes = [];
                  ?>
                  <label class="checkbox-card">
                    <input type="checkbox" name="remote[]" value="onsite" <?= in_array('onsite', $modes) ? 'checked' : '' ?>>
                    <span>Sur site</span>
                  </label>
                  <label class="checkbox-card">
                    <input type="checkbox" name="remote[]" value="hybrid" <?= in_array('hybrid', $modes) ? 'checked' : '' ?>>
                    <span>Hybride</span>
                  </label>
                  <label class="checkbox-card">
                    <input type="checkbox" name="remote[]" value="remote" <?= in_array('remote', $modes) ? 'checked' : '' ?>>
                    <span>100% remote</span>
                  </label>
                </div>
              </div>

              <div class="form-group">
                <label for="salary-min" class="form-label">Salaire minimum souhaité (€/mois)</label>
                <input type="number" id="salary-min" name="salary_min" class="form-input"
                  value="<?= htmlspecialchars($user['min_salary'] ?? '') ?>">
              </div>

              <div class="form-actions">
                <button type="submit" class="btn-primary">Enregistrer les préférences</button>
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

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const forms = document.querySelectorAll('.config-form');
      forms.forEach(form => {
        // CORRECTION : On ne touche pas si une action est déjà définie (comme upload_cv)
        if (!form.hasAttribute('data-api-action')) {
          form.setAttribute('data-api-action', 'update_student');
        }
      });
    });
  </script>
</body>

</html>