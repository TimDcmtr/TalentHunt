<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Configuration Entreprise - TalentHub</title>
  <link rel="stylesheet" href="assets/css/variables.css">
  <link rel="stylesheet" href="assets/css/config.css">
  <link rel="stylesheet" href="assets/css/entreprise-config.css">
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
            <div class="avatar-large company-avatar">🚀</div>
            <h3>TechCorp</h3>
            <p class="profile-status">Profil à 85%</p>
            <div class="progress-bar">
              <div class="progress-fill" style="width: 85%"></div>
            </div>
          </div>

          <nav class="config-nav">
            <a href="#infos" class="nav-item active">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
              </svg>
              Informations
            </a>
            <a href="#description" class="nav-item">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
              </svg>
              Description
            </a>
            <a href="#medias" class="nav-item">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                <circle cx="8.5" cy="8.5" r="1.5"/>
                <polyline points="21 15 16 10 5 21"/>
              </svg>
              Médias
            </a>
            <a href="#secteur" class="nav-item">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7"/>
                <rect x="14" y="3" width="7" height="7"/>
                <rect x="14" y="14" width="7" height="7"/>
                <rect x="3" y="14" width="7" height="7"/>
              </svg>
              Secteur
            </a>
            <a href="#contact" class="nav-item">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
              </svg>
              Contact
            </a>
          </nav>
        </aside>

        <!-- Main Config Area -->
        <div class="config-content">
          <!-- Section Informations -->
          <section id="infos" class="config-section glass-card active">
            <div class="section-header">
              <h2>Informations de l'entreprise</h2>
              <p>Les informations de base visibles par les étudiants</p>
            </div>

            <form class="config-form" method="POST" action="/entreprise/update">
              <div class="logo-upload-section">
                <div class="current-logo">🚀</div>
                <div>
                  <label for="logo-file" class="btn-secondary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                      <polyline points="17 8 12 3 7 8"/>
                      <line x1="12" y1="3" x2="12" y2="15"/>
                    </svg>
                    Changer le logo
                  </label>
                  <input type="file" id="logo-file" accept="image/*" hidden>
                  <p class="upload-hint">Format: PNG, JPG • Max: 2MB</p>
                </div>
              </div>

              <div class="form-group">
                <label for="company_name" class="form-label">Nom de l'entreprise *</label>
                <input type="text" id="company_name" name="company_name" class="form-input" value="TechCorp" required>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="company_size" class="form-label">Taille de l'entreprise *</label>
                  <select id="company_size" name="company_size" class="form-input" required>
                    <option value="">Sélectionnez</option>
                    <option value="1-10">1-10 employés</option>
                    <option value="11-50" selected>11-50 employés</option>
                    <option value="51-200">51-200 employés</option>
                    <option value="201-500">201-500 employés</option>
                    <option value="500+">500+ employés</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="founded_year" class="form-label">Année de création</label>
                  <input type="number" id="founded_year" name="founded_year" class="form-input" placeholder="2020" min="1800" max="2025">
                </div>
              </div>

              <div class="form-group">
                <label for="headquarters" class="form-label">Siège social *</label>
                <input type="text" id="headquarters" name="headquarters" class="form-input" placeholder="Paris, France" required>
              </div>

              <div class="form-group">
                <label for="website" class="form-label">Site web</label>
                <input type="url" id="website" name="website" class="form-input" placeholder="https://techcorp.com">
              </div>

              <div class="form-actions">
                <button type="button" class="btn-secondary">Annuler</button>
                <button type="submit" class="btn-primary">
                  Enregistrer
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"/>
                  </svg>
                </button>
              </div>
            </form>
          </section>

          <!-- Section Description -->
          <section id="description" class="config-section glass-card">
            <div class="section-header">
              <h2>Description et culture</h2>
              <p>Présentez votre entreprise aux futurs candidats</p>
            </div>

            <form class="config-form">
              <div class="form-group">
                <label for="short_description" class="form-label">Description courte (visible en aperçu)</label>
                <textarea id="short_description" name="short_description" class="form-textarea" rows="3" placeholder="Une phrase accrocheuse sur votre entreprise..." maxlength="200"></textarea>
                <small class="char-count">0/200</small>
              </div>

              <div class="form-group">
                <label for="long_description" class="form-label">Description complète</label>
                <textarea id="long_description" name="long_description" class="form-textarea" rows="8" placeholder="Présentez votre entreprise, votre mission, vos valeurs..."></textarea>
              </div>

              <div class="form-group">
                <label class="form-label">Valeurs de l'entreprise</label>
                <div id="values-container" class="values-grid">
                  <div class="value-card">
                    <input type="text" name="values[]" class="value-input" placeholder="Ex: Innovation" value="Innovation">
                    <button type="button" class="btn-remove-value">×</button>
                  </div>
                  <div class="value-card">
                    <input type="text" name="values[]" class="value-input" placeholder="Ex: Excellence">
                    <button type="button" class="btn-remove-value">×</button>
                  </div>
                </div>
                <button type="button" class="btn-add" id="addValue">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                  </svg>
                  Ajouter une valeur
                </button>
              </div>

              <div class="form-actions">
                <button type="submit" class="btn-primary">Enregistrer</button>
              </div>
            </form>
          </section>

          <!-- Section Médias -->
          <section id="medias" class="config-section glass-card">
            <div class="section-header">
              <h2>Photos et vidéos</h2>
              <p>Partagez l'ambiance de votre entreprise</p>
            </div>

            <div class="media-upload-area">
              <input type="file" id="media-files" accept="image/*,video/*" multiple hidden>
              <label for="media-files" class="media-upload-zone">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                  <circle cx="8.5" cy="8.5" r="1.5"/>
                  <polyline points="21 15 16 10 5 21"/>
                </svg>
                <h4>Ajouter des médias</h4>
                <p>Photos des bureaux, équipe, événements...</p>
                <span class="file-types">JPG, PNG, MP4 (max 10MB par fichier)</span>
              </label>
            </div>

            <div class="media-gallery">
              <div class="media-item">
                <img src="/placeholder-office.jpg" alt="Bureau" style="width: 100%; height: 200px; object-fit: cover; background: var(--bg-tertiary); border-radius: var(--radius-md);">
                <button class="btn-remove-media">×</button>
              </div>
              <div class="media-item">
                <img src="/placeholder-team.jpg" alt="Équipe" style="width: 100%; height: 200px; object-fit: cover; background: var(--bg-tertiary); border-radius: var(--radius-md);">
                <button class="btn-remove-media">×</button>
              </div>
            </div>
          </section>

          <!-- Section Secteur -->
          <section id="secteur" class="config-section glass-card">
            <div class="section-header">
              <h2>Secteur d'activité</h2>
              <p>Aidez les étudiants à vous trouver</p>
            </div>

            <form class="config-form">
              <div class="form-group">
                <label for="industry" class="form-label">Secteur principal *</label>
                <select id="industry" name="industry" class="form-input" required>
                  <option value="">Sélectionnez</option>
                  <option value="tech" selected>Technologies</option>
                  <option value="finance">Finance</option>
                  <option value="health">Santé</option>
                  <option value="retail">Commerce</option>
                  <option value="education">Éducation</option>
                  <option value="consulting">Conseil</option>
                  <option value="industry">Industrie</option>
                  <option value="services">Services</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label">Spécialités</label>
                <div class="specialties-grid">
                  <label class="specialty-card">
                    <input type="checkbox" name="specialties[]" value="web" checked>
                    <span>Web Development</span>
                  </label>
                  <label class="specialty-card">
                    <input type="checkbox" name="specialties[]" value="mobile" checked>
                    <span>Mobile</span>
                  </label>
                  <label class="specialty-card">
                    <input type="checkbox" name="specialties[]" value="ai">
                    <span>Intelligence Artificielle</span>
                  </label>
                  <label class="specialty-card">
                    <input type="checkbox" name="specialties[]" value="cloud">
                    <span>Cloud</span>
                  </label>
                  <label class="specialty-card">
                    <input type="checkbox" name="specialties[]" value="security">
                    <span>Cybersécurité</span>
                  </label>
                  <label class="specialty-card">
                    <input type="checkbox" name="specialties[]" value="data">
                    <span>Data Science</span>
                  </label>
                </div>
              </div>

              <div class="form-actions">
                <button type="submit" class="btn-primary">Enregistrer</button>
              </div>
            </form>
          </section>

          <!-- Section Contact -->
          <section id="contact" class="config-section glass-card">
            <div class="section-header">
              <h2>Informations de contact</h2>
              <p>Comment les candidats peuvent vous joindre</p>
            </div>

            <form class="config-form">
              <div class="form-group">
                <label for="contact_email" class="form-label">Email de contact *</label>
                <input type="email" id="contact_email" name="contact_email" class="form-input" placeholder="contact@techcorp.com" required>
              </div>

              <div class="form-group">
                <label for="phone" class="form-label">Téléphone</label>
                <input type="tel" id="phone" name="phone" class="form-input" placeholder="+33 1 23 45 67 89">
              </div>

              <div class="form-group">
                <label class="form-label">Réseaux sociaux</label>
                <div class="social-inputs">
                  <div class="social-input-group">
                    <span class="social-icon">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                      </svg>
                    </span>
                    <input type="url" name="linkedin" class="form-input" placeholder="https://linkedin.com/company/...">
                  </div>

                  <div class="social-input-group">
                    <span class="social-icon">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                      </svg>
                    </span>
                    <input type="url" name="twitter" class="form-input" placeholder="https://twitter.com/...">
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
  
  <script src="assets/js/navbar.js"></script>
  <script src="assets/js/config.js"></script>
  <script src="assets/js/entreprise-config.js"></script>
</body>
</html>