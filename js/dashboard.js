function updateInputField() {
    const searchAttribute = document.getElementById('search_attribute');
    const selectedOption = searchAttribute.options[searchAttribute.selectedIndex];
    const dataType = selectedOption.getAttribute('data-type');

    const searchInputContainer = document.getElementById('search_input_container');
    searchInputContainer.innerHTML = '';  // Clear previous input fields

    if (dataType.includes('timestamp')) {
        // If the data type is a timestamp, show date inputs
        searchInputContainer.innerHTML = `
            <input type="date" id="end_date" name="record_search_end_date" class="w-full p-3 border rounded focus:outline-none focus:border-accent mb-2 md:mb-0 md:mr-2" placeholder="End Date" />
        `;
    } else {
        // For other data types, show a text input
        searchInputContainer.innerHTML = `
            <input type="text" id="search_input" name="record_search" placeholder="Search records..." class="w-full p-3 border rounded focus:outline-none focus:border-accent mb-2 md:mb-0 md:mr-2" />
        `;
    }
}

function toggleImageInput() {
    const checkbox = document.getElementById('changeImageCheckbox');
    const imageInput = document.getElementById('newImageInput');
    imageInput.classList.toggle('hidden', !checkbox.checked); // Show input if checkbox is checked
}

// Function to handle adding new special feature containers dynamically
function addSpecialFeatureCategory() {
    const container = document.getElementById('special-feature-container');

    // Create a new feature section
    const featureSection = document.createElement('div');
    featureSection.classList.add('special-feature-item', 'flex', 'flex-col', 'gap-2', 'border', 'p-4', 'rounded', 'shadow', 'relative');
    
    // Create input for the category name
    const categoryNameInput = document.createElement('input');
    categoryNameInput.type = 'text';
    categoryNameInput.placeholder = 'Category (e.g., Sensor)';
    categoryNameInput.name = 'special-feature-title[]';
    categoryNameInput.classList.add('block', 'w-full', 'border', 'rounded', 'p-2', 'outline-accent');
    
    // Create textarea for the category description
    const categoryDescription = document.createElement('textarea');
    categoryDescription.placeholder = 'Feature Details';
    categoryDescription.name = 'special-feature-details[]';
    categoryDescription.classList.add('block', 'w-full', 'border', 'rounded', 'p-2', 'outline-accent');
    
    // Create delete button
    const deleteButton = document.createElement('button');
    deleteButton.type = 'button';
    deleteButton.textContent = 'Delete Feature';
    deleteButton.classList.add('absolute', 'top-2', 'right-2', 'text-red-500', 'font-semibold', 'py-1', 'px-2', 'rounded');
    deleteButton.onclick = () => container.removeChild(featureSection); // Remove feature section on click

    // Append inputs and delete button to the feature section
    featureSection.appendChild(categoryNameInput);
    featureSection.appendChild(categoryDescription);
    featureSection.appendChild(deleteButton);

    // Add the feature section to the main container
    container.appendChild(featureSection);
}