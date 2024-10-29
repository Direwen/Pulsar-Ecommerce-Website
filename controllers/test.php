<div
    id="overlay"
    class="w-screen h-screen bg-light-gray/95 fixed top-0 left-0 z-50 flex justify-center items-center"
    onclick="closeOverlay(event)">

    <section id="overlay_content_container" class="w-11/12 sm:w-10/12 md:w-8/12 lg:w-1/2 bg-secondary p-5 flex flex-col gap-4 rounded">
        <!-- ========================== -->
        <section class="border-b border-light-dark pb-4">
            <h2 class="text-xl font-semibold text-dark">Edit Category</h2>
        </section>

        <form action="${submissionPath}" method="POST" class="flex flex-col gap-4" enctype="multipart/form-data">
            <section class="flex flex-col justify-start items-start gap-2">
                <label for="name" class="block text-sm font-medium text-dark">Category Name</label>
                <input type="text" name="name" value="${currentCategoryName}" class="block w-full border shadow rounded outline-accent p-2">
            </section>

            <section class="flex flex-col justify-start items-start gap-2">
                <label for="software" class="block text-sm font-medium text-dark">Software Link</label>
                <input type="url" name="software" value="${currentSoftwareLink}" class="block w-full border shadow rounded outline-accent p-2">
            </section>

            <section class="flex flex-col justify-start items-start gap-2">
                <label for="firmware" class="block text-sm font-medium text-dark">Firmware Link</label>
                <input type="url" name="firmware" value="${currentFirmwareLink}" class="block w-full border shadow rounded outline-accent p-2">
            </section>

            <section class="flex flex-col justify-start items-start gap-2">
                <label for="manual" class="block text-sm font-medium text-dark">Manual Link</label>
                <input type="url" name="manual" value="${currentManualLink}" class="block w-full border shadow rounded outline-accent p-2">
            </section>

            <!-- Existing Image Preview Section -->
            <section class="flex flex-col justify-start items-start gap-2">
                <label class="block text-sm font-medium text-dark">Current Image</label>
                <img src=`${current_value['rootDirectory'].concat(${currentImageUrl})}` alt="Current Image" class="w-32 h-32 object-cover border rounded">

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
        </form>


        <!-- ================================== -->
    </section>

</div>