// /public/assets/js/offre-detail.js

document.addEventListener('DOMContentLoaded', function() {
  const applyBtn = document.getElementById('applyBtn');
  const modal = document.getElementById('applicationModal');
  const closeModalBtns = document.querySelectorAll('.close-modal');
  const applicationForm = document.querySelector('.application-form');

  // Open application modal
  if (applyBtn) {
    applyBtn.addEventListener('click', function() {
      modal.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    });
  }

  // Close modal
  closeModalBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      modal.style.display = 'none';
      document.body.style.overflow = '';
    });
  });

  // Close modal on outside click
  modal?.addEventListener('click', function(e) {
    if (e.target === this) {
      this.style.display = 'none';
      document.body.style.overflow = '';
    }
  });

  // Handle form submission
  if (applicationForm) {
    applicationForm.addEventListener('submit', function(e) {
      e.preventDefault();

      const formData = new FormData(this);
      const data = Object.fromEntries(formData);

      console.log('Application submitted:', data);

      // Show success message
      showNotification('Candidature envoyée avec succès !', 'success');

      // Close modal
      modal.style.display = 'none';
      document.body.style.overflow = '';

      // Reset form
      this.reset();

      // TODO: Send to backend
    });
  }

  // Save offer
  const saveBtn = document.querySelector('.offre-sidebar .btn-secondary');
  if (saveBtn) {
    saveBtn.addEventListener('click', function() {
      this.classList.toggle('saved');
      
      const svg = this.querySelector('svg');
      if (this.classList.contains('saved')) {
        svg.setAttribute('fill', 'currentColor');
        this.innerHTML = `
          <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2">
            <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
          </svg>
          Sauvegardé
        `;
        showNotification('Offre sauvegardée', 'success');
      } else {
        svg.setAttribute('fill', 'none');
        this.innerHTML = `
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
          </svg>
          Sauvegarder
        `;
        showNotification('Offre retirée des favoris', 'info');
      }

      // TODO: Save to backend
    });
  }

  // Share offer (optional)
  const shareBtn = document.createElement('button');
  shareBtn.className = 'btn-secondary btn-full';
  shareBtn.innerHTML = `
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <circle cx="18" cy="5" r="3"/>
      <circle cx="6" cy="12" r="3"/>
      <circle cx="18" cy="19" r="3"/>
      <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/>
      <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
    </svg>
    Partager
  `;

  const applyCard = document.querySelector('.apply-card');
  if (applyCard) {
    const lastBtn = applyCard.querySelector('.btn-secondary');
    lastBtn.insertAdjacentElement('afterend', shareBtn);
  }

  shareBtn.addEventListener('click', async function() {
    const url = window.location.href;
    const title = document.querySelector('.offre-header h1').textContent;
    
    if (navigator.share) {
      try {
        await navigator.share({
          title: title,
          url: url
        });
        showNotification('Offre partagée', 'success');
      } catch (err) {
        console.log('Share cancelled');
      }
    } else {
      // Fallback: copy to clipboard
      navigator.clipboard.writeText(url);
      showNotification('Lien copié dans le presse-papier', 'success');
    }
  });

  // Track view (analytics)
  trackOfferView();
});

function trackOfferView() {
  const offreId = window.location.pathname.split('/').pop();
  console.log('Tracking view for offer:', offreId);
  // TODO: Send analytics event
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
    border-left: 4px solid ${type === 'success' ? '#10b981' : 'var(--primary)'};
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