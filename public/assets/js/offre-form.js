// /public/assets/js/offre-form.js

let currentStep = 1;
const totalSteps = 4;

document.addEventListener('DOMContentLoaded', function() {
  setupStepNavigation();
  setupDynamicLists();
  setupPreview();
  setupAutoSave();
  loadDraft();
});

function setupStepNavigation() {
  const prevBtn = document.getElementById('prevBtn');
  const nextBtn = document.getElementById('nextBtn');
  const submitBtn = document.getElementById('submitBtn');
  const form = document.getElementById('offreForm');

  // Next button
  nextBtn?.addEventListener('click', function() {
    if (validateStep(currentStep)) {
      if (currentStep < totalSteps) {
        goToStep(currentStep + 1);
      }
    }
  });

  // Previous button
  prevBtn?.addEventListener('click', function() {
    if (currentStep > 1) {
      goToStep(currentStep - 1);
    }
  });

  // Form submission
  form?.addEventListener('submit', function(e) {
    e.preventDefault();

    if (!validateStep(currentStep)) {
      return;
    }

    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    // Convert arrays
    data.domains = formData.getAll('domains[]');
    data.missions = formData.getAll('missions[]');
    data.requirements = formData.getAll('requirements[]');
    data.benefits = formData.getAll('benefits[]');

    console.log('Form submitted:', data);

    // Show success message
    showSuccessModal();

    // TODO: Send to backend
  });

  // Click on progress steps
  const progressSteps = document.querySelectorAll('.progress-step');
  progressSteps.forEach(step => {
    step.addEventListener('click', function() {
      const stepNumber = parseInt(this.getAttribute('data-step'));
      if (stepNumber <= currentStep || this.classList.contains('completed')) {
        goToStep(stepNumber);
      }
    });
  });
}

function goToStep(stepNumber) {
  // Hide all steps
  document.querySelectorAll('.form-step').forEach(step => {
    step.classList.remove('active');
  });

  // Show target step
  const targetStep = document.querySelector(`.form-step[data-step="${stepNumber}"]`);
  if (targetStep) {
    targetStep.classList.add('active');
  }

  // Update progress
  document.querySelectorAll('.progress-step').forEach((step, index) => {
    step.classList.remove('active');
    if (index + 1 < stepNumber) {
      step.classList.add('completed');
    } else {
      step.classList.remove('completed');
    }
  });

  const currentProgressStep = document.querySelector(`.progress-step[data-step="${stepNumber}"]`);
  if (currentProgressStep) {
    currentProgressStep.classList.add('active');
  }

  // Update buttons
  const prevBtn = document.getElementById('prevBtn');
  const nextBtn = document.getElementById('nextBtn');
  const submitBtn = document.getElementById('submitBtn');

  prevBtn.style.display = stepNumber === 1 ? 'none' : 'flex';
  nextBtn.style.display = stepNumber === totalSteps ? 'none' : 'flex';
  submitBtn.style.display = stepNumber === totalSteps ? 'flex' : 'none';

  currentStep = stepNumber;

  // Scroll to top
  window.scrollTo({ top: 0, behavior: 'smooth' });

  // Update preview if on last step
  if (stepNumber === totalSteps) {
    updatePreview();
  }
}

function validateStep(step) {
  const currentStepEl = document.querySelector(`.form-step[data-step="${step}"]`);
  if (!currentStepEl) return false;

  const requiredInputs = currentStepEl.querySelectorAll('[required]');
  let isValid = true;

  requiredInputs.forEach(input => {
    if (input.type === 'checkbox') {
      // Check if at least one checkbox in group is checked
      if (input.name === 'domains[]') {
        const checkedDomains = currentStepEl.querySelectorAll('input[name="domains[]"]:checked');
        if (checkedDomains.length === 0) {
          showError('Veuillez sélectionner au moins un domaine');
          isValid = false;
        }
      } else if (!input.checked) {
        showError('Veuillez accepter les conditions');
        isValid = false;
      }
    } else if (!input.value.trim()) {
      input.style.borderColor = 'var(--accent)';
      isValid = false;
    } else {
      input.style.borderColor = '';
    }
  });

  if (!isValid) {
    showError('Veuillez remplir tous les champs obligatoires');
  }

  return isValid;
}

function setupDynamicLists() {
  const addButtons = document.querySelectorAll('.btn-add');

  addButtons.forEach(btn => {
    btn.addEventListener('click', function() {
      const target = this.getAttribute('data-target');
      const container = document.getElementById(`${target}-container`);
      
      if (container) {
        const listItem = document.createElement('div');
        listItem.className = 'list-item';
        listItem.innerHTML = `
          <input 
            type="text" 
            name="${target}[]" 
            class="form-input" 
            placeholder="${target === 'missions' ? 'Mission' : target === 'requirements' ? 'Compétence' : 'Avantage'}"
          >
          <button type="button" class="btn-remove">×</button>
        `;
        
        container.appendChild(listItem);

        // Add remove functionality
        const removeBtn = listItem.querySelector('.btn-remove');
        removeBtn.addEventListener('click', function() {
          listItem.remove();
        });

        // Show remove buttons if more than one item
        updateRemoveButtons(container);
      }
    });
  });
}

function updateRemoveButtons(container) {
  const items = container.querySelectorAll('.list-item');
  const removeBtns = container.querySelectorAll('.btn-remove');
  
  removeBtns.forEach((btn, index) => {
    btn.style.display = items.length > 1 ? 'block' : 'none';
  });
}

function setupPreview() {
  // Update preview in real-time
  const titleInput = document.getElementById('title');
  const locationInput = document.getElementById('location');
  const contractSelect = document.getElementById('contract_type');
  const remoteSelect = document.getElementById('remote');
  const salaryMinInput = document.getElementById('salary_min');
  const salaryMaxInput = document.getElementById('salary_max');

  titleInput?.addEventListener('input', function() {
    document.getElementById('preview-title').textContent = this.value || 'Titre du poste';
  });

  locationInput?.addEventListener('input', function() {
    document.getElementById('preview-location').textContent = '📍 ' + (this.value || 'Localisation');
  });

  contractSelect?.addEventListener('change', function() {
    const text = this.options[this.selectedIndex]?.text || 'Type de contrat';
    document.getElementById('preview-contract').textContent = '📄 ' + text;
  });

  remoteSelect?.addEventListener('change', function() {
    const text = this.options[this.selectedIndex]?.text || 'Télétravail';
    document.getElementById('preview-remote').textContent = '💻 ' + text;
  });

  function updateSalaryPreview() {
    const min = salaryMinInput?.value;
    const max = salaryMaxInput?.value;
    let text = 'Rémunération';
    
    if (min && max) {
      text = `${min}-${max}€/mois`;
    } else if (min) {
      text = `À partir de ${min}€/mois`;
    } else if (max) {
      text = `Jusqu'à ${max}€/mois`;
    }
    
    document.getElementById('preview-salary-range').textContent = text;
  }

  salaryMinInput?.addEventListener('input', updateSalaryPreview);
  salaryMaxInput?.addEventListener('input', updateSalaryPreview);
}

function updatePreview() {
  // Trigger all preview updates
  const titleInput = document.getElementById('title');
  if (titleInput) {
    titleInput.dispatchEvent(new Event('input'));
  }
}

function setupAutoSave() {
  const form = document.getElementById('offreForm');
  let autoSaveTimeout;

  form?.addEventListener('input', function() {
    clearTimeout(autoSaveTimeout);
    autoSaveTimeout = setTimeout(() => {
      saveDraft();
    }, 2000);
  });
}

function saveDraft() {
  const form = document.getElementById('offreForm');
  const formData = new FormData(form);
  const data = {};

  formData.forEach((value, key) => {
    if (key.endsWith('[]')) {
      if (!data[key]) data[key] = [];
      data[key].push(value);
    } else {
      data[key] = value;
    }
  });

  localStorage.setItem('talenthub_offre_draft', JSON.stringify(data));
  console.log('Draft auto-saved');
}

function loadDraft() {
  const draft = localStorage.getItem('talenthub_offre_draft');
  if (!draft) return;

  try {
    const data = JSON.parse(draft);
    
    // Fill form with draft data
    Object.keys(data).forEach(key => {
      if (key.endsWith('[]')) {
        // Handle arrays (checkboxes, dynamic lists)
        const values = data[key];
        if (Array.isArray(values)) {
          values.forEach((value, index) => {
            const input = document.querySelector(`input[name="${key}"]`);
            if (input && input.type === 'checkbox') {
              const checkbox = document.querySelector(`input[name="${key}"][value="${value}"]`);
              if (checkbox) checkbox.checked = true;
            }
          });
        }
      } else {
        const input = document.querySelector(`[name="${key}"]`);
        if (input) {
          input.value = data[key];
        }
      }
    });

    // Show notification
    showNotification('Brouillon chargé', 'info');
  } catch (e) {
    console.error('Error loading draft:', e);
  }
}

function showSuccessModal() {
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
    <div class="glass-card" style="max-width: 500px; width: 90%; padding: 2rem; text-align: center; animation: slideIn 0.3s ease-out;">
      <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: white; font-size: 2.5rem;">
        ✓
      </div>
      <h2 style="font-family: var(--font-heading); font-size: 1.8rem; margin-bottom: 1rem;">Offre publiée avec succès !</h2>
      <p style="color: var(--text-secondary); margin-bottom: 2rem; line-height: 1.6;">
        Votre offre est maintenant visible par tous les étudiants. Vous recevrez une notification dès qu'un candidat postulera.
      </p>
      <div style="display: flex; gap: 1rem; justify-content: center;">
        <a href="/pro/dashboard" class="btn-secondary">Retour au dashboard</a>
        <a href="/offre/1" class="btn-primary">Voir l'offre</a>
      </div>
    </div>
  `;

  document.body.appendChild(modal);

  // Clear draft
  localStorage.removeItem('talenthub_offre_draft');

  // Auto redirect after 5 seconds
  setTimeout(() => {
    window.location.href = '/pro/dashboard';
  }, 5000);
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
    border-left: 4px solid ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : 'var(--primary)'};
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
          : type === 'error'
          ? '<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>'
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

function showError(message) {
  showNotification(message, 'error');
}