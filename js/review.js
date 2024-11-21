document.addEventListener('DOMContentLoaded', checkProductsToReview);

function checkProductsToReview() {
    const rootDirectory = document.getElementById("root-directory").getAttribute("root-directory");

    axios.get(rootDirectory + "api/get-products-to-rate")
        .then(res => {
            let variants = res.data.records;
            if (variants.length != 0) renderRatingForm(variants, rootDirectory);
        })
        .catch(err => console.log(err));
}

function renderRatingForm(variants, root) {
    // Generate the rating rows for each product
    let rows = variants.map(variant => `
        <section class="border-b py-4" id="rating-product-${variant.id}">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between sm:gap-4">
                <!-- Product Image and Details -->
                <section class="flex justify-center sm:justify-start items-center gap-3">
                    <section class="bg-secondary border shadow rounded w-fit">
                        <img src="${root}assets/products/${variant.img}" alt="img" class="w-12 h-12 lg:w-24 lg:h-24 rounded">
                    </section>
                    <section class="flex flex-col">
                        <span class="text-dark font-semibold uppercase text-sm md:text-base tracking-tighter truncate">${variant.product_name}</span>
                        <span class="text-dark text-xs md:text-sm tracking-tighter truncate">${variant.name}</span>
                    </section>
                </section>
                
                <!-- Star Rating (shown below details on mobile) -->
                <div class="flex gap-2 justify-center sm:justify-end mt-2 sm:mt-0">
                    <input type="hidden" name="rating[${variant.id}]" id="rating-${variant.id}" value="0" />
                    ${[1, 2, 3, 4, 5].map(value => `
                        <span class="star text-3xl lg:text-4xl cursor-pointer text-light-gray interactive hover:text-yellow-400 select-none" data-value="${value}" onclick="setRating(${variant.id}, ${value})">★</span>
                    `).join('')}
                </div>
            </div>
        </section>
    `).join('');

    // Full content with the rating form
    let content = `
        <section class="border-b border-light-dark pb-4">
            <h2 class="text-xl font-semibold text-dark tracking-tighter">We Value Your Feedback</h2>
        </section>

        <form id="reviewForm" class="flex flex-col gap-4">
            <p class="text-light-dark text-xs md:text-sm tracking-tighter">
                Your opinion matters to us! Share your thoughts about these products, and as a token of our appreciation, you’ll receive an exclusive coupon code for your next purchase.
            </p>
            
            <!-- Inject dynamic rows here -->
            <section id="product-rating-container" class="flex flex-col gap-4">
                ${rows}
            </section>

            <section class="flex justify-end items-center gap-2 pt-4">
                <button type="button" onclick="reviewLater(${root})" class="w-fit bg-primary interactive text-accent font-semibold py-2 px-6 rounded shadow">Maybe Later</button>
                <button type="submit" class="w-fit bg-accent interactive text-white font-semibold py-2 px-6 rounded shadow">Submit</button>
            </section>
        </form>

    `;

    // Pass the content to the modal
    openOverlayModal(content);

    const form = document.getElementById('reviewForm');
    form.addEventListener('submit', (event) => handleReviewFormSubmit(event, root));
}


function setRating(productId, value) {
    // Update the hidden input's value
    const input = document.getElementById(`rating-${productId}`);
    input.value = value;

    // Update star colors
    const stars = document.querySelectorAll(`#rating-product-${productId} .star`);
    stars.forEach(star => {
        const starValue = parseInt(star.getAttribute('data-value'), 10);
        if (starValue <= value) {
            star.classList.add('text-yellow-400', 'animate-pulse'); // Add gold color
            star.classList.remove('text-light-gray'); // Remove gray
        } else {
            star.classList.add('text-light-gray'); // Add gray color
            star.classList.remove('text-yellow-400', 'animate-pulse'); // Remove gold
        }
    });
}

function handleReviewFormSubmit(event, root) {
    // Prevent the default form submission (page reload)
    event.preventDefault();

    // Create an object to store the ratings
    const ratings = {};

    // Get all hidden input fields representing ratings
    const ratingInputs = document.querySelectorAll('[id^="rating-"]');
    ratingInputs.forEach(input => {
        const productId = input.id.split('-')[1]; // Extract the product ID from the input ID
        const ratingValue = input.value; // Get the rating value
        if (ratingValue > 0) { // Include only rated products
            ratings[productId] = ratingValue;
        }
    });

    // Prepare the data to send
    const requestData = {
        ratings: ratings
    };

    // Debugging log
    console.log('Ratings to Submit:', requestData);

    axios.post(root + "api/review-submission", requestData)
        .then(res => {
            let data = res.data;
            if (data.success) {
                //display discount code as the reward
                renderSuccessModal(root + "assets/illustrations/order_complete.svg", "Your discount code is ready! Please keep it in a safe place and use it during checkout. Your discount code:", data.code);
            } else {
                //display error message
                renderErrorModal(root);
            }
            
        })
        .catch(err => console.log(err));

}

function reviewLater(root)
{
    axios.post(root + "api/review-later", {"review_later": true});
    forceOverlayToClose();
}
