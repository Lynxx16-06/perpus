let currentIndex = 0;

function updateCarousel() {
    const carousel = document.querySelector('.carousel');
    const imageWidth = document.querySelector('.carousel-container').offsetWidth;

    // Pindahkan carousel langsung
    carousel.style.transition = 'none'; // Hilangkan transisi
    carousel.style.transform = `translateX(${-currentIndex * imageWidth}px)`;
}

function prevSlide() {
    const carousel = document.querySelector('.carousel');
    const totalImages = document.querySelectorAll('.carousel-image').length;

    currentIndex = (currentIndex > 0) ? currentIndex - 1 : totalImages - 1;
    
    // Pastikan transisi hilang saat memindahkan slide
    carousel.style.transition = 'none';
    updateCarousel();
}

function nextSlide() {
    const carousel = document.querySelector('.carousel');
    const totalImages = document.querySelectorAll('.carousel-image').length;

    currentIndex = (currentIndex + 1) % totalImages;
    
    // Pastikan transisi hilang saat memindahkan slide
    carousel.style.transition = 'none';
    updateCarousel();
}

// Initialize carousel position
window.addEventListener('resize', updateCarousel);
document.addEventListener('DOMContentLoaded', updateCarousel);
