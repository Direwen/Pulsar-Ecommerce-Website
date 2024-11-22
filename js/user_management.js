// Function to handle user edit actions and trigger the overlay
function editUser(recordId, submissionPath, currentValues) {

    // Fetch or build the content dynamically based on the recordId
    const content = `
        <section class="border-b border-light-dark pb-4">
            <h2 class="text-xl font-semibold text-dark">Update User?</h2>
        </section>

        <form action="${submissionPath}" method="POST" class="flex flex-col gap-4">
            <section class="flex justify-start items-center gap-2">
                <input type="hidden" name="id" value="${recordId}"> <!-- Hidden input for record ID -->

                <!-- User Role Selection -->
                <label for="role" class="block text-sm font-medium text-dark mb-1">User Role</label>
                <select name="role" id="role" class="block grow border border-light-gray rounded-md p-2 shadow hover:outline-accent">
                    <option value="user" ${currentValues.role === 'user' ? 'selected' : ''}>User</option>
                    <option value="admin" ${currentValues.role === 'admin' ? 'selected' : ''}>Admin</option>
                </select>
            </section>

            <section class="flex justify-start items-center gap-4">
                <!-- Account Status: Active or Disabled with Radio Buttons -->
                <label class="block text-sm font-medium text-dark mb-1">Account Status</label>
                <div class="flex items-center gap-4">
                    <label for="isActiveTrue" class="flex items-center gap-2">
                        <input type="radio" id="isActiveTrue" name="is_active" value="1" ${currentValues.isActive === '1' ? 'checked' : ''}>
                        <span>Active</span>
                    </label>
                    <label for="isActiveFalse" class="flex items-center gap-2">
                        <input type="radio" id="isActiveFalse" name="is_active" value="0" ${currentValues.isActive === '0' ? 'checked' : ''}>
                        <span>Disabled</span>
                    </label>
                </div>
            </section>


            <section class="flex justify-end items-center gap-2">
                <button type="button" onclick="forceOverlayToClose()" class="w-fit bg-primary interactive text-accent font-semibold py-2 px-6 rounded shadow">Cancel</button>
                <button type="submit" class="w-fit bg-accent interactive text-primary font-semibold py-2 px-6 rounded shadow">Save</button>
            </section>
        </form>
    `;

    // Show the overlay with the dynamic content
    openOverlayModal(content);
}


function deleteUser(recordId, userEmail, submissionPath) {
    const content = `<section class="border-b border-light-dark pb-4">
            <h2 class="text-xl font-semibold text-dark">Confirm Deletion</h2>
        </section>
        
        <form action="${submissionPath}" method="POST" class="flex flex-col gap-4">
            <p class="text-dark">Are you sure you want to delete the user <span class="font-bold">${userEmail}</span>?</p>
            <section class="flex justify-end items-center gap-2">
                <input type="hidden" name="id" value="${recordId}"> <!-- Hidden input for record ID -->
                <button type="button" onclick="forceOverlayToClose()" class="w-fit bg-primary interactive text-accent font-semibold py-2 px-6 rounded shadow">Cancel</button>
                <button type="submit" class="w-fit bg-danger interactive text-primary font-semibold py-2 px-6 rounded shadow">Delete</button>
            </section>
        </form>`;

    openOverlayModal(content);
}

function createUser(submissionPath) {
    const content = `<section class="border-b border-light-dark pb-4">
            <h2 class="text-xl font-semibold text-dark">New User</h2>
        </section>


        <form action="${submissionPath}" method="POST" class="flex flex-col gap-4">
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="email" class="block text-sm font-medium text-dark">Email</label>
                <input type="email" name="email" id="email" class="block w-full border shadow rounded outline-accent p-2">
            </section>

            <section class="flex justify-end items-center gap-2">
                <button type="button" onclick="forceOverlayToClose()" class="w-fit bg-primary interactive text-accent font-semibold py-2 px-6 rounded shadow">Cancel</button>
                <button type="submit" class="w-fit bg-accent interactive text-primary font-semibold py-2 px-6 rounded shadow">Create</button>
            </section>
        </form>`;

    openOverlayModal(content);
}


// Function to attach the editUser function to all edit buttons in the User Management dashboard
document.querySelectorAll('.edit-user-button').forEach(button => {
    button.addEventListener('click', function () {
        console.log( this.getAttribute('is_active'));
        const recordId = this.getAttribute('data-id');  // Get the record ID from the data attribute
        const submissionPath = this.getAttribute('submission-path');  // Get the submission path from the data attribute
        const userDetails = {
            'role': this.getAttribute('role'),
            'isActive' : this.getAttribute('is_active')
        }
        editUser(recordId, submissionPath, userDetails);  // Call the edit function specific to User Management
    });
});

document.querySelectorAll('.delete-user-button').forEach(button => {
    button.addEventListener('click', function () {
        const recordId = this.getAttribute('data-id');
        const userEmail = this.getAttribute('email');
        const submissionPath = this.getAttribute('submission-path');
        deleteUser(recordId, userEmail, submissionPath);
    })
})

document.querySelectorAll('.create-user-button').forEach(button => {
    button.addEventListener('click', function () {
        const submissionPath = this.getAttribute('submission-path');
        createUser(submissionPath);
    })
})

