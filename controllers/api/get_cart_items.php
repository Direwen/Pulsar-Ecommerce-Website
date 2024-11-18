<?php

header('Content-Type: application/json');

if (!isset($_COOKIE["CART"])) {
    echo json_encode([]);
    exit();
}

// Decode the JSON string from the CART cookie to get cart items (variant_id => quantity)
$cart = json_decode($_COOKIE["CART"]);

// Initialize an empty array to store selected variant details
$selected_variants = [];

foreach ($cart as $variant_id => $quantity) {
    // Retrieve the variant data along with the associated product name
    $variant = ErrorHandler::handle(fn() => $variant_model->get(
        conditions: [
            $variant_model->getTableName() . '.' . $variant_model->getColumnId() => $variant_id
        ],
        select: [
            ["column" => $variant_model->getColumnId()],
            ["column" => $variant_model->getColumnType()],
            ["column" => $variant_model->getColumnName()],
            ["column" => $variant_model->getColumnUnitPrice()],
            ["column" => $variant_model->getColumnImg()],
            ["column" => $product_model->getColumnName(), "alias" => "product_name", "table" => $product_model->getTableName()]
        ],
        join: [
            ["type" => "INNER JOIN", "table" => $product_model->getTableName(), "on" => "variants.product_id = products.id"]
        ]
    ));


    // If variant data is found, add it to the selected variants array with quantity
    if ($variant && is_array(($variant))) {
        $selected_variants[] = ["quantity" => $quantity, ...$variant];
    }
}

echo json_encode($selected_variants);