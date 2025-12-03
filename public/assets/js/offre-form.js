document.addEventListener('DOMContentLoaded', function () {
    
    // --- 1. NAVIGATION MULTI-ÉTAPES ---
    let currentStep = 1;
    const totalSteps = 4;
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('offreForm');

    function updateStep(step) {
        // Validation avant de passer à l'étape suivante
        if (step > currentStep && !validateStep(currentStep)) return;

        currentStep = step;

        // Mise à jour visuelle (Sidebar)
        document.querySelectorAll('.progress-step').forEach(el => {
            const s = parseInt(el.dataset.step);
            el.classList.toggle('active', s === step);
            el.classList.toggle('completed', s < step);
        });

        // Affichage du contenu
        document.querySelectorAll('.form-step').forEach(el => {
            el.classList.toggle('active', parseInt(el.dataset.step) === step);
        });

        // Gestion des boutons
        prevBtn.style.display = step === 1 ? 'none' : 'flex';
        if (step === totalSteps) {
            nextBtn.style.display = 'none';
            submitBtn.style.display = 'flex';
            updatePreview(); // Mise à jour de l'aperçu à la fin
        } else {
            nextBtn.style.display = 'flex';
            submitBtn.style.display = 'none';
        }
    }

    // Boutons Suivant / Précédent
    nextBtn.addEventListener('click', () => updateStep(currentStep + 1));
    prevBtn.addEventListener('click', () => updateStep(currentStep - 1));

    // Clic sur la sidebar (Navigation directe)
    document.querySelectorAll('.progress-step').forEach(el => {
        el.addEventListener('click', () => {
            const targetStep = parseInt(el.dataset.step);
            // On ne peut pas sauter des étapes non validées
            if (targetStep < currentStep || validateStep(currentStep)) {
                updateStep(targetStep);
            }
        });
    });

    // Validation simple (Champs required)
    function validateStep(step) {
        const currentSection = document.querySelector(`.form-step[data-step="${step}"]`);
        const inputs = currentSection.querySelectorAll('input[required], select[required], textarea[required]');
        let isValid = true;

        inputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
                input.style.borderColor = '#ef4444';
                // Reset couleur au focus
                input.addEventListener('input', () => input.style.borderColor = '', {once: true});
            }
        });

        if (!isValid) {
            alert("Veuillez remplir tous les champs obligatoires.");
        }
        return isValid;
    }


    // --- 2. CHAMPS DYNAMIQUES (Missions, Requirements, Benefits) ---
    // Fonction générique pour ajouter un input
    document.querySelectorAll('.btn-add').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.dataset.target; // ex: 'missions'
            const container = document.getElementById(targetId + '-container');
            const placeholder = container.querySelector('input').placeholder;
            
            const div = document.createElement('div');
            div.className = 'list-item';
            div.innerHTML = `
                <input type="text" name="${targetId}[]" class="form-input" placeholder="${placeholder}">
                <button type="button" class="btn-remove">×</button>
            `;
            
            container.appendChild(div);
            
            // Focus sur le nouveau champ
            div.querySelector('input').focus();
        });
    });

    // Délégation d'événement pour le bouton Supprimer (×)
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove')) {
            const item = e.target.closest('.list-item');
            // On garde toujours au moins un champ
            if (item.parentElement.children.length > 1) {
                item.remove();
            } else {
                item.querySelector('input').value = ''; // Juste vider si c'est le dernier
            }
        }
    });


    // --- 3. PRÉVISUALISATION ---
    function updatePreview() {
        // Mapping simple ID Input -> ID Preview
        const map = {
            'title': 'preview-title',
            'location': 'preview-location',
            'contract_type': 'preview-contract',
            'remote': 'preview-remote'
        };

        for (const [inputId, previewId] of Object.entries(map)) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            if(input && preview) preview.innerText = input.value || 'Non spécifié';
        }

        // Salaire
        const min = document.getElementById('salary_min').value;
        const max = document.getElementById('salary_max').value;
        const salaryTxt = min && max ? `${min} - ${max} €` : (min ? `Dès ${min} €` : 'Non communiqué');
        document.getElementById('preview-salary-range').innerText = salaryTxt;
    }


    // --- 4. ENVOI DU FORMULAIRE (API) ---
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const token = getCookie('authToken');
        if (!token) {
            alert("Session expirée. Veuillez vous reconnecter.");
            window.location.href = '/login';
            return;
        }

        // UI Loading
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Publication...';

        // Construction des données JSON
        // On ne peut pas utiliser Object.fromEntries directement à cause des tableaux []
        const formData = new FormData(this);
        const data = {};

        for (let [key, value] of formData.entries()) {
            if (key.endsWith('[]')) {
                const cleanKey = key.slice(0, -2); // missions[] -> missions
                if (!data[cleanKey]) data[cleanKey] = [];
                if (value.trim()) data[cleanKey].push(value); // On n'envoie pas les lignes vides
            } else {
                data[key] = value;
            }
        }

        console.log("Payload:", data);

        try {
            const response = await fetch('api?action=create_offer', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (response.ok) {
                alert('Offre publiée avec succès ! 🚀');
                window.location.href = '/company/dashboard'; // Redirection
            } else {
                alert('Erreur : ' + (result.message || 'Problème technique'));
            }

        } catch (error) {
            console.error(error);
            alert('Erreur réseau.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });

    // Utilitaire Cookie
    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return null;
    }
});