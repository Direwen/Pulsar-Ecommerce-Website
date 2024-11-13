document.addEventListener("DOMContentLoaded", function() {
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

function updateShippingAndTotal(selector) {
    const selectedOption = selector.options[selector.selectedIndex];
    const shippingFee = parseFloat(selectedOption.getAttribute("shipping"));

    // // Update the shipping fee and total price in the DOM
    document.getElementById("shipping-fee").textContent = shippingFee.toFixed(2);
    let subtotalPriceDisplay = document.getElementById("sub-total");
    let totalPriceDisplay = document.getElementById("total");

    let newTotal = parseFloat(subtotalPriceDisplay.textContent) + shippingFee;
    totalPriceDisplay.textContent = newTotal.toFixed(2);
}
