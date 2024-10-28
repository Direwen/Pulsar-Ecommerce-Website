// Toggle product items visibility
document.getElementById('product-section').addEventListener('click', function () {
    var productItems = document.getElementById('product-items');
    var addIcon = document.getElementById('product-section').children[1];
    var removeIcon = document.getElementById('product-section').children[2];

    if (productItems.classList.contains('hidden')) { // Check if the hidden class is present
        productItems.classList.remove('hidden'); // Remove the hidden class
        productItems.classList.add('flex'); // Add the flex class
        removeIcon.classList.remove('hidden');
        addIcon.classList.add('hidden');
    } else {
        productItems.classList.remove('flex'); // Remove the flex class
        productItems.classList.add('hidden'); // Add the hidden class
        removeIcon.classList.add('hidden');
        addIcon.classList.remove('hidden');
    }
});