// Your FAQ data
const faqs = {
    "Can I cancel my order?": "You can still cancel and get the full refund if the order status is still pending or confirmed.",
    "How do I track my order?": "You can track your order using the tracking number in the email we sent you.",
    "How do I contact customer support?": "You can contact our customer support team via email or through the 'Contact Us' page on our website.",
    "Do you offer international shipping?": "Yes, we offer international shipping to most countries. Check our shipping page for more details.",
    "How can I check if an item is in stock?": "You can check stock availability directly on the product page.",
    "Can I change my order after it has been confirmed?": "Once an order is confirmed, changes are not possible. Please contact customer support for assistance.",
    "How do I use a discount code?": "Enter your discount code in the 'Promo Code' field during checkout to apply the discount.",
    "Why was my payment declined?": "If your payment was declined, please check your payment details or contact your bank for more information.",
    "Do you offer gift cards?": "Yes, we offer gift cards. You can purchase them on our website.",
    "How can I track my shipment?": "Once your order ships, you'll receive a tracking number by email to track the shipment.",
    "What should I do if I received a damaged or faulty item?": "Please contact our customer support team with photos of the damaged item, and we will assist you with a replacement or refund.",
    "Can I buy products in bulk or for wholesale?": "Yes, please contact our sales team for bulk or wholesale pricing.",
    "Can I make changes to my order after it has been shipped?": "Once the order has been shipped, changes are not possible. Please contact customer support if there's an issue.",
    "What should I do if I’ve received the wrong item?": "Please contact our customer support team with your order details, and we will send you the correct item.",
    "How can I delete my account?": "To delete your account, contact customer support, and we will assist you with the process.",
    "Do you offer express shipping?": "Yes, express shipping is available for an additional fee. Choose the option during checkout.",
    "How do I track my order status?": "You can track your order status by logging into your account or using the tracking number sent to your email.",
    "What happens if my order is delayed?": "If your order is delayed, you will receive an email notification with an updated estimated delivery time.",
    "How do I change my shipping address after placing an order?": "Please contact customer support to change your shipping address before your order ships.",
    "Can I update my payment method after placing an order?": "Once an order is placed, the payment method cannot be changed, but you can contact support for assistance.",
    "How can I report a missing package?": "If your package is missing, please check the tracking status. If there's an issue, contact customer support.",
    "How can I check the status of a return?": "To check the status of a return, log into your account or contact customer support for an update.",
    "Do you accept PayPal?": "Yes, we accept PayPal as a payment method.",
    "Can I change my shipping method after placing an order?": "Once your order has been processed, shipping methods cannot be changed. Please contact customer support for assistance."
};

function handleFAQSearch() {
    const faqSearchInput = document.getElementById("faq-search");
    const query = faqSearchInput.value.trim(); // Get the current input value
    displayFAQs(query); // Call the display function with the query
    faqSearchInput.value = ""; // Clear the input field
}


// Function to get random FAQs
function getRandomFAQs(faqs, count) {
    const keys = Object.keys(faqs);
    const randomFAQs = [];

    // Shuffle the keys array and take the first 'count' items
    while (randomFAQs.length < count) {
        const randomIndex = Math.floor(Math.random() * keys.length);
        const randomKey = keys[randomIndex];

        // Ensure unique questions are added
        if (!randomFAQs.some(faq => faq.question === randomKey)) {
            randomFAQs.push({
                question: randomKey,
                answer: faqs[randomKey]
            });
        }
    }

    return randomFAQs;
}

// Function to display FAQs in the container
function displayFAQs(query = "") {
    const faqContainer = document.getElementById("faq-container");

    if (!faqContainer) return;

    let faqHTML = "";
    let faqCounter = 0;

    if (query.trim() === "") {
        // If no query is entered, display 5 random FAQs
        const randomFAQs = getRandomFAQs(faqs, 5);
        randomFAQs.forEach(faq => {
            faqCounter++;
            faqHTML += `
                <div class="w-full faq-item bg-primary border p-5 mb-4 shadow rounded flex flex-col gap-2 items-start justify-start transition-all ease-in-out duration-200 hover:-translate-y-1 hover:shadow-lg cursor-help">
                    <button 
                            class="w-full text-left font-semibold text-dark"
                            data-toggle="faq-${faqCounter}"
                            onclick="toggleDropdown(this)"
                        >
                            ${faq.question}
                    </button>
                    <section class="hidden" data-toggle="faq-${faqCounter}">
                        <p class="text-xs lg:text-sm text-light-dark">${faq.answer}</p>
                    </section>
                </div>
            `;
        });
    } else {
        // If there's a query, search for matching FAQs
        let found = false;
        for (let question in faqs) {
            if (question.toLowerCase().includes(query.toLowerCase())) {
                found = true;
                faqCounter++;
                faqHTML += `
                    <div class="w-full faq-item bg-primary border p-5 mb-4 shadow rounded flex flex-col gap-2 items-start justify-start transition-all ease-in-out duration-200 hover:-translate-y-1 hover:shadow-lg cursor-help">
                        <button 
                            class="w-full text-left font-semibold text-dark"
                            data-toggle="faq-${faqCounter}"
                            onclick="toggleDropdown(this)"
                        >
                            ${question}
                         </button>
                        <section class="hidden" data-toggle="faq-${faqCounter}">
                            <p class="text-xs lg:text-sm text-light-dark">${faqs[question]}</p>
                        </section>
                    </div>
                `;
            }
        }

        // If no FAQ matches, show a "not found" message
        if (!found) {
            faqHTML = `<div class="text-center text-lg text-light-dark">No FAQs found for your search.</div>`;
        }
    }

    // Insert the generated HTML into the container
    faqContainer.innerHTML = faqHTML;
}


// Run the displayFAQs function when the page loads
window.onload = function () {
    displayFAQs();
};

// Debounce Timer for search input
let debounceTimer;
const faqSearch = document.getElementById("faq-search");
let faqContainer = document.getElementById("faq-container");

faqSearch.addEventListener("input", function () {
    // Clear previous debounce timer if user is still typing
    clearTimeout(debounceTimer);

    // Set a new debounce timer (e.g., 300ms)
    debounceTimer = setTimeout(function () {
        const query = faqSearch.value;
        displayFAQs(query);
    }, 300); // 300ms delay
});
