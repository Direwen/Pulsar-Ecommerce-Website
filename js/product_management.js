function createProduct(submissionPath, categories) {
    // Create options for categories dynamically
    const categoryOptions = categories.map(category => 
        `<option value="${category.id}">${category.name}</option>`
    ).join('');

    const content = `<section class="border-b border-light-dark pb-4">
    <h2 class="text-xl font-semibold text-dark">Create Product</h2>
</section>

<form action="${submissionPath}" method="POST" class="flex flex-col gap-4" enctype="multipart/form-data">

    <section class="block text-sm font-medium text-dark">
        <label for="product-name">Product Name</label>
        <input type="text" name="product-name" id="product-name" class="block w-full border shadow rounded outline-accent p-2">
    </section>

    <section class="flex justify-start items-center gap-2">
        <label for="category" class="block text-sm font-medium text-dark mb-1">Category</label>
        <select name="category" id="category" class="block grow border border-light-gray rounded-md p-2 shadow">
            ${categoryOptions}
        </select>
    </section>

    <section class="block text-sm font-medium text-dark">
        <label for="description">Description</label>
        <textarea name="description" id="description" class="block w-full border shadow rounded outline-accent p-2"></textarea>
    </section>

    <section class="block text-sm font-medium text-dark">
        <label for="dimension">Dimension</label>
        <input type="text" name="dimension" id="dimension" class="block w-full border shadow rounded outline-accent p-2">
    </section>

    <!-- Feature Input -->
    <section class="block text-sm font-medium text-dark">
        <label for="feature">Feature (separate by commas)</label>
        <input type="text" name="feature" id="feature-input" class="block w-full border shadow rounded outline-accent p-2" placeholder="e.g., item1, item2">
    </section>

    <!-- Special Feature Input -->
    <section id="special-feature-section" class="flex flex-col gap-4 text-sm font-medium text-dark">
        <label>Special Features</label>

        <!-- Container for dynamic special feature entries -->
        <div id="special-feature-container"></div>

        <!-- Button to add a new category for special features -->
        <button type="button" onclick="addSpecialFeatureCategory()" class="w-fit bg-primary text-accent font-semibold py-2 px-4 rounded shadow">+ Add Special Feature</button>
    </section>

    <section class="block text-sm font-medium text-dark">
        <label for="requirement">Requirement (separate by commas)</label>
        <textarea name="requirement" id="requirement" class="block w-full border shadow rounded outline-accent p-2"></textarea>
    </section>

    <section class="block text-sm font-medium text-dark">
        <label for="package-content">Package Content (separate by commas)</label>
        <textarea name="package-content" id="package-content" class="block w-full border shadow rounded outline-accent p-2"></textarea>
    </section>

    <section class="flex flex-col justify-start items-start gap-2">
        <label for="img" class="block text-sm font-medium text-dark">Images for Ads</label>
        <input multiple type="file" name="img[]" id="img" accept="image/*" class="block w-full bg-primary border shadow rounded outline-accent p-2">
    </section>

    <section class="flex flex-col justify-start items-start gap-2">
        <label for="main-img" class="block text-sm font-medium text-dark">Main Image</label>
        <input type="file" name="main-img" id="main-img" accept="image/*" class="block w-full bg-primary border shadow rounded outline-accent p-2">
    </section>

    <section class="flex justify-end items-center gap-2">
        <button type="button" onclick="forceOverlayToClose()" class="w-fit bg-primary interactive text-accent font-semibold py-2 px-6 rounded shadow">Cancel</button>
        <button type="submit" class="w-fit bg-accent interactive text-primary font-semibold py-2 px-6 rounded shadow">Update</button>
    </section>
</form>
`;

    showOverlay(content);
}


document.querySelectorAll('.create-product-button').forEach(button => {
    button.addEventListener('click', function () {
        const submissionPath = this.getAttribute('submission-path');
        const apiForCategories = this.getAttribute('path-for-api');

        axios.get(apiForCategories)
        .then(response => {
            createProduct(submissionPath, response.data.categories);  // Now this runs only after the response
        })
        .catch(error => {
            console.log("Error with fetching:", error);
        });
    });
});
