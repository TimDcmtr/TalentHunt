// /public/assets/js/entreprise-config.js

document.addEventListener('DOMContentLoaded', function() {

  // Character counter
  setupCharCounter();
  
  // Values management
  setupValuesManagement();
  
  // Media upload
  setupMediaUpload();
});

function setupCharCounter() {
  const shortDesc = document.getElementById('short_description');
  const charCount = document.querySelector('.char-count');

  if (shortDesc && charCount) {
    shortDesc.addEventListener('input', function() {
      const count = this.value.length;
      const max = this.maxLength;
      charCount.textContent = `${count}/${max}`;
      
      if (count > max * 0.9) {
        charCount.style.color = 'var(--accent)';
      } else {
        charCount.style.color = 'var(--text-muted)';
      }
    });
  }
}

function setupValuesManagement() {
  const addValueBtn = document.getElementById('addValue');
  const valuesContainer = document.getElementById('values-container');

  if (addValueBtn && valuesContainer) {
    addValueBtn.addEventListener('click', function() {
      const valueCard = document.createElement('div');
      valueCard.className = 'value-card';
      valueCard.innerHTML = `
        <input type="text" name="values[]" class="value-input" placeholder="Nouvelle valeur">
        <button type="button" class="btn-remove-value">×</button>
      `;

      valuesContainer.appendChild(valueCard);

      // Add remove functionality
      const removeBtn = valueCard.querySelector('.btn-remove-value');
      removeBtn.addEventListener('click', function() {
        valueCard.remove();
      });
    });

    // Add remove functionality to existing buttons
    valuesContainer.querySelectorAll('.btn-remove-value').forEach(btn => {
      btn.addEventListener('click', function() {
        this.closest('.value-card').remove();
      });
    });
  }
}

function setupMediaUpload() {
  const mediaInput = document.getElementById('media-files');
  const mediaGallery = document.querySelector('.media-gallery');

  if (mediaInput && mediaGallery) {
    mediaInput.addEventListener('change', function(e) {
      const files = Array.from(e.target.files);

      files.forEach(file => {
        // Validate
        const isImage = file.type.startsWith('image/');
        const isVideo = file.type.startsWith('video/');

        if (!isImage && !isVideo) {
          alert(`Format non supporté: ${file.name}`);
          return;
        }

        if (file.size > 10 * 1024 * 1024) {
          alert(`Fichier trop volumineux: ${file.name}`);
          return;
        }

        // Create preview
        const reader = new FileReader();
        reader.onload = function(event) {
          const mediaItem = document.createElement('div');
          mediaItem.className = 'media-item';

          if (isImage) {
            mediaItem.innerHTML = `
              <img src="${event.target.result}" alt="Media" style="width: 100%; height: 200px; object-fit: cover; border-radius: var(--radius-md);">
              <button class="btn-remove-media">×</button>
            `;
          } else if (isVideo) {
            mediaItem.innerHTML = `
              <video src="${event.target.result}" style="width: 100%; height: 200px; object-fit: cover; border-radius: var(--radius-md);" controls></video>
              <button class="btn-remove-media">×</button>
            `;
          }

          mediaGallery.appendChild(mediaItem);

          // Add remove functionality
          const removeBtn = mediaItem.querySelector('.btn-remove-media');
          removeBtn.addEventListener('click', function() {
            mediaItem.remove();
          });
        };

        reader.readAsDataURL(file);
      });

      console.log(`${files.length} média(s) ajouté(s)`);
      // TODO: Upload to backend
    });

    // Add remove functionality to existing buttons
    mediaGallery.querySelectorAll('.btn-remove-media').forEach(btn => {
      btn.addEventListener('click', function() {
        this.closest('.media-item').remove();
      });
    });
  }
}

// Form submission handlers
document.querySelectorAll('.config-form').forEach(form => {
  form.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    console.log('Form submitted:', data);

    // Show success notification
    showNotification('Modifications enregistrées avec succès', 'success');

    // TODO: Send to backend
  });
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
    border-left: 4px solid ${type === 'success' ? '#10b981' : 'var(--primary)'};
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
    notification.style.animation = 'fadeOut 0.3s ease-out';
    setTimeout(() => notification.remove(), 300);
  }, 3000);
}