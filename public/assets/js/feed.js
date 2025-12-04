// /public/assets/js/feed.js

document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    setupFilters();
    
    // Delete candidature (API Connected)
    setupDeleteButtons();
    
    // Check empty state
    checkEmptyState();

    // Sort functionality setup
    const sortSelect = document.querySelector('.sort-select-small');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            sortCandidatures(this.value);
        });
    }
});

// --- FILTERS & SORT ---

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
                const status = card.getAttribute('data-status');
                let isVisible = false;

                if (filter === 'all') {
                    isVisible = true;
                } else if (filter === 'pending') {
                    isVisible = ['pending', 'vue', 'entretien'].includes(status);
                } else if (filter === 'accepted') {
                    isVisible = ['accepted', 'accepte'].includes(status);
                } else if (filter === 'refused') {
                    isVisible = ['rejected', 'refuse'].includes(status);
                }

                if (isVisible) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Update tab count if needed (Optional UX improvement)
            // this.textContent = `${this.textContent.split('(')[0]} (${visibleCount})`;

            checkEmptyState();
        });
    });
}

function sortCandidatures(sortBy) {
    const container = document.querySelector('.candidatures-list');
    if(!container) return;
    
    const cards = Array.from(container.querySelectorAll('.candidature-card'));

    cards.sort((a, b) => {
        if (sortBy === 'recent') {
            const dateA = new Date(getDateFromCard(a));
            const dateB = new Date(getDateFromCard(b));
            return dateB - dateA;
        } else if (sortBy === 'old') {
            const dateA = new Date(getDateFromCard(a));
            const dateB = new Date(getDateFromCard(b));
            return dateA - dateB;
        }
        return 0;
    });

    // Re-append sorted cards
    cards.forEach(card => container.appendChild(card));
}

// Helper pour extraire la date proprement (format DD/MM/YYYY)
function getDateFromCard(card) {
    const dateStr = card.querySelector('.timeline-date')?.textContent || '';
    // Conversion "15/11/2024" -> "2024-11-15" pour le tri JS
    const parts = dateStr.split('/');
    if(parts.length === 3) return `${parts[2]}-${parts[1]}-${parts[0]}`;
    return new Date(); // Fallback
}

// --- DELETE FUNCTIONALITY (API) ---

function setupDeleteButtons() {
    const deleteBtns = document.querySelectorAll('.btn-delete');
    
    deleteBtns.forEach(btn => {
        btn.addEventListener('click', async function() {
            const candidatureId = this.getAttribute('data-id');
            const card = this.closest('.candidature-card');
            const offreName = card.querySelector('.candidature-info h3').textContent;

            // 1. Confirmation
            if (!confirm(`Êtes-vous sûr de vouloir retirer votre candidature pour "${offreName}" ? Cette action est irréversible.`)) {
                return;
            }

            // 2. Token Check
            const token = getCookie('authToken');
            if (!token) {
                showNotification("Session expirée. Veuillez vous reconnecter.", "error");
                setTimeout(() => window.location.href = '/login', 1500);
                return;
            }

            // 3. UI Loading state
            const originalHTML = this.innerHTML;
            this.disabled = true;
            this.innerHTML = '...';

            try {
                // 4. API Call
                const response = await fetch('api?action=withdraw_application', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify({ application_id: candidatureId })
                });

                const result = await response.json();

                if (response.ok) {
                    // 5. Success Animation & Removal
                    card.style.transition = 'all 0.3s ease';
                    card.style.opacity = '0';
                    card.style.transform = 'translateX(20px)';
                    
                    setTimeout(() => {
                        card.remove();
                        checkEmptyState();
                        showNotification('Candidature retirée avec succès', 'success');
                        // Update stats counters if they exist in DOM (Optional)
                        updateStatsCounters(); 
                    }, 300);
                } else {
                    showNotification(result.message || 'Erreur lors du retrait', 'error');
                    this.disabled = false;
                    this.innerHTML = originalHTML;
                }

            } catch (error) {
                console.error(error);
                showNotification('Erreur réseau. Vérifiez votre connexion.', 'error');
                this.disabled = false;
                this.innerHTML = originalHTML;
            }
        });
    });
}

function updateStatsCounters() {
    // Simple logic to decrease total counter visually
    const totalEl = document.querySelector('.stat-value-small'); // Cibler le premier compteur (Total)
    if(totalEl) {
        let current = parseInt(totalEl.textContent);
        if(!isNaN(current) && current > 0) totalEl.textContent = current - 1;
    }
}

// --- UTILS ---

function checkEmptyState() {
    const candidatureCards = document.querySelectorAll('.candidature-card');
    const visibleCards = Array.from(candidatureCards).filter(c => c.style.display !== 'none');
    const emptyState = document.querySelector('.empty-state');
    
    if(!emptyState) return;

    if (visibleCards.length === 0) {
        emptyState.style.display = 'flex'; // Flex pour centrer si ton CSS le prévoit
    } else {
        emptyState.style.display = 'none';
    }
}

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
    return null;
}

function showNotification(message, type = 'info') {
    // Remove existing
    const existing = document.querySelector('.custom-notification');
    if(existing) existing.remove();

    const notification = document.createElement('div');
    notification.className = 'custom-notification';
    
    let color = 'var(--primary)';
    if(type === 'success') color = '#10b981';
    if(type === 'error') color = '#ef4444';

    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0,0,0,0.1);
        border-left: 4px solid ${color};
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        color: #333;
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 10px;
        animation: slideIn 0.3s ease-out;
    `;
    
    notification.innerHTML = `
        <span>${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}