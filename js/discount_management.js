function createDiscount(submissionPath) {

    const content = `
        <section class="border-b border-light-dark pb-4">
            <h2 class="text-xl font-semibold text-dark">Create a new Discount</h2>
        </section>

        <form action="${submissionPath}" method="POST" class="flex flex-col gap-4">
            <!-- Short Code Input -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="code" class="block text-sm font-medium text-dark">Discount Code</label>
                <input type="text" name="code" class="block w-full border shadow rounded outline-accent p-2" placeholder="Enter short code">
            </section>

            <section class="flex flex-col justify-start items-start gap-2">
                <label for="amount" class="block text-sm font-medium text-dark">Percentage</label>
                <input type="number" name="amount" class="block w-full border shadow rounded outline-accent p-2" min="1" max="100" placeholder="Enter stock quantity" required>
            </section>
            
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="max_usage" class="block text-sm font-medium text-dark">Maximum Usage</label>
                <input type="number" name="max_usage" class="block w-full border shadow rounded outline-accent p-2" min="0" placeholder="Enter stock quantity">
            </section>
            
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="expired_at" class="block text-sm font-medium text-dark">Expiry</label>
                <input type="date" name="expired_at" class="block w-full border shadow rounded outline-accent p-2" min="0" placeholder="Enter stock quantity">
            </section>

            <!-- Buttons -->
            <section class="flex justify-end items-center gap-2">
                <button type="button" onclick="forceOverlayToClose()" class="w-fit bg-primary interactive text-accent font-semibold py-2 px-6 rounded shadow">Cancel</button>
                <button type="submit" class="w-fit bg-accent interactive text-primary font-semibold py-2 px-6 rounded shadow">Create</button>
            </section>
        </form>`;

    openOverlayModal(content);
}

function deleteDiscount(recordId, code, submissionPath) {
    const content = `<section class="border-b border-light-dark pb-4">
            <h2 class="text-xl font-semibold text-dark">Confirm Deletion</h2>
        </section>
        
        <form action="${submissionPath}" method="POST" class="flex flex-col gap-4">
            <p class="text-dark">Are you sure you want to delete this discount code: <span class="font-bold">${code}</span>?</p>
            <section class="flex justify-end items-center gap-2">
                <input type="hidden" name="id" value="${recordId}">
                <button type="button" onclick="forceOverlayToClose()" class="w-fit bg-primary interactive text-accent font-semibold py-2 px-6 rounded shadow">Cancel</button>
                <button type="submit" class="w-fit bg-danger interactive text-primary font-semibold py-2 px-6 rounded shadow">Delete</button>
            </section>
        </form>`;

    openOverlayModal(content);
}

function editDiscount(recordId, submissionPath, current_values) {
    // Format the expiredAt value to YYYY-MM-DD
    const formattedDate = current_values.expiredAt.split(' ')[0]; // Extracts only the date portion

    // Create the modal content with the correct fields for editing discounts
    const content = `
        <section class="border-b border-light-dark pb-4">
            <h2 class="text-xl font-semibold text-dark">Edit Discount</h2>
        </section>

        <form action="${submissionPath}" method="POST" class="flex flex-col gap-4">
            <!-- Hidden Field for Discount ID -->
            <input type="hidden" name="id" value="${recordId}">

            <!-- Discount Code Input -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="code" class="block text-sm font-medium text-dark">Discount Code</label>
                <input type="text" name="code" value="${current_values.code}" class="block w-full border shadow rounded outline-accent p-2" placeholder="Enter short code" required>
            </section>

            <!-- Discount Amount Input -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="amount" class="block text-sm font-medium text-dark">Percentage</label>
                <input type="number" name="amount" value="${current_values.amount}" class="block w-full border shadow rounded outline-accent p-2" min="1" max="100" placeholder="Enter stock quantity" required>
            </section>

            <!-- Max Usage Input -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="max_usage" class="block text-sm font-medium text-dark">Maximum Usage</label>
                <input type="number" name="max_usage" value="${current_values.maxUsage}" class="block w-full border shadow rounded outline-accent p-2" min="1" placeholder="Enter stock quantity" required>
            </section>

            <!-- Expiry Date Input -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="expired_at" class="block text-sm font-medium text-dark">Expiry</label>
                <input type="date" name="expired_at" value="${formattedDate}" class="block w-full border shadow rounded outline-accent p-2" required>
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



document.querySelectorAll('.create-discount-button').forEach(button => {
    button.addEventListener('click', function () {
        const submissionPath = this.getAttribute('submission-path');

        createDiscount(submissionPath);

    });
});

document.querySelectorAll('.delete-discount-button').forEach(button => {
    button.addEventListener('click', function () {
        const recordId = this.getAttribute('data-id');
        const code = this.getAttribute('code');
        const submissionPath = this.getAttribute('submission-path');
        deleteDiscount(recordId, code, submissionPath);
    })
})

document.querySelectorAll('.edit-discount-button').forEach(button => {
    button.addEventListener('click', function () {
        const recordId = this.getAttribute('data-id');
        const submissionPath = this.getAttribute('submission-path');

        const discountDetails = {
            code: this.getAttribute('code'),
            amount: this.getAttribute('amount') ?? "",
            maxUsage: this.getAttribute('max_usage') ?? "",
            expiredAt: this.getAttribute('expired_at') ?? "",
            rootDirectory: this.getAttribute('root-directory')
        };

        // Call the editCategory function with the object
        editDiscount(recordId, submissionPath, discountDetails);
    });
});