<?php
$category = ErrorHandler::handle(fn() => $category_model->get([
    $category_model->getColumnId() => $_GET["category"] ?? $category_model->getAll()["records"][0]["id"]
]));

if($category && !is_array($category)) {
    header("Location: " . $root_directory);
    exit();
}

$fetched_products_data = ErrorHandler::handle(fn () => $product_model->getAll());

$products = $fetched_products_data["records"];

//If so, fetch products with the condition of its category id being the same as this category id
//If not, fetch products with the default category id
?>

<div class="bg-gray-300">

<div class="flex flex-wrap gap-4 justify-start  ">
    <?php foreach($products as $product): ?>
        <section class="border border-red-600 w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5">
            <section class="h-56 bg-black overflow-hidden">
                <img src="<?= $root_directory ?>/assets/products/<?= $product['img'] ?>" alt="product image" class="w-full h-full object-cover object-center">
            </section>
            <p class="mt-2 text-center"><?= $product["name"]; ?></p>
        </section>
        <section class="border border-red-600 w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5">
            <section class="h-56 bg-black overflow-hidden">
                <img src="<?= $root_directory ?>/assets/products/<?= $product['img'] ?>" alt="product image" class="w-full h-full object-cover object-center">
            </section>
            <p class="mt-2 text-center"><?= $product["name"]; ?></p>
        </section>
        <section class="border border-red-600 w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5">
            <section class="h-56 bg-black overflow-hidden">
                <img src="<?= $root_directory ?>/assets/products/<?= $product['img'] ?>" alt="product image" class="w-full h-full object-cover object-center">
            </section>
            <p class="mt-2 text-center"><?= $product["name"]; ?></p>
        </section>
        <section class="border border-red-600 w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5">
            <section class="h-56 bg-black overflow-hidden">
                <img src="<?= $root_directory ?>/assets/products/<?= $product['img'] ?>" alt="product image" class="w-full h-full object-cover object-center">
            </section>
            <p class="mt-2 text-center"><?= $product["name"]; ?></p>
        </section>
    <?php endforeach; ?>
</div>



</div>