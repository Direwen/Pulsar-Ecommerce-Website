function createEvent(submissionPath, products, root) {
    const productCheckboxes = products.map(product => `
        <label class="flex items-center gap-4 border-b interactive hover:bg-light-gray p-2">
            <input type="checkbox" name="products[]" value="${product.id}" class="w-4 h-4 accent-accent">
            <img src="${root}assets/products/${product.img}" alt="${product.name}" class="w-12 h-12 object-cover rounded">
            <span class="text-sm font-medium text-dark">${product.name}</span>
        </label>
    `).join('');

    const content = `<section class="border-b border-light-dark pb-4">
            <h2 class="text-xl font-semibold text-dark">New Event</h2>
        </section>

        <form action="${submissionPath}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
            <!-- Event Name -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="name" class="block text-sm font-medium text-dark">Event Name</label>
                <input required type="text" name="name" class="block w-full border shadow rounded outline-accent p-2">
            </section>

            <!-- Event Description -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="desc" class="block text-sm font-medium text-dark">Event Description</label>
                <textarea name="description" rows="4" class="block w-full border shadow rounded outline-accent p-2"></textarea>
            </section>

            <!-- Banner Image -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="banner_img" class="block text-sm font-medium text-dark">Banner Image</label>
                <input required type="file" name="banner_img" accept="image/*" class="block w-full bg-primary border shadow rounded outline-accent p-2">
            </section>

            <!-- Start Date -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="start_at" class="block text-sm font-medium text-dark">Start Date</label>
                <input required type="date" name="start_at" class="block w-full border shadow rounded outline-accent p-2">
            </section>

            <!-- End Date -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="end_at" class="block text-sm font-medium text-dark">End Date</label>
                <input required type="date" name="end_at" class="block w-full border shadow rounded outline-accent p-2">
            </section>

            <!-- Products Selection -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="products" class="block text-sm font-medium text-dark">Select Products</label>
                <div class="border shadow rounded max-h-64 overflow-y-auto w-full p-2">
                    ${productCheckboxes}
                </div>
            </section>

            <!-- Discount Percentage -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="discount" class="block text-sm font-medium text-dark">Discount Percentage (%)</label>
                <input required type="number" name="discount" min="0" max="100" step="1" 
                    class="block w-full border shadow rounded outline-accent p-2" placeholder="Enter discount percentage">
            </section>

            <!-- Actions -->
            <section class="flex justify-end items-center gap-2">
                <button type="button" onclick="forceOverlayToClose()" class="w-fit bg-primary interactive text-accent font-semibold py-2 px-6 rounded shadow">Cancel</button>
                <button type="submit" class="w-fit bg-accent interactive text-primary font-semibold py-2 px-6 rounded shadow">Create</button>
            </section>
        </form>`;

    openOverlayModal(content);
}

function editEvent(recordId, submissionPath, root, currentValues) {
    console.log(currentValues);

    const products = currentValues.products;
    const eventProducts = currentValues.eventProducts.map(ep => ep.product_id); // Extract only product IDs

    // Format date values to "YYYY-MM-DD"
    const formatDate = (dateTime) => dateTime.split(' ')[0]; // Extract date part from "YYYY-MM-DD HH:MM:SS"
    const startAtFormatted = formatDate(currentValues.startAt);
    const endAtFormatted = formatDate(currentValues.endAt);

    // Generate product checkboxes with pre-selected options
    const productCheckboxes = products
        .map(
            product => `
        <label class="flex items-center gap-4 border-b interactive hover:bg-light-gray p-2">
            <input 
                type="checkbox" 
                name="products[]" 
                value="${product.id}" 
                class="w-4 h-4 accent-accent" 
                ${eventProducts.includes(product.id) ? 'checked' : ''}>
            <img src="${root}assets/products/${product.img}" alt="${product.name}" class="w-12 h-12 object-cover rounded">
            <span class="text-sm font-medium text-dark">${product.name}</span>
        </label>
        `
        )
        .join('');

    // Generate the modal content
    const content = `
        <section class="border-b border-light-dark pb-4">
            <h2 class="text-xl font-semibold text-dark">Edit Event</h2>
        </section>

        <form action="${submissionPath}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">

            <input type="hidden" value="${recordId}" name="id">
            <!-- Event Name -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="name" class="block text-sm font-medium text-dark">Event Name</label>
                <input required type="text" name="name" value="${currentValues.name}" 
                    class="block w-full border shadow rounded outline-accent p-2">
            </section>

            <!-- Event Description -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="desc" class="block text-sm font-medium text-dark">Event Description</label>
                <textarea name="description" rows="4" 
                    class="block w-full border shadow rounded outline-accent p-2">${currentValues.description}</textarea>
            </section>

            <!-- Banner Image -->
            <!-- Existing Image Preview Section -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label class="block text-sm font-medium text-dark">Current Banner Image</label>
                <img src="${root + 'assets/events/' + currentValues.bannerImg}" alt="Current Image" class="w-full max-h-48 object-cover border rounded">

                <!-- Option to change image -->
                <label class="flex items-center gap-2 mt-2">
                    <input type="checkbox" name="changeImage" data-toggle="newImgInput" class="accent-accent" onclick="toggleImageInput(this)">
                    <span>Change Image</span>
                </label>

                <!-- New Image Upload Input, hidden initially -->
                <input type="file" name="banner_img" accept="image/*" data-toggle="newImgInput" class="block w-full bg-primary border shadow rounded outline-accent p-2 mt-2 hidden">
            </section>

            <!-- Discount Percentage -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="discount" class="block text-sm font-medium text-dark">Discount Percentage (%)</label>
                <input required type="number" name="discount" min="0" max="100" step="1" 
                    value="${currentValues.discount}" 
                    class="block w-full border shadow rounded outline-accent p-2" placeholder="Enter discount percentage">
            </section>

            <!-- Start Date -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="start_at" class="block text-sm font-medium text-dark">Start Date</label>
                <input required type="date" name="start_at" value="${startAtFormatted}" 
                    class="block w-full border shadow rounded outline-accent p-2">
            </section>

            <!-- End Date -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="end_at" class="block text-sm font-medium text-dark">End Date</label>
                <input required type="date" name="end_at" value="${endAtFormatted}" 
                    class="block w-full border shadow rounded outline-accent p-2">
            </section>

            <!-- Products Selection -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="products" class="block text-sm font-medium text-dark">Select Products</label>
                <div class="border shadow rounded max-h-64 overflow-y-auto w-full p-2">
                    ${productCheckboxes}
                </div>
            </section>

            <!-- Actions -->
            <section class="flex justify-end items-center gap-2">
                <button type="button" onclick="forceOverlayToClose()" class="w-fit bg-primary interactive text-accent font-semibold py-2 px-6 rounded shadow">Cancel</button>
                <button type="submit" class="w-fit bg-accent interactive text-primary font-semibold py-2 px-6 rounded shadow">Update</button>
            </section>
        </form>
    `;

    openOverlayModal(content);
}

function deleteEvent(recordId, name, submissionPath) {

    const content = `<section class="border-b border-light-dark pb-4">
            <h2 class="text-xl font-semibold text-dark">Confirm Deletion</h2>
        </section>
        
        <form action="${submissionPath}" method="POST" class="flex flex-col gap-4">
            <p class="text-dark">Are you sure you want to delete the event: <span class="font-bold">${name}</span>?</p>
            <section class="flex justify-end items-center gap-2">
                <input type="hidden" name="id" value="${recordId}">
                <button type="button" onclick="forceOverlayToClose()" class="w-fit bg-primary interactive text-accent font-semibold py-2 px-6 rounded shadow">Cancel</button>
                <button type="submit" class="w-fit bg-danger interactive text-primary font-semibold py-2 px-6 rounded shadow">Delete</button>
            </section>
        </form>`;

    openOverlayModal(content);
}


document.querySelectorAll('.create-event-button').forEach(button => {
    button.addEventListener('click', function () {

        const apiForProducts = this.getAttribute('api-for-products');
        const submissionPath = this.getAttribute('submission-path');
        const rootDirectory = this.getAttribute('root-directory');

        axios.get(apiForProducts)
            .then(res => {
                createEvent(submissionPath, res.data.products, rootDirectory);
            })
            .catch(err => console.log(err))
    })
})

// Function to attach the editUser function to all edit buttons in the User Management dashboard
document.querySelectorAll('.edit-event-button').forEach(button => {
    button.addEventListener('click', function () {
        const recordId = this.getAttribute('data-id');
        const submissionPath = this.getAttribute('submission-path');
        const apiForEventProducts = this.getAttribute('api-for-event-products');
        const apiForProducts = this.getAttribute('api-for-products');
        const root = this.getAttribute('root-directory');

        Promise.all([
            axios.get(apiForEventProducts).then(response => response.data.event_products ?? []),
            axios.get(apiForProducts).then(response => response.data.products ?? [])
        ])
            .then(([eventProducts, products]) => {

                const currentValues = {
                    "name": this.getAttribute('name'),
                    "description": this.getAttribute('description'),
                    "code": this.getAttribute('code'),
                    "startAt": this.getAttribute('start_at'),
                    "endAt": this.getAttribute('end_at'),
                    "discount": this.getAttribute('discount'),
                    "bannerImg": this.getAttribute('banner_img'),
                    "products": products, 
                    "eventProducts": eventProducts
                }
                editEvent(recordId, submissionPath, root, currentValues);
            })
            .catch(err => console.log(err))

    });
});

document.querySelectorAll('.delete-event-button').forEach(button => {
    button.addEventListener('click', function () {
        const recordId = this.getAttribute('data-id');
        const name = this.getAttribute('name');
        const submissionPath = this.getAttribute('submission-path');
        deleteEvent(recordId, name, submissionPath);
    })
})