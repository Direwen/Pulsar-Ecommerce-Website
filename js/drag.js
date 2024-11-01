const draggable = document.getElementById('draggable');

let isDragging = false;
let offset = { x: 0, y: 0 };
let overlayTimeout;
let startX, startY; // Store initial touch coordinates
const dragThreshold = 10; // Minimum movement required to be considered dragging
let lastTouchTime = 0; // Track the last touch time

const dashboardMenuContent = `<a class="w-full py-2 text-center hover:shadow" href="?view=user-management">Users Management</a>
<a class="w-full py-2 text-center hover:shadow" href="?view=product-management">Products Management</a>
<a class="w-full py-2 text-center hover:shadow" href="?view=orders-management">Orders Management</a>
<a class="w-full py-2 text-center hover:shadow" href="?view=logs">Logs</a>`;

// Get viewport dimensions
const getViewportDimensions = () => {
    return {
        width: window.innerWidth,
        height: window.innerHeight,
    };
};

// Mouse down event
draggable.addEventListener('mousedown', (e) => {
    // Check if the Ctrl key is pressed
    if (!e.ctrlKey) {
        console.log(dashboardMenuContent)
        showOverlay(dashboardMenuContent); // Show overlay if Ctrl is not pressed
        return; // Exit the function if Ctrl is not pressed
    }

    // Proceed with dragging
    isDragging = true;
    offset.x = e.clientX - draggable.getBoundingClientRect().left;
    offset.y = e.clientY - draggable.getBoundingClientRect().top;

    // Disable transition
    draggable.style.transition = 'none';

    // Start a timeout to show the overlay, but do not execute it while dragging
    overlayTimeout = setTimeout(() => {
        if (!isDragging) return;
        showOverlay(dashboardMenuContent); // Show overlay if Ctrl is not pressed
    }, 200);
});

// Mouse move event
document.addEventListener('mousemove', (e) => {
    if (isDragging) {
        const { width, height } = getViewportDimensions();

        // Calculate new position
        let newX = e.clientX - offset.x;
        let newY = e.clientY - offset.y;

        // Prevent it from going out of bounds
        newX = Math.max(0, Math.min(newX, width - draggable.offsetWidth));
        newY = Math.max(0, Math.min(newY, height - draggable.offsetHeight));

        draggable.style.left = `${newX}px`;
        draggable.style.top = `${newY}px`;

        clearTimeout(overlayTimeout); // Clear the overlay timeout to prevent it from showing
    }
});

document.addEventListener('mouseup', () => {
    isDragging = false;
    clearTimeout(overlayTimeout); // Clear the overlay timeout

    // Get the current position of the draggable element
    const { left } = draggable.getBoundingClientRect();
    const { width } = getViewportDimensions();

    // Calculate the center of the viewport
    const viewportCenter = width / 2;

    // Snap to the nearest side (left or right)
    draggable.style.left = left < viewportCenter ? '0px' : `${width - draggable.offsetWidth}px`; // Snap to left or right

    // Re-enable transition for the snapping effect
    draggable.style.transition = 'all ease-in-out 0.3s';
});

// Touch events
draggable.addEventListener('touchstart', (e) => {
    const currentTime = new Date().getTime();

    // Check if the time between touches is less than 300ms for a double tap
    if (currentTime - lastTouchTime < 300) {
        showOverlay(dashboardMenuContent); // Show overlay if Ctrl is not pressed
    }
    lastTouchTime = currentTime; // Update last touch time

    // Get the first touch
    const touch = e.touches[0];

    // Store initial touch coordinates
    startX = touch.clientX;
    startY = touch.clientY;

    // Start dragging immediately on touch
    isDragging = true;
    offset.x = touch.clientX - draggable.getBoundingClientRect().left;
    offset.y = touch.clientY - draggable.getBoundingClientRect().top;

    // Disable transition
    draggable.style.transition = 'none';
});

document.addEventListener('touchmove', (e) => {
    if (isDragging) {
        const touch = e.touches[0];
        const { width, height } = getViewportDimensions();

        // Calculate movement delta
        const deltaX = touch.clientX - startX;
        const deltaY = touch.clientY - startY;

        // Only start dragging if the movement exceeds the threshold
        if (Math.abs(deltaX) > dragThreshold || Math.abs(deltaY) > dragThreshold) {
            // Calculate new position
            let newX = touch.clientX - offset.x;
            let newY = touch.clientY - offset.y;

            // Prevent it from going out of bounds
            newX = Math.max(0, Math.min(newX, width - draggable.offsetWidth));
            newY = Math.max(0, Math.min(newY, height - draggable.offsetHeight));

            draggable.style.left = `${newX}px`;
            draggable.style.top = `${newY}px`;

            clearTimeout(overlayTimeout); // Clear the overlay timeout to prevent it from showing
        }
    }
});

document.addEventListener('touchend', () => {
    isDragging = false;
    clearTimeout(overlayTimeout); // Clear the overlay timeout

    // Get the current position of the draggable element
    const { left } = draggable.getBoundingClientRect();
    const { width } = getViewportDimensions();

    // Calculate the center of the viewport
    const viewportCenter = width / 2;

    // Snap to the nearest side (left or right)
    draggable.style.left = left < viewportCenter ? '0px' : `${width - draggable.offsetWidth}px`; // Snap to left or right

    // Re-enable transition for the snapping effect
    draggable.style.transition = 'all ease-in-out 0.3s';
});

// Optional: Prevent default touch behavior
draggable.addEventListener('touchstart', (e) => {
    e.preventDefault(); // Prevent scrolling while dragging
});
