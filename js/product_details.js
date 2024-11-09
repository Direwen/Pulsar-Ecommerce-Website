function selectVariant(selector, text_details) {

    var price_tag = document.getElementById("variant_price_tag");
    var name_tag = document.getElementById("variant_name_tag");
    
    console.log("Clicked", selector);
    price_tag.innerHTML = text_details.price;
    name_tag.innerHTML = `${text_details.type}: ${text_details.name}`;
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

// Initial call to set the display
updateQuantityDisplay();
