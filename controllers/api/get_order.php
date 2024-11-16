<?php

header('Content-Type: application/json');

// Read the JSON data from the body of the request
$data = json_decode(file_get_contents("php://input"), true);

$order = ErrorHandler::handle(fn () => $order_model->get(
    select: [
        ["column" => $order_model->getColumnId()],
        ["column" => $order_model->getColumnOrderCode()],
        ["column" => $order_model->getColumnStatus()],
        ["column" => $order_model->getColumnTotalPrice()],
        ["column" => $order_model->getColumnFirstName()],
        ["column" => $order_model->getColumnLastName()],
        ["column" => $order_model->getColumnCompany()],
        ["column" => $order_model->getColumnAddress()],
        ["column" => $order_model->getColumnApartment()],
        ["column" => $order_model->getColumnPostalCode()],
        ["column" => $order_model->getColumnCity()],
        ["column" => $order_model->getColumnCountry()],
        ["column" => $order_model->getColumnPhone()],
        ["column" => $order_model->getColumnCreatedAt()],
        ["column" => $order_model->getColumnIsRefunded()],
        ["column" => $order_model->getColumnShippingFee()],
        ["column" => $discount_model->getColumnCode(), "alias" => "discount", "table" => $discount_model->getTableName()],
        ["column" => $discount_model->getColumnAmount(), "alias" => "discount_amount", "table" => $discount_model->getTableName()],
        ["column" => $user_model->getColumnEmail(), "alias" => "email", "table" => $user_model->getTableName()],
    ],
    join: [
        ["type" => "LEFT JOIN", "table" => $user_model->getTableName(), "on" => "orders.user_id = users.id"],
        ["type" => "LEFT JOIN", "table" => $discount_model->getTableName(), "on" => "orders.used_discount_id = discounts.id"]
    ],
    conditions: [
        $order_model->getTableName() . '.' . $order_model->getColumnId() => $data["id"]
    ]
));

$variants = [];
$page=0;
$fetched_overview_variants_data = ErrorHandler::handle(fn () => $order_variant_model->getAll(
    select: [
        ["column" => $order_variant_model->getColumnQuantity()],
        ["column" => $order_variant_model->getColumnPriceAtOrder()],
        ["column" => $variant_model->getColumnType(), "alias" => "type", "table" => $variant_model->getTableName()],
        ["column" => $variant_model->getColumnName(), "alias" => "variant_name", "table" => $variant_model->getTableName()],
        ["column" => $product_model->getColumnName(), "alias" => "product_name", "table" => $product_model->getTableName()],
    ],
    joins: [
        ["type" => "LEFT JOIN", "table" => $variant_model->getTableName(), "on" => "order_variants.variant_id = variants.id"],
        ["type" => "LEFT JOIN", "table" => $product_model->getTableName(), "on" => "variants.product_id = products.id"],
    ],
    conditions: [
        [
            'attribute' => 'order_variants.order_id',
            'operator' => "=",
            'value' => $data["id"]
        ]
    ]
));
$variants = array_merge($variants, $fetched_overview_variants_data["records"]);
while ($fetched_overview_variants_data["hasMore"]) {

    $fetched_overview_variants_data = ErrorHandler::handle(fn() => $order_variant_model->getAll(
        select: [
            ["column" => $order_variant_model->getColumnQuantity()],
            ["column" => $order_variant_model->getColumnPriceAtOrder()],
            ["column" => $variant_model->getColumnType(), "alias" => "type", "table" => $variant_model->getTableName()],
            ["column" => $variant_model->getColumnName(), "alias" => "variant_name", "table" => $variant_model->getTableName()],
            ["column" => $product_model->getColumnName(), "alias" => "product_name", "table" => $product_model->getTableName()],
        ],
        joins: [
            ["type" => "LEFT JOIN", "table" => $variant_model->getTableName(), "on" => "order_variants.variant_id = variants.id"],
            ["type" => "LEFT JOIN", "table" => $product_model->getTableName(), "on" => "variants.product_id = products.id"],
        ],
        conditions: [
            [
                'attribute' => 'order_variants.order_id',
                'operator' => "=",
                'value' => $data["id"]
            ]
        ]
    ));
    $variants = array_merge($variants, $fetched_overview_variants_data["records"]);
}

echo json_encode(["order" => $order, "variants" => $variants]);
exit();