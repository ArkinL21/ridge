document.addEventListener('DOMContentLoaded', () => {

  // ===============================
  // 1. Mobile Menu Toggle
  // ===============================
  const menuToggle = document.getElementById("menuToggle");
  const siteNav = document.getElementById("siteNav");

  if (menuToggle && siteNav) {
    menuToggle.addEventListener("click", () => {
      siteNav.classList.toggle("show");
    });
  }


  // ===============================
  // 2. Gallery Slideshow
  // ===============================
  const slides = document.querySelectorAll('.slideshow-container img.slide');
  const prevBtn = document.querySelector('.slideshow-container .prev');
  const nextBtn = document.querySelector('.slideshow-container .next');
  let index = 0;
  let autoSlide;

  function showSlide(i) {
    slides.forEach((slide, idx) => {
      slide.classList.toggle('active', idx === i);
    });
  }

  function nextSlide() {
    index = (index + 1) % slides.length;
    showSlide(index);
  }

  function prevSlide() {
    index = (index - 1 + slides.length) % slides.length;
    showSlide(index);
  }

  if (slides.length > 0) {
    showSlide(index);
    autoSlide = setInterval(nextSlide, 4000);

    if (nextBtn) {
      nextBtn.addEventListener('click', () => {
        nextSlide();
        clearInterval(autoSlide);
        autoSlide = setInterval(nextSlide, 4000);
      });
    }

    if (prevBtn) {
      prevBtn.addEventListener('click', () => {
        prevSlide();
        clearInterval(autoSlide);
        autoSlide = setInterval(nextSlide, 4000);
      });
    }
  }


  // ===============================
  // 3. Smooth Scroll for Anchor Links
  // ===============================
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const href = this.getAttribute('href');

      if (href.startsWith('#')) {
        e.preventDefault();
        const target = document.querySelector(href);

        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      }
    });
  });


  // ===============================
  // 4. Close Mobile Menu After Click
  // ===============================
  const navLinks = document.querySelectorAll('.site-nav a');

  navLinks.forEach(link => {
    link.addEventListener('click', () => {
      if (siteNav && siteNav.classList.contains('show')) {
        siteNav.classList.remove('show');
      }
    });
  });


  // ===============================
  // 5. Scroll Animations
  // ===============================
  const animateElements = document.querySelectorAll('.animate-on-scroll');

  if (animateElements.length > 0) {
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
        }
      });
    }, { threshold: 0.1 });

    animateElements.forEach(el => observer.observe(el));
  }

});