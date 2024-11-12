document.addEventListener("DOMContentLoaded", function() {
    // Select payment options and billing address containers
    const paymentOptions = document.querySelectorAll(".payment-option");
    const billingOptions = document.querySelectorAll(".billing-option");
    
    // Function to handle payment option selection
    paymentOptions.forEach((option, index) => {
        const container = option.querySelector(".inner-section");

        // Set the first option as selected by default
        if (index === 0) {
            option.classList.add("border-accent");
            container.style.display = "block";
        }

        option.addEventListener("click", () => {
            // Hide all inner sections and remove highlight from all options
            paymentOptions.forEach(opt => {
                opt.classList.remove("border-accent");
                opt.querySelector(".inner-section").style.display = "none";
            });
            // Show inner section for the selected option and highlight it
            option.classList.add("border-accent");
            container.style.display = "block";
        });
    });

    // Function to handle billing address option selection
    billingOptions.forEach((option, index) => {
        const formContainer = document.querySelector(".billing-form");

        // Set the first option as selected by default
        if (index === 0) {
            option.classList.add("border-accent");
            formContainer.style.display = "none";
        }

        option.addEventListener("click", () => {
            // Remove highlight from all options
            billingOptions.forEach(opt => opt.classList.remove("border-accent"));
            // Highlight selected option
            option.classList.add("border-accent");

            // Show or hide the billing address form based on selection
            if (index === 1) {
                formContainer.style.display = "block";
            } else {
                formContainer.style.display = "none";
            }
        });
    });
});