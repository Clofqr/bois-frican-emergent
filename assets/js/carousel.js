document.addEventListener('DOMContentLoaded', () => {
  const carouselSection = document.getElementById('carousel-menu');

  if (!carouselSection) return;

  const carouselContainer = carouselSection.querySelector('.carousel-container');
  const carouselSlide = carouselSection.querySelector('.carousel-slide');
  const slides = carouselSlide.querySelectorAll('img, video');
  const prevBtn = carouselSection.querySelector('.carousel-prev');
  const nextBtn = carouselSection.querySelector('.carousel-next');

  if (!carouselContainer || !carouselSlide || slides.length === 0) return;

  let currentIndex = 0;

  function getOffset(index) {
    let offset = 0;
    for (let i = 0; i < index; i++) {
      offset += slides[i].clientWidth;
    }
    return offset;
  }

  function updateCarousel(instant = false) {
    const offset = getOffset(currentIndex);
    carouselSlide.style.transition = instant ? 'none' : 'transform 0.6s ease-in-out';
    carouselSlide.style.transform = `translateX(-${offset}px)`;
  }

  function smoothReturnToStart() {
    const totalWidth = getOffset(slides.length);
    const duration = 800;
    let start = null;

    function animate(timestamp) {
      if (!start) start = timestamp;
      const progress = timestamp - start;
      const percentage = Math.min(progress / duration, 1);
      const currentOffset = totalWidth * (1 - percentage);

      carouselSlide.style.transform = `translateX(-${currentOffset}px)`;

      if (percentage < 1) {
        requestAnimationFrame(animate);
      } else {
        currentIndex = 0;
      }
    }

    carouselSlide.style.transition = 'none';
    requestAnimationFrame(animate);
  }

  nextBtn.addEventListener('click', () => {
    currentIndex++;
    if (currentIndex >= slides.length) {
      smoothReturnToStart();
      return;
    }
    updateCarousel();
  });

  prevBtn.addEventListener('click', () => {
    currentIndex--;
    if (currentIndex < 0) {
      currentIndex = slides.length - 1;
      updateCarousel();
      return;
    }
    updateCarousel();
  });

  window.addEventListener('resize', () => updateCarousel(true));

  updateCarousel(true);
});
