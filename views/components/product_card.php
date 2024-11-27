<section class="rounded-lg">
    <!-- Product main image -->
    <a href="<?= $root_directory . "product/view?id=" . urlencode($product["id"]); ?>">
        <div class="relative h-64 bg-secondary flex items-center justify-center rounded overflow-hidden lg:h-80">
            <img src="<?= $root_directory ?>/assets/products/<?= htmlspecialchars($product["img"]) ?>" 
                 alt="product image" 
                 class="w-64 h-64 lg:w-80 lg:h-80 object-cover z-10 rounded transition-all ease-in duration-300 hover:scale-110">
            
            <!-- Add to cart icon -->
            <?php
                // Get the first available variant ID if any
                $variant_id = $is_available ? $available_variant_ids[0] : null;

                // Define button classes based on availability
                $button_classes = $is_available 
                    ? "bg-primary hover:bg-accent hover:text-secondary interactive" 
                    : "bg-light-gray text-secondary cursor-not-allowed";
            ?>
            <button
                title="<?= $is_available ? 'add to cart' : 'Out of Stock' ?>"
                onclick="<?= $is_available ? "event.stopPropagation(); event.preventDefault(); addToCartShortcut($variant_id, $root_directory)" : "return false;" ?>"
                class="absolute bottom-4 right-4 rounded-full px-2 py-1 shadow text-light-dark z-10 <?= $button_classes ?>"
                <?= $is_available ? "" : "disabled" ?>
            >
                <span class="material-symbols-outlined">shopping_bag</span>
            </button>

            <?php if(!$is_available): ?>
                <span class="absolute top-0 left-0 text-xs md:text-sm tracking-tighter font-semibold px-4 py-1 bg-danger text-secondary rounded-br border shadow">Sold Out</span>
            <?php endif; ?>

        </div>
    </a>

    <!-- Variant images -->
    <div class="flex flex-wrap justify-start mt-4 gap-1">
        <?php foreach (array_map("trim", explode(",", $product['variants'])) as $variant): ?>
            <img src="<?= $root_directory ?>/assets/products/<?= htmlspecialchars($variant) ?>" 
                 alt="variant image" 
                 class="w-12 h-12 object-cover border border-light-gray rounded hover:border-accent cursor-pointer">
        <?php endforeach; ?>
    </div>

    <!-- Product name -->
    <p class="mt-4 text-left text-dark text-lg font-bold tracking-tight mb-2">
        <?= ucwords($product["name"]); ?>
    </p>

    <!-- Product price -->
    <section class="flex justify-between items-center">
        <p class="text-left text-dark text-sm mt-1 font-bold">
            <span class="text-xs">$</span><?= htmlspecialchars($product["min_price"]); ?>
        </p>

        <?php if($is_popular): ?>
            <span class="text-xs md:text-sm tracking-tighter font-semibold px-3 py-1 bg-yellow-500 text-primary border shadow rounded-full">Most Popular</span>
        <?php endif; ?>
    </section>
    

    
</section>
