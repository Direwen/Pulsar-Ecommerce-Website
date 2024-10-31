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


let variantCount = 1;

function addVariant() {
    const variantContainer = document.getElementById('variantsContainer');
    
    // Update `variantCount` based on the number of variant items
    variantCount = variantContainer.querySelectorAll('.variant-item').length + 1;

    const variantHTML = `<div class="variant-item relative flex flex-col gap-4 border shadow px-4 py-6" data-variant-id="${variantCount}">

    <section class="block text-sm font-medium text-dark flex flex-col gap-2">
        <label for="variantType_${variantCount}" class="text-gray-700">Type</label>
        <input type="text" id="variantType_${variantCount}" name="variants[${variantCount - 1}][type]" class="block w-full border shadow rounded outline-accent p-2" required>
    </section>

    <section class="block text-sm font-medium text-dark flex flex-col gap-2">
        <label for="variantName_${variantCount}" class="text-gray-700">Name</label>
        <input type="text" id="variantName_${variantCount}" name="variants[${variantCount - 1}][name]" class="block w-full border shadow rounded outline-accent p-2" required>
    </section>

    <section class="block text-sm font-medium text-dark flex flex-col gap-2">
        <label for="variantUnitPrice_${variantCount}" class="text-gray-700">Unit Price</label>
        <input type="number" step="0.01" id="variantUnitPrice_${variantCount}" name="variants[${variantCount - 1}][unit_price]" class="block w-full border shadow rounded outline-accent p-2" required>
    </section>

    <section class="block text-sm font-medium text-dark flex flex-col gap-2">
        <label for="variantImg_${variantCount}" class="text-gray-700">Image for the variant</label>
        <input type="file" accept="image/*" id="variantImg_${variantCount}" name="variants[${variantCount - 1}]" class="block w-full bg-primary border shadow rounded outline-accent p-2" required>
    </section>

    <button type="button" class="absolute -top-3 -right-2 w-fit interactive bg-secondary text-light-dark font-semibold rounded-full px-1 border shadow hover:bg-red-500 hover:text-primary" onclick="removeVariant(this)">
        <span class="material-symbols-outlined">remove</span>
    </button>
</div>
`;
    variantContainer.insertAdjacentHTML('beforeend', variantHTML);
}

function removeVariant(button) {
    button.closest('.variant-item').remove();

    // Ensure at least one variant remains visible
    const remainingVariants = document.querySelectorAll('.variant-item').length;
    if (remainingVariants === 0) {
        addVariant();
    }
}
