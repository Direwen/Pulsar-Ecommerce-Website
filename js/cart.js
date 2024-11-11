function addToCart(btn) {

    const variantId = document.getElementById("variant-id-display");
    // const quantityDisplay = document.getElementById("quantity-display");
    const quantityDisplayElement = document.getElementById('product-quantity-display');

    console.log("add to cart");
    console.log(quantityDisplayElement);

    axios.post(btn.getAttribute('cart-api'), {id: variantId.textContent, quantity: quantityDisplayElement.textContent})
        .then(response => {
            console.log(response);
            openShoppingCart(btn.getAttribute('root'));
        })
        .catch(error => {
            console.log(error);
        });

}

// Increment quantity function
function incrementCartItemQuantity(rootDirectory, itemId, targetElement) {
    const quantityDisplay = document.getElementById(targetElement);
    let newQuantity = parseInt(quantityDisplay.textContent.trim()) + 1;

    // Show loading spinner
    toggleCartLoadingSpinner(true);

    axios.post(rootDirectory + "api/cart", { id: itemId, quantity: newQuantity })
        .then(response => {
            console.log(response);
            openShoppingCart(rootDirectory); // Re-render cart with updated quantity
        })
        .catch(error => {
            console.log(error);
        })
        .finally(() => {
            toggleCartLoadingSpinner(false);
        });
}

// Decrement quantity function
function decrementCartItemQuantity(rootDirectory, itemId, targetElement) {
    const quantityDisplay = document.getElementById(targetElement);
    let newQuantity = parseInt(quantityDisplay.textContent.trim()) - 1;

    // If new quantity is zero, remove the item
    if (newQuantity < 0) return;

    // Show loading spinner
    toggleCartLoadingSpinner(true);

    axios.post(rootDirectory + "api/cart", { id: itemId, quantity: newQuantity })
        .then(response => {
            console.log(response);
            openShoppingCart(rootDirectory); // Re-render cart with updated quantity
        })
        .catch(error => {
            console.log(error);
        })
        .finally(() => {
            toggleCartLoadingSpinner(false);
        });
}

// Helper function to show/hide loading spinner
function toggleCartLoadingSpinner(show) {
    const loadingSpinner = document.getElementById('cart-loader-overlay');
    if (show) {
        loadingSpinner.classList.remove('hidden');
        loadingSpinner.classList.add('flex');
    } else {
        loadingSpinner.classList.remove('flex');
        loadingSpinner.classList.add('hidden');
    }
}

// Open shopping cart and render its contents
function openShoppingCart(rootDirectory) {
    shoppingCartState = true;
    cart.classList.remove("hidden");
    cart.classList.add("block");
    showOverlay();

    axios.get(rootDirectory + 'api/cart-items')
        .then(response => {
            const cart_items = response.data;
            let subtotal = 0;
            let content = `
                <div id="cart-loader-overlay" class="absolute top-0 left-0 w-full h-full bg-secondary/85 flex items-center justify-center hidden">
                    <span class="w-12 h-12 border-4 border-accent border-y-transparent rounded-full flex justify-center items-center text-accent animate-spin">
                        <span class="material-symbols-outlined">mouse</span>
                    </span>
                </div>

                <div class="p-6 bg-secondary text-dark text-2xl flex justify-between items-center">
                    <h2 class="">Shopping Cart (${cart_items.length})</h2>
                    <span class="material-symbols-outlined interactive" onclick="forceOverlayToClose()">close</span>
                </div>
                <div class="p-6">`;

            // Display a message if the cart is empty
            if (cart_items.length <= 0) {
                content += `
                    <div class="flex flex-col items-center justify-center text-center mt-10">
                        <span class="text-xl text-light-dark font-semibold mb-4">
                            Your cart is currently empty.
                        </span>
                        <p class="text-sm text-light-dark mb-6">
                            Browse our collection and add items to your cart.
                        </p>
                        <a href="${rootDirectory}" class="px-4 py-2 bg-accent text-primary rounded hover:bg-accent-dark transition">
                            Start Shopping
                        </a>
                    </div>
                </div>`;
                cart.innerHTML = content;
                return;
            }

            content += `
                <p class="text-sm text-light-dark">
                    The checkout price does not include import duties, VAT, and other taxes.
                </p>
                <div class="mt-6">`;

            // Render cart items if the cart is not empty
            cart_items.forEach((item, index) => {
                const itemPrice = item.unit_price * item.quantity;
                subtotal += itemPrice;

                content += `
                    <div class="flex justify-between items-start border-b py-4">
                        <section class="flex items-stretch gap-4">
                            <div class="w-16 flex-shrink-0 self-stretch">
                                <img src="${rootDirectory}assets/products/${item.img}"
                                     alt="${item.name}"
                                     class="object-cover w-full h-full rounded-md">
                            </div>

                            <section class="flex flex-col items-start gap-2">
                                <span class="text-dark tracking-tighter">
                                    ${item.product_name.charAt(0).toUpperCase() + item.product_name.slice(1)}
                                </span>

                                <div class="flex items-center px-2 border shadow w-fit gap-4 rounded">
                                    <button type="button" onclick="decrementCartItemQuantity('${rootDirectory}', ${item.id}, 'cart-quantity-display-${index}')"
                                        class="interactive">
                                        <span class="material-symbols-outlined text-xl">remove</span>
                                    </button>
                                    <span 
                                        id="cart-quantity-display-${index}"
                                        class="font-thin quantity-display"
                                    >
                                        ${item.quantity}
                                    </span>
                                    <button type="button" onclick="incrementCartItemQuantity('${rootDirectory}', ${item.id}, 'cart-quantity-display-${index}')"
                                        class="interactive">
                                        <span class="material-symbols-outlined text-xl">add</span>
                                    </button>
                                </div>
                            </section>
                        </section>

                        <span class="font-bold tracking-tighter">
                            <span class="text-sm">$</span>${itemPrice.toFixed(2)}
                        </span>
                    </div>`;
            });

            content += `
                    </div>
                    <div class="mt-6 border-t pt-4">
                        <div class="flex justify-between text-lg tracking-tighter font-medium text-dark">
                            <span>Subtotal</span>
                            <span>$${subtotal.toFixed(2)}</span>
                        </div>
                        <button class="mt-4 w-full py-3 text-center text-primary bg-accent interactive rounded">
                            CHECK OUT ➔
                        </button>
                    </div>
                </div>`;

            cart.innerHTML = content;
        })
        .catch(error => {
            console.log(error);
        });
}


