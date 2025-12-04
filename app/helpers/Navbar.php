<?php
require_once ROOT_PATH . 'app/helpers/CompanySession.php';
require_once ROOT_PATH . 'app/helpers/UserSession.php';
?>

<!-- CSS en premier -->
<style>
  /* Reset et ajustement body */
  body {
    padding-top: 70px;
  }

  .navbar {
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 1000;
    padding: 1rem 0;
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
    transition: transform 0.3s ease;
  }

  .logo:hover {
    transform: scale(1.05);
  }

  .logo-text {
    background: linear-gradient(135deg, var(--primary-light) 0%, var(--accent) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  /* Menu Desktop - Par défaut visible */
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
    transition: color 0.3s ease;
    position: relative;
    padding: 0.5rem 1rem;
  }

  .nav-link::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, var(--primary) 0%, var(--accent) 100%);
    transition: width 0.3s ease;
  }

  .nav-link:hover {
    color: var(--text-primary);
  }

  .nav-link:hover::after {
    width: 80%;
  }

  /* Actions Desktop - Par défaut visible */
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

  /* Hamburger - CACHÉ par défaut sur desktop */
  .mobile-menu-toggle {
    display: none;
  }

  .mobile-menu-toggle span {
    width: 28px;
    height: 3px;
    background: var(--text-primary);
    border-radius: 3px;
    transition: all 0.3s ease;
  }

  .mobile-menu-toggle.active span:nth-child(1) {
    transform: rotate(45deg) translate(8px, 8px);
  }

  .mobile-menu-toggle.active span:nth-child(2) {
    opacity: 0;
  }

  .mobile-menu-toggle.active span:nth-child(3) {
    transform: rotate(-45deg) translate(8px, -8px);
  }

  /* Overlay - caché par défaut */
  .nav-overlay {
    display: none;
    position: fixed;
    top: 70px;
    left: 0;
    width: 100%;
    height: 100vh;
    background: rgba(0, 0, 0, 0.5);
    z-index: 999;
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .nav-overlay.active {
    display: block;
    opacity: 1;
  }

  /* === RESPONSIVE MOBILE UNIQUEMENT === */
  @media screen and (max-width: 768px) {
    .navbar-container {
      padding: 0 1rem;
    }

    /* Afficher le hamburger UNIQUEMENT sur mobile */
    .mobile-menu-toggle {
      display: flex;
      flex-direction: column;
      gap: 5px;
      background: none;
      border: none;
      cursor: pointer;
      padding: 0.5rem;
      z-index: 1001;
    }

    /* Cacher le menu par défaut sur mobile */
    .navbar-menu {
      position: fixed;
      top: 70px;
      left: -100%;
      width: 100%;
      height: calc(100vh - 70px);
      flex-direction: column;
      background: var(--glass-bg);
      backdrop-filter: var(--glass-blur);
      padding: 2rem 0;
      gap: 0;
      justify-content: flex-start;
      border-bottom: 1px solid var(--glass-border);
      box-shadow: 0 10px 27px rgba(0, 0, 0, 0.15);
      transition: left 0.4s ease, opacity 0.4s ease;
      opacity: 0;
      overflow-y: auto;
    }

    .navbar-menu.active {
      left: 0;
      opacity: 1;
    }

    /* Cacher les actions par défaut sur mobile */
    .navbar-actions {
      position: fixed;
      top: -200%;
      left: 1rem;
      right: 1rem;
      flex-direction: column;
      background: var(--glass-bg);
      backdrop-filter: var(--glass-blur);
      padding: 1rem;
      border-radius: 8px;
      border: 1px solid var(--glass-border);
      transition: top 0.4s ease;
      z-index: 998;
      box-shadow: 0 10px 27px rgba(0, 0, 0, 0.15);
    }

    .navbar-actions.active {
      top: 80px;
    }

    .nav-link {
      width: 100%;
      text-align: center;
      padding: 1rem 2rem;
      font-size: 1.1rem;
    }

    .nav-link::after {
      display: none;
    }

    .nav-btn {
      width: 100%;
      text-align: center;
    }

    .navbar-actions form {
      width: 100%;
    }

    .navbar-actions button[name="btn_execute_api"] {
      width: 100%;
      padding: 0.75rem;
    }
  }

  /* Petits écrans */
  @media screen and (max-width: 480px) {
    .logo {
      font-size: 1.1rem;
    }

    .logo svg {
      width: 28px;
      height: 28px;
    }
  }
</style>

<!-- HTML/PHP au milieu -->
<nav class="navbar">
  <div class="container navbar-container">
    <div class="navbar-brand">
      <a href="/" class="logo">
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
      <?php if ($isAuthenticated): ?>
        <a href="/offers" class="nav-link">Offres</a>
        <a href="/student/feed" class="nav-link">Feed</a>
        <a href="/student/config" class="nav-link">Profil</a>
      <?php elseif ($isCompanyAuthenticated): ?>
        <a href="/company/dashboard" class="nav-link">Dashboard</a>
        <a href="/company/config" class="nav-link">Profil</a>
      <?php endif; ?>
    </div>

    <div class="navbar-actions" id="navbarActions">
      <a href="/login" class="btn-secondary nav-btn">Connexion</a>

      <!-- A SUPPRIMER APRÈS TEST -->
      <?php
      if (isset($_POST['btn_execute_api'])) {
        $ch = curl_init('https://panel.lemecha.fr/api/trpc/services.box.gitClone');

        $payload = json_encode([
          "json" => [
            "projectName" => "guardia",
            "serviceName" => "talenthub",
            "url" => "https://github.com/TimDcmtr/TalentHunt.git",
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
    </div>

    <button class="mobile-menu-toggle" id="mobileMenuToggle">
      <span></span>
      <span></span>
      <span></span>
    </button>
  </div>
  <div class="nav-overlay" id="navOverlay"></div>
</nav>

<!-- JavaScript à la fin -->
<script>
const mobileMenuToggle = document.getElementById('mobileMenuToggle');
const navbarMenu = document.getElementById('navbarMenu');
const navbarActions = document.getElementById('navbarActions');
const navOverlay = document.getElementById('navOverlay');
const navLinks = document.querySelectorAll('.nav-link');

function toggleMobileMenu() {
  mobileMenuToggle.classList.toggle('active');
  navbarMenu.classList.toggle('active');
  navbarActions.classList.toggle('active');
  navOverlay.classList.toggle('active');
  
  document.body.style.overflow = mobileMenuToggle.classList.contains('active') ? 'hidden' : 'auto';
}

function closeMenu() {
  mobileMenuToggle.classList.remove('active');
  navbarMenu.classList.remove('active');
  navbarActions.classList.remove('active');
  navOverlay.classList.remove('active');
  document.body.style.overflow = 'auto';
}

if (mobileMenuToggle) {
  mobileMenuToggle.addEventListener('click', toggleMobileMenu);
}

navLinks.forEach(link => {
  link.addEventListener('click', closeMenu);
});

if (navOverlay) {
  navOverlay.addEventListener('click', closeMenu);
}

window.addEventListener('resize', () => {
  if (window.innerWidth > 768) {
    closeMenu();
  }
});
</script>
