document.addEventListener('DOMContentLoaded', function() {
    
    const applyBtn = document.getElementById('applyBtn');
    
    // --- FONCTION ONE-CLICK APPLY ---
    if (applyBtn) {
        applyBtn.addEventListener('click', async function() {
            
            // 1. Vérification Token (Connecté ?)
            const token = getCookie('authToken');
            if (!token) {
                showNotification("Vous devez être connecté pour postuler.", "error");
                // On attend 1.5s pour que l'utilisateur lise le message avant de rediriger
                setTimeout(() => window.location.href = '/login', 1500);
                return;
            }

            // 2. Gestion UI (Chargement)
            const originalHTML = this.innerHTML;
            this.disabled = true;
            this.innerHTML = 'Envoi...'; // Feedback immédiat
            this.style.opacity = '0.7';

            // 3. Récupération de l'ID de l'offre depuis l'attribut HTML
            const offreId = this.getAttribute('data-offre-id');

            // 4. Préparation des données
            const payload = {
                offre_id: offreId,
                cover_letter: "Candidature simplifiée (One-Click)", 
                availability: null
            };

            try {
                // 5. Appel API
                const response = await fetch('api?action=apply', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify(payload)
                });

                // On tente de lire la réponse JSON
                let result;
                try {
                    result = await response.json();
                } catch(e) {
                    throw new Error("Réponse serveur invalide");
                }

                if (response.ok) {
                    // --- SUCCÈS ---
                    showNotification('🚀 Candidature envoyée avec succès !', 'success');
                    
                    // On change le bouton définitivement pour montrer que c'est fait
                    applyBtn.innerHTML = `
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Déjà postulé
                    `;
                    applyBtn.classList.add('btn-success'); // Tu peux styliser ça en vert en CSS
                    applyBtn.style.backgroundColor = '#10b981'; // Vert (Tailwind emerald-500)
                    applyBtn.style.borderColor = '#10b981';
                    applyBtn.style.opacity = '1';
                } else {
                    // --- ERREUR LOGIQUE (ex: Déjà postulé) ---
                    const msg = result.message || 'Erreur lors de la candidature.';
                    
                    if (response.status === 409) {
                        showNotification('Vous avez déjà postulé à cette offre.', 'info');
                        applyBtn.innerHTML = 'Déjà candidaté';
                    } else {
                        showNotification(msg, 'error');
                        // On remet le bouton normal pour réessayer
                        applyBtn.disabled = false;
                        applyBtn.innerHTML = originalHTML;
                        applyBtn.style.opacity = '1';
                    }
                }

            } catch (error) {
                // --- ERREUR TECHNIQUE ---
                console.error(error);
                showNotification('Erreur de connexion. Réessayez.', 'error');
                applyBtn.disabled = false;
                applyBtn.innerHTML = originalHTML;
                applyBtn.style.opacity = '1';
            }
        });
    }

    // --- FAVORIS (Sauvegarder l'offre) ---
    const saveBtn = document.querySelector('.offre-sidebar .btn-secondary');
    if (saveBtn) {
        saveBtn.addEventListener('click', function() {
            this.classList.toggle('saved');
            const svg = this.querySelector('svg');
            
            if (this.classList.contains('saved')) {
                svg.setAttribute('fill', 'currentColor');
                // On met à jour le texte tout en gardant l'icône
                this.innerHTML = `
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2">
                        <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
                    </svg>
                    Sauvegardé
                `;
                showNotification('Offre ajoutée aux favoris', 'success');
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
        });
    }

    // --- ANALYTICS ---
    trackOfferView();
});

// ==========================================
// FONCTIONS UTILITAIRES
// ==========================================

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
    return null;
}

function trackOfferView() {
    const offreId = new URLSearchParams(window.location.search).get('id');
    if(offreId) console.log('View tracked for offer:', offreId);
}

// Système de notification joli et moderne
function showNotification(message, type = 'info') {
    // Supprime l'ancienne notif s'il y en a une
    const existing = document.querySelector('.custom-notification');
    if(existing) existing.remove();

    const notification = document.createElement('div');
    notification.className = 'custom-notification';
    
    let borderColor = 'var(--primary)';
    let icon = '<circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>';
    
    if (type === 'success') {
        borderColor = '#10b981'; // Vert
        icon = '<polyline points="20 6 9 17 4 12"/>';
    } else if (type === 'error') {
        borderColor = '#ef4444'; // Rouge
    }

    // CSS injecté directement pour éviter les dépendances
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(0,0,0,0.05);
        border-left: 4px solid ${borderColor};
        border-radius: 8px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        color: #333;
        z-index: 99999;
        font-family: 'Inter', sans-serif;
        font-weight: 500;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 12px;
        transform: translateX(120%);
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    `;
    
    notification.innerHTML = `
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="${borderColor}" stroke-width="2">
            ${icon}
        </svg>
        <span>${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    // Animation d'entrée
    requestAnimationFrame(() => {
        notification.style.transform = 'translateX(0)';
    });
    
    // Disparition auto
    setTimeout(() => {
        notification.style.transform = 'translateX(120%)';
        setTimeout(() => notification.remove(), 400);
    }, 4000);
}