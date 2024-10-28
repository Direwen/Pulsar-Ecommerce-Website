// Function to handle user edit actions and trigger the overlay
function editUser(recordId, submissionPath) {

    // Fetch or build the content dynamically based on the recordId
    const content = `<form action="${submissionPath}" method="POST" class="bg-primary p-6 rounded-lg shadow-md">
                        <h2 class="text-xl font-semibold mb-4 text-dark">Editing</h2>
                        <input type="hidden" name="id" value="${recordId}">  <!-- Hidden input for record ID -->
                        
                        <label for="role" class="block text-sm font-medium text-dark mb-1">User Role:</label>
                        <select name="role" id="role" class="block w-full border border-light-gray rounded-md p-2 mb-4">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                        
                        <button type="submit" class="w-full bg-accent interactive text-primary font-semibold py-2 rounded-md">Save</button>
                    </form>`;


    // Show the overlay with the dynamic content
    showOverlay(content);

}

function deleteUser(recordId, userEmail, submissionPath) {
    const content = `
            <div class="bg-primary p-6 rounded-lg shadow-lg w-full">
                <h2 class="text-xl font-semibold text-dark mb-4">Confirm Deletion</h2>
                <p class="text-dark mb-6">Are you sure you want to delete the user <span class="font-bold">${userEmail}</span>?</p>
                
                <form action="${submissionPath}" method="POST" class="flex justify-center space-x-4">
                    <input type="hidden" name="id" value="${recordId}">  <!-- Hidden input for record ID -->
                    <button type="submit" class="bg-accent text-primary font-semibold py-2 px-4 rounded-md hover:bg-light-dark">Yes</button>
                    <button type="button" onclick="forceOverlayToClose()" class="bg-gray-400 text-primary font-semibold py-2 px-4 rounded-md hover:bg-gray-500">No</button>
                </form>
            </div>`;

    showOverlay(content);
}

function createUser(submissionPath) {
    const content = `
    <div class="bg-primary p-6 rounded-lg shadow-lg w-full">
        <h2 class="text-xl font-semibold text-dark mb-4">Creating a new user</h2>
        
        <form action="${submissionPath}" method="POST" class="flex justify-center space-x-4">
            <input type="email" name="email">
            <button type="submit" class="bg-accent text-primary font-semibold py-2 px-4 rounded-md hover:bg-light-dark">Yes</button>
        </form>
    </div>`;

    showOverlay(content);
}


// Function to attach the editUser function to all edit buttons in the User Management dashboard
document.querySelectorAll('.edit-user-button').forEach(button => {
    button.addEventListener('click', function () {
        const recordId = this.getAttribute('data-id');  // Get the record ID from the data attribute
        const submissionPath = this.getAttribute('submission-path');  // Get the submission path from the data attribute
        editUser(recordId, submissionPath);  // Call the edit function specific to User Management
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

