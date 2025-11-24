// /public/assets/js/etudiant.js

document.addEventListener('DOMContentLoaded', function() {
  // Bookmark functionality
  const bookmarkBtns = document.querySelectorAll('.btn-bookmark');
  
  bookmarkBtns.forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.stopPropagation();
      this.classList.toggle('bookmarked');
      
      const svg = this.querySelector('svg');
      if (this.classList.contains('bookmarked')) {
        svg.setAttribute('fill', 'currentColor');
        // TODO: Save to backend
        console.log('Job bookmarked');
      } else {
        svg.setAttribute('fill', 'none');
        // TODO: Remove from backend
        console.log('Job unbookmarked');
      }
    });
  });

  // Filter reset
  const resetBtn = document.querySelector('.btn-reset');
  if (resetBtn) {
    resetBtn.addEventListener('click', function() {
      const checkboxes = document.querySelectorAll('.filter-checkbox input[type="checkbox"]');
      checkboxes.forEach(checkbox => checkbox.checked = false);
      
      const inputs = document.querySelectorAll('.filter-section input[type="text"], .filter-section select');
      inputs.forEach(input => input.value = '');
      
      // TODO: Reload jobs without filters
      console.log('Filters reset');
    });
  }

  // Search functionality
  const searchInput = document.querySelector('.search-input');
  const searchBtn = document.querySelector('.search-bar .btn-primary');
  
  if (searchBtn) {
    searchBtn.addEventListener('click', function() {
      performSearch();
    });
  }

  if (searchInput) {
    searchInput.addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        performSearch();
      }
    });
  }

  function performSearch() {
    const query = searchInput.value.trim();
    if (query) {
      // TODO: Send search query to backend
      console.log('Searching for:', query);
    }
  }

  // Filter application
  const applyFiltersBtn = document.querySelector('.sidebar .btn-primary');
  if (applyFiltersBtn) {
    applyFiltersBtn.addEventListener('click', function() {
      const filters = collectFilters();
      // TODO: Apply filters to job list
      console.log('Applying filters:', filters);
    });
  }

  function collectFilters() {
    const filters = {
      contracts: [],
      location: '',
      domain: '',
      remote: []
    };

    // Collect contract types
    document.querySelectorAll('input[name="contract"]:checked').forEach(input => {
      filters.contracts.push(input.value);
    });

    // Collect location
    const locationInput = document.querySelector('.filter-section input[type="text"]');
    if (locationInput) {
      filters.location = locationInput.value;
    }

    // Collect domain
    const domainSelect = document.querySelector('.filter-section select');
    if (domainSelect) {
      filters.domain = domainSelect.value;
    }

    // Collect remote preferences
    document.querySelectorAll('input[name="remote"]:checked').forEach(input => {
      filters.remote.push(input.value);
    });

    return filters;
  }

  // Job card click
  const jobCards = document.querySelectorAll('.job-card');
  jobCards.forEach(card => {
    card.addEventListener('click', function(e) {
      // Don't navigate if clicking bookmark button
      if (e.target.closest('.btn-bookmark')) {
        return;
      }
      
      const viewBtn = this.querySelector('.btn-view');
      if (viewBtn) {
        window.location.href = viewBtn.getAttribute('href');
      }
    });
  });

  // Sort functionality
  const sortSelect = document.querySelector('.sort-select');
  if (sortSelect) {
    sortSelect.addEventListener('change', function() {
      const sortBy = this.value;
      // TODO: Re-sort jobs based on selection
      console.log('Sorting by:', sortBy);
    });
  }

  // Pagination
  const paginationBtns = document.querySelectorAll('.pagination-btn:not([disabled])');
  paginationBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      if (this.textContent.trim() === '') return; // Skip arrow buttons for now
      
      // Remove active class from all buttons
      paginationBtns.forEach(b => b.classList.remove('active'));
      
      // Add active class to clicked button
      this.classList.add('active');
      
      const page = this.textContent.trim();
      // TODO: Load jobs for selected page
      console.log('Loading page:', page);
      
      // Scroll to top
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  });

  // Tag click filtering
  const tags = document.querySelectorAll('.tag');
  tags.forEach(tag => {
    tag.addEventListener('click', function(e) {
      e.stopPropagation();
      const tagText = this.textContent;
      
      // Add tag to search
      if (searchInput) {
        searchInput.value = tagText;
        performSearch();
      }
    });
  });

  // Auto-save filters in localStorage
  const filterInputs = document.querySelectorAll('.filter-section input, .filter-section select');
  filterInputs.forEach(input => {
    input.addEventListener('change', function() {
      const filters = collectFilters();
      localStorage.setItem('talenthub_filters', JSON.stringify(filters));
    });
  });

  // Load saved filters on page load
  const savedFilters = localStorage.getItem('talenthub_filters');
  if (savedFilters) {
    try {
      const filters = JSON.parse(savedFilters);
      
      // Apply saved contract filters
      filters.contracts.forEach(contract => {
        const checkbox = document.querySelector(`input[name="contract"][value="${contract}"]`);
        if (checkbox) checkbox.checked = true;
      });

      // Apply saved remote filters
      filters.remote.forEach(remote => {
        const checkbox = document.querySelector(`input[name="remote"][value="${remote}"]`);
        if (checkbox) checkbox.checked = true;
      });

      // Apply saved location
      if (filters.location) {
        const locationInput = document.querySelector('.filter-section input[type="text"]');
        if (locationInput) locationInput.value = filters.location;
      }

      // Apply saved domain
      if (filters.domain) {
        const domainSelect = document.querySelector('.filter-section select');
        if (domainSelect) domainSelect.value = filters.domain;
      }
    } catch (e) {
      console.error('Error loading saved filters:', e);
    }
  }
});