<section class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5 p-4 rounded-lg">
    <!-- Product main image -->
    <a href="<?= $root_directory . "product/view?id=" . urlencode($product["id"]); ?>">
        <div class="relative h-64 bg-secondary flex items-center justify-center rounded">
            <img src="<?= $root_directory ?>/assets/products/<?= htmlspecialchars($product["img"]) ?>" 
                 alt="product image" 
                 class="w-64 h-64 object-cover z-10 transition-all ease-in duration-300 hover:scale-125">
            
            <!-- Background "mini" text -->
            <span class="absolute top-2 w-full text-center text-5xl text-light-gray font-semibold z-0">
                <?= strtoupper(explode(" ", $product["name"])[0]); ?>
            </span>
            
            <!-- Add to cart icon -->
            <button class="absolute bottom-4 right-4 bg-primary rounded-full px-2 py-1 shadow text-light-dark interactive z-10 hover:bg-accent hover:text-secondary">
                <span class="material-symbols-outlined">shopping_bag</span>
            </button>
        </div>
    </a>

    <!-- Variant images -->
    <div class="flex flex-wrap justify-start mt-4 gap-1">
        <?php foreach (explode(",", $product["variants"]) as $variant): ?>
            <img src="<?= $root_directory ?>/assets/products/<?= htmlspecialchars(trim($variant)) ?>" 
                 alt="variant image" 
                 class="interactive w-12 h-12 object-cover border border-light-gray rounded hover:border-accent cursor-pointer">
        <?php endforeach; ?>
    </div>

    <!-- Product name -->
    <p class="mt-4 text-left text-dark text-lg font-bold tracking-tight">
        <?= ucwords($product["name"]); ?>
    </p>

    <!-- Product price -->
    <p class="text-left text-dark text-sm mt-1 font-bold">
        <span class="text-xs">$</span><?= htmlspecialchars($product["min_price"]); ?>
    </p>
</section>
