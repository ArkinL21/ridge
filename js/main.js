document.addEventListener('DOMContentLoaded', () => {
  // ===============================
  // 1️⃣ Gallery Slideshow
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
    autoSlide = setInterval(nextSlide, 4000); // Auto slide every 4s

    nextBtn.addEventListener('click', () => {
      nextSlide();
      clearInterval(autoSlide);
      autoSlide = setInterval(nextSlide, 4000);
    });

    prevBtn.addEventListener('click', () => {
      prevSlide();
      clearInterval(autoSlide);
      autoSlide = setInterval(nextSlide, 4000);
    });
  }

  // ===============================
  // 2️⃣ Smooth Scroll for Anchors
  // ===============================
  document.querySelectorAll('a[href^="#"], a[href*=".html"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const href = this.getAttribute('href');
      if (href.startsWith('#')) {
        e.preventDefault();
        const target = document.querySelector(href);
        if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });

  // ===============================
  // 3️⃣ Floating Book Button
  // ===============================
  const floatingBook = document.querySelector('.floating-book');
  if (floatingBook) {
    floatingBook.addEventListener('click', () => {
      window.location.href = 'booking.html';
    });
  }

  // ===============================
  // 4️⃣ Scroll Animations
  // ===============================
  const animateElements = document.querySelectorAll('.animate-on-scroll');
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
      }
    });
  }, { threshold: 0.1 });

  animateElements.forEach(el => observer.observe(el));
});