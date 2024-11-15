document.addEventListener("DOMContentLoaded", function () {
    // Select payment options and billing address containers
    const paymentOptions = document.querySelectorAll(".payment-option");
    const billingOptions = document.querySelectorAll(".billing-option");

    // Function to handle payment option selection
    paymentOptions.forEach((option, index) => {
        const container = option.querySelector(".inner-section");
        const radio = option.querySelector("input[type='radio']");

        // Set the first option as selected by default
        if (index === 0) {
            option.classList.add("border-2", "border-accent");
            container.style.display = "flex";
            radio.checked = true; // Set the radio button as checked
        }

        option.addEventListener("click", () => {
            // Hide all inner sections, remove highlight, and uncheck all radio buttons
            paymentOptions.forEach(opt => {
                opt.classList.remove("border-2", "border-accent");
                opt.querySelector(".inner-section").style.display = "none";
                opt.querySelector("input[type='radio']").checked = false;
            });
            // Show inner section for the selected option, highlight it, and check its radio button
            option.classList.add("border-2", "border-accent");
            container.style.display = "flex";
            radio.checked = true;
        });
    });

    // Function to handle billing address option selection
    billingOptions.forEach((option, index) => {
        const formContainer = document.querySelector(".billing-form");
        const radio = option.querySelector("input[type='radio']");

        // Set the first option as selected by default
        if (index === 0) {
            option.classList.add("border-2", "border-accent");
            formContainer.style.display = "none";
            radio.checked = true; // Set the radio button as checked
        }

        option.addEventListener("click", () => {
            // Remove highlight from all options and uncheck all radio buttons
            billingOptions.forEach(opt => {
                opt.classList.remove("border-2", "border-accent");
                opt.querySelector("input[type='radio']").checked = false;
            });
            // Highlight selected option, check its radio button, and show or hide the billing address form
            option.classList.add("border-2", "border-accent");
            radio.checked = true;

            // Show or hide the billing address form based on selection
            if (index === 1) {
                formContainer.style.display = "block";
            } else {
                formContainer.style.display = "none";
            }
        });
    });
});

let currentDiscount = 0; // Store discount percentage here

// Centralized function to calculate and display the total
function calculateTotal() {
    const subtotalPriceDisplay = document.getElementById("sub-total");
    console.log(subtotalPriceDisplay.textContent);
    const shippingFeeDisplay = document.getElementById("shipping-fee");
    const totalPriceDisplay = document.getElementById("total");

    // Remove commas from the subtotal before parsing
    const subtotal = parseFloat(subtotalPriceDisplay.textContent.replace(/,/g, '')) || 0;
    const shippingFee = parseFloat(shippingFeeDisplay.textContent) || 0;

    // Calculate the discount value based on the subtotal
    const discountValue = subtotal * (currentDiscount / 100);

    // Calculate final total
    const newTotal = subtotal + shippingFee - discountValue;
    totalPriceDisplay.textContent = newTotal.toFixed(2);
}

// Update shipping fee and call calculateTotal()
function updateShippingAndTotal(selector) {
    const selectedOption = selector.options[selector.selectedIndex];
    const shippingFee = parseFloat(selectedOption.getAttribute("shipping")) || 0;

    document.getElementById("shipping-fee").textContent = shippingFee.toFixed(2);

    // Recalculate total with updated shipping fee
    calculateTotal();
}

// Toggle discount code application and call calculateTotal()
function toggleDiscount(button) {
    const discountInput = document.getElementById("discount_input");
    const discountCode = discountInput.value.trim();
    const discountMessage = document.getElementById("discount-message");
    const hiddenDiscountCode = document.getElementById("applied_discount_code");

    if (discountInput.readOnly) {
        // Clear discount if already applied
        discountInput.value = "";
        discountInput.readOnly = false;
        discountMessage.textContent = "Not Applied";
        button.textContent = "Apply";
        hiddenDiscountCode.value = ""; // Clear hidden input value

        button.classList.add("bg-accent", "text-secondary");
        button.classList.remove("bg-light-gray", "text-dark");

        // Reset current discount to 0 and recalculate total
        currentDiscount = 0;
        calculateTotal();
    } else {
        // Apply discount if code is valid
        if (!discountCode) {
            discountMessage.textContent = "Invalid";
            return;
        }

        axios.post(button.getAttribute('path_to_validate'), { code: discountCode })
            .then(response => {
                if (response.data.valid) {
                    const discountAmount = response.data.data.amount; // Assuming percentage discount

                    discountMessage.textContent = discountAmount + '% OFF';
                    discountInput.readOnly = true;
                    button.textContent = "Clear";

                    button.classList.add("bg-light-gray", "text-dark");
                    button.classList.remove("bg-accent", "text-secondary");

                    // Update current discount and recalculate total
                    currentDiscount = discountAmount;

                    // Set hidden input with discount code for form submission
                    hiddenDiscountCode.value = discountCode;

                    calculateTotal();
                } else {
                    discountMessage.textContent = "Invalid";
                }
            })
            .catch(error => {
                discountMessage.textContent = "Invalid";
                console.error("Error applying discount:", error);
            });
    }
}

function toggleCartItemsContainer() {
    const itemsContainer = document.getElementById('cart-items-for-checkout-container');
    itemsContainer.classList.toggle('hidden');
}

function closeCartItemsContainer() {
    const itemsContainer = document.getElementById('cart-items-for-checkout-container');
    itemsContainer.classList.remove('hidden');
}


