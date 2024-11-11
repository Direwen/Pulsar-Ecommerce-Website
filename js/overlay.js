const overlay = document.getElementById('overlay');
const overlayContent = document.getElementById('overlay_content_container');
const navbar = document.getElementById('navbar');
const cart = document.getElementById('shopping_cart');

var modalState = false;
var navbarState = false;
var shoppingCartState = false;


function openOverlayModal(content) {
    modalState = true;
    overlayContent.classList.remove('hidden');
    overlayContent.classList.add('flex');
    overlayContent.innerHTML = content; // Insert the dynamic content
    showOverlay();
}

function openNavbar() {
    navbarState = true;
    navbar.classList.remove('hidden');
    navbar.classList.add('flex');
    showOverlay();
}

// Function to show the overlay with dynamic content
const showOverlay = () => {
    overlay.classList.remove('hidden');
    if (modalState) overlay.classList.add('flex', 'justify-center', 'items-center') ;
    else if (navbarState || shoppingCartState) overlay.classList.add('flex', 'justify-end') ;
};

// Function to close the overlay
const closeOverlay = (event) => {
    if (event.target.id === 'overlay') forceOverlayToClose();
};

function forceOverlayToClose() {
    overlay.classList.remove('flex', 'justify-center', 'justify-end', 'items-center');
    overlay.classList.add('hidden');

    overlayContent.classList.add('hidden');
    overlayContent.classList.remove('flex');

    navbar.classList.add('hidden');
    navbar.classList.remove('flex');

    cart.classList.add("hidden");
    cart.classList.remove("block");

    modalState = false;
    navbarState = false;
    shoppingCartState = false;
}

// Event listener to close the overlay when clicking outside
overlay.addEventListener('click', closeOverlay);