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