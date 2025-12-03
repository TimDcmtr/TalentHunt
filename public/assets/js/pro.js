// /public/assets/js/pro.js

document.addEventListener('DOMContentLoaded', function() {
  // Animate stats on page load
  animateStatsOnLoad();

  // Offer actions
  setupOfferActions();

  // Application interactions
  setupApplicationInteractions();

  // Quick actions
  setupQuickActions();

  // Match score animations
  animateMatchScores();
});

// Animate stats numbers
function animateStatsOnLoad() {
  const statValues = document.querySelectorAll('.stat-value');
  
  statValues.forEach(stat => {
    const finalValue = parseInt(stat.textContent);
    if (isNaN(finalValue)) return;

    let currentValue = 0;
    const increment = finalValue / 50;
    const duration = 1000; // 1 second
    const stepTime = duration / 50;

    const counter = setInterval(() => {
      currentValue += increment;
      if (currentValue >= finalValue) {
        stat.textContent = finalValue;
        clearInterval(counter);
      } else {
        stat.textContent = Math.floor(currentValue);
      }
    }, stepTime);
  });
}

// Setup offer actions (edit, view)
function setupOfferActions() {
  const editBtns = document.querySelectorAll('.offer-item .btn-icon[title="Modifier"]');
  const viewBtns = document.querySelectorAll('.offer-item .btn-icon[title="Voir les candidatures"]');

  editBtns.forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      const offerId = this.getAttribute('href').split('/')[2];
      console.log('Edit offer:', offerId);
      // TODO: Navigate to edit page or open modal
      // window.location.href = this.getAttribute('href');
    });
  });

  viewBtns.forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      const offerId = this.getAttribute('href').split('/')[2];
      console.log('View applications for offer:', offerId);
      // TODO: Navigate to applications page
      // window.location.href = this.getAttribute('href');
    });
  });

}

// Setup application interactions
function setupApplicationInteractions() {
  // Add quick actions to application items
  const applicationItems = document.querySelectorAll('.application-item');
  applicationItems.forEach(item => {
    // Accept application
    const acceptBtn = document.createElement('button');
    acceptBtn.className = 'btn-icon btn-success';
    acceptBtn.title = 'Accepter';
    acceptBtn.innerHTML = `
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polyline points="20 6 9 17 4 12"/>
      </svg>
    `;
    acceptBtn.style.cssText = `
      background: rgba(16, 185, 129, 0.2);
      border-color: #10b981;
      color: #10b981;
      margin-left: 8px;
    `;
    
    // Reject application
    const rejectBtn = document.createElement('button');
    rejectBtn.className = 'btn-icon btn-danger';
    rejectBtn.title = 'Refuser';
    rejectBtn.innerHTML = `
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="18" y1="6" x2="6" y2="18"/>
        <line x1="6" y1="6" x2="18" y2="18"/>
      </svg>
    `;
    rejectBtn.style.cssText = `
      background: rgba(239, 68, 68, 0.2);
      border-color: #ef4444;
      color: #ef4444;
      margin-left: 4px;
    `;

    const viewBtn = item.querySelector('.btn-secondary');
    if (viewBtn) {
      viewBtn.insertAdjacentElement('afterend', rejectBtn);
      viewBtn.insertAdjacentElement('afterend', acceptBtn);
    }

    acceptBtn.addEventListener('click', function() {
      if (confirm('Accepter cette candidature ?')) {
        const name = item.querySelector('.app-info h4').textContent;
        console.log('Accept application:', name);
        showNotification(`Candidature de ${name} acceptée`, 'success');
        item.style.animation = 'fadeOut 0.3s ease-out';
        setTimeout(() => item.remove(), 300);
      }
    });

    rejectBtn.addEventListener('click', function() {
      if (confirm('Refuser cette candidature ?')) {
        const name = item.querySelector('.app-info h4').textContent;
        console.log('Reject application:', name);
        showNotification(`Candidature de ${name} refusée`, 'info');
        item.style.animation = 'fadeOut 0.3s ease-out';
        setTimeout(() => item.remove(), 300);
      }
    });
  });
}

// Setup quick actions
function setupQuickActions() {
  const actionCards = document.querySelectorAll('.action-card');
  
  actionCards.forEach(card => {
    card.addEventListener('click', function(e) {
      e.preventDefault();
      const href = this.getAttribute('href');
      console.log('Quick action:', href);
      
      // TODO: Navigate or open modal
      // window.location.href = href;
    });
  });
}

// Animate match scores (circular progress)
function animateMatchScores() {
  const matchCircles = document.querySelectorAll('.match-circle');
  
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const circle = entry.target.querySelector('circle:last-child');
        if (circle) {
          circle.style.transition = 'stroke-dashoffset 1s ease-out';
          circle.style.strokeDashoffset = circle.getAttribute('stroke-dashoffset');
        }
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.5 });

  matchCircles.forEach(circle => observer.observe(circle));
}

// Notification system
function showNotification(message, type = 'info') {
  const notification = document.createElement('div');
  notification.className = `notification notification-${type}`;
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
    z-index: 9999;
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
  }, 4000);
}
