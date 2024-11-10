function selectVariant(selector, text_details) {
    var price_tag = document.getElementById("variant_price_tag");
    var name_tag = document.getElementById("variant_name_tag");
    var stock_tag = document.getElementById("stock-display");
    var addToCartButton = document.getElementById("add-to-cart-btn");
    var soldOutButton = document.getElementById("sold-out-btn");

    // Update price and variant name
    price_tag.innerHTML = text_details.price.toFixed(2);
    name_tag.innerHTML = `${text_details.type}: ${text_details.name}`;

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

function updateQuantityDisplay() {
    const quantityDisplay = document.getElementById("quantity-display");
    quantityDisplay.textContent = quantity;
}

function incrementQuantity() {
    quantity += 1;
    updateQuantityDisplay();
}

function decrementQuantity() {
    // Ensure quantity doesn't go below 1
    if (quantity > 1) {
        quantity -= 1;
    }
    updateQuantityDisplay();
}

// Initial call to set the display (for the default variant)
updateQuantityDisplay();
