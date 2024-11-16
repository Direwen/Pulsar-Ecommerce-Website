<?php

$products = [];
$category = null;

//fetch category flexibly
$search_input = isset($_GET["search"]) ? $_GET["search"] : null;
$category_input = isset($_GET["category"]) ? $_GET["category"] : null;

if (!empty($search_input)) {
    $category = ErrorHandler::handle(fn() => $category_model->get([
        $category_model->getColumnName() => strtolower($search_input)
    ]));
} else {
    $category = ErrorHandler::handle(fn()  => $category_model->get([
        $category_model->getColumnId() => $category_input ?? $category_model->getAll()["records"][0]["id"]
    ]));
} 

//fetch products
if (is_array($category)) {
    $fetched_overview_products_data = ErrorHandler::handle(fn() => $product_model->getAll(
        select: [
            ["column" => $product_model->getColumnId()],
            ["column" => $product_model->getColumnName()],
            ["column" => $product_model->getColumnImg()],
        ],
        aggregates: [
            ["column" => $variant_model->getColumnUnitPrice(), "function" => "MIN", "alias" => "min_price", "table" => $variant_model->getTableName()],
            ["column" => $variant_model->getColumnImg(), "function" => "GROUP_CONCAT", "alias" => "variants", "table" => $variant_model->getTableName()],
        ],
        joins: [
            [
                'type' => "INNER JOIN",
                'table' => $variant_model->getTableName(),
                'on' => "variants.product_id = products.id"
            ]
        ],
        groupBy: $product_model->getTableName() . "." . $product_model->getColumnId(),
        conditions: [
            [
                'attribute' => 'products.category_id',
                'operator' => "=",
                'value' => $category["id"]
            ]
        ]

    ));
    ;

    $products = $fetched_overview_products_data["records"];
} else {
    $category = null;
}



?>

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
                <section class="text-sm">
                    <span class="mr-4">Sort By:</span>
                    <span>Best Selling</span>
                </section>
            </div>

            <div class="flex flex-wrap justify-center items-start gap-x-2 gap-y-4">
                <?php foreach ($products as $product): ?>
                    <?php renderProductCard($product); ?>
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