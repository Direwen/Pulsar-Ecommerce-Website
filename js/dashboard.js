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

// function toggleImageInput() {
//     const checkbox = document.getElementById('changeImageCheckbox');
//     const imageInput = document.getElementById('newImageInput');
//     imageInput.classList.toggle('hidden', !checkbox.checked); // Show input if checkbox is checked
// }

function toggleImageInput(checkbox) {
    // Find the corresponding file input using the data-toggle attribute
    const toggleId = checkbox.getAttribute('data-toggle');
    const fileInput = document.querySelector(`input[type="file"][data-toggle="${toggleId}"]`);

    // Show or hide the file input based on the checkbox state
    if (fileInput) {
        fileInput.classList.toggle('hidden', !checkbox.checked);
    }
}

// Function to handle adding new special feature containers dynamically
function addSpecialFeatureCategory() {
    const container = document.getElementById('special-feature-container');

    // Get the count of existing feature items
    const featureCount = container.querySelectorAll('.special-feature-item').length + 1;

    // Define the HTML structure for a new feature section
    const featureHTML = `
        <div class="special-feature-item relative flex flex-col gap-4 border shadow px-4 py-6" data-feature-id="${featureCount}">
        
            <section class="block text-sm font-medium text-dark flex flex-col gap-2">
                <label for="featureCategory_${featureCount}" class="text-gray-700">Title</label>
                <input type="text" id="featureCategory_${featureCount}" name="special-feature-title[]" placeholder="Category (e.g., Sensor)" class="block w-full border shadow rounded outline-accent p-2" required>
            </section>

            <section class="block text-sm font-medium text-dark flex flex-col gap-2">
                <label for="featureDescription_${featureCount}" class="text-gray-700">Details</label>
                <textarea id="featureDescription_${featureCount}" name="special-feature-details[]" placeholder="Feature Details" class="block w-full border shadow rounded outline-accent p-2" required></textarea>
            </section>

            <button type="button" class="absolute -top-3 -right-2 w-fit interactive bg-secondary text-light-dark font-semibold rounded-full px-1 border shadow hover:bg-red-500 hover:text-primary" onclick="removeFeature(this)">
                <span class="material-symbols-outlined">remove</span>
            </button>
        </div>
    `;

    // Insert the new feature section HTML into the container
    container.insertAdjacentHTML('beforeend', featureHTML);
}

// Function to remove a feature item
function removeFeature(button) {
    const featureItem = button.closest('.special-feature-item');
    featureItem.remove();
}

function toggleProductForm() {
    const productOption = document.getElementById('productOption').value;
    const existingProductForm = document.getElementById('existingProductForm');
    const createProductForm = document.getElementById('createProductForm');
    const actionType = document.getElementById('actionType');

    if (productOption === 'create') {
        existingProductForm.classList.add('hidden');
        createProductForm.classList.remove('hidden');
        actionType.value = 'create'; // Update hidden input for creating a new product
    } else {
        existingProductForm.classList.remove('hidden');
        createProductForm.classList.add('hidden');
        actionType.value = 'add-variant'; // Update hidden input for adding a variant
    }
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
