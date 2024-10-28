const overlay = document.getElementById('overlay');
const overlayContent = document.getElementById('overlay_content_container');


// Function to show the overlay with dynamic content
const showOverlay = (content) => {
    overlayContent.innerHTML = content; // Insert the dynamic content
    overlay.style.display = 'flex';     // Show the overlay
};

// Function to close the overlay
const closeOverlay = (event) => {
    if (event.target.id === 'overlay') overlay.style.display = 'none';
};

function forceOverlayToClose() {
    overlay.style.display = 'none';
}

// Event listener to close the overlay when clicking outside
overlay.addEventListener('click', closeOverlay);