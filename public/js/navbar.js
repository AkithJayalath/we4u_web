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
// Toggle the dropdown menu on small screens
const menuToggle = document.getElementById('mobile-menu');
const menuContent = document.querySelector('.menu-content');

menuToggle.addEventListener('click', () => {
    menuContent.classList.toggle('show');
});



