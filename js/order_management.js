function deleteOrder(recordId, orderCode, submissionPath) {
    const content = `
        <section class="border-b border-gray-300 pb-4">
            <h2 class="text-2xl font-semibold text-gray-800">Confirm Deletion</h2>
        </section>

        <form action="${submissionPath}" method="POST" class="flex flex-col gap-4">
            <p class="text-gray-700">
                You are about to delete the order record with code: 
                <span class="font-bold text-gray-900">${orderCode}</span>.<br>
                <span class="text-red-600 font-medium">This action is irreversible.</span>
            </p>
            <p class="text-sm text-light-dark">
                Please ensure this action is necessary. Deleting records recklessly can result in data inconsistency or loss of critical information.
            </p>
            <section class="flex justify-end items-center gap-3">
                <input type="hidden" name="id" value="${recordId}">
                <button type="button" onclick="forceOverlayToClose()" 
                    class="bg-light-gray interactive text-gray-800 font-medium py-2 px-5 rounded shadow">
                    Cancel
                </button>
                <button type="submit" 
                    class="bg-danger interactive text-primary font-medium py-2 px-5 rounded shadow">
                    Delete Record
                </button>
            </section>
        </form>`;
    
    openOverlayModal(content);
}

function editOrder(recordId, submissionPath, status) {
    status = status.toLowerCase();

    // Check if the order is already delivered or cancelled
    if (status === 'delivered' || status === 'cancelled') {
        const content = `
            <section class="border-b border-light-dark pb-4">
                <h2 class="text-xl font-semibold text-dark">Update Not Available</h2>
            </section>

            <p class="text-gray-700">
                The order is currently <span class="font-bold text-gray-900">${status.charAt(0).toUpperCase() + status.slice(1)}</span>. 
                There are no further updates available for this order.
            </p>

            <section class="flex justify-end items-center gap-2">
                <button type="button" onclick="forceOverlayToClose()" class="w-fit bg-primary interactive text-accent font-semibold py-2 px-6 rounded shadow">Cancel</button>
            </section>`;

        // Display the overlay with the generated content
        openOverlayModal(content);
        return; // Exit the function early
    }

    // Define valid status transitions
    const statusOptions = {
        pending: ['pending', 'confirmed', 'cancelled'],
        confirmed: ['confirmed', 'processing', 'cancelled'],
        processing: ['processing', 'shipping'],
        shipping: ['shipping', 'delivered'],
        delivered: [],
        cancelled: []
    };

    // Create the modal content with the correct fields for editing inventory
    const options = statusOptions[status].map(option => 
        `<option value="${option}" ${option === status ? 'selected' : ''}>${option.charAt(0).toUpperCase() + option.slice(1)}</option>`
    ).join('');

    const content = `
        <section class="border-b border-light-dark pb-4">
            <h2 class="text-xl font-semibold text-dark">Edit Inventory</h2>
        </section>

        <form action="${submissionPath}" method="POST" class="flex flex-col gap-4">
            <!-- Hidden Field for Inventory ID -->
            <input type="hidden" name="id" value="${recordId}">

            <section class="flex justify-start items-center gap-2">
                <label for="status" class="block text-sm font-medium text-dark mb-1">Order Status</label>
                <select name="status" class="block grow border border-light-gray rounded-md p-2 shadow">
                    ${options}
                </select>
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

document.querySelectorAll('.edit-order-button').forEach(button => {
    button.addEventListener('click', function () {
        const recordId = this.getAttribute('data-id');
        const submissionPath = this.getAttribute('submission-path');
        const status = this.getAttribute('status');

        editOrder(recordId, submissionPath, status);
    });
});

document.querySelectorAll('.delete-order-button').forEach(button => {
    button.addEventListener('click', function () {
        const recordId = this.getAttribute('data-id');
        const code = this.getAttribute('order_code');
        const submissionPath = this.getAttribute('submission-path');
        deleteOrder(recordId, code, submissionPath);
    })
})