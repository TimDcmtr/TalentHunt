// /public/assets/js/navbar.js

document.addEventListener('DOMContentLoaded', function() {
  const mobileMenuToggle = document.getElementById('mobileMenuToggle');
  const navbarMenu = document.getElementById('navbarMenu');

  if (mobileMenuToggle && navbarMenu) {
    mobileMenuToggle.addEventListener('click', function() {
      this.classList.toggle('active');
      navbarMenu.classList.toggle('active');
      
      // Empêcher le scroll quand le menu est ouvert
      if (navbarMenu.classList.contains('active')) {
        document.body.style.overflow = 'hidden';
      } else {
        document.body.style.overflow = '';
      }
    });

    // Fermer le menu en cliquant sur un lien
    const navLinks = navbarMenu.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
      link.addEventListener('click', function() {
        mobileMenuToggle.classList.remove('active');
        navbarMenu.classList.remove('active');
        document.body.style.overflow = '';
      });
    });

    // Fermer le menu en cliquant en dehors
    document.addEventListener('click', function(event) {
      const isClickInsideNav = navbarMenu.contains(event.target) || 
                               mobileMenuToggle.contains(event.target);
      
      if (!isClickInsideNav && navbarMenu.classList.contains('active')) {
        mobileMenuToggle.classList.remove('active');
        navbarMenu.classList.remove('active');
        document.body.style.overflow = '';
      }
    });
  }

  // Effet de scroll sur la navbar
  let lastScroll = 0;
  const navbar = document.querySelector('.navbar');

  window.addEventListener('scroll', function() {
    const currentScroll = window.pageYOffset;

    if (currentScroll <= 0) {
      navbar.style.transform = 'translateY(0)';
      navbar.style.boxShadow = 'var(--shadow-md)';
      return;
    }

    if (currentScroll > lastScroll && currentScroll > 100) {
      // Scroll vers le bas
      navbar.style.transform = 'translateY(-100%)';
    } else {
      // Scroll vers le haut
      navbar.style.transform = 'translateY(0)';
      navbar.style.boxShadow = '0 8px 32px 0 rgba(31, 38, 135, 0.5)';
    }

    lastScroll = currentScroll;
  });

  // Highlight active page
  const currentPath = window.location.pathname;
  const navLinks = document.querySelectorAll('.nav-link');
  
  navLinks.forEach(link => {
    if (link.getAttribute('href') === currentPath) {
      link.style.color = 'var(--primary-light)';
      link.style.fontWeight = '600';
    }
  });
});