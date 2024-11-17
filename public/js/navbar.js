let lastScrollY = window.scrollY;
const navbar = document.querySelector('.navbar');
const mobileMenu = document.getElementById('mobile-menu');
const navLinks = document.querySelector('.nav-links');
const contactSignin = document.querySelector('.contact-signin');

// Throttle scroll handler to improve performance
let isScrolling = false;

window.addEventListener('scroll', () => {
    if (!isScrolling) {
        isScrolling = true;
        requestAnimationFrame(() => {
            handleScroll();
            isScrolling = false;
        });
    }
});

function handleScroll() {
    if (window.scrollY === 0) {
        // Always show navbar when at the top of the page
        navbar.classList.remove('hide');
        navbar.classList.add('show');
    } else {
        // Hide navbar while scrolling down or up unless at the top
        navbar.classList.remove('show');
        navbar.classList.add('hide');
    }

    lastScrollY = window.scrollY;
}

// Toggle mobile menu visibility
mobileMenu.addEventListener('click', () => {
    navLinks.classList.toggle('active');
    contactSignin.classList.toggle('active');
    mobileMenu.classList.toggle('open'); // Optional: Add class to style toggle button
});
