<div class="flex flex-col gap-4 w-11/12 mx-auto mb-4">

    <section class="text-dark border-b border-light-dark py-4 flex justify-between items-center tracking-tigher">
        <h2 class="text-xl font-semibold text-dark tracking-tighter">Recently Viewed</h2>
    </section>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
        <?php foreach ($products as $product): ?>
            <a href=""
                class="block cursor-pointer relative h-64 bg-secondary flex items-center justify-center rounded shadow overflow-hidden">
                <img src="<?= $root_directory ?>/assets/products/<?= htmlspecialchars($product["img"]) ?>"
                    alt="product image"
                    class="w-64 h-64 object-cover z-10 rounded transition-all ease-in duration-300 hover:scale-110">
            </a>
        <?php endforeach; ?>
    </div>

</div>