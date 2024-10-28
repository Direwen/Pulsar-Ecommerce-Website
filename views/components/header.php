<?php
// Current page to highlight the active link
$currentPage = basename($_SERVER['SCRIPT_FILENAME']);

// Define navigation links
$navLinks = [
    ['url' => 'product.php', 'label' => 'PRODUCT'],
    ['url' => 'support.php', 'label' => 'SUPPORT'],
    ['url' => 'where_to_buy.php', 'label' => 'WHERE TO BUY'],
    ['url' => './src/views/login.php', 'label' => 'RELEASE'],
];
?>

<div class="fixed top-0 left-0 w-screen h-fit bg-primary shadow z-40">
    <!-- Primary Header (Permanent Section) -->
    <div class="py-5 sm:py-0 px-8 flex justify-between items-center">
        <!-- Logo Section -->
        <section class="w-3/12 sm:w-1/12 flex-none">
            <img src="<?= $root_directory; ?>assets/pulsar_logo.jpg" alt="LOGO" class="h-fit">
        </section>

        <!-- Navigation Links -->
        <section id="navigation-links-container" class="hidden flex-grow sm:flex justify-center items-stretch gap-5 uppercase">
            <?php foreach ($navLinks as $link): ?>
                <a href="<?php echo $link['url']; ?>" class="header_links interactive h-full <?php echo ($currentPage == basename($link['url'])) ? 'active' : ''; ?>">
                    <?php echo $link['label']; ?>
                </a>
            <?php endforeach; ?>
        </section>

        <!-- Right Side Icons (Menu, Search, Profile, Cart) -->
        <section class="flex justify-between items-center gap-4 sm:gap-5">
            <span class="material-symbols-outlined interactive sm:hidden" id="menu-button">menu</span>
            <span class="material-symbols-outlined interactive" id="search-button">search</span>


            <?php if ($auth_service->getAuthUser()): ?>
                <a href="<?= $root_directory; ?>logout"><span class="material-symbols-outlined interactive">logout</span></a>
            <?php else: ?>
                <a href="<?= $root_directory; ?>login"><span class="material-symbols-outlined interactive">person</span></a>
            <?php endif; ?>

            <div class="interactive">
                <span class="material-symbols-outlined">shopping_cart</span>
            </div>
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
            <form action="" method="GET" class="w-full flex items-center gap-5">
                <button type="submit" class="interactive">
                    <span class="material-symbols-outlined">
                        search
                    </span>
                </button>
                <input
                    type="text"
                    id="search-input"
                    name="query"
                    placeholder="Search..."
                    class="border-b border-dark outline-none focus:outline-none focus:border-accent focus:border-b-2 p-2 w-full h-full" />
                <span id="search-close-button" class="material-symbols-outlined interactive">close</span>
            </form>
        </div>
    </div>


</div>