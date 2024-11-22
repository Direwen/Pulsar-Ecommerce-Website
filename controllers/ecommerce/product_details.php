<?php
// At the start of product-details.php
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

$data = $review_model->getRatingsAndCount($_GET["id"]);
$finalized_rating = generateRating($data["total_ratings"], $data["rating_count"]);

$fetched_data = ErrorHandler::handle(fn() => $product_model->getAll(
    select: [
        ["column" => $product_model->getColumnId()],
        ["column" => $product_model->getColumnName()],
        ["column" => $product_model->getColumnDescription()],
        ["column" => $product_model->getColumnDimension()],
        ["column" => $product_model->getColumnFeature()],
        ["column" => $product_model->getColumnImportantFeature()],
        ["column" => $product_model->getColumnRequirement()],
        ["column" => $product_model->getColumnPackageContent()],
        ["column" => $product_model->getColumnImgForAds()],
        ["column" => $product_model->getColumnImg()],
        ["column" => $variant_model->getColumnId(), "alias" => "variant_id", "table" => $variant_model->getTableName()],
        ["column" => $variant_model->getColumnType(), "table" => $variant_model->getTableName()],
        ["column" => $variant_model->getColumnName(), "alias" => "variant_name", "table" => $variant_model->getTableName()],
        ["column" => $variant_model->getColumnUnitPrice(), "table" => $variant_model->getTableName()],
        ["column" => $variant_model->getColumnImg(), "alias" => "variant_img", "table" => $variant_model->getTableName()],
        ["column" => $variant_model->getColumnImgForAds(), "alias" => "variant_img_for_ads", "table" => $variant_model->getTableName()],
        ["column" => $inventory_model->getColumnStockQuantity(), "alias" => "stock_quantity", "table" => $inventory_model->getTableName()]
    ],
    joins: [
        [
            'type' => 'LEFT JOIN',
            'table' => $variant_model->getTableName(),
            'on' => "{$product_model->getTableName()}.{$product_model->getColumnId()} = {$variant_model->getTableName()}.{$variant_model->getColumnProductId()}"
        ],
        [
            'type' => 'LEFT JOIN',
            'table' => $inventory_model->getTableName(),
            'on' => "{$inventory_model->getTableName()}.{$inventory_model->getColumnVariantId()} = {$variant_model->getTableName()}.{$variant_model->getColumnId()}"
        ]
    ],
    conditions: [
        [
            'attribute' => "{$product_model->getTableName()}.{$product_model->getColumnId()}",
            'operator' => "=",
            'value' => $_GET["id"]
        ]
    ]
));

// Initialize arrays to store product and variants
$product = [];
$variants = [];

// Iterate over each record and organize the data
foreach ($fetched_data['records'] as $record) {
    // Store common product data in the $product array (only once)
    $product = [
        "id" => $record['id'],
        "name" => $record['name'],
        "description" => $record['description'],
        "dimension" => json_decode($record['dimension']),
        "feature" => json_decode($record['feature'], true), // Decode feature string to array
        "important_feature" => json_decode($record['important_feature'], true), // Decode important_feature to array
        "requirement" => json_decode($record['requirement'], true),
        "package_content" => json_decode($record['package_content'], true),
        "img_for_ads" => json_decode($record['img_for_ads'], true),
        "img" => $record['img']
    ];

    // Add variant-specific data to the $variants array
    $variants[] = [
        "id" => $record['variant_id'],
        "type" => $record['type'],
        "name" => $record['variant_name'],
        "unit_price" => $record['unit_price'],
        "img" => $record['variant_img'],
        "img_for_ads" => json_decode($record['variant_img_for_ads']),
        "stock_quantity" => $record["stock_quantity"] ?? 0
    ];
}

if (!empty($product)) {
    $browsing_history_service->addViewedItem($product["id"]);
}


require("./views/ecommerce/product_details.view.php");

?>