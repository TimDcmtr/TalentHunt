// /public/assets/js/auth.js

document.addEventListener('DOMContentLoaded', function () {
  const tabs = document.querySelectorAll('.auth-tab');
  const forms = document.querySelectorAll('.auth-form');


  const params = new URLSearchParams(window.location.search);
  const tab = params.get("tab");

  if (tab) {
    const button = document.querySelector(`.auth-tab[data-tab="${tab}]"`);
    if (button) {
      button.click();
    }
  }

  // Switch between login and register forms
  tabs.forEach(tab => {
    tab.addEventListener('click', function () {
      const targetTab = this.getAttribute('data-tab');

      // Remove active class from all tabs and forms
      tabs.forEach(t => t.classList.remove('active'));
      forms.forEach(f => f.classList.remove('active'));

      // Add active class to clicked tab and corresponding form
      this.classList.add('active');
      document.getElementById(targetTab + 'Form').classList.add('active');
    });
  });

  // Form validation
  const loginForm = document.getElementById('loginForm');
  const registerForm = document.getElementById('registerForm');
  const registerENForm = document.getElementById('registerENForm');

  if (loginForm) {
    loginForm.addEventListener('submit', function (e) {
      e.preventDefault();

      const email = document.getElementById('login-email').value;
      const password = document.getElementById('login-password').value;

      // Validation basique
      if (!validateEmail(email)) {
        showError('login-email', 'Veuillez entrer un email valide');
        return;
      }

      if (password.length < 6) {
        showError('login-password', 'Le mot de passe doit contenir au moins 6 caractères');
        return;
      }

      // TODO: Envoyer au backend
      console.log('Login:', { email, password });
      // this.submit(); // Décommenter pour soumettre réellement
    });
  }

  if (registerForm) {
    registerForm.addEventListener('submit', function (e) {
      e.preventDefault();

      const formData = {
        firstname: document.getElementById('register-firstname').value,
        lastname: document.getElementById('register-lastname').value,
        email: document.getElementById('register-email').value,
        tel: document.getElementById('register-tel').value,
        password: document.getElementById('register-password').value,
        school: document.getElementById('register-school').value,
        region: document.getElementById('register-region').value,
        domain: document.getElementById('register-domain').value
      };

      // Validation
      if (!formData.firstname || !formData.lastname) {
        alert('Veuillez remplir tous les champs obligatoires');
        return;
      }

      if (!validateEmail(formData.email)) {
        showError('register-email', 'Veuillez entrer un email valide');
        return;
      }

      if (formData.password.length < 8) {
        showError('register-password', 'Le mot de passe doit contenir au moins 8 caractères');
        return;
      }

      // TODO: Envoyer au backend
      console.log('Register:', formData);
      // this.submit(); // Décommenter pour soumettre réellement
    });
  }
  if (registerENForm) {
    registerENForm.addEventListener('submit', function (e) {
      e.preventDefault();

      const formData = {
        entreprisename: document.getElementById('register-entreprisename').value,
        email: document.getElementById('register-email').value,
        tel: document.getElementById('register-tel').value,
        password: document.getElementById('register-password').value,
        region: document.getElementById('register-region').value,
        domain: document.getElementById('register-domain').value
      };

      // Validation
      if (!formData.entreprisename) {
        alert('Veuillez remplir tous les champs obligatoires');
        return;
      }

      if (!validateEmail(formData.email)) {
        showError('register-email', 'Veuillez entrer un email valide');
        return;
      }

      if (formData.password.length < 8) {
        showError('register-password', 'Le mot de passe doit contenir au moins 8 caractères');
        return;
      }

      // TODO: Envoyer au backend
      console.log('Register:', formData);
      // this.submit(); // Décommenter pour soumettre réellement
    });
  }


  // Email validation
  function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
  }

  // Show error message
  function showError(inputId, message) {
    const input = document.getElementById(inputId);
    input.style.borderColor = 'var(--accent)';

    // Remove existing error message
    const existingError = input.parentElement.querySelector('.error-message');
    if (existingError) {
      existingError.remove();
    }

    // Add new error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.style.color = 'var(--accent)';
    errorDiv.style.fontSize = '0.85rem';
    errorDiv.style.marginTop = '4px';
    errorDiv.textContent = message;
    input.parentElement.appendChild(errorDiv);

    // Remove error on input
    input.addEventListener('input', function () {
      this.style.borderColor = '';
      const error = this.parentElement.querySelector('.error-message');
      if (error) error.remove();
    }, { once: true });
  }

  // Password visibility toggle (optional enhancement)
  const passwordInputs = document.querySelectorAll('input[type="password"]');
  passwordInputs.forEach(input => {
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
    toggleBtn.style.transition = 'opacity 0.2s';

    toggleBtn.addEventListener('mouseenter', () => toggleBtn.style.opacity = '1');
    toggleBtn.addEventListener('mouseleave', () => toggleBtn.style.opacity = '0.6');

    toggleBtn.addEventListener('click', function () {
      const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
      input.setAttribute('type', type);
      this.innerHTML = type === 'password' ? '👁️' : '🙈';
    });

    wrapper.appendChild(toggleBtn);
  });

});




