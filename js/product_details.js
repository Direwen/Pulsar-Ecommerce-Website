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

    var parentContainer = document.getElementById("variants-selections-container"); // Get the container by class
    var previouslySelected = parentContainer.querySelector(".border-accent"); // Scope query to container
    if (previouslySelected) {
        previouslySelected.classList.remove("border-accent");
    }
    selector.classList.add("border-accent");
}

function toggleDownloadModal(btn) {
    console.log('clicked');
    var root = btn.getAttribute('root-directory');
    var id = btn.getAttribute('product-id');
    axios.post(root + 'api/category', {id : id})
        .then(res => {
            if (!res.data.record) {
                openOverlayModal("Nothing");
                return;
            }
            
            renderDownloadModal(res.data.record, root);
        })
        .catch(err => {
            console.log(err);
        });
}

function renderDownloadModal(record, root) {
    var content = `
        <div class="flex justify-end items-center">
            <span class="material-symbols-outlined interactive" onclick="forceOverlayToClose()">close</span>
        </div>
        <div class="relative w-full min-h-[10rem] lg:min-h-[15rem] max-h-screen bg-[url('${root}assets/categories/${record.category_banner_img}')] bg-cover bg-center rounded-lg shadow">
            <!-- Overlay -->
            <section class="absolute top-0 left-0 w-full h-full transition-all duration-1000 ease-in-out bg-dark/70 p-4 sm:px-20 sm:py-6 flex justify-center items-center rounded-lg">
                <!-- Product Link -->
                <section class="uppercase">
                    <h1 class="text-2xl sm:text-4xl lg:text-6xl text-primary font-thin tracking-tighter">${record.category_name}</h1>
                </section>
            </section>
        </div>

        <div class="flex flex-wrap justify-evenly gap-2 p-4 text-xs sm:text-sm font-semibold">
            <button root-directory="${root}" onclick="updateDownloadContent(this, 'software', '${record.category_software || ''}')" class="text-secondary bg-accent px-6 py-2 sm:px-8 sm:py-2 rounded-full interactive">
                SOFTWARE
            </button>
            <button root-directory="${root}" onclick="updateDownloadContent(this, 'firmware', '${record.category_firmware || ''}')" class="text-secondary bg-dark px-6 py-2 sm:px-8 sm:py-2 rounded-full interactive">
                FIRMWARE
            </button>
            <button root-directory="${root}" onclick="updateDownloadContent(this, 'manual', '${record.category_manual || ''}')" class="text-secondary bg-dark px-6 py-2 sm:px-8 sm:py-2 rounded-full interactive">
                MANUAL
            </button>
        </div>
        <div class="p-4 sm:p-6 text-center bg-primary border shadow flex flex-col items-center gap-4" id="download-content-container">
            ${generateContent(root, 'software', record.category_software)}
        </div>
    `;

    openOverlayModal(content);
}

function updateDownloadContent(btn, state, link) {
    const root = btn.getAttribute('root-directory');

    // Find all sibling buttons and remove the 'bg-accent' class from them
    const allButtons = btn.parentElement.querySelectorAll('button');
    allButtons.forEach(button => {
        button.classList.remove('bg-accent');
        button.classList.add('bg-dark'); // Reset to default background
    });

    // Add 'bg-accent' to the currently clicked button
    btn.classList.remove('bg-dark');
    btn.classList.add('bg-accent');

    // Update the download content container
    const container = document.getElementById('download-content-container');
    container.innerHTML = generateContent(root, state, link);
}

function generateContent(root, state, link) {
    if (!link) {
        return `
            <div class="flex flex-col items-center gap-4">
                <p class="text-xs sm:text-sm text-danger font-medium">This feature is not available for this product.</p>
            </div>
        `;
    }

    switch (state) {
        case 'software':
            return `
                <div class="flex flex-col items-center gap-4">
                    <img src="${root}assets/pulsar_icon.webp" alt="Pulsar Icon" class="w-16 h-16 sm:w-20 sm:h-20">
                    <button class="bg-accent text-primary interactive px-4 py-2 sm:px-6 sm:py-2 rounded interactive">
                        <a href="${link}" target="_blank">Download Pulsar Software</a>
                    </button>
                    <p class="text-xs sm:text-sm text-dark">Optimize your device's performance and features.</p>
                </div>
            `;
        case 'firmware':
            return `
                <div class="flex flex-col items-center gap-4">
                    <button class="bg-accent text-primary interactive px-4 py-2 sm:px-6 sm:py-2 rounded interactive">
                        <a href="${link}" target="_blank">Download Latest Firmware</a>
                    </button>
                    <p class="text-xs sm:text-sm text-dark">Keep your device up to date with the latest features and fixes.</p>
                </div>
            `;
        case 'manual':
            return `
                <div class="flex flex-col items-center gap-4">
                    <a href="${link}" target="_blank" class="bg-secondary border shadow interactive px-3 py-2 sm:px-4 sm:py-3">
                        <span class="material-symbols-outlined text-3xl sm:text-4xl">picture_as_pdf</span>
                    </a>
                    <p class="text-xs sm:text-sm text-dark">Learn how to get the most out of your device.</p>
                </div>
            `;
    }
}


// Helper function to capitalize the state name
function capitalize(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
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

