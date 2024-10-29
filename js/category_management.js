function editCategory(recordId, submissionPath, current_values) {

    // Fetch or build the content dynamically based on the recordId
    const content = `<section class="border-b border-light-dark pb-4">
            <h2 class="text-xl font-semibold text-dark">Edit Category</h2>
        </section>

        <form action="${submissionPath}" method="POST" class="flex flex-col gap-4" enctype="multipart/form-data">
            
            <input type="hidden" name="id" value="${recordId}">

            <section class="flex flex-col justify-start items-start gap-2">
                <label for="name" class="block text-sm font-medium text-dark">Category Name</label>
                <input type="text" name="name" value="${current_values.categoryName}" class="block w-full border shadow rounded outline-accent p-2">
            </section>

            <section class="flex flex-col justify-start items-start gap-2">
                <label for="software" class="block text-sm font-medium text-dark">Software Link</label>
                <input type="url" name="software" value="${current_values.software}" class="block w-full border shadow rounded outline-accent p-2">
            </section>

            <section class="flex flex-col justify-start items-start gap-2">
                <label for="firmware" class="block text-sm font-medium text-dark">Firmware Link</label>
                <input type="url" name="firmware" value="${current_values.firmware}" class="block w-full border shadow rounded outline-accent p-2">
            </section>

            <section class="flex flex-col justify-start items-start gap-2">
                <label for="manual" class="block text-sm font-medium text-dark">Manual Link</label>
                <input type="url" name="manual" value="${current_values.manual}" class="block w-full border shadow rounded outline-accent p-2">
            </section>

            <!-- Existing Image Preview Section -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label class="block text-sm font-medium text-dark">Current Image</label>
                <img src="${current_values.rootDirectory + 'assets/' + 'mouse_skate.jpg'}" alt="Current Image" class="w-32 h-32 object-cover border rounded">

                <!-- Option to change image -->
                <label class="flex items-center gap-2 mt-2">
                    <input type="checkbox" name="changeImage" id="changeImageCheckbox" class="accent-accent" onclick="toggleImageInput()">
                    <span>Change Image</span>
                </label>

                <!-- New Image Upload Input, hidden initially -->
                <input type="file" name="img" accept="image/*" id="newImageInput" class="block w-full bg-primary border shadow rounded outline-accent p-2 mt-2 hidden">
            </section>

            <section class="flex justify-end items-center gap-2">
                <button type="button" onclick="forceOverlayToClose()" class="w-fit bg-primary interactive text-accent font-semibold py-2 px-6 rounded shadow">Cancel</button>
                <button type="submit" class="w-fit bg-accent interactive text-primary font-semibold py-2 px-6 rounded shadow">Update</button>
            </section>
        </form>`;

    // Show the overlay with the dynamic content
    showOverlay(content);
}


function deleteCategory(recordId, name, submissionPath) {
    const content = `<section class="border-b border-light-dark pb-4">
            <h2 class="text-xl font-semibold text-dark">Confirm Deletion</h2>
        </section>
        
        <form action="${submissionPath}" method="POST" class="flex flex-col gap-4">
            <p class="text-dark">Are you sure you want to delete the category: <span class="font-bold">${name}</span>?</p>
            <section class="flex justify-end items-center gap-2">
                <input type="hidden" name="id" value="${recordId}">
                <button type="button" onclick="forceOverlayToClose()" class="w-fit bg-primary interactive text-accent font-semibold py-2 px-6 rounded shadow">Cancel</button>
                <button type="submit" class="w-fit bg-accent interactive text-primary font-semibold py-2 px-6 rounded shadow">Delete</button>
            </section>
        </form>`;

    showOverlay(content);
}

function createCategory(submissionPath) {
    const content = `<section class="border-b border-light-dark pb-4">
            <h2 class="text-xl font-semibold text-dark">New Category</h2>
        </section>

        <form action="${submissionPath}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="name" class="block text-sm font-medium text-dark">Category Name</label>
                <input type="text" name="name" class="block w-full border shadow rounded outline-accent p-2">
            </section>

            <section class="flex flex-col justify-start items-start gap-2">
                <label for="software" class="block text-sm font-medium text-dark">Software Link</label>
                <input type="url" name="software" class="block w-full border shadow rounded outline-accent p-2">
            </section>

            <section class="flex flex-col justify-start items-start gap-2">
                <label for="firmware" class="block text-sm font-medium text-dark">Firmware Link</label>
                <input type="url" name="firmware" class="block w-full border shadow rounded outline-accent p-2">
            </section>

            <section class="flex flex-col justify-start items-start gap-2">
                <label for="manual" class="block text-sm font-medium text-dark">Manual Link</label>
                <input type="url" name="manual" class="block w-full border shadow rounded outline-accent p-2">
            </section>

            <section class="flex flex-col justify-start items-start gap-2">
                <label for="img" class="block text-sm font-medium text-dark">Image</label>
                <input type="file" name="img" accept="image/*" class="block w-full bg-primary border shadow rounded outline-accent p-2">
            </section>

            <section class="flex justify-end items-center gap-2">
                <button type="button" onclick="forceOverlayToClose()" class="w-fit bg-primary interactive text-accent font-semibold py-2 px-6 rounded shadow">Cancel</button>
                <button type="submit" class="w-fit bg-accent interactive text-primary font-semibold py-2 px-6 rounded shadow">Create</button>
            </section>
        </form>`;

    showOverlay(content);
}

// Function to attach the editUser function to all edit buttons in the User Management dashboard
document.querySelectorAll('.edit-category-button').forEach(button => {
    button.addEventListener('click', function () {
        const recordId = this.getAttribute('data-id');
        const submissionPath = this.getAttribute('submission-path');
        
        // Collect all attributes in an object to pass to editCategory
        const categoryDetails = {
            categoryName: this.getAttribute('name'),
            software: this.getAttribute('software'),
            firmware: this.getAttribute('firmware'),
            manual: this.getAttribute('manual'),
            img: this.getAttribute('img'),
            rootDirectory: this.getAttribute('root-directory')
        };

        // Call the editCategory function with the object
        editCategory(recordId, submissionPath, categoryDetails);
    });
});

document.querySelectorAll('.delete-category-button').forEach(button => {
    button.addEventListener('click', function () {
        const recordId = this.getAttribute('data-id');
        const categoryName = this.getAttribute('name');
        const submissionPath = this.getAttribute('submission-path');
        deleteCategory(recordId, categoryName, submissionPath);
    })
})

document.querySelectorAll('.create-category-button').forEach(button => {
    button.addEventListener('click', function () {
        const submissionPath = this.getAttribute('submission-path');
        createCategory(submissionPath);
    })
})