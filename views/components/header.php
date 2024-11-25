<?php
// Current page to highlight the active link
$currentPage = basename($_SERVER['SCRIPT_FILENAME']);

// Define navigation links
$navLinks = [
    ['url' => './', 'label' => 'PRODUCT'],
    ['url' => 'support', 'label' => 'SUPPORT'],
    ['url' => 'about', 'label' => 'About'],
];
?>

<div class="fixed top-0 left-0 w-screen h-fit bg-primary shadow z-40">
    <!-- Primary Header (Permanent Section) -->
    <div class="py-5 sm:py-0 px-8 flex justify-between items-center">
        <!-- Logo Section -->
        <section class="w-3/12 sm:w-1/12 flex-none">
            <a href="<?= $root_directory; ?>" class="">
                <img src="<?= $root_directory; ?>assets/pulsar_logo.jpg" alt="LOGO" class="h-fit">
            </a>
        </section>

        <!-- Navigation Links -->
        <section id="navigation-links-container"
            class="hidden flex-grow sm:flex justify-center items-stretch gap-5 uppercase">
            <?php foreach ($navLinks as $link): ?>
                <a href="<?= $root_directory . $link['url']; ?>"
                    class="header_links interactive h-full">
                    <?php echo $link['label']; ?>
                </a>
            <?php endforeach; ?>
        </section>

        <!-- Right Side Icons (Menu, Search, Profile, Cart) -->
        <section class="flex justify-between items-center gap-4 sm:gap-5">
            <span class="material-symbols-outlined interactive sm:hidden" onclick="openNavbar()">menu</span>
            <span class="material-symbols-outlined interactive" id="search-button">search</span>


            <?php if ($auth_service->getAuthUser()): ?>
                <div class="relative sm:inline-block hidden">
                    <button
                        class="flex justify-between items-center gap-2 lg:shadow lg:border lg:px-3 rounded interactive text-dark"
                        onclick="toggleUserInfo()">
                        <span class="font-medium tracking-tighter hidden lg:inline"><?= $_SESSION["user_email"] ?></span>
                        <span class="material-symbols-outlined">account_circle</span>
                    </button>
                    <div id="user-info-menu"
                        class="hidden absolute w-auto min-w-[200px] right-0 bg-primary border shadow rounded mt-2">
                        <?php if ($_SESSION["user_role"] != "user"): ?>
                            <a href="<?= $root_directory; ?>admin/dashboard"
                                class="block px-4 py-2 text-dark hover:bg-secondary interactive">
                                <span class="material-symbols-outlined interactive">widgets</span> Dashboard
                            </a>
                        <?php endif; ?>

                        <a href="<?= $root_directory; ?>history"
                            class="block px-4 py-2 text-dark hover:bg-secondary interactive">
                            <span class="material-symbols-outlined interactive">history</span> Order History
                        </a>
                        <span onclick="confirmAccountDisable(<?= $root_directory ?>)"class="cursor-pointer block px-4 py-2 text-dark hover:bg-secondary interactive">
                            <span class="material-symbols-outlined interactive">block</span> Disable Account
                        </span>
                        <a href="<?= $root_directory; ?>logout"
                            class="block px-4 py-2 text-dark hover:bg-secondary interactive">
                            <span class="material-symbols-outlined interactive">logout</span> Logout
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?= $root_directory; ?>login"><span class="material-symbols-outlined interactive">person</span></a>
            <?php endif; ?>

            <a class="interactive relative" onclick="openShoppingCart('<?= $root_directory ?>')">
                <span class="material-symbols-outlined">shopping_cart</span>

                <?php
                // Check if the cookie is set and contains valid JSON data
                $item_count = 0;
                if (isset($_COOKIE["CART"])) {
                    $cart_data = json_decode($_COOKIE["CART"]);

                    // Check if json_decode was successful and if it's an object or array
                    if (json_last_error() === JSON_ERROR_NONE && (is_object($cart_data) || is_array($cart_data))) {
                        $item_count = count((array) $cart_data);
                    }
                }
                ?>

                <?php if ($item_count > 0): ?>
                    <span class="absolute -top-1 -right-3 bg-accent text-primary px-2 rounded-full text-sm">
                        <?= $item_count; ?>
                    </span>
                <?php endif; ?>


            </a>

        </section>
    </div>

    <!-- Secondary Header (Dynamic Section) -->
    <div id="second-header" class="w-full h-fit flex justify-center items-center p-10 hidden">
        <!-- Categories Section -->
        <div id="categories-container" class="flex justify-center items-center gap-10">
            <?php
            renderCategories(
                "font-light group-hover:text-accent tracking-tighter uppercase",
                true,
                "w-20 h-20 transition-all ease-in-out duration-300 group-hover:scale-110",
                "flex flex-col justify-center items-center group cursor-pointer"
            );
            ?>
        </div>

        <!-- Search Box (hidden by default) -->
        <div id="search-container" class="hidden w-full h-full">
            <form action="<?= $root_directory . 'products' ?>" method="GET" class="w-full flex items-center gap-5">
                <button type="submit" class="interactive">
                    <span class="material-symbols-outlined">
                        search
                    </span>
                </button>
                <input type="text" id="search-input" name="search" placeholder="Search Products"
                    class="border-b border-dark outline-none focus:outline-none focus:border-accent focus:border-b-2 p-2 w-full h-full" />
                <span id="search-close-button" class="material-symbols-outlined interactive">close</span>
            </form>
        </div>
    </div>


</div>