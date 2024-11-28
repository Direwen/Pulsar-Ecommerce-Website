<div class="w-full h-screen relative bg-black overflow-hidden">
    <!-- Background Video -->
    <video autoplay loop muted playsinline class="absolute top-0 left-0 w-full h-full object-cover z-0">
        <source src="<?= $root_directory?>assets/pulsar.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <!-- Overlay with Content -->
    <div class="w-full h-full bg-secondary/60 relative flex justify-start items-end p-4 z-10 sm:px-20 sm:py-16">
        <section class="flex flex-col items-start justify-center text-center uppercase">
            <span class="text-4xl sm:text-7xl font-bold tracking-tighter">
                <span class="text-accent">pulsar</span> Official
            </span>
            <span class="text-2xl sm:text-7xl tracking-tighter">Gaming Gears</span>
            <?php renderLinkButton("Discover", $root_directory . 'products') ?>
        </section>
    </div>
</div>

<div class="py-5">
    <section class="flex flex-col text-center text-xl sm:text-2xl lg:text-3xl">
        <span class="text-light-dark font-bold">Maximize Your Gaming Performance</span>
        <span class="font-semibold">with Pulsar Gaming Gears.</span>
    </section>

    <div id="scroll-container" class="p-5 overflow-x-auto whitespace-nowrap scroll-smooth hide-scrollbar">
        <?php
        renderCategories(
            "block mt-2 font-semibold",
            true,
            "w-max h-64 object-cover text-center mx-auto",
            "floatable bg-secondary border inline-block rounded w-8/12 sm:w-5/12 md:w-3/12 xl:w-2/12 mr-4 text-center p-2"
        );
        ?>
    </div>
</div>

<div class="w-full h-32 sm:h-auto md:p-12">
    <img src="<?= $root_directory ?>assets/Pulsar_x_VCT_pacific.webp" alt="" class="w-full h-full">
</div>

<?php renderMediumBanner("SuperGlideV2", "Glass Mousepad", "Discover", $root_directory . "products?search=mouse pad", $root_directory . "assets/mouse_pad.webp", false); ?>
<?php renderMediumBanner("Collaboration", "Pulsar x Demon slayer", "Discover", $root_directory . "products?search=mouse", $root_directory .  "assets/demon_slayer_collab.jpg", false); ?>

<div class="p-4 sm:p-10">
    <div class="w-11/12 sm:w-10/12 lg:w-8/12 mx-auto grid grid-cols-1 sm:grid-cols-2 gap-4">
        <?php
        renderMiniBanner("4K", "Dongle", $root_directory . "products?search=mouse accessories", $root_directory . "assets/4k_dongle.webp");
        renderMiniBanner("Xlite V2", "Ergonomic", $root_directory . "products?search=mouse", $root_directory . "assets/xlite_v2.webp");
        renderMiniBanner("X2A", "Ambidextrous", $root_directory . "products?search=mouse", $root_directory . "assets/x2a.webp");
        renderMiniBanner("Superglide", "Mouse Skate", $root_directory . "products?search=superglide", $root_directory . "assets/mouse_skate.jpg");
        ?>
    </div>
</div>