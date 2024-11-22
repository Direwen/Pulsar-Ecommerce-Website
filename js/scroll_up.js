// Get the button element
const scrollUpBtn = document.getElementById('scrollUpBtn');

// Show the button when the user scrolls down
window.addEventListener('scroll', () => {
    if (window.scrollY > 200) { // Show button after 200px scroll
        scrollUpBtn.classList.remove('hidden', 'opacity-0', 'translate-y-2');
        scrollUpBtn.classList.add('opacity-100', 'translate-y-0');
    } else {
        scrollUpBtn.classList.remove('opacity-100', 'translate-y-0');
        scrollUpBtn.classList.add('opacity-0', 'translate-y-2');
    }
});

// Scroll to the top when the button is clicked
scrollUpBtn.addEventListener('click', () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth' // Smooth scrolling effect
    });
});
