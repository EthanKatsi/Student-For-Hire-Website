print("hello world")

// Toggles a hamburger menu when in on mobile or window size is too small
function toggleMenu() {
    const mobileMenu = document.querySelector('.mobile-menu');
    const hamburger = document.querySelector('.hamburger');

    if (!mobileMenu || !hamburger) return;

    mobileMenu.classList.toggle('open');
    hamburger.classList.toggle('active');

    if (mobileMenu.classList.contains('open')) {
        document.addEventListener('click', closeMenuOnOutsideClick);
    } else {
        document.removeEventListener('click', closeMenuOnOutsideClick);
    }
}

// if user clicks anywhere outside the menu it dissapears
function closeMenuOnOutsideClick(event) {
    const mobileMenu = document.querySelector('.mobile-menu');
    const hamburger = document.querySelector('.hamburger');

    if (!mobileMenu.contains(event.target) && !hamburger.contains(event.target)) {
        mobileMenu.classList.remove('open');
        hamburger.classList.remove('active');
        document.removeEventListener('click', closeMenuOnOutsideClick);
    }
}

function checkHeaderButtons() {
    const headerButtons = document.querySelector('.header-buttons');
    const hamburger = document.querySelector('.hamburger');

    if (!headerButtons || !hamburger) return;

    const headerButtonsVisible = window.getComputedStyle(headerButtons).display !== 'none';
    hamburger.style.visibility = headerButtonsVisible ? 'hidden' : 'visible';
}

// Book a Quote form if the service is lawn mowing, weeding/gardening, leaf cleanup, or hedge trimming
function setupQuoteForm() {
    const serviceSelect = document.getElementById('service');
    const bagClippingsQuestion = document.getElementById('bag-clippings-question');
    const provideBagsQuestion = document.getElementById('provide-bags-question');
    const bagClippingsSelect = document.getElementById('bag-clippings');

    if (!serviceSelect) {
        console.warn('Service select element not found!');
        return;
    }

    serviceSelect.addEventListener('change', () => {
        const selectedService = serviceSelect.value;
        if (selectedService === 'Lawn mowing') {
            bagClippingsQuestion.classList.remove('hidden');
            provideBagsQuestion.classList.add('hidden');
        } else if (['Weeding/Gardening', 'Leaf Cleanup', 'Hedge Trimming'].includes(selectedService)) {
            bagClippingsQuestion.classList.add('hidden');
            provideBagsQuestion.classList.remove('hidden');
        } else {
            bagClippingsQuestion.classList.add('hidden');
            provideBagsQuestion.classList.add('hidden');
        }
    });

    if (bagClippingsSelect) {
        bagClippingsSelect.addEventListener('change', () => {
            const selectedOption = bagClippingsSelect.value;
            if (selectedOption === 'Yes') {
                provideBagsQuestion.classList.remove('hidden');
            } else {
                provideBagsQuestion.classList.add('hidden');
            }
        });
    }
}

// Animate feature cards when user scrolls
function setupScrollAnimations() {
    const animatableElements = document.querySelectorAll(
        '.feature-card, button, img, h1, h2, h3, p'
    );

    const observerOptions = {
        root: null,
        threshold: 0.2,
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-element');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    animatableElements.forEach((element) => observer.observe(element));
}

// Adds button hover effects
function setupButtonHoverEffects() {
    document.querySelectorAll('button').forEach((button) => {
        button.addEventListener('mouseenter', () => {
            button.style.transform = 'scale(1.05)';
            button.style.transition = 'transform 0.3s ease-in-out';
        });

        button.addEventListener('mouseleave', () => {
            button.style.transform = 'scale(1)';
            button.style.transition = 'transform 0.3s ease-in-out';
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    checkHeaderButtons();
    const hamburger = document.querySelector('.hamburger');
    if (hamburger) hamburger.addEventListener('click', toggleMenu);

    setupQuoteForm();
    setupScrollAnimations();
    setupButtonHoverEffects();
});

window.addEventListener('resize', checkHeaderButtons);
