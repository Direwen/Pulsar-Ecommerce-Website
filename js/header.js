// Get DOM elements
const navigationLinksContainer = document.getElementById('navigation-links-container');
const productLink = navigationLinksContainer.querySelector('a:first-child');
const secondHeader = document.getElementById('second-header');
const searchIcon = document.getElementById('search-button');
const categoriesContainer = document.getElementById('categories-container');
const searchContainer = document.getElementById('search-container');
const searchCloseButton = document.getElementById('search-close-button');

let isSearchMode = false;

// Function to show/hide the second header and categories
const toggleHeaders = (showSecondHeader, showCategories) => {
    secondHeader.classList.toggle('hidden', !showSecondHeader);
    categoriesContainer.classList.toggle('hidden', !showCategories);
    searchContainer.classList.toggle('hidden', showCategories);
};

// Mouse enter/leave event for product link
productLink.addEventListener('mouseenter', () => {
    if (isSearchMode) {
        secondHeader.classList.remove('search-mode-active');
        isSearchMode = false;
    }
    toggleHeaders(true, true); // Show both second header and categories
});

productLink.addEventListener('mouseleave', () => {
    if (!isSearchMode) {
        secondHeader.classList.add('hidden'); // Hide second header
    }
});

// Mouse enter/leave event for second header
secondHeader.addEventListener('mouseenter', () => {
    secondHeader.classList.remove('hidden'); // Ensure second header is shown
});

secondHeader.addEventListener('mouseleave', () => {
    if (!isSearchMode) {
        secondHeader.classList.add('hidden'); // Hide second header
    }
});

// Search icon click event
searchIcon.addEventListener('click', () => {
    isSearchMode = true; // Set search mode to true
    secondHeader.classList.toggle('search-mode-active', isSearchMode);
    toggleHeaders(true, false); // Show search box and hide categories
});

// Search close button click event
searchCloseButton.addEventListener('click', () => {
    isSearchMode = false;
    secondHeader.classList.add('hidden'); // Immediately hide the second header
    toggleHeaders(false, true); // Show categories and hide search box
});
