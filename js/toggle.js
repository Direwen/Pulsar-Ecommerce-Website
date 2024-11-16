function toggleDropdown(button) {
    // Find the corresponding section using the data-toggle attribute
    const toggleId = button.getAttribute('data-toggle');
    const section = document.querySelector(`section[data-toggle="${toggleId}"]`);

    // Toggle the 'hidden' class to show or hide the section
    if (section) {
        section.classList.toggle('hidden');
    }
}

function toggleUserInfo() {
    const dropdown = document.getElementById('user-info-menu');
    dropdown.classList.toggle('hidden');
}