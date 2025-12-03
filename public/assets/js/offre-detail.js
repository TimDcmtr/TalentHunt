document.addEventListener('DOMContentLoaded', function() {
    
    const applyBtn = document.getElementById('applyBtn');
    
    // --- FONCTION ONE-CLICK APPLY ---
    if (applyBtn) {
        applyBtn.addEventListener('click', async function() {
            
            // 1. Vérification Token (Connecté ?)
            const token = getCookie('authToken');
            if (!token) {
                // Si pas connecté, on redirige ou on notifie
                showNotification("Vous devez être connecté pour postuler.", "error");
                setTimeout(() => window.location.href = '/login', 1500);
                return;
            }

            // 2. Gestion UI (Chargement)
            const originalText = this.innerHTML;
            this.disabled = true;
            this.innerHTML = '<span class="loader-small"></span> Envoi...'; // Tu peux mettre juste "Envoi..."

            // 3. Récupération de l'ID de l'offre depuis l'attribut HTML
            const offreId = this.getAttribute('data-offre-id');

            // 4. Préparation des données (Minimaliste)
            // Le backend ApplicationController attend 'offre_id' et 'user_id' (ajouté par l'API via le token)
            // On peut envoyer un message vide ou null pour cover_letter
            const payload = {
                offre_id: offreId,
                cover_letter: "Candidature rapide", 
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

                let result;
                try {
                    result = await response.json();
                } catch(e) {
                    throw new Error("Erreur serveur (Réponse invalide)");
                }

                if (response.ok) {
                    // SUCCÈS
                    showNotification('🚀 Candidature envoyée avec succès !', 'success');
                    
                    // On change le bouton définitivement
                    applyBtn.innerHTML = `
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Candidature envoyée
                    `;
                    applyBtn.classList.add('btn-success'); // Tu peux styliser cette classe en vert
                } else {
                    // ERREUR (ex: Déjà postulé, Erreur 409)
                    const msg = result.message || 'Erreur lors de la candidature.';
                    showNotification(msg, 'info'); // 'info' car souvent c'est "Déjà postulé"
                    
                    // On remet le bouton normal
                    applyBtn.disabled = false;
                    applyBtn.innerHTML = originalText;
                }

            } catch (error) {
                console.error(error);
                showNotification('Erreur technique. Vérifiez votre connexion.', 'error');
                applyBtn.disabled = false;
                applyBtn.innerHTML = originalText;
            }
        });
    }

    // --- SAVE OFFER (Favoris) ---
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
            // TODO: Appel API pour sauvegarder
        });
    }

    // --- ANALYTICS ---
    trackOfferView();
});

// --- UTILITAIRES ---

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
    return null;
}

function trackOfferView() {
    // Logique analytics simple
    const offreId = window.location.search.split('id=')[1];
    if(offreId) console.log('View tracked for offer:', offreId);
}

// Système de notification (Style Tailwind/Glassmorphism)
function showNotification(message, type = 'info') {
    // Supprime l'ancienne notif s'il y en a une pour éviter l'empilement
    const existing = document.querySelector('.custom-notification');
    if(existing) existing.remove();

    const notification = document.createElement('div');
    notification.className = 'custom-notification'; // Classe pour ciblage facile
    
    // Couleurs selon le type
    let borderColor = 'var(--primary)';
    let icon = '<circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>';
    
    if (type === 'success') {
        borderColor = '#10b981'; // Vert
        icon = '<polyline points="20 6 9 17 4 12"/>';
    } else if (type === 'error') {
        borderColor = '#ef4444'; // Rouge
    }

    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px; /* En haut à droite c'est plus standard */
        padding: 1rem 1.5rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0,0,0,0.1);
        border-left: 4px solid ${borderColor};
        border-radius: 8px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        color: #333;
        z-index: 99999;
        font-family: 'Inter', sans-serif;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 12px;
        transform: translateX(100%);
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    `;
    
    notification.innerHTML = `
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="${borderColor}" stroke-width="2">
            ${icon}
        </svg>
        <span>${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    // Animation d'entrée (petit délai pour laisser le DOM se mettre à jour)
    requestAnimationFrame(() => {
        notification.style.transform = 'translateX(0)';
    });
    
    // Disparition automatique
    setTimeout(() => {
        notification.style.transform = 'translateX(120%)';
        setTimeout(() => notification.remove(), 300);
    }, 4000);
}