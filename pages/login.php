<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion / Inscription - TalentHub</title>
  <link rel="stylesheet" href="assets/css/variables.css">
  <link rel="stylesheet" href="assets/css/auth.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
</head>
<body>
  <?php require_once ROOT_PATH . 'app/helpers/Navbar.php'; ?>

  <main class="auth-main">
    <div class="auth-background">
      <div class="gradient-orb orb-1"></div>
      <div class="gradient-orb orb-2"></div>
    </div>

    <div class="container auth-container">
      <div class="auth-card glass-card">
        <div class="auth-header">
          <h1 class="auth-title">Bienvenue sur <span class="gradient-text">TalentHub</span></h1>
          <p class="auth-subtitle">Connectez-vous ou créez votre compte pour commencer</p>
        </div>

        <!-- Tabs -->
        <div class="auth-tabs">
          <button class="auth-tab active" data-tab="login">Connexion</button>
          <button class="auth-tab" data-tab="register">Inscription</button>
        </div>

        <!-- Login Form -->
        <form id="loginForm" class="auth-form active" method="POST" action="/login">
          <div class="form-group">
            <label for="login-email" class="form-label">Email</label>
            <input 
              type="email" 
              id="login-email" 
              name="email" 
              class="form-input" 
              placeholder="votre@email.com"
              required
            >
          </div>

          <div class="form-group">
            <label for="login-password" class="form-label">Mot de passe</label>
            <input 
              type="password" 
              id="login-password" 
              name="password" 
              class="form-input" 
              placeholder="••••••••"
              required
            >
          </div>

          <div class="form-options">
            <label class="checkbox-label">
              <input type="checkbox" name="remember">
              <span>Se souvenir de moi</span>
            </label>
            <a href="/forgot-password" class="link">Mot de passe oublié ?</a>
          </div>

          <button type="submit" class="btn-primary btn-full">
            Se connecter
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M5 12h14M12 5l7 7-7 7"/>
            </svg>
          </button>

          <div class="divider">
            <span>ou continuer avec</span>
          </div>

          <div class="social-buttons">
            <button type="button" class="btn-social">
              <svg width="20" height="20" viewBox="0 0 24 24">
                <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
              </svg>
              Google
            </button>
            <button type="button" class="btn-social">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
              </svg>
              LinkedIn
            </button>
          </div>
        </form>

        <!-- Register Form -->
        <form id="registerForm" class="auth-form" method="POST" action="/register">
          <div class="form-row">
            <div class="form-group">
              <label for="register-firstname" class="form-label">Prénom</label>
              <input 
                type="text" 
                id="register-firstname" 
                name="firstname" 
                class="form-input" 
                placeholder="Jean"
                required
              >
            </div>
            <div class="form-group">
              <label for="register-lastname" class="form-label">Nom</label>
              <input 
                type="text" 
                id="register-lastname" 
                name="lastname" 
                class="form-input" 
                placeholder="Dupont"
                required
              >
            </div>
          </div>

          <div class="form-group">
            <label for="register-email" class="form-label">Email</label>
            <input 
              type="email" 
              id="register-email" 
              name="email" 
              class="form-input" 
              placeholder="votre@email.com"
              required
            >
          </div>

          <div class="form-group">
            <label for="register-tel" class="form-label">Téléphone</label>
            <input 
              type="tel" 
              id="register-tel" 
              name="tel" 
              class="form-input" 
              placeholder="+33 6 12 34 56 78"
            >
          </div>

          <div class="form-group">
            <label for="register-password" class="form-label">Mot de passe</label>
            <input 
              type="password" 
              id="register-password" 
              name="password" 
              class="form-input" 
              placeholder="••••••••"
              required
            >
          </div>

          <div class="form-group">
            <label for="register-school" class="form-label">École</label>
            <input 
              type="text" 
              id="register-school" 
              name="school" 
              class="form-input" 
              placeholder="Nom de votre école"
            >
          </div>

          <div class="form-group">
            <label for="register-region" class="form-label">Région</label>
            <select id="register-region" name="region" class="form-input">
              <option value="">Sélectionnez une région</option>
              <option value="idf">Île-de-France</option>
              <option value="aura">Auvergne-Rhône-Alpes</option>
              <option value="paca">Provence-Alpes-Côte d'Azur</option>
              <option value="occitanie">Occitanie</option>
              <option value="nouvelle-aquitaine">Nouvelle-Aquitaine</option>
              <option value="autres">Autres</option>
            </select>
          </div>

          <div class="form-group">
            <label for="register-domain" class="form-label">Domaine d'études</label>
            <input 
              type="text" 
              id="register-domain" 
              name="domain" 
              class="form-input" 
              placeholder="Ex: Informatique, Marketing..."
            >
          </div>

          <label class="checkbox-label">
            <input type="checkbox" name="terms" required>
            <span>J'accepte les <a href="/conditions" class="link">conditions d'utilisation</a></span>
          </label>

          <button type="submit" class="btn-primary btn-full">
            Créer mon compte
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M5 12h14M12 5l7 7-7 7"/>
            </svg>
          </button>
        </form>
      </div>
    </div>
  </main>

  <script src="assets/js/navbar.js"></script>
  <script src="assets/js/auth.js"></script>
</body>
</html>