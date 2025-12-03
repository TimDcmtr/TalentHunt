// /public/assets/js/profil.js

document.addEventListener('DOMContentLoaded', function() {
  // Contact button
  setupContactButton();
  
  // Save/Follow button
  setupSaveButton();
  
  // CV download
  setupCVDownload();
  
  // Social links tracking
  setupSocialTracking();
  
  // Photo gallery lightbox (optional)
  setupPhotoGallery();
});

function setupContactButton() {
  const contactBtn = document.querySelector('.profil-actions .btn-primary');
  
  if (contactBtn && contactBtn.textContent.includes('Contacter')) {
    contactBtn.addEventListener('click', function(e) {
      e.preventDefault();
      
      const email = document.querySelector('.info-value')?.textContent;
      if (email) {
        showContactModal(email);
      }
    });
  }
}

function showContactModal(email) {
  const modal = document.createElement('div');
  modal.className = 'modal-overlay';
  modal.style.cssText = `
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    animation: fadeIn 0.3s ease-out;
  `;

  modal.innerHTML = `
    <div class="glass-card" style="max-width: 500px; width: 90%; animation: slideIn 0.3s ease-out;">
      <div style="padding: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
          <h2 style="font-family: var(--font-heading); font-size: 1.5rem;">Envoyer un message</h2>
          <button class="btn-icon close-modal">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="18" y1="6" x2="6" y2="18"/>
              <line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
          </button>
        </div>
        
        <form id="contactForm" style="display: flex; flex-direction: column; gap: 1rem;">
          <div class="form-group">
            <label class="form-label">Objet</label>
            <input type="text" name="subject" class="form-input" placeholder="Opportunité de stage" required>
          </div>
          
          <div class="form-group">
            <label class="form-label">Message</label>
            <textarea name="message" class="form-textarea" rows="6" placeholder="Votre message..." required></textarea>
          </div>
          
          <div style="display: flex; gap: 1rem;">
            <button type="button" class="btn-secondary close-modal" style="flex: 1;">Annuler</button>
            <button type="submit" class="btn-primary" style="flex: 1;">
              Envoyer
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="22" y1="2" x2="11" y2="13"/>
                <polygon points="22 2 15 22 11 13 2 9 22 2"/>
              </svg>
            </button>
          </div>
        </form>
      </div>
    </div>
  `;

  document.body.appendChild(modal);

  // Close modal
  modal.querySelectorAll('.close-modal').forEach(btn => {
    btn.addEventListener('click', () => {
      modal.style.animation = 'fadeOut 0.3s ease-out';
      setTimeout(() => modal.remove(), 300);
    });
  });

  // Handle form submission
  const form = modal.querySelector('#contactForm');
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    console.log('Message sent:', data);
    
    // Close modal
    modal.style.animation = 'fadeOut 0.3s ease-out';
    setTimeout(() => modal.remove(), 300);
    
    // Show success notification
    showNotification('Message envoyé avec succès', 'success');
    
    // TODO: Send to backend
  });

  // Close on outside click
  modal.addEventListener('click', function(e) {
    if (e.target === this) {
      this.style.animation = 'fadeOut 0.3s ease-out';
      setTimeout(() => this.remove(), 300);
    }
  });
}

function setupSaveButton() {
  const saveBtn = document.querySelector('.profil-actions .btn-secondary');
  
  if (saveBtn && (saveBtn.textContent.includes('Sauvegarder') || saveBtn.textContent.includes('Suivre'))) {
    saveBtn.addEventListener('click', function() {
      const isSaved = this.classList.toggle('saved');
      const svg = this.querySelector('svg');
      
      if (isSaved) {
        svg.setAttribute('fill', 'currentColor');
        
        if (this.textContent.includes('Sauvegarder')) {
          this.innerHTML = `
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2">
              <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
            </svg>
            Sauvegardé
          `;
        } else {
          this.innerHTML = `
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2">
              <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
            </svg>
            Suivi
          `;
        }
        
        showNotification('Profil sauvegardé', 'success');
      } else {
        svg.setAttribute('fill', 'none');
        
        if (this.textContent.includes('Sauvegardé')) {
          this.innerHTML = `
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
            </svg>
            Sauvegarder
          `;
        } else {
          this.innerHTML = `
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
            </svg>
            Suivre
          `;
        }
        
        showNotification('Profil retiré', 'info');
      }
      
      // TODO: Save to backend
      console.log('Save/Follow toggled:', isSaved);
    });
  }
}

function setupCVDownload() {
  const downloadBtn = document.querySelector('.cv-display .btn-primary');
  
  if (downloadBtn) {
    downloadBtn.addEventListener('click', function(e) {
      
      showNotification('Affichage du cv...', 'info');

    });
  }
}

function setupSocialTracking() {
  const socialLinks = document.querySelectorAll('.social-link');
  
  socialLinks.forEach(link => {
    link.addEventListener('click', function() {
      const platform = this.getAttribute('title');
      console.log('Social link clicked:', platform);
      
      // TODO: Track in analytics
    });
  });
}

function setupPhotoGallery() {
  const photos = document.querySelectorAll('.photo-item');
  
  photos.forEach((photo, index) => {
    photo.addEventListener('click', function() {
      // Could open a lightbox with the full-size image
      console.log('Photo clicked:', index);
      
      // TODO: Implement lightbox gallery
    });
  });
}

// Smooth scroll to sections
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function(e) {
    const href = this.getAttribute('href');
    if (href === '#') return;
    
    e.preventDefault();
    const target = document.querySelector(href);
    
    if (target) {
      target.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
      });
    }
  });
});

// Share profile (optional)
function shareProfile() {
  const url = window.location.href;
  const name = document.querySelector('.profil-name, .company-name')?.textContent;
  
  if (navigator.share) {
    navigator.share({
      title: `Profil de ${name} - TalentHub`,
      url: url
    }).then(() => {
      console.log('Profile shared');
    }).catch(err => {
      console.log('Share cancelled');
    });
  } else {
    // Fallback: copy to clipboard
    navigator.clipboard.writeText(url);
    showNotification('Lien copié dans le presse-papier', 'success');
  }
}

function showNotification(message, type = 'info') {
  const notification = document.createElement('div');
  notification.style.cssText = `
    position: fixed;
    top: 100px;
    right: 20px;
    padding: 1rem 1.5rem;
    background: var(--glass-bg);
    backdrop-filter: var(--glass-blur);
    border: 1px solid var(--glass-border);
    border-left: 4px solid ${
      type === 'success' ? '#10b981' : 
      type === 'info' ? 'var(--primary)' : 
      '#ef4444'
    };
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-lg);
    color: var(--text-primary);
    z-index: 99999;
    animation: slideIn 0.3s ease-out;
    max-width: 400px;
  `;
  
  notification.innerHTML = `
    <div style="display: flex; align-items: center; gap: 0.75rem;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        ${type === 'success' 
          ? '<polyline points="20 6 9 17 4 12"/>'
          : '<circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>'
        }
      </svg>
      <span>${message}</span>
    </div>
  `;
  
  document.body.appendChild(notification);
  
  setTimeout(() => {
    notification.style.animation = 'fadeOut 0.3s ease-out';
    setTimeout(() => notification.remove(), 300);
  }, 3000);
}