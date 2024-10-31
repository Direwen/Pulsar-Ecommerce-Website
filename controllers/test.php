<div
    id="overlay"
    class="w-screen h-screen bg-light-gray/95 fixed top-0 left-0 z-50 flex justify-center items-center"
    onclick="closeOverlay(event)">

    <section id="overlay_content_container" class="w-11/12 overflow-y-scroll sm:w-10/12 md:w-8/12 lg:w-1/2 bg-secondary p-5 flex flex-col gap-4 rounded max-h-screen overflow-y-scoll">
        <!-- ========================== -->
        <section class="border-b border-light-dark pb-4">
            <h2 class="text-xl font-semibold text-dark">Create Product</h2>
        </section>

        <form action="${submissionPath}" method="POST" class="flex flex-col gap-4" enctype="multipart/form-data">

            <section class="block text-sm font-medium text-dark">
                <label for="product-name">Product Name</label>
                <input type="email" name="product-name" class="block w-full border shadow rounded outline-accent p-2">
            </section>

            <section class="flex justify-start items-center gap-2">
                <label for="category" class="block text-sm font-medium text-dark mb-1">Category</label>
                <select name="category" id="role" class="block grow border border-light-gray rounded-md p-2 shadow">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </section>

            <section class="block text-sm font-medium text-dark">
                <label for="description">Description</label>
                <textarea name="description" class="block w-full border shadow rounded outline-accent p-2"></textarea>
            </section>

            <section class="block text-sm font-medium text-dark">
                <label for="dimension">Dimension</label>
                <input type="text" name="dimension" class="block w-full border shadow rounded outline-accent p-2">
            </section>

            <!-- Feature Input -->
            <section class="block text-sm font-medium text-dark">
                <label for="feature">Feature (separate by commas)</label>
                <input type="text" name="feature" id="feature-input" class="block w-full border shadow rounded outline-accent p-2" placeholder="e.g., item1, item2">
            </section>

            <!-- Special Feature Input -->
            <section id="special-feature-section" class="flex flex-col gap-4 text-sm font-medium text-dark">
                <label>Special Features</label>

                <!-- Container for dynamic special feature entries -->
                <div id="special-feature-container"></div>

                <!-- Button to add a new category for special features -->
                <button type="button" onclick="addSpecialFeatureCategory()" class="w-fit bg-primary text-accent font-semibold py-2 px-4 rounded shadow">+ Add Special Feature</button>
            </section>

            <section class="block text-sm font-medium text-dark">
                <label for="requirement">Requirement</label>
                <textarea name="requirement" class="block w-full border shadow rounded outline-accent p-2"></textarea>
            </section>

            <section class="block text-sm font-medium text-dark">
                <label for="package-content">Package Content</label>
                <textarea name="package-content" class="block w-full border shadow rounded outline-accent p-2"></textarea>
            </section>

            <section class="flex flex-col justify-start items-start gap-2">
                <label for="img" class="block text-sm font-medium text-dark">Images for Ads</label>
                <input multiple type="file" name="img[]" accept="image/*" class="block w-full bg-primary border shadow rounded outline-accent p-2">
            </section>

            <section class="flex flex-col justify-start items-start gap-2">
                <label for="main-img" class="block text-sm font-medium text-dark">Main Image</label>
                <input type="file" name="main-img" accept="image/*" class="block w-full bg-primary border shadow rounded outline-accent p-2">
            </section>

            <section class="flex justify-end items-center gap-2">
                <button type="button" onclick="forceOverlayToClose()" class="w-fit bg-primary interactive text-accent font-semibold py-2 px-6 rounded shadow">Cancel</button>
                <button type="submit" class="w-fit bg-accent interactive text-primary font-semibold py-2 px-6 rounded shadow">Update</button>
            </section>
        </form>


        <!-- ================================== -->
    </section>

</div>