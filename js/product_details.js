function selectVariant(selector, text_details) {
    var price_tag = document.getElementById("variant_price_tag");
    var name_tag = document.getElementById("variant_name_tag");
    var stock_tag = document.getElementById("stock-display");
    var variant_id_tag = document.getElementById("variant-id-display");
    var addToCartButton = document.getElementById("add-to-cart-btn");
    var soldOutButton = document.getElementById("sold-out-btn");

    // Update price and variant name
    price_tag.innerHTML = text_details.price.toFixed(2);
    name_tag.innerHTML = `${text_details.type}: ${text_details.name}`;
    variant_id_tag.innerHTML = text_details.id;

    // Update stock information
    stock_tag.innerHTML = `Stock: ${text_details.stock_quantity}`;

    // Show/hide buttons based on stock
    if (text_details.stock_quantity > 0) {
        addToCartButton.style.display = "block";  // Show "Add to Cart" button
        soldOutButton.style.display = "none";  // Hide "Sold Out" button
    } else {
        addToCartButton.style.display = "none";  // Hide "Add to Cart" button
        soldOutButton.style.display = "block";  // Show "Sold Out" button
    }
}

// Set the initial quantity
let quantity = 1;

// Function to update a specific quantity display element
function updateQuantityDisplay(quantityDisplayElement) {
    if (quantityDisplayElement) {
        quantityDisplayElement.textContent = quantity;
    }
}

// Increment quantity function
function incrementQuantity(quantityDisplayId) {
    quantity += 1;
    const quantityDisplayElement = document.getElementById(quantityDisplayId);
    updateQuantityDisplay(quantityDisplayElement);
}

// Decrement quantity function
function decrementQuantity(quantityDisplayId) {
    // Ensure quantity doesn't go below 1
    if (quantity > 1) {
        quantity -= 1;
    }
    const quantityDisplayElement = document.getElementById(quantityDisplayId);
    updateQuantityDisplay(quantityDisplayElement);
}

// Initial call to set the display on page load
updateQuantityDisplay('product-quantity-display');

