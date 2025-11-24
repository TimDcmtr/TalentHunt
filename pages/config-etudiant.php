<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Configuration du profil - TalentHub</title>
  <link rel="stylesheet" href="assets/css/variables.css">
  <link rel="stylesheet" href="assets/css/config.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
</head>
<body>
  <?php require_once ROOT_PATH . 'app/helpers/Navbar.php'; ?>

  <main class="main-content">
    <div class="container">
      <div class="config-layout">
        <!-- Sidebar Navigation -->
        <aside class="config-sidebar glass-card">
          <div class="profile-preview">
            <div class="avatar-large">
              <span>JD</span>
            </div>
            <h3>Jean Dupont</h3>
            <p class="profile-status">Profil à 75%</p>
            <div class="progress-bar">
              <div class="progress-fill" style="width: 75%"></div>
            </div>
          </div>

          <nav class="config-nav">
            <a href="#infos" class="nav-item active">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
              </svg>
              Informations
            </a>
            <a href="#categories" class="nav-item">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7"/>
                <rect x="14" y="3" width="7" height="7"/>
                <rect x="14" y="14" width="7" height="7"/>
                <rect x="3" y="14" width="7" height="7"/>
              </svg>
              Catégories
            </a>
            <a href="#type-recherche" class="nav-item">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/>
                <path d="m21 21-4.35-4.35"/>
              </svg>
              Type de recherche
            </a>
            <a href="#cv" class="nav-item">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
              </svg>
              CV
            </a>
            <a href="#recherche-travail" class="nav-item">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
              </svg>
              Préférences
            </a>
          </nav>
        </aside>

        <!-- Main Config Area -->
        <div class="config-content">
          <!-- Section Informations -->
          <section id="infos" class="config-section glass-card active">
            <div class="section-header">
              <h2>Informations personnelles</h2>
              <p>Complétez vos informations pour améliorer votre visibilité</p>
            </div>

            <form class="config-form" method="POST" action="/update-profile">
              <div class="avatar-upload">
                <div class="avatar-current">
                  <span>JD</span>
                </div>
                <div class="avatar-actions">
                  <label for="avatar-file" class="btn-secondary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                      <polyline points="17 8 12 3 7 8"/>
                      <line x1="12" y1="3" x2="12" y2="15"/>
                    </svg>
                    Changer la photo
                  </label>
                  <input type="file" id="avatar-file" accept="image/*" hidden>
                  <button type="button" class="btn-text-danger">Supprimer</button>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="firstname" class="form-label">Prénom *</label>
                  <input type="text" id="firstname" name="firstname" class="form-input" value="Jean" required>
                </div>
                <div class="form-group">
                  <label for="lastname" class="form-label">Nom *</label>
                  <input type="text" id="lastname" name="lastname" class="form-input" value="Dupont" required>
                </div>
              </div>

              <div class="form-group">
                <label for="email" class="form-label">Email *</label>
                <input type="email" id="email" name="email" class="form-input" value="jean.dupont@email.com" required>
              </div>

              <div class="form-group">
                <label for="phone" class="form-label">Téléphone</label>
                <input type="tel" id="phone" name="phone" class="form-input" placeholder="+33 6 12 34 56 78">
              </div>

              <div class="form-group">
                <label for="school" class="form-label">École / Université *</label>
                <input type="text" id="school" name="school" class="form-input" placeholder="Nom de votre école" required>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="region" class="form-label">Région *</label>
                  <select id="region" name="region" class="form-input" required>
                    <option value="">Sélectionnez une région</option>
                    <option value="idf" selected>Île-de-France</option>
                    <option value="aura">Auvergne-Rhône-Alpes</option>
                    <option value="paca">Provence-Alpes-Côte d'Azur</option>
                    <option value="occitanie">Occitanie</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="domain" class="form-label">Domaine d'études *</label>
                  <input type="text" id="domain" name="domain" class="form-input" placeholder="Ex: Informatique" required>
                </div>
              </div>

              <div class="form-group">
                <label for="bio" class="form-label">Bio / Présentation</label>
                <textarea id="bio" name="bio" class="form-textarea" rows="4" placeholder="Parlez-nous de vous, de vos aspirations..."></textarea>
              </div>

              <div class="form-actions">
                <button type="button" class="btn-secondary">Annuler</button>
                <button type="submit" class="btn-primary">
                  Enregistrer les modifications
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"/>
                  </svg>
                </button>
              </div>
            </form>
          </section>

          <!-- Section Catégories -->
          <section id="categories" class="config-section glass-card">
            <div class="section-header">
              <h2>Catégories d'intérêt</h2>
              <p>Sélectionnez les domaines qui vous intéressent</p>
            </div>

            <div class="categories-grid">
              <label class="category-card">
                <input type="checkbox" name="category" value="dev" checked>
                <div class="category-content">
                  <div class="category-icon">💻</div>
                  <span class="category-name">Développement</span>
                </div>
              </label>

              <label class="category-card">
                <input type="checkbox" name="category" value="design">
                <div class="category-content">
                  <div class="category-icon">🎨</div>
                  <span class="category-name">Design</span>
                </div>
              </label>

              <label class="category-card">
                <input type="checkbox" name="category" value="marketing" checked>
                <div class="category-content">
                  <div class="category-icon">📱</div>
                  <span class="category-name">Marketing</span>
                </div>
              </label>

              <label class="category-card">
                <input type="checkbox" name="category" value="data">
                <div class="category-content">
                  <div class="category-icon">📊</div>
                  <span class="category-name">Data Science</span>
                </div>
              </label>

              <label class="category-card">
                <input type="checkbox" name="category" value="business">
                <div class="category-content">
                  <div class="category-icon">💼</div>
                  <span class="category-name">Business</span>
                </div>
              </label>

              <label class="category-card">
                <input type="checkbox" name="category" value="finance">
                <div class="category-content">
                  <div class="category-icon">💰</div>
                  <span class="category-name">Finance</span>
                </div>
              </label>
            </div>

            <div class="form-actions">
              <button type="button" class="btn-primary">
                Enregistrer les catégories
              </button>
            </div>
          </section>

          <!-- Section Type de recherche -->
          <section id="type-recherche" class="config-section glass-card">
            <div class="section-header">
              <h2>Type de recherche</h2>
              <p>Indiquez le type de poste que vous recherchez</p>
            </div>

            <div class="search-types">
              <label class="search-type-card">
                <input type="radio" name="search_type" value="stage" checked>
                <div class="search-type-content">
                  <h4>Stage</h4>
                  <p>Courte ou longue durée</p>
                </div>
              </label>

              <label class="search-type-card">
                <input type="radio" name="search_type" value="alternance">
                <div class="search-type-content">
                  <h4>Alternance</h4>
                  <p>Contrat d'apprentissage ou professionnalisation</p>
                </div>
              </label>

              <label class="search-type-card">
                <input type="radio" name="search_type" value="cdd">
                <div class="search-type-content">
                  <h4>CDD</h4>
                  <p>Contrat à durée déterminée</p>
                </div>
              </label>

              <label class="search-type-card">
                <input type="radio" name="search_type" value="cdi">
                <div class="search-type-content">
                  <h4>CDI</h4>
                  <p>Contrat à durée indéterminée</p>
                </div>
              </label>
            </div>
          </section>

          <!-- Section CV -->
          <section id="cv" class="config-section glass-card">
            <div class="section-header">
              <h2>Curriculum Vitae</h2>
              <p>Importez votre CV pour faciliter vos candidatures</p>
            </div>

            <div class="cv-upload-area">
              <input type="file" id="cv-file" accept=".pdf,.doc,.docx" hidden>
              <label for="cv-file" class="cv-upload-zone">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                  <polyline points="17 8 12 3 7 8"/>
                  <line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
                <h4>Cliquez pour importer votre CV</h4>
                <p>ou glissez-déposez votre fichier ici</p>
                <span class="file-types">PDF, DOC, DOCX (max 5MB)</span>
              </label>
            </div>

            <div class="cv-current" style="display: none;">
              <div class="cv-file-card">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                  <polyline points="14 2 14 8 20 8"/>
                </svg>
                <div class="cv-file-info">
                  <h4>CV_Jean_Dupont.pdf</h4>
                  <p>Uploadé le 15/11/2024 • 1.2 MB</p>
                </div>
                <div class="cv-file-actions">
                  <button class="btn-secondary">Voir</button>
                  <button class="btn-text-danger">Supprimer</button>
                </div>
              </div>
            </div>
          </section>

          <!-- Section Préférences -->
          <section id="recherche-travail" class="config-section glass-card">
            <div class="section-header">
              <h2>Préférences de recherche</h2>
              <p>Personnalisez vos critères de recherche</p>
            </div>

            <form class="config-form">
              <div class="form-group">
                <label class="form-label">Mode de travail préféré</label>
                <div class="checkbox-group">
                  <label class="checkbox-card">
                    <input type="checkbox" name="remote" value="onsite" checked>
                    <span>Sur site</span>
                  </label>
                  <label class="checkbox-card">
                    <input type="checkbox" name="remote" value="hybrid" checked>
                    <span>Hybride</span>
                  </label>
                  <label class="checkbox-card">
                    <input type="checkbox" name="remote" value="remote">
                    <span>100% remote</span>
                  </label>
                </div>
              </div>

              <div class="form-group">
                <label for="salary-min" class="form-label">Salaire minimum souhaité (€/mois)</label>
                <input type="number" id="salary-min" name="salary_min" class="form-input" placeholder="1000">
              </div>

              <div class="form-group">
                <label class="form-label">Notifications</label>
                <div class="notification-options">
                  <label class="switch-option">
                    <span>Nouvelles offres correspondant à mon profil</span>
                    <label class="switch">
                      <input type="checkbox" checked>
                      <span class="slider"></span>
                    </label>
                  </label>
                  <label class="switch-option">
                    <span>Actualités de mes candidatures</span>
                    <label class="switch">
                      <input type="checkbox" checked>
                      <span class="slider"></span>
                    </label>
                  </label>
                  <label class="switch-option">
                    <span>Newsletter hebdomadaire</span>
                    <label class="switch">
                      <input type="checkbox">
                      <span class="slider"></span>
                    </label>
                  </label>
                </div>
              </div>

              <div class="form-actions">
                <button type="submit" class="btn-primary">
                  Enregistrer les préférences
                </button>
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>
  </main>

  <?php require_once ROOT_PATH . 'app/helpers/Footer.php'; ?>
  
  <script src="assets/js/navbar.js"></script>
  <script src="assets/js/config.js"></script>
</body>
</html>