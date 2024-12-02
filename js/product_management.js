function createProduct(submissionPath, extra_info) {

    // Create options for categories dynamically
    const categoryOptions = extra_info.categories.map(category =>
        `<option value="${category.id}">${category.name}</option>`
    ).join('');

    const productOptions = extra_info.products.map(product =>
        `<option value="${product.id}">${product.name}</option>`
    ).join('');

    const content = `<section class="border-b border-light-dark pb-4">
            <h2 class="text-xl font-semibold text-dark">Create Product</h2>
        </section>

        <!-- Form to choose between creating a new product or adding a variant -->
        <section class="flex items-center gap-4 my-2">
            <label for="productOption" class="block text-sm font-medium text-dark">Select an option:</label>
            <select id="productOption" class="block grow border border-light-gray rounded-md p-2 shadow outline-accent" onchange="toggleProductForm()">
                <option value="create">Create New Product</option>
                <option value="addVariant">Add Variant to Existing Product</option>
            </select>
        </section>

        <form action="${submissionPath}" method="POST" class="flex flex-col gap-4" enctype="multipart/form-data">

            <!-- Hidden input to indicate the action type -->
            <input type="hidden" id="actionType" name="action-type" value="create">

            <!-- Existing Product Selection (Visible only when adding a variant) -->
            <div id="existingProductForm" class="flex justify-start items-center gap-2 hidden">
                <label for="product-id" class="block text-sm font-medium text-dark mb-1">Select Product</label>
                <select name="product-id" id="productSelection" class="block grow border border-light-gray rounded-md p-2 shadow">
                    ${productOptions}
                </select>
            </div>

            <!-- Product Creation Section -->
            <div id="createProductForm" class="flex flex-col gap-4">

                <section class="block text-sm font-medium text-dark flex flex-col gap-2">
                    <label for="product-name">Product Name</label>
                    <input required type="text" name="product-name" id="product-name" class="block w-full border shadow rounded outline-accent p-2" placeholder="X2A Gaming Mouse">
                </section>

                <section class="flex justify-start items-center gap-2">
                    <label for="category" class="block text-sm font-medium text-dark mb-1">Category</label>
                    <select required name="category" id="category" class="block grow border border-light-gray rounded-md p-2 shadow">
                        ${categoryOptions}
                    </select>
                </section>

                <!-- Description and other inputs -->
                <section class="block text-sm font-medium text-dark flex flex-col gap-2">
                    <label for="description">Description</label>
                    <textarea required name="description" id="description" class="block w-full border shadow rounded outline-accent p-2" placeholder="describe the product"></textarea>
                </section>

                <section class="block text-sm font-medium text-dark flex flex-col gap-2">
                    <label>Dimensions (mm/g)</label>
                    <div class="flex border rounded overflow-hidden shadow">
                        <input
                            required
                            type="number" 
                            name="dimension[length]" 
                            id="length" 
                            placeholder="Length (mm)" 
                            min="10" 
                            max="800" 
                            step="0.01" 
                            class="w-full p-2 text-center border-b-2 outline-none border-transparent focus:border-accent hover:border-accent"
                        />
                        <input 
                            required
                            type="number" 
                            name="dimension[width]" 
                            id="width" 
                            placeholder="Width (mm)" 
                            min="10" 
                            max="500" 
                            step="0.01" 
                            class="w-full p-2 text-center border-b-2 outline-none border-transparent focus:border-accent hover:border-accent"
                        />
                        <input 
                            required
                            type="number" 
                            name="dimension[height]" 
                            id="height" 
                            placeholder="Height (mm)" 
                            min="5" 
                            max="200" 
                            step="0.01" 
                            class="w-full p-2 text-center border-b-2 outline-none border-transparent focus:border-accent hover:border-accent"
                        />
                        <input 
                            required
                            type="number" 
                            name="dimension[weight]" 
                            id="weight" 
                            placeholder="Weight (g)" 
                            min="5" 
                            max="5000" 
                            step="0.1" 
                            class="w-full p-2 text-center border-b-2 outline-none border-transparent focus:border-accent hover:border-accent"
                        />
                    </div>
                </section>

                <section class="block text-sm font-medium text-dark">
                    <label for="requirement">Requirement (separate by commas)</label>
                    <textarea name="requirement" id="requirement" class="block w-full border shadow rounded outline-accent p-2" placeholder="e.g., Requirement1, Requirement2, Requirement3"></textarea>
                </section>

                <section class="block text-sm font-medium text-dark">
                    <label for="package-content">Package Content (separate by commas)</label>
                    <textarea required name="package-content" id="package-content" class="block w-full border shadow rounded outline-accent p-2" placeholder="e.g., Cable, Manual, Warranty Card"></textarea>
                </section>

                <!-- Feature Input -->
                <section class="block text-sm font-medium text-dark flex flex-col gap-2">
                    <label for="feature">Feature (separate by commas)</label>
                    <input required type="text" name="feature" id="feature-input" class="block w-full border shadow rounded outline-accent p-2" placeholder="e.g., Waterproof, Wireless, 10-hour battery life">
                </section>

                <!-- Special Feature Input -->
                <section id="special-feature-section" class="flex flex-col gap-2 text-sm font-medium text-dark">
                    <label>Special Features</label>

                    <!-- Container for dynamic special feature entries -->
                    <div id="special-feature-container" class="flex flex-col gap-4"></div>

                    <!-- Button to add a new category for special features -->
                    <button type="button" onclick="addSpecialFeatureCategory()" class="interactive w-fit bg-primary text-accent font-semibold py-2 px-4 rounded shadow">+ Add Special Feature</button>
                </section>

                <!-- Image Inputs -->
                <section class="flex flex-col justify-start items-start gap-2">
                    <label for="img" class="block text-sm font-medium text-dark">Images for Ads</label>
                    <input required multiple type="file" name="img[]" id="img" accept="image/*" class="block w-full bg-primary border shadow rounded outline-accent p-2">
                </section>

                <section class="flex flex-col justify-start items-start gap-2">
                    <label for="main-img" class="block text-sm font-medium text-dark">Main Image</label>
                    <input required type="file" name="main-img" id="main-img" accept="image/*" class="block w-full bg-primary border shadow rounded outline-accent p-2">
                </section>
                
            </div>

            <!-- Variant Section (Visible for both options) -->
            <section class="border-b border-light-dark pb-4 mb-2">
                <h2 class="text-xl font-semibold text-dark">Add Product Variant</h2>
            </section>

            <div id="variantForm" class="block text-sm font-medium text-dark flex flex-col gap-4">
                <div id="variantsContainer" class="flex flex-col gap-6">
                    <!-- Variant Fields -->
                    <div class="variant-item relative flex flex-col gap-4 border shadow px-4 py-6" data-variant-id="1">
                        <section class="block text-sm font-medium text-dark flex flex-col gap-2">
                            <label for="variantType_1" class="text-gray-700">Type</label>
                            <input type="text" id="variantType_1" name="variants[0][type]" placeholder="Color" class="block w-full border shadow rounded outline-accent p-2" required>
                        </section>
                        <section class="block text-sm font-medium text-dark flex flex-col gap-2">
                            <label for="variantName_1" class="text-gray-700">Name</label>
                            <input type="text" id="variantName_1" name="variants[0][name]" placeholder="White" class="block w-full border shadow rounded outline-accent p-2" required>
                        </section>
                        <section class="block text-sm font-medium text-dark flex flex-col gap-2">
                            <label for="variantUnitPrice_1" class="text-gray-700">Unit Price</label>
                            <input 
                                type="number" 
                                step="0.01" 
                                min="0.01" 
                                max="10000" 
                                id="variantUnitPrice_1" 
                                name="variants[0][unit_price]" 
                                class="block w-full border shadow rounded outline-accent p-2" 
                                required
                            >
                        </section>
                        <section class="block text-sm font-medium text-dark flex flex-col gap-2">
                            <label for="variantImg_1" class="text-gray-700">Main Image of the variant</label>
                            <input type="file" accept="image/*" id="variantImg_1" name="variants_img[0]" class="block w-full bg-primary border shadow rounded outline-accent p-2" required>
                        </section>
                        <section class="block text-sm font-medium text-dark flex flex-col gap-2">
                            <label for="variantImg_1" class="text-gray-700">Ads Images of the variant</label>
                            <input multiple type="file" accept="image/*" id="variantImg_1" name="variants_img_for_ads[0][]" class="block w-full bg-primary border shadow rounded outline-accent p-2" required>
                        </section>
                        <button type="button" class="absolute -top-3 -right-2 w-fit interactive bg-secondary text-light-dark font-semibold rounded-full px-1 border shadow hover:bg-red-500 hover:text-primary" onclick="removeVariant(this)">
                            <span class="material-symbols-outlined">remove</span>
                        </button>
                    </div>
                </div>
                <!-- Button to add new variant dynamically -->
                <button type="button" onclick="addVariant()" class="w-fit bg-accent interactive text-primary font-semibold py-2 px-6 rounded shadow">+ Add Variant</button>
            </div>

            <section class="flex justify-end items-center gap-2">
                <button type="button" onclick="forceOverlayToClose()" class="w-fit bg-primary interactive text-accent font-semibold py-2 px-6 rounded shadow">Cancel</button>
                <button type="submit" class="w-fit bg-accent interactive text-primary font-semibold py-2 px-6 rounded shadow">Update</button>
            </section>
        </form>`;

    openOverlayModal(content);
}

function editProduct(recordId, submissionPath, categories, currentValues) {

    const categoryOptions = categories.map(category => {
        const isSelected = category.id === currentValues.categoryId;
        return `<option value="${category.id}" ${isSelected ? 'selected' : ''}>${category.name}</option>`;
    }).join('');

    const adImagesHTML = currentValues.adsImage?.map((image, index) =>
        `<div class="w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 overflow-hidden rounded shadow border border-gray-200">
            <img src="${currentValues.rootDirectory + 'assets/products/' + image}" alt="Ad Image ${index + 1}" class="object-cover w-full h-full">
        </div>`
    ).join('') ?? '';
    
    const variantImagesHtml = currentValues.variantImageForAds?.map((image, index) =>
        `<div class="w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 overflow-hidden rounded shadow border border-gray-200">
            <img src="${currentValues.rootDirectory + 'assets/products/' + image}" alt="Variant Image ${index + 1}" class="object-cover w-full h-full">
        </div>`
    ).join('') ?? '';

    const specialsContent = Object.entries(currentValues.specials ?? {}).map(([title, details], featureCount) =>
        `<div class="special-feature-item relative flex flex-col gap-4 border shadow px-4 py-6" data-feature-id="${featureCount}">
            <section class="block text-sm font-medium text-dark flex flex-col gap-2">
                <label for="featureCategory_${featureCount}" class="text-gray-700">Title</label>
                <input type="text" id="featureCategory_${featureCount}" name="special-feature-title[]" value="${title}" placeholder="Category (e.g., Sensor)" class="block w-full border shadow rounded outline-accent p-2" required>
            </section>
            <section class="block text-sm font-medium text-dark flex flex-col gap-2">
                <label for="featureDescription_${featureCount}" class="text-gray-700">Details</label>
                <textarea id="featureDescription_${featureCount}" name="special-feature-details[]" placeholder="Feature Details" class="block w-full border shadow rounded outline-accent p-2" required>${Object.values(details).join(", ")}</textarea>
            </section>
            <button type="button" class="absolute -top-3 -right-2 w-fit interactive bg-secondary text-light-dark font-semibold rounded-full px-1 border shadow hover:bg-red-500 hover:text-primary" onclick="removeFeature(this)">
                <span class="material-symbols-outlined">remove</span>
            </button>
        </div>`
    ).join('');

    const content = `<section class="border-b border-light-dark pb-4">
            <h2 class="text-xl font-semibold text-dark">Edit Product</h2>
        </section>
        <form action="${submissionPath}" method="POST" class="flex flex-col gap-4" enctype="multipart/form-data">
            <input type="hidden" name="id" value="${recordId}">
            <input type="hidden" name="product-id" value="${currentValues.productId}">
            <section class="flex flex-col gap-2">
                <label for="product-name" class="text-sm font-medium text-dark">Product Name</label>
                <input required type="text" name="product-name" value="${currentValues.productName}" class="w-full border shadow rounded outline-accent p-2" placeholder="X2A Gaming Mouse">
            </section>
            <section class="flex justify-start items-center gap-2">
                <label for="category" class="text-sm font-medium text-dark">Category</label>
                <select required name="category" class="grow border border-light-gray rounded-md p-2 shadow">
                    ${categoryOptions}
                </select>
            </section>
            <section class="flex flex-col gap-2">
                <label for="description" class="text-sm font-medium text-dark">Description</label>
                <textarea required name="description" class="w-full border shadow rounded outline-accent p-2" placeholder="describe the product">${currentValues.description ?? ""}</textarea>
            </section>

            <section class="block text-sm font-medium text-dark flex flex-col gap-2">
                <label>Dimensions (mm/g)</label>
                <div class="flex border rounded overflow-hidden shadow">
                    <input
                        required 
                        type="number" 
                        value="${currentValues.dimension.length}" 
                        name="dimension[length]" 
                        id="length" 
                        placeholder="Length (mm)" 
                        min="10" 
                        max="800" 
                        step="0.01" 
                        class="w-full p-2 text-center border-b-2 outline-none border-transparent focus:border-accent hover:border-accent"
                    />
                    <input 
                        required
                        type="number" 
                        value="${currentValues.dimension.width}" 
                        name="dimension[width]" 
                        id="width" 
                        placeholder="Width (mm)" 
                        min="10" 
                        max="500" 
                        step="0.01" 
                        class="w-full p-2 text-center border-b-2 outline-none border-transparent focus:border-accent hover:border-accent"
                    />
                    <input 
                        required
                        type="number" 
                        value="${currentValues.dimension.height}" 
                        name="dimension[height]" 
                        id="height" 
                        placeholder="Height (mm)" 
                        min="5" 
                        max="200" 
                        step="0.01" 
                        class="w-full p-2 text-center border-b-2 outline-none border-transparent focus:border-accent hover:border-accent"
                    />
                    <input 
                        required
                        type="number" 
                        value="${currentValues.dimension.weight}" 
                        name="dimension[weight]" 
                        id="weight" 
                        placeholder="Weight (g)" 
                        min="5" 
                        max="5000" 
                        step="0.1" 
                        class="w-full p-2 text-center border-b-2 outline-none border-transparent focus:border-accent hover:border-accent"
                    />
                </div>
            </section>


            <section class="flex flex-col gap-2">
                <label for="feature" class="text-sm font-medium text-dark">Feature (separate by commas)</label>
                <input required type="text" name="feature" value="${currentValues.feature ? Object.values(currentValues.feature).join(", ") : ""}" class="w-full border shadow rounded outline-accent p-2" placeholder="e.g., Waterproof, Wireless, 10-hour battery life">
            </section>
            <section class="flex flex-col gap-2">
                <label>Special Features</label>
                <div id="special-feature-container" class="flex flex-col gap-4">
                    ${specialsContent}
                </div>
                <button type="button" onclick="addSpecialFeatureCategory()" class="w-fit bg-primary text-accent font-semibold py-2 px-4 rounded shadow">+ Add Special Feature</button>
            </section>
            <section class="flex flex-col gap-2">
                <label for="requirement" class="text-sm font-medium text-dark">Requirement (separate by commas)${currentValues.requirement}</label>
                <textarea name="requirement" class="w-full border shadow rounded outline-accent p-2" placeholder="e.g., Requirement1, Requirement2, Requirement3">${currentValues.requirement ? Object.values(currentValues.requirement).join(", ") : ""}</textarea>
            </section>
            <section class="flex flex-col gap-2">
                <label for="package-content" class="text-sm font-medium text-dark">Package Content (separate by commas)</label>
                <textarea required name="package-content" class="w-full border shadow rounded outline-accent p-2" placeholder="e.g., Cable, Manual, Warranty Card">${currentValues.packageContent ? Object.values(currentValues.packageContent).join(", ") : ""}</textarea>
            </section>
            <section class="flex flex-col gap-2">
                <label for="img" class="text-sm font-medium text-dark">Images for Ads</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    ${adImagesHTML}
                </div>
                <label class="flex items-center gap-2 mt-2">
                    <input type="checkbox" name="changeImage" data-toggle="newAdsImageInput" class="accent-accent" onclick="toggleImageInput(this)">
                    <span>Change Image</span>
                </label>
                <input multiple type="file" name="img_for_ads[]" accept="image/*" data-toggle="newAdsImageInput" class="block w-full bg-primary border shadow rounded outline-accent p-2 mt-2 hidden">
            </section>
            <section class="flex flex-col justify-start items-start gap-2">
                <label class="block text-sm font-medium text-dark">Current Main Image</label>
                <img src="${currentValues.rootDirectory + 'assets/products/' + currentValues.productImage}" alt="Current Image" class="w-32 h-32 object-cover border rounded">
                <label class="flex items-center gap-2 mt-2">
                    <input type="checkbox" name="changeImage" data-toggle="newImageInput" class="accent-accent" onclick="toggleImageInput(this)">
                    <span>Change Image</span>
                </label>
                <input type="file" name="img" accept="image/*" data-toggle="newImageInput" class="block w-full bg-primary border shadow rounded outline-accent p-2 mt-2 hidden">
            </section>
            <section class="border-b border-light-dark pb-4 mb-2">
                <h2 class="text-xl font-semibold text-dark">Edit Product Variants</h2>
            </section>
            <section id="variant-section" class="flex flex-col gap-4">
                <div id="variantsContainer" class="flex flex-col gap-6">
                    <div class="variant-item flex flex-col gap-4 border shadow px-4 py-6" data-variant-id="1">
                        <section class="flex flex-col gap-2">
                            <label for="variantType_1" class="text-sm font-medium text-dark">Type</label>
                            <input type="text" id="variantType_1" name="variants[0][type]" value="${currentValues.variantType}" class="w-full border shadow rounded outline-accent p-2">
                        </section>
                        <section class="flex flex-col gap-2">
                            <label for="variantName_1" class="text-sm font-medium text-dark">Name</label>
                            <input type="text" id="variantName_1" name="variants[0][name]" value="${currentValues.variantTypeName}" class="w-full border shadow rounded outline-accent p-2">
                        </section>
                        <section class="flex flex-col gap-2">
                            <label for="variantUnitPrice_1" class="text-sm font-medium text-dark">Unit Price</label>
                            <input 
                                type="number" 
                                step="0.01" 
                                min="0.01" 
                                max="10000" 
                                id="variantUnitPrice_1" 
                                name="variants[0][unit_price]" 
                                value="${currentValues.unitPrice}" 
                                class="w-full border shadow rounded outline-accent p-2"
                            >
                        </section>
                        <section class="flex flex-col justify-start items-start gap-2">
                            <label class="block text-sm font-medium text-dark">Current Main Image</label>
                            <img src="${currentValues.rootDirectory + 'assets/products/' + currentValues.variantImage}" alt="Current Image" class="w-32 h-32 object-cover border rounded">
                            <label class="flex items-center gap-2 mt-2">
                                <input type="checkbox" name="changeImage" data-toggle="newVariantImageInput" class="accent-accent" onclick="toggleImageInput(this)">
                                <span>Change Image</span>
                            </label>
                            <input type="file" name="variants_img[0]" accept="image/*" data-toggle="newVariantImageInput" class="block w-full bg-primary border shadow rounded outline-accent p-2 mt-2 hidden">
                        </section>

                        <section class="flex flex-col gap-2">
                            <label for="img" class="text-sm font-medium text-dark">Images for this Variant</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                ${variantImagesHtml}
                            </div>
                            <label class="flex items-center gap-2 mt-2">
                                <input type="checkbox" name="changeImage" data-toggle="newVariantAdsImageInput" class="accent-accent" onclick="toggleImageInput(this)">
                                <span>Change Image</span>
                            </label>
                            <input multiple type="file" name="variants_img_for_ads[0][]" accept="image/*" data-toggle="newVariantAdsImageInput" class="block w-full bg-primary border shadow rounded outline-accent p-2 mt-2 hidden">
                        </section>

                    </div>
                </div>
            </section>
            <section class="flex justify-end items-center gap-2">
                <button type="button" onclick="forceOverlayToClose()" class="w-fit bg-primary interactive text-accent font-semibold py-2 px-6 rounded shadow">Cancel</button>
                <button type="submit" class="w-fit bg-accent interactive text-primary font-semibold py-2 px-6 rounded shadow">Update</button>
            </section>
        </form>`;

    openOverlayModal(content);
}


function deleteProduct(recordId, name, productId, submissionPath) {
    const content = `<section class="border-b border-light-dark pb-4">
            <h2 class="text-xl font-semibold text-dark">Confirm Deletion</h2>
        </section>
        
        <form action="${submissionPath}" method="POST" class="flex flex-col gap-4">
            <p class="text-dark">Are you sure you want to delete the product variant: <span class="font-bold">${name}</span>?</p>
            <section class="flex justify-end items-center gap-2">
                <input type="hidden" name="id" value="${recordId}">
                <input type="hidden" name="product_id" value="${productId}">
                <button type="button" onclick="forceOverlayToClose()" class="w-fit bg-primary interactive text-accent font-semibold py-2 px-6 rounded shadow">Cancel</button>
                <button type="submit" class="w-fit bg-danger interactive text-primary font-semibold py-2 px-6 rounded shadow">Delete</button>
            </section>
        </form>`;

    openOverlayModal(content);
}

document.querySelectorAll('.create-product-button').forEach(button => {
    button.addEventListener('click', function () {
        const submissionPath = this.getAttribute('submission-path');
        const apiForCategories = this.getAttribute('api-for-categories');
        const apiForProducts = this.getAttribute('api-for-products');

        // Fetch both categories and products
        Promise.all([
            axios.get(apiForCategories).then(response => response.data.categories ?? []),
            axios.get(apiForProducts).then(response => response.data.products ?? [])
        ])
            .then(([categories, products]) => {
                // Only call createProduct after both requests complete
                createProduct(submissionPath, { "products": products, "categories": categories });
            })
            .catch(error => console.log("Error with fetching data:", error));
    });
});

document.querySelectorAll('.edit-product-button').forEach(button => {
    button.addEventListener('click', function () {
        const recordId = this.getAttribute('data-id');
        const submissionPath = this.getAttribute('submission-path');
        const apiForCategories = this.getAttribute('path-for-api');

        const productDetails = {
            product: this.getAttribute('product'),
            variantType: this.getAttribute('variant_type'),
            variantTypeName: this.getAttribute('variant_type_name'),
            unitPrice: this.getAttribute('unit_price') ?? "",
            variantImage: this.getAttribute('variant_image'),
            variantImageForAds: parseJSONorString(this.getAttribute('variant_ads_image')),
            productName: this.getAttribute('product') ?? "",
            productId: this.getAttribute('product_id'),
            category: this.getAttribute('category'),
            categoryId: this.getAttribute('category_id'),
            description: this.getAttribute('description'),
            dimension: parseJSONorString(this.getAttribute('dimension')),
            feature: parseJSONorString(this.getAttribute('feature')),
            specials: parseJSONorString(this.getAttribute('specials')),
            requirement: parseJSONorString(this.getAttribute('requirement')),
            packageContent: parseJSONorString(this.getAttribute('package_content')),
            productImage: this.getAttribute('product_image'),
            adsImage: parseJSONorString(this.getAttribute('ads_image')),
            rootDirectory: this.getAttribute('root-directory')
        };

        axios.get(apiForCategories)
            .then(response => {
                editProduct(recordId, submissionPath, response.data.categories, productDetails);
            })
            .catch(error => {
                console.log("Error fetching categories:", error);
            });
    });
});

document.querySelectorAll('.delete-product-button').forEach(button => {
    button.addEventListener('click', function () {
        const recordId = this.getAttribute('data-id');
        const productId = this.getAttribute("product_id");
        const variantName = this.getAttribute('variant_type_name');
        const submissionPath = this.getAttribute('submission-path');
        deleteProduct(recordId, variantName, productId, submissionPath);
    })
})

// Helper function to handle JSON-like attributes
function parseJSONorString(value) {
    try {
        return JSON.parse(value.replace(/&quot;/g, '"'));
    } catch (e) {
        return value;
    }
}

