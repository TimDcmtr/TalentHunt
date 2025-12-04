// /public/assets/js/feed.js

document.addEventListener('DOMContentLoaded', function() {
  // Filter functionality
  setupFilters();
  
  // Delete candidature
  setupDeleteButtons();
  
  // Check empty state
  checkEmptyState();
});

function setupFilters() {
  const filterTabs = document.querySelectorAll('.filter-tab');
  const candidatureCards = document.querySelectorAll('.candidature-card');
  const emptyState = document.querySelector('.empty-state');

  filterTabs.forEach(tab => {
    tab.addEventListener('click', function() {
      // Remove active from all tabs
      filterTabs.forEach(t => t.classList.remove('active'));
      
      // Add active to clicked tab
      this.classList.add('active');
      
      const filter = this.getAttribute('data-filter');
      let visibleCount = 0;

      // Filter candidatures
      candidatureCards.forEach(card => {
        if (filter === 'all' || card.getAttribute('data-status') === filter) {
          card.style.display = 'block';
          visibleCount++;
        } else {
          card.style.display = 'none';
        }
      });

      // Show/hide empty state
      if (visibleCount === 0) {
        if (emptyState) emptyState.style.display = 'block';
      } else {
        if (emptyState) emptyState.style.display = 'none';
      }
    });
  });

  // Sort functionality
  const sortSelect = document.querySelector('.sort-select-small');
  if (sortSelect) {
    sortSelect.addEventListener('change', function() {
      const sortBy = this.value;
      sortCandidatures(sortBy);
    });
  }
}

function sortCandidatures(sortBy) {
  const container = document.querySelector('.candidatures-list');
  const cards = Array.from(container.querySelectorAll('.candidature-card'));

  cards.sort((a, b) => {
    if (sortBy === 'recent') {
      // Sort by date (most recent first)
      const dateA = a.querySelector('.timeline-date')?.textContent || '';
      const dateB = b.querySelector('.timeline-date')?.textContent || '';
      return dateB.localeCompare(dateA);
    } else if (sortBy === 'old') {
      // Sort by date (oldest first)
      const dateA = a.querySelector('.timeline-date')?.textContent || '';
      const dateB = b.querySelector('.timeline-date')?.textContent || '';
      return dateA.localeCompare(dateB);
    } else if (sortBy === 'company') {
      // Sort alphabetically by company
      const companyA = a.querySelector('.candidature-info p').textContent;
      const companyB = b.querySelector('.candidature-info p').textContent;
      return companyA.localeCompare(companyB);
    }
    return 0;
  });

  // Re-append sorted cards
  cards.forEach(card => container.appendChild(card));
}

function setupDeleteButtons() {
  const deleteBtns = document.querySelectorAll('.btn-delete');
  
  deleteBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      const candidatureId = this.getAttribute('data-id');
      const card = this.closest('.candidature-card');
      const offreName = card.querySelector('.candidature-info h3').textContent;

      if (confirm(`Êtes-vous sûr de vouloir retirer votre candidature pour "${offreName}" ?`)) {
        // Animate removal
        card.style.animation = 'fadeOut 0.3s ease-out';
        
        setTimeout(() => {
          // 1) comportement visuel comme avant
          card.remove();
          checkEmptyState();
          showNotification('Candidature retirée', 'info');
          
          // 2) appel au backend pour supprimer en base
          fetch('/api.php?action=delete_application_simple&id=' + encodeURIComponent(candidatureId))
            .then(response => response.json())
            .then(data => {
              console.log('Résultat suppression BDD:', data);
            })
            .catch(error => {
              console.error('Erreur suppression BDD:', error);
            });
        }, 300);
      }
    });
  });
}

function checkEmptyState() {
  const candidatureCards = document.querySelectorAll('.candidature-card:not([style*="display: none"])');
  const emptyState = document.querySelector('.empty-state');
  
  if (candidatureCards.length === 0) {
    if (emptyState) emptyState.style.display = 'block';
  } else {
    if (emptyState) emptyState.style.display = 'none';
  }
}

// Add to calendar functionality
document.querySelectorAll('.candidature-actions button').forEach(btn => {
  if (btn.textContent.includes('calendrier')) {
    btn.addEventListener('click', function() {
      const card = this.closest('.candidature-card');
      const offreName = card.querySelector('.candidature-info h3').textContent;
      const company = card.querySelector('.candidature-info p').textContent;
      
      // Extract date from message if available
      const message = card.querySelector('.candidature-message p')?.textContent || '';
      
      // Create calendar event
      addToCalendar(offreName, company, message);
    });
  }
});

function addToCalendar(title, company, details) {
  // For demonstration - would integrate with Google Calendar API or similar
  const event = {
    title: `Entretien - ${title}`,
    description: `Entretien chez ${company}\n\n${details}`,
    location: company
  };
  
  console.log('Adding to calendar:', event);
  showNotification('Événement ajouté au calendrier', 'success');
  
  // TODO: Integrate with actual calendar API
}

// Download contract functionality
document.querySelectorAll('.candidature-actions button').forEach(btn => {
  if (btn.textContent.includes('contrat')) {
    btn.addEventListener('click', function() {
      const card = this.closest('.candidature-card');
      const offreName = card.querySelector('.candidature-info h3').textContent;
      
      console.log('Downloading contract for:', offreName);
      showNotification('Téléchargement du contrat...', 'info');
      
      // TODO: Implement actual download
      // This would typically fetch the contract from the backend
    });
  }
});

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
      card.remove();
      checkEmptyState();
      showNotification('Candidature retirée', 'info');

      // Appel simple au backend pour supprimer en base
      fetch('/delete_application.php?id=' + encodeURIComponent(candidatureId), {
          method: 'GET' // ou 'POST' si tu préfères
      })
      .then(r => r.text())
      .then(txt => {
          console.log('Suppression BDD OK:', txt);
      })
      .catch(err => {
          console.error('Erreur suppression BDD:', err);
      });

  }, 300);
}