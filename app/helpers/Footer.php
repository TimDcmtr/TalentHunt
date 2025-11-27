<!-- /app/helpers/Footer.php -->

<footer class="footer">
  <div class="footer-gradient"></div>
  <div class="container">
    <div class="footer-content">
      <div class="footer-section">
        <div class="footer-brand">
          <svg width="40" height="40" viewBox="0 0 32 32" fill="none">
            <circle cx="16" cy="16" r="14" fill="url(#gradient2)" />
            <path d="M16 8L12 14H16V20L20 14H16V8Z" fill="white" />
            <defs>
              <linearGradient id="gradient2" x1="0" y1="0" x2="32" y2="32">
                <stop offset="0%" stop-color="#6366f1" />
                <stop offset="100%" stop-color="#8b5cf6" />
              </linearGradient>
            </defs>
          </svg>
          <h3 class="gradient-text">TalentHub</h3>
        </div>
        <p class="footer-description">
          La plateforme qui connecte les talents de demain avec les opportunités d'aujourd'hui.
        </p>
        <div class="footer-social">

          <a title="Maël Q." href="https://www.linkedin.com/in/ma%C3%ABl-quillier-2998a2236/" class="social-link"
            aria-label="LinkedIn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
              <path
                d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
            </svg>
          </a>

          <a title="Matéo A." href="https://www.linkedin.com/in/mat%C3%A9o-a-63ba61386/" class="social-link"
            aria-label="LinkedIn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
              <path
                d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
            </svg>
          </a>

          <a title="Timeo D." href="https://www.linkedin.com/in/timeo-d/" href="#" class="social-link"
            aria-label="LinkedIn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
              <path
                d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
            </svg>
          </a>

        </div>
      </div>

      <div class="footer-section">
        <h4 class="footer-title">Étudiants</h4>
        <ul class="footer-links">
          <li><a href="/offers">Rechercher des offres</a></li>
          <li><a href="/student/config">Créer un profil</a></li>
          <li><a href="/student/feed">Mes candidatures</a></li>
        </ul>
      </div>

      <div class="footer-section">
        <h4 class="footer-title">Entreprises</h4>
        <ul class="footer-links">
          <li><a href="/company/dashboard">Gérer les offres</a></li>
          <li><a href="/company/new-offer">Recruter</a></li>
          <li><a href="/company/config">Modifier mon entreprise</a></li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <p>&copy; <?php echo date('Y'); ?> TalentHub. Tous droits réservés.</p>
      <p class="footer-made">Conçu avec ❤️ pour connecter les talents</p>
    </div>
  </div>
</footer>

<style>
  .footer {
    margin-top: var(--spacing-xl);
    padding: var(--spacing-xl) 0 var(--spacing-lg);
    background: var(--bg-secondary);
    border-top: 1px solid var(--glass-border);
    overflow: hidden;
  }

  .footer-content {
    display: grid;
    grid-template-columns: 2fr repeat(2, 1fr);
    gap: var(--spacing-xl);
    margin-bottom: var(--spacing-lg);
  }

  .footer-section {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
  }

  .footer-brand {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    margin-bottom: var(--spacing-xs);
  }

  .footer-brand h3 {
    font-size: 1.5rem;
    font-family: var(--font-heading);
  }

  .footer-description {
    color: var(--text-secondary);
    font-size: 0.95rem;
    line-height: 1.6;
    max-width: 300px;
  }

  .footer-social {
    display: flex;
    gap: var(--spacing-sm);
    margin-top: var(--spacing-xs);
  }

  .social-link {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--glass-bg);
    backdrop-filter: var(--glass-blur);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius-sm);
    color: var(--text-secondary);
    transition: all var(--transition-base);
  }

  .social-link:hover {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
    transform: translateY(-2px);
  }

  .footer-title {
    color: var(--text-primary);
    font-size: 1rem;
    font-weight: 600;
    font-family: var(--font-heading);
    margin-bottom: var(--spacing-xs);
  }

  .footer-links {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: var(--spacing-sm);
  }

  .footer-links a {
    color: var(--text-secondary);
    text-decoration: none;
    font-size: 0.9rem;
    transition: color var(--transition-base);
    display: inline-block;
  }

  .footer-links a:hover {
    color: var(--primary-light);
    transform: translateX(5px);
  }

  .footer-bottom {
    padding-top: var(--spacing-lg);
    border-top: 1px solid var(--glass-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: var(--text-muted);
    font-size: 0.9rem;
  }

  .footer-made {
    display: flex;
    align-items: center;
    gap: 4px;
  }

  @media (max-width: 1024px) {
    .footer-content {
      grid-template-columns: repeat(3, 1fr);
    }

    .footer-section:first-child {
      grid-column: span 3;
    }
  }

  @media (max-width: 768px) {
    .footer-content {
      grid-template-columns: repeat(2, 1fr);
      gap: var(--spacing-lg);
    }

    .footer-section:first-child {
      grid-column: span 2;
    }

    .footer-bottom {
      flex-direction: column;
      gap: var(--spacing-sm);
      text-align: center;
    }
  }

  @media (max-width: 480px) {
    .footer-content {
      grid-template-columns: 1fr;
    }

    .footer-section:first-child {
      grid-column: span 1;
    }
  }
</style>