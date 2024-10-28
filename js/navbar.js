document.getElementById('menu-button').addEventListener('click', () => {
    var navbar = document.getElementById('navbar');
    
    if (navbar.style.display === 'none' || navbar.style.display === '') {
        navbar.style.display = 'flex'; // Show the product items
    } else {
        navbar.style.display = 'none'; // Hide the product items
    }
})

document.getElementById('menu-close-button').addEventListener('click', () => {
    var navbar = document.getElementById('navbar');
    if (navbar.style.display === 'flex') {
        navbar.style.display = 'none'; // Show the product items
    }
})

// Add event listener for window resize
window.addEventListener('resize', () => {
    var navbar = document.getElementById('navbar');
    if (window.innerWidth >= 640) { // Adjust the width as needed
        navbar.style.display = 'none'; // Hide the navbar on larger screens
    }
});