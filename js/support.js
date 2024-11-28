function openTicketForm(btn) {
    // Make sure the success and error containers are hidden initially
    const root = btn.getAttribute('root-directory');
    const content = `
        <section class="border-b border-light-dark pb-4">
            <h2 class="text-xl font-semibold text-dark">Submit a Support Ticket</h2>
        </section>

        <form id="ticketForm" class="flex flex-col gap-4">
            <!-- Email Input -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="user_email" class="block text-sm font-medium text-dark">Email Address</label>
                <input type="email" name="user_email" class="block w-full border shadow rounded outline-accent p-2" placeholder="Enter your email" required>
            </section>

            <!-- Subject Input -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="subject" class="block text-sm font-medium text-dark">Subject</label>
                <select name="subject" class="block w-full border shadow rounded outline-accent p-2" required>
                    <option value="" disabled selected>Select a subject</option>
                    <option value="billing">Billing Issue</option>
                    <option value="technical">Technical Support</option>
                    <option value="order">Order Issue</option>
                    <option value="feedback">Feedback</option>
                    <option value="other">Other</option>
                </select>
            </section>

            <!-- Message Input -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="message" class="block text-sm font-medium text-dark">Message</label>
                <textarea name="message" class="block w-full border shadow rounded outline-accent p-2" rows="4" placeholder="Describe your issue" required></textarea>
            </section>

            <!-- Buttons -->
            <section class="flex justify-end items-center gap-2">
                <button type="button" onclick="forceOverlayToClose()" class="w-fit bg-primary interactive text-accent font-semibold py-2 px-6 rounded shadow">Cancel</button>
                <button type="submit" class="w-fit bg-accent interactive text-primary font-semibold py-2 px-6 rounded shadow">Submit</button>
            </section>
        </form>
    `;

    openOverlayModal(content);

    // Attach event listener to the form
    const form = document.getElementById('ticketForm');
    form.addEventListener('submit', (event) => handleFormSubmit(event, root));
}

function handleFormSubmit(event, root) {
    // Prevent the default form submission (page reload)
    event.preventDefault();

    // Extract form data
    const form = event.target;
    const email = form.user_email.value;
    const subject = form.subject.value;
    const message = form.message.value;

    // Validate form data
    if (!email || !subject || !message) {
        alert('Please fill in all required fields!');
        return;
    }

    // Create data object
    const ticketData = {
        user_email: email,
        subject: subject,
        message: message,
    };

    // API call
    axios.post(root + 'api/ticket-submission', ticketData)
        .then((res) => {
            const success = res.data.success;
            if (success) {
                // Replace form with success container
                renderErrorModal(root);
                // renderSuccessModal(`${root}assets/illustrations/mail_sent.svg`, "Your ticket has been submitted successfully!");
            } else {
                // Replace form with error container
                renderErrorModal(root);
            }
        })
        .catch((err) => {
            console.error(err);
            // Replace form with error container on API failure
            document.getElementById('ticketForm').classList.add('hidden');
            document.getElementById('form_error').classList.remove('hidden');
        });
}
