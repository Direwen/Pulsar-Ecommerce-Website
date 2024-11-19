<div id="overlay" class="w-screen h-screen bg-light-gray/95 fixed top-0 left-0 z-50 hidden"
    onclick="closeOverlay(event)">

    <section id="overlay_content_container"
        class="hide-scrollbar w-11/12 overflow-y-scroll sm:w-10/12 md:w-8/12 lg:w-1/2 bg-secondary p-5 flex flex-col gap-4 rounded max-h-full overflow-y-scoll shadow-xl">

        <section class="border-b border-light-dark pb-4">
            <h2 class="text-xl font-semibold text-dark">Submit a Support Ticket</h2>
        </section>

        <form action="/submit-ticket" method="POST" class="flex flex-col gap-4">
            <!-- Email Input -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="user_email" class="block text-sm font-medium text-dark">Email Address</label>
                <input type="email" name="user_email" class="block w-full border shadow rounded outline-accent p-2"
                    placeholder="Enter your email" required>
            </section>

            <!-- Subject Input -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="subject" class="block text-sm font-medium text-dark">Subject</label>
                <input type="text" name="subject" class="block w-full border shadow rounded outline-accent p-2"
                    placeholder="Enter ticket subject" required>
            </section>

            <!-- Message Input -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="message" class="block text-sm font-medium text-dark">Message</label>
                <textarea name="message" class="block w-full border shadow rounded outline-accent p-2" rows="4"
                    placeholder="Describe your issue" required></textarea>
            </section>

            <!-- Status Dropdown -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="status" class="block text-sm font-medium text-dark">Status</label>
                <select name="status" class="block w-full border shadow rounded outline-accent p-2">
                    <option value="open" selected>Open</option>
                    <option value="replied">Replied</option>
                </select>
            </section>

            <!-- Buttons -->
            <section class="flex justify-end items-center gap-2">
                <button type="button" onclick="forceOverlayToClose()"
                    class="w-fit bg-primary interactive text-accent font-semibold py-2 px-6 rounded shadow">Cancel</button>
                <button type="submit"
                    class="w-fit bg-accent interactive text-primary font-semibold py-2 px-6 rounded shadow">Submit</button>
            </section>
        </form>


    </section>

</div>