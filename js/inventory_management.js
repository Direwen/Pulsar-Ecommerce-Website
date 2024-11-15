function createInventory(submissionPath, variants) {

    const variantOptions = variants.map(variant =>
        `<option value="${variant.id}">${variant.product_name} (${variant.name})</option>`
    ).join('');

    const content = `
        <section class="border-b border-light-dark pb-4">
            <h2 class="text-xl font-semibold text-dark">Create a new inventory</h2>
        </section>

        <form action="${submissionPath}" method="POST" class="flex flex-col gap-4">
            <!-- Short Code Input -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="short_code" class="block text-sm font-medium text-dark">Short Code</label>
                <input type="text" name="code" class="block w-full border shadow rounded outline-accent p-2" placeholder="Enter short code">
            </section>

            <!-- Variant Selector -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="variant" class="block text-sm font-medium text-dark">Select Product</label>
                <select name="variant_id" class="block w-full border shadow rounded outline-accent p-2">
                    ${variantOptions}
                </select>
            </section>

            <!-- Stock Quantity Input -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="stock_quantity" class="block text-sm font-medium text-dark">Stock Quantity</label>
                <input type="number" name="stock_quantity" class="block w-full border shadow rounded outline-accent p-2" min="0" placeholder="Enter stock quantity">
            </section>

            <!-- Reorder Level Input -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="reorder_level" class="block text-sm font-medium text-dark">Reorder Level</label>
                <input type="number" name="reorder_level" class="block w-full border shadow rounded outline-accent p-2" min="0" placeholder="Enter reorder level">
            </section>

            <!-- Buttons -->
            <section class="flex justify-end items-center gap-2">
                <button type="button" onclick="forceOverlayToClose()" class="w-fit bg-primary interactive text-accent font-semibold py-2 px-6 rounded shadow">Cancel</button>
                <button type="submit" class="w-fit bg-accent interactive text-primary font-semibold py-2 px-6 rounded shadow">Create</button>
            </section>
        </form>`;

    openOverlayModal(content);
}

function deleteInventory(recordId, code, submissionPath) {
    const content = `<section class="border-b border-light-dark pb-4">
            <h2 class="text-xl font-semibold text-dark">Confirm Deletion</h2>
        </section>
        
        <form action="${submissionPath}" method="POST" class="flex flex-col gap-4">
            <p class="text-dark">Are you sure you want to delete this inventory: <span class="font-bold">${code}</span>?</p>
            <section class="flex justify-end items-center gap-2">
                <input type="hidden" name="id" value="${recordId}">
                <button type="button" onclick="forceOverlayToClose()" class="w-fit bg-primary interactive text-accent font-semibold py-2 px-6 rounded shadow">Cancel</button>
                <button type="submit" class="w-fit bg-danger interactive text-primary font-semibold py-2 px-6 rounded shadow">Delete</button>
            </section>
        </form>`;

    openOverlayModal(content);
}

function editInventory(recordId, submissionPath, current_values, variants) {
    // Generate options for the select dropdown using the available variants
    const variantOptions = variants.map(variant =>
        `<option value="${variant.id}" ${variant.id == current_values.variantId ? 'selected' : ''}>${variant.product_name} (${variant.name})</option>`
    ).join('');

    // Create the modal content with the correct fields for editing inventory
    const content = `
        <section class="border-b border-light-dark pb-4">
            <h2 class="text-xl font-semibold text-dark">Edit Inventory</h2>
        </section>

        <form action="${submissionPath}" method="POST" class="flex flex-col gap-4">
            <!-- Hidden Field for Inventory ID -->
            <input type="hidden" name="id" value="${recordId}">

            <!-- Short Code Input -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="short_code" class="block text-sm font-medium text-dark">Short Code</label>
                <input type="text" name="code" value="${current_values.code}" class="block w-full border shadow rounded outline-accent p-2" placeholder="Enter short code">
            </section>

            <!-- Variant Selector -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="variant" class="block text-sm font-medium text-dark">Select Product</label>
                <select name="variant_id" class="block w-full border shadow rounded outline-accent p-2">
                    ${variantOptions}
                </select>
            </section>

            <!-- Stock Quantity Input -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="stock_quantity" class="block text-sm font-medium text-dark">Stock Quantity</label>
                <input type="number" name="stock_quantity" value="${current_values.stockQuantity}" class="block w-full border shadow rounded outline-accent p-2" min="0" placeholder="Enter stock quantity">
            </section>

            <!-- Reorder Level Input -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="reorder_level" class="block text-sm font-medium text-dark">Reorder Level</label>
                <input type="number" name="reorder_level" value="${current_values.reorderLevel}" class="block w-full border shadow rounded outline-accent p-2" min="0" placeholder="Enter reorder level">
            </section>

            <!-- Buttons -->
            <section class="flex justify-end items-center gap-2">
                <button type="button" onclick="forceOverlayToClose()" class="w-fit bg-primary interactive text-accent font-semibold py-2 px-6 rounded shadow">Cancel</button>
                <button type="submit" class="w-fit bg-accent interactive text-primary font-semibold py-2 px-6 rounded shadow">Update</button>
            </section>
        </form>`;

    // Display the overlay with the generated content
    openOverlayModal(content);
}



document.querySelectorAll('.create-inventory-button').forEach(button => {
    button.addEventListener('click', function () {
        const submissionPath = this.getAttribute('submission-path');
        const apiForVariants = this.getAttribute('api-for-variants');

        axios.get(apiForVariants)
            .then(response => {
                console.log(response);
                createInventory(submissionPath, response.data.variants)
            })
            .catch(error => {
                console.log(error);
            });
    });
});

document.querySelectorAll('.delete-inventory-button').forEach(button => {
    button.addEventListener('click', function () {
        const recordId = this.getAttribute('data-id');
        const code = this.getAttribute('code');
        const submissionPath = this.getAttribute('submission-path');
        deleteInventory(recordId, code, submissionPath);
    })
})

document.querySelectorAll('.edit-inventory-button').forEach(button => {
    button.addEventListener('click', function () {
        const recordId = this.getAttribute('data-id');
        const submissionPath = this.getAttribute('submission-path');
        const apiForVariants = this.getAttribute('api-for-variants');

        axios.get(apiForVariants)
            .then(response => {
                // Collect all attributes in an object to pass to editCategory
                const inventoryDetails = {
                    code: this.getAttribute('code'),
                    variantId: this.getAttribute('variant_id') ?? "",
                    stockQuantity: this.getAttribute('stock_quantity') ?? "",
                    reorderLevel: this.getAttribute('reorder_level') ?? "",
                    rootDirectory: this.getAttribute('root-directory')
                };

                // Call the editCategory function with the object
                editInventory(recordId, submissionPath, inventoryDetails, response.data.variants);
            })
            .catch(error => {
                console.log(error);
            });
    });
});