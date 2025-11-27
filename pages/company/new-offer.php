<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Publier une offre - TalentHub</title>
  <link rel="stylesheet" href="assets/css/variables.css">
  <link rel="stylesheet" href="assets/css/offre-form.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
</head>
<body>
  <?php require_once ROOT_PATH . 'app/helpers/Navbar.php'; ?>

  <main class="main-content">
    <div class="container">
      <div class="form-layout">
        <!-- Progress Sidebar -->
        <aside class="progress-sidebar glass-card">
          <h3>Création de l'offre</h3>
          <div class="progress-steps">
            <div class="progress-step active" data-step="1">
              <div class="step-number">1</div>
              <div class="step-info">
                <span class="step-title">Informations générales</span>
                <span class="step-desc">Poste et entreprise</span>
              </div>
            </div>

            <div class="progress-step" data-step="2">
              <div class="step-number">2</div>
              <div class="step-info">
                <span class="step-title">Détails du poste</span>
                <span class="step-desc">Missions et profil</span>
              </div>
            </div>

            <div class="progress-step" data-step="3">
              <div class="step-number">3</div>
              <div class="step-info">
                <span class="step-title">Conditions</span>
                <span class="step-desc">Salaire et avantages</span>
              </div>
            </div>

            <div class="progress-step" data-step="4">
              <div class="step-number">4</div>
              <div class="step-info">
                <span class="step-title">Publication</span>
                <span class="step-desc">Vérification et validation</span>
              </div>
            </div>
          </div>

          <div class="progress-tips">
            <h4>💡 Conseils</h4>
            <ul>
              <li>Soyez précis dans la description</li>
              <li>Mentionnez les technologies utilisées</li>
              <li>Indiquez les perspectives d'évolution</li>
            </ul>
          </div>
        </aside>

        <!-- Form Content -->
        <div class="form-content">
          <div class="form-header">
            <h1>Publier une nouvelle offre</h1>
            <p>Créez une offre attractive pour trouver les meilleurs talents</p>
          </div>

          <form id="offreForm" method="POST" action="/offre/create">
            <!-- Step 1: Informations générales -->
            <section class="form-step active" data-step="1">
              <div class="step-card glass-card">
                <h2>Informations générales</h2>

                <div class="form-group">
                  <label for="title" class="form-label">Titre du poste *</label>
                  <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    class="form-input" 
                    placeholder="Ex: Développeur Full Stack"
                    required
                  >
                </div>

                <div class="form-row">
                  <div class="form-group">
                    <label for="contract_type" class="form-label">Type de contrat *</label>
                    <select id="contract_type" name="contract_type" class="form-input" required>
                      <option value="">Sélectionnez</option>
                      <option value="stage">Stage</option>
                      <option value="alternance">Alternance</option>
                      <option value="cdd">CDD</option>
                      <option value="cdi">CDI</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="duration" class="form-label">Durée</label>
                    <input 
                      type="text" 
                      id="duration" 
                      name="duration" 
                      class="form-input" 
                      placeholder="Ex: 6 mois"
                    >
                  </div>
                </div>

                <div class="form-row">
                  <div class="form-group">
                    <label for="location" class="form-label">Localisation *</label>
                    <input 
                      type="text" 
                      id="location" 
                      name="location" 
                      class="form-input" 
                      placeholder="Ex: Paris, France"
                      required
                    >
                  </div>

                  <div class="form-group">
                    <label for="remote" class="form-label">Télétravail *</label>
                    <select id="remote" name="remote" class="form-input" required>
                      <option value="">Sélectionnez</option>
                      <option value="onsite">Sur site</option>
                      <option value="hybrid">Hybride</option>
                      <option value="remote">100% remote</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label for="start_date" class="form-label">Date de début souhaitée</label>
                  <input 
                    type="date" 
                    id="start_date" 
                    name="start_date" 
                    class="form-input"
                  >
                </div>

                <div class="form-group">
                  <label class="form-label">Domaine(s) *</label>
                  <div class="checkbox-grid">
                    <label class="checkbox-card">
                      <input type="checkbox" name="domains[]" value="dev">
                      <span>💻 Développement</span>
                    </label>
                    <label class="checkbox-card">
                      <input type="checkbox" name="domains[]" value="design">
                      <span>🎨 Design</span>
                    </label>
                    <label class="checkbox-card">
                      <input type="checkbox" name="domains[]" value="marketing">
                      <span>📱 Marketing</span>
                    </label>
                    <label class="checkbox-card">
                      <input type="checkbox" name="domains[]" value="data">
                      <span>📊 Data</span>
                    </label>
                    <label class="checkbox-card">
                      <input type="checkbox" name="domains[]" value="business">
                      <span>💼 Business</span>
                    </label>
                    <label class="checkbox-card">
                      <input type="checkbox" name="domains[]" value="finance">
                      <span>💰 Finance</span>
                    </label>
                  </div>
                </div>
              </div>
            </section>

            <!-- Step 2: Détails du poste -->
            <section class="form-step" data-step="2">
              <div class="step-card glass-card">
                <h2>Détails du poste</h2>

                <div class="form-group">
                  <label for="description" class="form-label">Description du poste *</label>
                  <textarea 
                    id="description" 
                    name="description" 
                    class="form-textarea" 
                    rows="6"
                    placeholder="Décrivez le poste, le contexte, l'équipe..."
                    required
                  ></textarea>
                </div>

                <div class="form-group">
                  <label for="missions" class="form-label">Missions principales *</label>
                  <div id="missions-container" class="dynamic-list">
                    <div class="list-item">
                      <input 
                        type="text" 
                        name="missions[]" 
                        class="form-input" 
                        placeholder="Mission 1"
                        required
                      >
                      <button type="button" class="btn-remove" style="display: none;">×</button>
                    </div>
                  </div>
                  <button type="button" class="btn-add" data-target="missions">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <line x1="12" y1="5" x2="12" y2="19"/>
                      <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Ajouter une mission
                  </button>
                </div>

                <div class="form-group">
                  <label for="requirements" class="form-label">Profil recherché *</label>
                  <div id="requirements-container" class="dynamic-list">
                    <div class="list-item">
                      <input 
                        type="text" 
                        name="requirements[]" 
                        class="form-input" 
                        placeholder="Compétence 1"
                        required
                      >
                      <button type="button" class="btn-remove" style="display: none;">×</button>
                    </div>
                  </div>
                  <button type="button" class="btn-add" data-target="requirements">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <line x1="12" y1="5" x2="12" y2="19"/>
                      <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Ajouter une compétence
                  </button>
                </div>

                <div class="form-group">
                  <label for="tags" class="form-label">Technologies / Mots-clés</label>
                  <input 
                    type="text" 
                    id="tags" 
                    name="tags" 
                    class="form-input" 
                    placeholder="React, Node.js, Python... (séparés par des virgules)"
                  >
                  <small class="form-hint">Ces tags aideront les étudiants à trouver votre offre</small>
                </div>
              </div>
            </section>

            <!-- Step 3: Conditions -->
            <section class="form-step" data-step="3">
              <div class="step-card glass-card">
                <h2>Conditions et avantages</h2>

                <div class="form-row">
                  <div class="form-group">
                    <label for="salary_min" class="form-label">Salaire minimum (€/mois)</label>
                    <input 
                      type="number" 
                      id="salary_min" 
                      name="salary_min" 
                      class="form-input" 
                      placeholder="1200"
                      min="0"
                    >
                  </div>

                  <div class="form-group">
                    <label for="salary_max" class="form-label">Salaire maximum (€/mois)</label>
                    <input 
                      type="number" 
                      id="salary_max" 
                      name="salary_max" 
                      class="form-input" 
                      placeholder="1500"
                      min="0"
                    >
                  </div>
                </div>

                <div class="form-group">
                  <label class="form-label">Avantages</label>
                  <div id="benefits-container" class="dynamic-list">
                    <div class="list-item">
                      <input 
                        type="text" 
                        name="benefits[]" 
                        class="form-input" 
                        placeholder="Ex: Tickets restaurant"
                      >
                      <button type="button" class="btn-remove" style="display: none;">×</button>
                    </div>
                  </div>
                  <button type="button" class="btn-add" data-target="benefits">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <line x1="12" y1="5" x2="12" y2="19"/>
                      <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Ajouter un avantage
                  </button>
                </div>

                <div class="form-group">
                  <label for="application_email" class="form-label">Email de contact *</label>
                  <input 
                    type="email" 
                    id="application_email" 
                    name="application_email" 
                    class="form-input" 
                    placeholder="recrutement@entreprise.com"
                    required
                  >
                  <small class="form-hint">Les candidatures seront envoyées à cette adresse</small>
                </div>
              </div>
            </section>

            <!-- Step 4: Publication -->
            <section class="form-step" data-step="4">
              <div class="step-card glass-card">
                <h2>Aperçu et publication</h2>

                <div class="preview-card">
                  <div class="preview-header">
                    <h3 id="preview-title">Titre du poste</h3>
                    <p id="preview-company">Votre Entreprise</p>
                  </div>
                  <div class="preview-meta">
                    <span id="preview-location">📍 Localisation</span>
                    <span id="preview-contract">📄 Type de contrat</span>
                    <span id="preview-remote">💻 Télétravail</span>
                  </div>
                  <div class="preview-salary">
                    <span id="preview-salary-range">Rémunération</span>
                  </div>
                </div>

              </div>
            </section>

            <!-- Navigation Buttons -->
            <div class="form-navigation">
              <button type="button" class="btn-secondary" id="prevBtn" style="display: none;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M15 18l-6-6 6-6"/>
                </svg>
                Précédent
              </button>

              <button type="button" class="btn-primary" id="nextBtn">
                Suivant
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M9 18l6-6-6-6"/>
                </svg>
              </button>

              <button type="submit" class="btn-primary" id="submitBtn" style="display: none;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="20 6 9 17 4 12"/>
                </svg>
                Publier l'offre
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>

  <?php require_once ROOT_PATH . 'app/helpers/Footer.php'; ?>
  
  <script src="assets/js/navbar.js"></script>
  <script src="assets/js/offre-form.js"></script>
</body>
</html>