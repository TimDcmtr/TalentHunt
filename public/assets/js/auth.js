document.addEventListener('DOMContentLoaded', function () {
  const tabs = document.querySelectorAll('.auth-tab');
  const forms = document.querySelectorAll('.auth-form');

  // --- 1. GESTION DES ONGLETS (TABS) ---
  tabs.forEach(tab => {
    tab.addEventListener('click', function () {
      const targetTab = this.getAttribute('data-tab');

      // Reset active classes
      tabs.forEach(t => t.classList.remove('active'));
      forms.forEach(f => f.classList.remove('active'));

      // Activate clicked tab
      this.classList.add('active');
      
      // Target form ID logic
      // Note: Assure-toi que les IDs dans ton HTML sont bien: loginForm, registerForm, registerENForm
      const targetForm = document.getElementById(targetTab + 'Form');
      if(targetForm) targetForm.classList.add('active');
    });
  });

  // --- 2. FONCTION D'ENVOI API GÉNÉRIQUE ---
  async function sendAuthRequest(action, data) {
    try {
      const response = await fetch(`api?action=${action}`, {
        method: 'POST', 
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify(data)
      });

      // On tente de lire le JSON même si le statut est une erreur (400, 404, etc.)
      const result = await response.json(); 
      
      return { 
        ok: response.ok, 
        status: response.status,
        data: result 
      };
    } catch (error) {
      console.error('Erreur réseau:', error);
      return { ok: false, data: { message: "Une erreur technique est survenue." } };
    }
  }

  // --- 3. GESTION DES FORMULAIRES ---

  const loginForm = document.getElementById('loginForm');
  const loginENForm = document.getElementById('loginENForm');
  const registerForm = document.getElementById('registerForm');
  const registerENForm = document.getElementById('registerENForm');

  // === LOGIN ===
  if (loginForm) {
    loginForm.addEventListener('submit', async function (e) {
      e.preventDefault();
      const btn = this.querySelector('button[type="submit"]');
      const originalBtnText = btn.innerHTML;

      // Sélection contextuelle pour éviter les conflits d'ID
      const email = this.querySelector('input[name="email"]').value;
      const password = this.querySelector('input[name="password"]').value;

      // Validation Frontend
      if (!validateEmail(email)) {
        showError(this.querySelector('input[name="email"]'), 'Veuillez entrer un email valide');
        return;
      }

      // Envoi Backend
      setLoading(btn, true);
      
      const response = await sendAuthRequest('login', { email, password });
      const data = JSON.parse(response.data);

      setLoading(btn, false, originalBtnText);

      if (await response.ok && await data.status === true) {
        document.cookie= `authToken=${data.token}`;
        window.location.href = '/offers'; // Ou index.php selon ta structure
      } else {
        showError(this.querySelector('input[name="password"]'), data.message || 'Identifiants incorrects');
      }
    });
  }

    // === LOGIN ENTREPRISE ===
  if (loginENForm) {
    loginForm.addEventListener('submit', async function (e) {
      e.preventDefault();
      const btn = this.querySelector('button[type="submit"]');
      const originalBtnText = btn.innerHTML;

      // Sélection contextuelle pour éviter les conflits d'ID
      const email = this.querySelector('input[name="email"]').value;
      const password = this.querySelector('input[name="password"]').value;

      // Validation Frontend
      if (!validateEmail(email)) {
        showError(this.querySelector('input[name="email"]'), 'Veuillez entrer un email valide');
        return;
      }

      // Envoi Backend
      setLoading(btn, true);
      
      const response = await sendAuthRequest('loginEN', { email, password });
      const data = JSON.parse(response.data);

      setLoading(btn, false, originalBtnText);

      if (await response.ok && await data.status === true) {
        document.cookie= `authToken=${data.token}`;
        window.location.href = '/company/dashboard'; // Ou index.php selon ta structure
      } else {
        showError(this.querySelector('input[name="password"]'), data.message || 'Identifiants incorrects');
      }
    });
  }

  // === REGISTER STUDENT ===
  if (registerForm) {
    registerForm.addEventListener('submit', async function (e) {
      e.preventDefault();
      const btn = this.querySelector('button[type="submit"]');
      const originalBtnText = btn.innerHTML;

      // Construction de l'objet data (Mapping HTML -> PHP Controller)
      // On utilise this.querySelector pour cibler les inputs DE CE formulaire
      const formData = {
        firstname: this.querySelector('input[name="firstname"]').value,
        lastname: this.querySelector('input[name="lastname"]').value,
        email: this.querySelector('input[name="email"]').value,
        tel: this.querySelector('input[name="tel"]')?.value || '', // tel -> phone (géré dans le controller)
        password: this.querySelector('input[name="password"]').value,
        school: this.querySelector('input[name="school"]')?.value || '',
        region: this.querySelector('select[name="region"]')?.value || '', // region -> location
        domain: this.querySelector('input[name="domain"]')?.value || '', // domain -> field_of_study
        type: 'student' // Utile si tu veux différencier plus tard
      };

      // Validation
      if (!validateEmail(formData.email)) {
        showError(this.querySelector('input[name="email"]'), 'Email invalide');
        return;
      }
      if (formData.password.length < 8) {
        showError(this.querySelector('input[name="password"]'), 'Min. 8 caractères');
        return;
      }

      // Envoi
      setLoading(btn, true);
      const response = await sendAuthRequest('register', formData);
      setLoading(btn, false, originalBtnText);

      if (response.ok) {
        alert("Compte créé avec succès ! Connectez-vous.");
        this.reset();
        document.querySelector('.auth-tab[data-tab="login"]').click();
      } else {
        alert("Erreur : " + (response.data.message || "Impossible de créer le compte"));
      }
    });
  }

  // === REGISTER COMPANY (Entreprise) ===
  if (registerENForm) {
    registerENForm.addEventListener('submit', async function (e) {
      e.preventDefault();
      const btn = this.querySelector('button[type="submit"]');
      const originalBtnText = btn.innerHTML;

      // Mapping spécifique pour l'entreprise
      // Le modèle User attend firstname/lastname. On adapte.
      const entreprisename = this.querySelector('input[name="firstname"]')?.value || ''; // ID html: register-entreprisename

      const formData = {
        firstname: entreprisename, // On met le nom de l'entreprise ici
        lastname: 'Entreprise',    // Valeur par défaut pour satisfaire la BDD
        email: this.querySelector('input[name="email"]').value,
        tel: this.querySelector('input[name="tel"]')?.value || '',
        password: this.querySelector('input[name="password"]').value,
        region: this.querySelector('select[name="region"]')?.value || '',
        domain: this.querySelector('input[name="domain"]')?.value || '',
        type: 'company'
      };

      // Validation
      if (!formData.firstname) {
        alert('Le nom de l\'entreprise est requis');
        return;
      }
      if (!validateEmail(formData.email)) {
        showError(this.querySelector('input[name="email"]'), 'Email invalide');
        return;
      }

      // Envoi
      setLoading(btn, true);
      const response = await sendAuthRequest('register', formData);
      setLoading(btn, false, originalBtnText);

      if (response.ok) {
        alert("Compte entreprise créé ! Connectez-vous.");
        this.reset();
        document.querySelector('.auth-tab[data-tab="login"]').click();
      } else {
        alert("Erreur : " + (response.data.message || "Impossible de créer le compte"));
      }
    });
  }

  // --- 4. UTILITAIRES ---

  function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
  }

  // Gestion UI du bouton pendant le chargement
  function setLoading(btn, isLoading, originalText = '') {
    if (isLoading) {
      btn.disabled = true;
      btn.innerHTML = '<span class="loader"></span> Chargement...'; // Tu peux ajouter du CSS pour .loader
    } else {
      btn.disabled = false;
      btn.innerHTML = originalText;
    }
  }

  // Affiche l'erreur sous l'input
  function showError(inputElement, message) {
    if(!inputElement) return;

    inputElement.style.borderColor = 'var(--accent, #ff4d4f)';

    // Supprime l'ancien message s'il existe
    const parent = inputElement.parentElement;
    const existingError = parent.querySelector('.error-message');
    if (existingError) {
      existingError.remove();
    }

    // Ajoute le nouveau message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.style.color = 'var(--accent, #ff4d4f)';
    errorDiv.style.fontSize = '0.85rem';
    errorDiv.style.marginTop = '4px';
    errorDiv.textContent = message;
    parent.appendChild(errorDiv);

    // Retire l'erreur quand l'utilisateur tape
    inputElement.addEventListener('input', function () {
      this.style.borderColor = '';
      const error = this.parentElement.querySelector('.error-message');
      if (error) error.remove();
    }, { once: true });
  }

  // Toggle Password Visibility
  const passwordInputs = document.querySelectorAll('input[type="password"]');
  passwordInputs.forEach(input => {
    // Création du wrapper si pas déjà présent (pour éviter duplication si script rechargé)
    if(input.parentNode.style.position === 'relative') return;

    const wrapper = document.createElement('div');
    wrapper.style.position = 'relative';
    input.parentNode.insertBefore(wrapper, input);
    wrapper.appendChild(input);

    const toggleBtn = document.createElement('button');
    toggleBtn.type = 'button';
    toggleBtn.innerHTML = '👁️';
    toggleBtn.style.position = 'absolute';
    toggleBtn.style.right = '10px';
    toggleBtn.style.top = '50%';
    toggleBtn.style.transform = 'translateY(-50%)';
    toggleBtn.style.background = 'none';
    toggleBtn.style.border = 'none';
    toggleBtn.style.cursor = 'pointer';
    toggleBtn.style.fontSize = '1.2rem';
    toggleBtn.style.opacity = '0.6';

    toggleBtn.addEventListener('click', function () {
      const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
      input.setAttribute('type', type);
      this.innerHTML = type === 'password' ? '👁️' : '🙈';
    });

    wrapper.appendChild(toggleBtn);
  });

  // Gestion de l'URL parameter ?tab=register
  const params = new URLSearchParams(window.location.search);
  const tab = params.get("tab");
  if (tab) {
    const button = document.querySelector(`.auth-tab[data-tab="${tab}"]`);
    if (button) button.click();
  }
});