<footer class="bg-black text-secondary py-8">
    <!-- Top Footer Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-8">
            <!-- Product Section -->
            <div>
                <h4 class="text-lg font-bold mb-4">Product</h4>
                <section class="flex flex-col gap-1">
                    <?php renderCategories("text-light-dark hover:text-secondary cursor-pointer"); ?>
                </section>
            </div>

            <!-- Support Section -->
            <div>
                <h4 class="text-lg font-bold mb-4">Support</h4>
                <section class="flex flex-col gap-1">
                    <a href="<?= $root_directory . 'support' ?>" class="text-light-dark hover:text-secondary cursor-pointer">Support</a>
                    <?php if (!$auth_service->getAuthUser()): ?>
                        <a href="#" class="text-light-dark hover:text-secondary cursor-pointer">Login</a>
                    <?php endif; ?>
                    <a href="<?= $root_directory . 'support' ?>" class="text-light-dark hover:text-secondary cursor-pointer">Warranty</a>
                    <a href="<?= $root_directory . 'support' ?>" class="text-light-dark hover:text-secondary cursor-pointer">Contact Us</a>
                    <a href="<?= $root_directory . 'support' ?>" class="text-light-dark hover:text-secondary cursor-pointer">Refund Policy</a>
                    <a href="<?= $root_directory . 'support' ?>" class="text-light-dark hover:text-secondary cursor-pointer">FAQ</a>
                    <a href="<?= $root_directory . 'support' ?>" class="text-light-dark hover:text-secondary cursor-pointer">Where to Buy</a>
                    <span class="text-light-dark hover:text-secondary cursor-pointer" onclick="openTermsForPurchase()">Terms & Conditions</span>
                </section>
            </div>

            <!-- Community Section -->
            <div>
                <h4 class="text-lg font-bold mb-4">Community</h4>
                <section class="flex flex-col gap-1">
                    <a href="<?= $root_directory . 'about' ?>" class="text-light-dark hover:text-secondary cursor-pointer">About us</a>
                    <a href="https://x.com/PulsarGears" target="_blank" class="text-light-dark hover:text-secondary cursor-pointer">News</a>
                    <a href="https://t.co/1d3hoNI15S" target="_blank" class="text-light-dark hover:text-secondary cursor-pointer">Discord</a>
                    <a href="https://x.com/PulsarGears" target="_blank" class="text-light-dark hover:text-secondary cursor-pointer">eSports</a>
                    <a href="https://x.com/PulsarGears" target="_blank" class="text-light-dark hover:text-secondary cursor-pointer">Facebook</a>
                    <a href="https://x.com/PulsarGears" target="_blank" class="text-light-dark hover:text-secondary cursor-pointer">Instagram</a>
                    <a href="https://x.com/PulsarGears" target="_blank" class="text-light-dark hover:text-secondary cursor-pointer">X</a>
                    <a href="https://x.com/PulsarGears" target="_blank" class="text-light-dark hover:text-secondary cursor-pointer">Tiktok</a>
                </section>
            </div>

            <!-- Stay Connected Section -->
            <div class="col-span-2 md:col-span-4">
                <h4 class="text-lg font-bold mb-4">Stay Connected</h4>
                <!-- Subscribe Form -->
                <div class="mt-4 w-full sm:w-8/12 md:w-1/2 xl:w-1/3">
                    <form>
                        <div class="flex w-full items-center border-2 border-primary rounded">
                            <input type="email" class="flex-grow px-4 py-2 w-full outline-none bg-transparent text-primary transition-all ease-in-out duration-300 focus:bg-primary focus:text-dark" placeholder="Email address">
                            <button class="w-fit border-l-2 border-primary bg-transparent text-secondary px-4 py-2 transition duration-300 ease-in-out hover:bg-accent active:bg-accent/80 focus:outline-none">Subscribe</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Footer Section -->
    <div class="mt-8 border-t border-light-dark pt-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <!-- Left Side -->
            <div class="text-sm text-light-dark">
                &copy; 2023 Pulsar Gaming Gears. All rights reserved.
            </div>

            <!-- Payment Icons -->
            <div class="space-x-4 bg-primary px-4 py-1 rounded hidden sm:flex">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/1024px-Visa_Inc._logo.svg.png" alt="Visa" class="h-6">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/2560px-Mastercard-logo.svg.png" alt="Mastercard" class="h-6">
                <img src="https://cdn.pixabay.com/photo/2015/05/26/09/37/paypal-784404_1280.png" alt="PayPal" class="h-6">
                <!-- Add more icons as needed -->
            </div>
        </div>
    </div>
</footer>