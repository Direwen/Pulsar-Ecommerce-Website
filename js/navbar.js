// Add event listener for window resize
window.addEventListener('resize', () => {
    var navbar = document.getElementById('navbar');
    if (window.innerWidth >= 640) { // Adjust the width as needed
        forceOverlayToClose();
    }

    if (window.innerWidth >= 1024) {
        closeCartItemsContainer();
    }
});