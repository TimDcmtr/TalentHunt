// /public/assets/js/config.js

document.addEventListener('DOMContentLoaded', function () {
  // Navigation between sections
  const navItems = document.querySelectorAll('.config-nav .nav-item');
  const sections = document.querySelectorAll('.config-section');

  navItems.forEach(item => {
    item.addEventListener('click', function (e) {
      e.preventDefault();

      const targetId = this.getAttribute('href').substring(1);

      // Remove active class from all nav items and sections
      navItems.forEach(nav => nav.classList.remove('active'));
      sections.forEach(section => section.classList.remove('active'));

      // Add active class to clicked nav item and target section
      this.classList.add('active');
      const targetSection = document.getElementById(targetId);
      if (targetSection) {
        targetSection.classList.add('active');

        // Scroll to section
        targetSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });

  // Avatar upload
  const avatarInput = document.getElementById('avatar-file');
  if (avatarInput) {
    avatarInput.addEventListener('change', function (e) {
      const file = e.target.files[0];
      if (file) {
        // Validate file
        if (!file.type.startsWith('image/')) {
          alert('Veuillez sélectionner une image');
          return;
        }

        if (file.size > 5 * 1024 * 1024) {
          alert('La taille du fichier ne doit pas dépasser 5MB');
          return;
        }

        // Preview avatar
        const reader = new FileReader();
        reader.onload = function (event) {
          const avatars = document.querySelectorAll('.avatar-large, .avatar-current');
          avatars.forEach(avatar => {
            avatar.style.backgroundImage = `url(${event.target.result})`;
            avatar.style.backgroundSize = 'cover';
            avatar.style.backgroundPosition = 'center';
            avatar.innerHTML = '';
          });
        };
        reader.readAsDataURL(file);

        console.log('Avatar uploaded:', file.name);
      }
    });
  }

  // CV upload with drag and drop
  const cvUploadZone = document.querySelector('.cv-upload-zone');
  const cvInput = document.getElementById('cv-file');
  const cvCurrent = document.querySelector('.cv-current');

  if (cvUploadZone && cvInput) {
    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
      cvUploadZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
      e.preventDefault();
      e.stopPropagation();
    }

    // Highlight drop zone when dragging over
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

    // Handle dropped files
    cvUploadZone.addEventListener('drop', function (e) {
      const files = e.dataTransfer.files;
      if (files.length) {
        handleCVUpload(files[0]);
      }
    });

    // Handle file input change
    cvInput.addEventListener('change', function (e) {
      if (e.target.files.length) {
        handleCVUpload(e.target.files[0]);
      }
    });

    function handleCVUpload(file) {
      // Validate file
      const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
      if (!allowedTypes.includes(file.type)) {
        alert('Format de fichier non supporté. Veuillez utiliser PDF, DOC ou DOCX');
        return;
      }

      if (file.size > 5 * 1024 * 1024) {
        alert('La taille du fichier ne doit pas dépasser 5MB');
        return;
      }

      // Update UI
      const fileName = file.name;
      const fileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';
      const uploadDate = new Date().toLocaleDateString('fr-FR');

      if (cvCurrent) {
        cvCurrent.style.display = 'block';
        cvCurrent.querySelector('.cv-file-info h4').textContent = fileName;
        cvCurrent.querySelector('.cv-file-info p').textContent = `Uploadé le ${uploadDate} • ${fileSize}`;
      }

      console.log('CV uploaded:', fileName);
      // TODO: Upload to server
    }
  }

  // Category selection
  const categoryCards = document.querySelectorAll('.category-card');
  categoryCards.forEach(card => {
    card.addEventListener('click', function () {
      const checkbox = this.querySelector('input[type="checkbox"]');
      checkbox.checked = !checkbox.checked;

      // Update progress
      updateProfileProgress();
    });
  });

  // Profile progress calculation
  function updateProfileProgress() {
    let completedFields = 0;
    const totalFields = 10;

    // Check required fields
    const requiredFields = document.querySelectorAll('input[required], select[required]');
    requiredFields.forEach(field => {
      if (field.value.trim() !== '') {
        completedFields++;
      }
    });

    // Check categories (at least one selected)
    const categoriesSelected = document.querySelectorAll('.category-card input:checked').length > 0;
    if (categoriesSelected) completedFields++;

    // Check CV uploaded
    const cvUploaded = cvCurrent && cvCurrent.style.display !== 'none';
    if (cvUploaded) completedFields++;

    // Calculate percentage
    const progress = Math.round((completedFields / totalFields) * 100);

    // Update progress bar
    const progressFill = document.querySelector('.progress-fill');
    const progressStatus = document.querySelector('.profile-status');

    if (progressFill) {
      progressFill.style.width = progress + '%';
    }

    if (progressStatus) {
      progressStatus.textContent = `Profil à ${progress}%`;
    }
  }

  // 1. Fonction utilitaire pour récupérer un cookie par son nom
  function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
    return null;
  }

  const configForms = document.querySelectorAll('.config-form');

  configForms.forEach(form => {
    form.addEventListener('submit', async function (e) {
      e.preventDefault();

      // --- A. Récupération du Token ---
      const token = getCookie('authToken');
      if (!token) {
        alert("Votre session a expiré. Veuillez vous reconnecter.");
        window.location.href = '/login'; // Redirection si pas de token
        return;
      }

      // --- B. Préparation des Données (Gestion des tableaux []) ---
      const formData = new FormData(this);
      const data = {};

      // On itère manuellement pour gérer les champs comme 'values[]' ou 'specialties[]'
      for (let [key, value] of formData.entries()) {
        // Si la clé finit par [], c'est un tableau (ex: specialties[])
        if (key.endsWith('[]')) {
          const cleanKey = key.slice(0, -2); // On enlève les [] du nom pour le JSON

          if (!data[cleanKey]) {
            data[cleanKey] = [];
          }
          data[cleanKey].push(value);
        } else {
          // Champ standard
          data[key] = value;
        }
      }

      console.log('Données envoyées :', data);

      // --- C. Interface Utilisateur (Chargement) ---
      const btn = this.querySelector('button[type="submit"]');
      const originalBtnText = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML = 'Enregistrement...';

      const apiAction = this.getAttribute('data-api-action') || 'updateCompany';

      try {
        const response = await fetch(`api?action=${apiAction}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}` // Le Token est ici !
          },
          body: JSON.stringify(data)
        });

        // Gestion de la réponse (même si erreur 4xx ou 5xx, on tente de lire le JSON)
        let result;
        try {
          result = await response.json();
        } catch (err) {
          throw new Error("Réponse serveur invalide (HTML reçu au lieu de JSON)");
        }

        if (response.ok) {
          // Succès (200, 201)
          if (typeof showNotification === 'function') {
            showNotification('Modifications enregistrées avec succès !', 'success');
            location.reload();
          } else {
            alert('Modifications enregistrées !');
          }
        } else {
          // Erreur API (400, 401, 500)
          const msg = result.message || 'Une erreur est survenue lors de la sauvegarde.';
          if (typeof showNotification === 'function') {
            showNotification(msg, 'error');
          } else {
            alert('Erreur : ' + msg);
          }
        }

      } catch (error) {
        console.error('Erreur Fetch:', error);
        if (typeof showNotification === 'function') {
          showNotification('Erreur réseau ou serveur.', 'error');
        } else {
          alert('Erreur technique. Vérifiez la console.');
        }
      } finally {
        // --- E. Restauration du bouton ---
        btn.disabled = false;
        btn.innerHTML = originalBtnText;
      }
    });
  });

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
      border-left: 4px solid ${type === 'success' ? 'var(--primary)' : 'var(--accent)'};
      border-radius: var(--radius-md);
      box-shadow: var(--shadow-lg);
      color: var(--text-primary);
      z-index: 9999;
      animation: slideIn 0.3s ease-out;
    `;

    notification.innerHTML = `
      <div style="display: flex; align-items: center; gap: 0.75rem;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          ${type === 'success'
        ? '<polyline points="20 6 9 17 4 12"/>'
        : '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>'
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

  // Initialize progress on load
  updateProfileProgress();

  // Auto-save draft (optional)
  let autoSaveTimeout;
  const formInputs = document.querySelectorAll('.config-form input, .config-form select, .config-form textarea');

  formInputs.forEach(input => {
    input.addEventListener('input', function () {
      clearTimeout(autoSaveTimeout);
      autoSaveTimeout = setTimeout(() => {
        // Save draft to localStorage
        const formId = this.closest('form')?.id || 'default';
        const formData = new FormData(this.closest('form'));
        const data = Object.fromEntries(formData);
        localStorage.setItem(`talenthub_draft_${formId}`, JSON.stringify(data));
        console.log('Draft auto-saved');
      }, 2000);
    });
  });
});