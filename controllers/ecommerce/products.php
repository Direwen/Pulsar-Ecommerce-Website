<?php
$category = ErrorHandler::handle(fn() => $category_model->get([
    $category_model->getColumnId() => $_GET["category"] ?? $category_model->getAll()["records"][0]["id"]
]));

if($category && !is_array($category)) {
    header("Location: " . $root_directory);
    exit();
}

$fetched_overview_products_data = ErrorHandler::handle(fn () => $product_model->getAll(
    select: [
        ["column" => $product_model->getColumnId()],
        ["column" => $product_model->getColumnName()],
        ["column" => $product_model->getColumnImg()],
    ],
    aggregates: [
        ["column" => $variant_model->getColumnUnitPrice(), "function" => "MIN", "alias" => "min_price", "table" => $variant_model->getTableName()],
        ["column" => $variant_model->getColumnImg(), "function" => "Group_concat", "alias" => "variants", "table" => $variant_model->getTableName()]
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

));;

$products = $fetched_overview_products_data["records"];

//If so, fetch products with the condition of its category id being the same as this category id
//If not, fetch products with the default category id
?>

<div class="">

    <?php renderHeroSection($category[$category_model->getColumnName()], $category[$category_model->getColumnBannerImg()]); ?>

    <div class="text-dark border-b border-light-dark py-4 w-11/12 mx-auto flex justify-between items-center tracking-tigher">
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
        <?php foreach($products as $product): ?>
            
            <?php renderProductCard(
                product_name: $product["name"],
                min_price: $product["min_price"],
                product_img: $product["img"],
                product_variant_img: $product["variants"]
            ); ?>

        <?php endforeach; ?>
    </div>



</div>