function editSupportTicket(recordId, submissionPath, current_values) {
    // Determine if the ticket status is "open"
    const isOpen = current_values.status.toLowerCase() === "open";

    // Set the background color of the status display based on the status
    const statusColor = isOpen ? "bg-yellow-500" : "bg-green-400";

    // Create the modal content with conditional logic for the reply section
    const content = `
        <section class="border-b border-light-dark pb-4 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-dark">Reply to Support Ticket</h2>
            <span class="px-6 py-1 ${statusColor} border shadow text-primary uppercase tracking-tighter rounded-full">${current_values.status}</span>
        </section>

        <form action="${submissionPath}" method="POST" class="flex flex-col gap-4">
            <section class="flex flex-col-reverse lg:flex-row lg:justify-between lg:items-center">
                <span class="text-xs lg:text-sm font-semibold">Email: ${current_values.email}</span>
                <span class="text-xs lg:text-sm">
                    ${new Date(current_values.createdAt).toLocaleString()}
                </span>
            </section>

            <!-- Hidden Field for Ticket ID -->
            <input type="hidden" name="id" value="${recordId}">
            <input type="hidden" name="email" value="${current_values.email}">
            
            <!-- Display Subject -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label class="block text-sm font-medium text-dark">Subject</label>
                <p class="block w-full border rounded bg-gray-100 p-2">${current_values.subject}</p>
            </section>

            <!-- Display Message -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label class="block text-sm font-medium text-dark">Message</label>
                <p class="block w-full border rounded bg-gray-100 p-2">${current_values.message}</p>
            </section>

            <!-- Admin Reply -->
            ${
                isOpen
                    ? `<section class="flex flex-col justify-start items-start gap-2">
                        <label for="admin_reply" class="block text-sm font-medium text-dark">Your Reply</label>
                        <textarea required name="reply" rows="5" class="block w-full border shadow rounded outline-accent p-2" placeholder="Write your reply here..."></textarea>
                    </section>`
                    : `<section class="flex justify-center items-center text-center text-accent font-medium tracking-tighter">
                        You have already replied to this ticket.
                    </section>`
            }

            <!-- Buttons -->
            <section class="flex justify-end items-center gap-2">
                <button type="button" onclick="forceOverlayToClose()" class="w-fit bg-primary interactive text-accent font-semibold py-2 px-6 rounded shadow">Cancel</button>
                ${
                    isOpen
                        ? `<button type="submit" class="w-fit bg-accent interactive text-primary font-semibold py-2 px-6 rounded shadow">Send Reply</button>`
                        : ""
                }
            </section>
        </form>`;

    // Display the overlay with the generated content
    openOverlayModal(content);
}

document.querySelectorAll('.edit-support-button').forEach(button => {
    button.addEventListener('click', function () {
        const recordId = this.getAttribute('data-id');
        const submissionPath = this.getAttribute('submission-path');

        const ticketDetails = {
            email: this.getAttribute('user_email'),
            subject: this.getAttribute('subject'),
            message: this.getAttribute('message') ?? "No message provided.",
            status: this.getAttribute('status') ?? "open",
            createdAt: this.getAttribute('created_at') ?? "N/A"
        };

        // Call the editSupportTicket function with the ticket details
        editSupportTicket(recordId, submissionPath, ticketDetails);
    });
});
