window.addEventListener('scroll', function () {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 100) {
        navbar.classList.add('active');
    } else {
        navbar.classList.remove('active');
    }
});
