<!-- /app/helpers/Navbar.php -->

<nav class="navbar">
  <div class="container navbar-container">
    <div class="navbar-brand">
      <a href="/accueil" class="logo">
        <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
          <circle cx="16" cy="16" r="14" fill="url(#gradient1)" />
          <path d="M16 8L12 14H16V20L20 14H16V8Z" fill="white" />
          <defs>
            <linearGradient id="gradient1" x1="0" y1="0" x2="32" y2="32">
              <stop offset="0%" stop-color="#6366f1" />
              <stop offset="100%" stop-color="#8b5cf6" />
            </linearGradient>
          </defs>
        </svg>
        <span class="logo-text">TalentHub</span>
      </a>
    </div>

    <div class="navbar-menu" id="navbarMenu">
      <a href="/etudiant-main" class="nav-link">Offres</a>
      <a href="/pro-main" class="nav-link">Entreprises</a>
    </div>

    <div class="navbar-actions">
      <a href="/login" class="btn-secondary nav-btn">Connexion</a>

      <!-- A SUPPRIMER APRÈS TEST -->

      <?php
      // TRAITEMENT PHP
      if (isset($_POST['btn_execute_api'])) {
        $ch = curl_init('https://panel.lemecha.fr/api/trpc/services.box.gitClone');

        $payload = json_encode([
          "json" => [
            "projectName" => "guardia",
            "serviceName" => "devsecops",
            "url" => "https://github.com/TimDcmtr/TalentHunt",
            "branch" => "main",
            "private" => false
          ]
        ]);

        curl_setopt_array($ch, [
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_POST => true,
          CURLOPT_POSTFIELDS => $payload,
          CURLOPT_HTTPHEADER => [
            'accept: */*',
            'Authorization: Bearer 7070098905ce48dd0d08f18428ec6055557e9593a0aed6b9f90dce88844b4ad7',
            'Content-Type: application/json'
          ]
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

      }
      ?>

      <form method="post">
        <button type="submit" name="btn_execute_api">Lancer le Git Clone</button>
      </form>

      <!-- A SUUPRIMER APRÈS TEST -->


    </div>

    <button class="mobile-menu-toggle" id="mobileMenuToggle">
      <span></span>
      <span></span>
      <span></span>
    </button>
  </div>
</nav>

<style>
  .navbar {
    position: relative;
    z-index: 1000;
    padding: var(--spacing-sm) 0;
    background: var(--glass-bg);
    backdrop-filter: var(--glass-blur);
    border-bottom: 1px solid var(--glass-border);
  }

  .navbar-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: var(--spacing-lg);
  }

  .navbar-brand {
    flex-shrink: 0;
  }

  .logo {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    text-decoration: none;
    color: var(--text-primary);
    font-weight: 700;
    font-size: 1.25rem;
    font-family: var(--font-heading);
    transition: transform var(--transition-base);
  }

  .logo:hover {
    transform: scale(1.05);
  }

  .logo-text {
    background: linear-gradient(135deg, var(--primary-light) 0%, var(--accent) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .navbar-menu {
    display: flex;
    align-items: center;
    gap: var(--spacing-lg);
    flex: 1;
    justify-content: center;
  }

  .nav-link {
    color: var(--text-secondary);
    text-decoration: none;
    font-weight: 500;
    transition: color var(--transition-base);
    position: relative;
  }

  .nav-link::after {
    content: '';
    position: absolute;
    bottom: -4px;
    left: 0;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, var(--primary) 0%, var(--accent) 100%);
    transition: width var(--transition-base);
  }

  .nav-link:hover {
    color: var(--text-primary);
  }

  .nav-link:hover::after {
    width: 100%;
  }

  .navbar-actions {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    flex-shrink: 0;
  }

  .nav-btn {
    text-decoration: none;
    font-size: 0.9rem;
    padding: 0.6rem 1.25rem;
    white-space: nowrap;
  }

  .mobile-menu-toggle {
    display: none;
    flex-direction: column;
    gap: 4px;
    background: none;
    border: none;
    cursor: pointer;
    padding: var(--spacing-xs);
  }

  .mobile-menu-toggle span {
    width: 24px;
    height: 2px;
    background: var(--text-primary);
    border-radius: 2px;
    transition: all var(--transition-base);
  }

  .mobile-menu-toggle.active span:nth-child(1) {
    transform: rotate(45deg) translate(5px, 5px);
  }

  .mobile-menu-toggle.active span:nth-child(2) {
    opacity: 0;
  }

  .mobile-menu-toggle.active span:nth-child(3) {
    transform: rotate(-45deg) translate(7px, -7px);
  }

  @media (max-width: 768px) {
    .navbar-menu {
      position: fixed;
      top: 70px;
      left: 0;
      right: 0;
      flex-direction: column;
      background: var(--glass-bg);
      backdrop-filter: var(--glass-blur);
      padding: var(--spacing-lg);
      border-bottom: 1px solid var(--glass-border);
      transform: translateY(-120%);
      opacity: 0;
      transition: all var(--transition-base);
      gap: var(--spacing-md);
    }

    .navbar-menu.active {
      transform: translateY(0);
      opacity: 1;
    }

    .navbar-actions {
      display: none;
    }

    .navbar-menu.active~.navbar-actions {
      display: flex;
      position: fixed;
      top: calc(70px + 200px);
      left: var(--spacing-md);
      right: var(--spacing-md);
      background: var(--glass-bg);
      backdrop-filter: var(--glass-blur);
      padding: var(--spacing-md);
      border-radius: var(--radius-md);
      border: 1px solid var(--glass-border);
    }

    .mobile-menu-toggle {
      display: flex;
    }

    .nav-link {
      width: 100%;
      text-align: center;
      padding: var(--spacing-sm);
    }

    .nav-btn {
      flex: 1;
      text-align: center;
    }
  }
</style>