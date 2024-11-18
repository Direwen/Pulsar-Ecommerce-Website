<div>

    <div class="flex flex-col xl:flex-row xl:items-stretch">
        <!-- Left Side to show varinat img and img_for_ads for the selected variant -->
        <div class="pt-24 xl:pt-0 w-full xl:w-8/12 bg-secondary xl:self-stretch">
            <!-- Main Swiper Slider -->
            <div class="swiper main-slider w-full xl:h-[35rem]">
                <div class="swiper-wrapper">
                    <?php foreach ($variants as $variant): ?>
                        <div class="swiper-slide flex justify-center items-center drop-shadow-2xl">
                            <a href="<?= $root_directory . "assets/products/" . $variant['img'] ?>"
                                data-fancybox="<?= $product['name'] . '_gallery' ?>" data-caption=""
                                class="w-72 h-72 md:w-96 md:h-96 flex items-center justify-center outline-none">
                                <img src="<?= $root_directory . "assets/products/" . $variant['img'] ?>" alt=""
                                    class="w-full h-full object-cover rounded-lg">
                            </a>
                        </div>

                        <?php foreach ($variant["img_for_ads"] as $img): ?>
                            <div class="swiper-slide flex justify-center items-center drop-shadow-2xl">
                                <a href="<?= $root_directory . "assets/products/" . $img ?>"
                                    data-fancybox="<?= $product['name'] . '_gallery' ?>" data-caption=""
                                    class="w-72 h-72 md:w-96 md:h-96 flex items-center justify-center outline-none">
                                    <img src="<?= $root_directory . "assets/products/" . $img ?>" alt=""
                                        class="w-full h-full object-cover rounded-lg">
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
                <div
                    class="swiper-button-prev text-dark p-10 scale-50 rounded border border-transparent hover:shadow hover:rounded-full hover:border-light-gray hover:bg-primary">
                </div>
                <div
                    class="swiper-button-next text-dark p-10 scale-50 rounded border border-transparent hover:shadow hover:rounded-full hover:border-light-gray hover:bg-primary">
                </div>
                <!-- <div class="swiper-pagination"></div> -->
                <!-- <div class="swiper-scrollbar"></div> -->
            </div>

            <!-- Thumbnail Swiper Slider -->
            <div class="swiper thumbs-slider w-8/12">
                <div class="swiper-wrapper">
                    <?php foreach ($variants as $variant): ?>
                        <div class="swiper-slide flex justify-center items-center">
                            <img src="<?= $root_directory . "assets/products/" . $variant['img'] ?>" alt="Thumbnail"
                                class="w-20 h-20 md:w-24 md:h-24 object-cover rounded-lg">
                        </div>

                        <?php foreach ($variant["img_for_ads"] as $img): ?>
                            <div class="swiper-slide flex justify-center items-center">
                                <img src="<?= $root_directory . "assets/products/" . $img ?>" alt="Thumbnail"
                                    class="w-20 h-20 md:w-24 md:h-24 object-cover rounded-lg">
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>

        <!-- Main Content -->
        <div class="pt-24 grow p-6 flex flex-col gap-4">
            <!-- Product Title -->
            <h1 class="text-2xl font-bold"><?= htmlspecialchars(ucwords($product['name'])) ?> Gaming Mouse</h1>

            <!-- Price of the selected variant -->
            <p class="font-bold">
                <span class="text-sm">$</span><span class="text-base lg:text-lg"
                    id="variant_price_tag"><?= $variants[0]['unit_price'] ?></span>
            </p>

            <!-- Color Selection and Images -->
            <section>
                <!-- To Display Type and Name of the selected Variant -->
                <section class="font-thin text-sm" id="variant_name_tag">
                    <?= $variants[0]["type"] . ": " . $variants[0]["name"] ?>
                </section>

                <!-- Variant main images to select -->
                <section id="variants-selections-container" class="flex justify-start items-center gap-2 flex-wrap">
                    <?php foreach ($variants as $index => $variant): ?>
                        <section class="bg-secondary border-2 shadow cursor-pointer interactive <?= ($index == 0 ) ? 'border-accent' : '' ?>"
                            onclick="selectVariant(this, {'price': <?= $variant['unit_price'] ?>, 'type': '<?= $variant['type'] ?>', 'name': '<?= $variant['name'] ?>', 'stock_quantity': <?= $variant['stock_quantity'] ?>, 'id': <?= $variant['id'] ?>})">
                            <img src="<?= $root_directory . "assets/products/" . $variant["img"] ?>" alt="img"
                                class="w-20 h-20">
                        </section>
                    <?php endforeach; ?>
                </section>
            </section>

            <!-- Quantity -->
            <section class="flex gap-2 items-center">
                <label for="quantity" class="font-thin tracking-tighter text-sm">Quantity:</label>
                <div class="flex items-center px-2 py-1 border shadow w-fit gap-4 rounded">
                    <button type="button" onclick="decrementQuantity('product-quantity-display')" class="interactive">
                        <span class="material-symbols-outlined text-xl">remove</span>
                    </button>
                    <span id="product-quantity-display" class="quantity-display">1</span>
                    <button type="button" onclick="incrementQuantity('product-quantity-display')" class="interactive">
                        <span class="material-symbols-outlined text-xl">add</span>
                    </button>
                </div>
            </section>

            <!-- variant id -->
            <section id="variant-id-display" class="hidden"><?= $variants[0]["id"] ?></section>

            <!-- Stock Display -->
            <span id="stock-display" class="hidden">Stock <?= $variants[0]['stock_quantity']; ?></span>

            <!-- Add to Cart Button -->
            <button id="add-to-cart-btn" cart-api="<?= $root_directory . 'api/cart'; ?>" root="<?= $root_directory; ?>" onclick="addToCart(this)"
                class="interactive uppercase font-semibold text-lg shadow shadow-accent bg-accent text-primary text-center py-2 rounded tracking-tighter"
                style="display: <?= $variants[0]['stock_quantity'] > 0 ? 'block' : 'none' ?>;">
                Add to Cart
            </button>

            <!-- Sold Out Button -->
            <button id="sold-out-btn"
                class="cursor-not-allowed uppercase font-semibold text-lg border shadow text-accent text-center py-2 rounded tracking-tighter"
                style="display: <?= $variants[0]['stock_quantity'] > 0 ? 'none' : 'block' ?>;">
                SOLD out
            </button>

            <?php $details_toggle_count = 1; ?>

            <?php foreach ($product["important_feature"] as $title => $details): ?>

                <?php
                renderSpecsToggleBox(
                    title: $title,
                    details: $details,
                    data_toggle_attr: "details_toggle_" . $details_toggle_count++,
                    extra_info: [
                        "title_css" => "bg-transparent",
                        "trigger_css" => "cursor-pointer border-b border-light-dark flex justify-between items-center px-3 py-1 uppercase font-medium tracking-tighter",
                        "details_css" => "tracking-tighter text-sm md:text-base font-thin"
                    ]
                );
                ?>

            <?php endforeach; ?>

            <?php
            renderSpecsToggleBox(
                title: "Specification",
                details: $product["feature"],
                data_toggle_attr: "details_toggle_0",
                extra_info: [
                    "title_css" => "bg-transparent",
                    "trigger_css" => "cursor-pointer border-b border-light-dark flex justify-between items-center px-3 py-1 uppercase font-medium tracking-tighter",
                    "details_css" => "tracking-tighter text-sm md:text-base font-thin"
                ]
            );
            ?>

        </div>

    </div>

    <!-- mini nav bar -->
    <div class="border-y border shadow py-3">
        <section
            class="w-full md:w-8/12 lg:w-1/2 flex justify-around items-center mx-auto font-medium text-sm md:text-base tracking-tighter text-dark">
            <a href="#product-desc-container" class="hover:text-accent">Product Details</a>
            <a href="#specs-container" class="hover:text-accent">Techinical Specifications</a>
            <a href="" class="hover:text-accent">Download</a>
        </section>
    </div>

    <!-- description -->
    <section class="tracking-tighter text-dark flex flex-col justify-center items-center gap-4 px-8 py-16 md:p-48"
        id="product-desc-container">
        <p class="text-justify text-sm lg:text-base w-full indent-10 leading-loose md:w-10/12 lg:w-8/12">
            <span
                class="text-4xl font-bold text-dark"><?= ucwords(htmlspecialchars(explode(' ', $product["description"])[0])); ?></span>
            <?= ucwords(htmlspecialchars(substr($product["description"], strpos($product["description"], ' ') + 1))); ?>
        </p>
    </section>

    <!-- ads img -->
    <?php foreach ($product["img_for_ads"] as $each): ?>
        <img src="<?= $root_directory . "assets/products/" . $each ?>" alt="ads image"
            class="w-full min-h-fit max-h-[45rem] object-scale-down">
    <?php endforeach; ?>

    <!-- Specifications -->
    <section class="w-10/12 mx-auto py-10" id="specs-container">
        <h1 class="font-bold text-left text-xl md:text-5xl mb-4">Techinical Specifications</h1>
        <section>
            <?php
            $specs_title = ['dimension', 'feature', 'requirement', 'package_content'];
            $data_toggle_count = 1;
            ?>
            <?php foreach ($specs_title as $title): ?>
                <?php
                renderSpecsToggleBox(
                    title: $title,
                    details: $product[$title],
                    data_toggle_attr: "specs_toggle_" . $data_toggle_count++,
                    extra_info: ($title == "dimension") ? ["unit" => "cm", "unit_conversion" => true] : []
                );
                ?>
            <?php endforeach; ?>

            <?php foreach ($product["important_feature"] as $title => $details): ?>
                <?php
                renderSpecsToggleBox(
                    title: $title,
                    details: $details,
                    data_toggle_attr: "specs_toggle_" . $data_toggle_count++,
                );
                ?>
            <?php endforeach; ?>
        </section>
    </section>

    <!-- manual -->
</div>