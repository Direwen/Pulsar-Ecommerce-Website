<div class="">

    <?php if (is_array($category)): ?>
        <?php renderHeroSection($category[$category_model->getColumnName()], $category[$category_model->getColumnBannerImg()]); ?>

        <?php if (count($products) > 0): ?>
            <div
                class="text-dark border-b border-light-dark py-4 w-11/12 mx-auto flex justify-between items-center tracking-tigher">
                <section class="">
                    <span class="material-symbols-outlined text-2xl">tune</span>
                    <span class="text-sm">Show Filters</span>
                </section>
                <section class="relative text-sm">
                    <span class="mr-4">Sort By:</span>
                    <div class="relative inline-block">
                        <button id="sort-toggle" class="px-2 border shadow rounded interactive" onclick="toggleSortOptions()">
                            <span id="current-sort"><?= !empty($sort_input) ? ucfirst($sort_input) : 'Default' ?></span>
                            <span class="material-symbols-outlined ml-2">expand_more</span>
                        </button>
                        <div id="sort-options" class="absolute right-0 mt-2 w-48 bg-white border rounded shadow z-20 hidden">
                            <a href="?<?= !empty($search_input) ? 'search=' . urlencode($search_input) . '&' : '' ?><?= !empty($category_input) ? 'category=' . urlencode($category_input) : '' ?>"
                                class="block px-2 py-1 text-light-dark bg-primary hover:bg-secondary interactive">Default</a>
                            <a href="?<?= !empty($search_input) ? 'search=' . urlencode($search_input) . '&' : '' ?><?= !empty($category_input) ? 'category=' . urlencode($category_input) . '&' : '' ?>sort=new"
                                class="block px-2 py-1 text-light-dark bg-primary hover:bg-secondary interactive">New</a>
                            <a href="?<?= !empty($search_input) ? 'search=' . urlencode($search_input) . '&' : '' ?><?= !empty($category_input) ? 'category=' . urlencode($category_input) . '&' : '' ?>sort=old"
                                class="block px-2 py-1 text-light-dark bg-primary hover:bg-secondary interactive">Old</a>
                            <a href="?<?= !empty($search_input) ? 'search=' . urlencode($search_input) . '&' : '' ?><?= !empty($category_input) ? 'category=' . urlencode($category_input) . '&' : '' ?>sort=lowest"
                                class="block px-2 py-1 text-light-dark bg-primary hover:bg-secondary interactive">Lowest</a>
                            <a href="?<?= !empty($search_input) ? 'search=' . urlencode($search_input) . '&' : '' ?><?= !empty($category_input) ? 'category=' . urlencode($category_input) . '&' : '' ?>sort=highest"
                                class="block px-2 py-1 text-light-dark bg-primary hover:bg-secondary interactive">Highest</a>
                        </div>
                    </div>
                </section>

            </div>

            <div class="w-11/12 mx-auto my-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                <?php foreach ($products as $product): ?>
                    <?php renderProductCard($product, $max_views); ?>
                <?php endforeach; ?>
            </div>


        <?php else: ?>

            <div class="flex flex-col justify-center items-center px-3 py-6 gap-4">
                <p class="text-2xl md:text-4xl font-thin tracking-tighter uppercase">No Products Found</p>
                <img src="<?= $root_directory . 'assets/illustrations/not_found.svg' ?>" alt="svg"
                    class="w-1/2 md:w-1/4 lg:w-1/6 animate-pulse">
            </div>

        <?php endif; ?>
    <?php else: ?>
        <div class="flex flex-col justify-center items-center px-3 py-6 pt-24 gap-4">
            <p class="text-2xl md:text-4xl font-thin tracking-tighter uppercase">No Products Found</p>
            <img src="<?= $root_directory . 'assets/illustrations/not_found.svg' ?>" alt="svg"
                class="w-1/2 md:w-1/4 lg:w-1/6 animate-pulse">
        </div>
    <?php endif; ?>

</div>