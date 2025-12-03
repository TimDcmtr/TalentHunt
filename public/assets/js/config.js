// /public/assets/js/config.js

document.addEventListener('DOMContentLoaded', function () {
    
    // ============================================================
    // 1. NAVIGATION & UI
    // ============================================================

    // Navigation entre les sections (Tabs)
    const navItems = document.querySelectorAll('.config-nav .nav-item');
    const sections = document.querySelectorAll('.config-section');

    navItems.forEach(item => {
        item.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);

            // Gestion des classes active
            navItems.forEach(nav => nav.classList.remove('active'));
            sections.forEach(section => section.classList.remove('active'));

            this.classList.add('active');
            const targetSection = document.getElementById(targetId);
            if (targetSection) {
                targetSection.classList.add('active');
                targetSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Gestion des Checkboxes "Catégories" (Style visuel)
    const categoryCards = document.querySelectorAll('.category-card');
    categoryCards.forEach(card => {
        card.addEventListener('click', function (e) {
            // Empêche le double déclenchement si on clique directement sur la checkbox
            if (e.target.type !== 'checkbox') {
                const checkbox = this.querySelector('input[type="checkbox"]');
                checkbox.checked = !checkbox.checked;
            }
            updateProfileProgress();
        });
    });

    // ============================================================
    // 2. GESTION DES FICHIERS (AVATAR & CV)
    // ============================================================

    // Avatar Upload (Prévisualisation)
    const avatarInput = document.getElementById('avatar-file');
    if (avatarInput) {
        avatarInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                if (!file.type.startsWith('image/')) {
                    alert('Veuillez sélectionner une image');
                    return;
                }
                if (file.size > 5 * 1024 * 1024) { // 5MB
                    alert('La taille du fichier ne doit pas dépasser 5MB');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (event) {
                    const avatars = document.querySelectorAll('.avatar-large, .avatar-current');
                    avatars.forEach(avatar => {
                        // On gère les deux types d'affichage (img tag ou div background)
                        if(avatar.tagName === 'IMG') {
                            avatar.src = event.target.result;
                        } else {
                            // Nettoyage du span/texte existant
                            const span = avatar.querySelector('span');
                            if(span) span.style.display = 'none';
                            
                            avatar.style.backgroundImage = `url(${event.target.result})`;
                            avatar.style.backgroundSize = 'cover';
                            avatar.style.backgroundPosition = 'center';
                        }
                    });
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // CV Upload (Drag & Drop + Input)
    const cvUploadZone = document.querySelector('.cv-upload-zone');
    const cvInput = document.getElementById('cv-file');
    const cvCurrent = document.querySelector('.cv-current');

    if (cvUploadZone && cvInput) {
        // Empêcher les comportements par défaut
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            cvUploadZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        // Effets visuels
        ['dragenter', 'dragover'].forEach(eventName => {
            cvUploadZone.addEventListener(eventName, () => {
                cvUploadZone.style.borderColor = 'var(--primary)';
                cvUploadZone.style.background = 'rgba(99, 102, 241, 0.1)';
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            cvUploadZone.addEventListener(eventName, () => {
                cvUploadZone.style.borderColor = '';
                cvUploadZone.style.background = '';
            });
        });

        // Drop
        cvUploadZone.addEventListener('drop', function (e) {
            const files = e.dataTransfer.files;
            if (files.length) {
                // IMPORTANT : On assigne les fichiers droppés à l'input pour l'envoi
                cvInput.files = files; 
                handleCVUpload(files[0]);
            }
        });

        // Click / Change
        cvInput.addEventListener('change', function (e) {
            if (e.target.files.length) {
                handleCVUpload(e.target.files[0]);
            }
        });

        function handleCVUpload(file) {
            const allowedTypes = [
                'application/pdf', 
                'application/msword', 
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ];
            
            if (!allowedTypes.includes(file.type)) {
                alert('Format non supporté. PDF, DOC ou DOCX uniquement.');
                cvInput.value = ''; // Reset
                return;
            }

            if (file.size > 5 * 1024 * 1024) {
                alert('Fichier trop volumineux (Max 5MB)');
                cvInput.value = ''; // Reset
                return;
            }

            // Mise à jour de l'UI
            const fileName = file.name;
            const fileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';
            const uploadDate = new Date().toLocaleDateString('fr-FR');
            
            // Mise à jour du texte dans la zone de drop
            const labelH4 = document.getElementById('file-label');
            if(labelH4) labelH4.textContent = fileName;

            // Affichage de la carte "Fichier actuel" (simulation visuelle)
            /* Note: Le fichier n'est pas encore uploadé sur le serveur, 
               il le sera lors du submit du formulaire.
            */
            console.log('CV prêt pour l\'envoi:', fileName);
            updateProfileProgress();
        }
    }

    // ============================================================
    // 3. BARRE DE PROGRESSION & UTILITAIRES
    // ============================================================

    function updateProfileProgress() {
        let completedFields = 0;
        const totalFields = 10; // Valeur arbitraire pour l'exemple

        // Champs requis remplis
        const requiredFields = document.querySelectorAll('input[required], select[required], textarea[required]');
        requiredFields.forEach(field => {
            if (field.value.trim() !== '') completedFields++;
        });

        // Catégories
        const categoriesSelected = document.querySelectorAll('input[name="category[]"]:checked').length > 0;
        if (categoriesSelected) completedFields++;

        // CV
        const cvUploaded = cvCurrent && cvCurrent.style.display !== 'none';
        if (cvUploaded) completedFields++;

        // Calcul simple (à adapter selon ta logique exacte)
        const progress = Math.min(100, Math.round((completedFields / totalFields) * 100));

        const progressFill = document.querySelector('.progress-fill');
        const progressStatus = document.querySelector('.profile-status');

        if (progressFill) progressFill.style.width = progress + '%';
        if (progressStatus) progressStatus.textContent = `Profil à ${progress}%`;
    }

    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return null;
    }

    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        // Styles injectés dynamiquement pour éviter d'avoir besoin du CSS externe
        notification.style.cssText = `
            position: fixed; top: 100px; right: 20px; padding: 1rem 1.5rem;
            background: var(--glass-bg, rgba(255,255,255,0.9)); 
            backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);
            border-left: 4px solid ${type === 'success' ? '#10B981' : '#EF4444'};
            border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            color: var(--text-primary, #333); z-index: 9999;
            animation: slideIn 0.3s ease-out;
        `;

        notification.innerHTML = `
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <strong>${type === 'success' ? '✓' : '!'}</strong>
                <span>${message}</span>
            </div>
        `;

        document.body.appendChild(notification);
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Initialisation
    updateProfileProgress();

    // ============================================================
    // 4. SOUMISSION DES FORMULAIRES (API)
    // ============================================================

    const configForms = document.querySelectorAll('.config-form');

    configForms.forEach(form => {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            // 1. Vérification Token
            const token = getCookie('authToken');
            if (!token) {
                showNotification("Session expirée. Redirection...", "error");
                setTimeout(() => window.location.href = '/login', 1500);
                return;
            }

            const btn = this.querySelector('button[type="submit"]');
            const originalBtnText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="loader"></span> Enregistrement...';

            // 2. Détection du type de contenu (Fichier ou JSON)
            const isMultipart = this.enctype === 'multipart/form-data';
            const apiAction = this.getAttribute('data-api-action') || 'update_company'; // Fallback

            let bodyPayload;
            let headers = {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            };

            // 3. Construction des données
            if (isMultipart) {
                // Cas Upload (FormData brut pour les fichiers)
                // Le navigateur gère le Content-Type boundary automatiquement
                const formData = new FormData(this);
                bodyPayload = formData;
            } else {
                // Cas Classique (JSON)
                headers['Content-Type'] = 'application/json';
                
                const formData = new FormData(this);
                const data = {};
                
                // Gestion intelligente des tableaux (ex: category[], specialties[])
                for (let [key, value] of formData.entries()) {
                    if (key.endsWith('[]')) {
                        const cleanKey = key.slice(0, -2);
                        if (!data[cleanKey]) data[cleanKey] = [];
                        data[cleanKey].push(value);
                    } else {
                        data[key] = value;
                    }
                }
                bodyPayload = JSON.stringify(data);
            }

            try {
                // 4. Appel API
                const response = await fetch(`api?action=${apiAction}`, {
                    method: 'POST',
                    headers: headers,
                    body: bodyPayload
                });

                // Tentative de lecture JSON même en cas d'erreur HTTP
                let result;
                try {
                    result = await response.json();
                } catch (err) {
                    throw new Error("Erreur serveur (Format invalide)");
                }

                if (response.ok) {
                    showNotification('Enregistré avec succès !', 'success');
                    
                    // Si c'est un upload de fichier, on recharge pour voir le changement
                    if (apiAction === 'upload_cv') {
                        setTimeout(() => location.reload(), 1000);
                    }
                } else {
                    const msg = result.message || 'Erreur lors de la sauvegarde.';
                    showNotification(msg, 'error');
                }

            } catch (error) {
                console.error('Erreur Fetch:', error);
                showNotification('Erreur technique / Réseau', 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalBtnText;
            }
        });
    });

    // Auto-save draft (Optionnel - Sauvegarde locale)
    let autoSaveTimeout;
    const formInputs = document.querySelectorAll('.config-form input:not([type="file"]), .config-form textarea');
    formInputs.forEach(input => {
        input.addEventListener('input', function () {
            clearTimeout(autoSaveTimeout);
            autoSaveTimeout = setTimeout(() => {
                const formId = this.closest('form')?.getAttribute('data-api-action') || 'default';
                // On ne sauvegarde pas les mots de passe
                if(this.type !== 'password') {
                    // Logique simplifiée pour l'exemple
                    console.log(`Brouillon sauvegardé pour ${formId}`);
                }
            }, 2000);
        });
    });
});