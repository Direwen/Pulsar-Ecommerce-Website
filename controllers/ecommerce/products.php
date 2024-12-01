<?php

$products = [];
$category = null;

//fetch category flexibly
$search_input = isset($_GET["search"]) ? $_GET["search"] : null;
$category_input = isset($_GET["category"]) ? $_GET["category"] : null;
$sort_input = isset($_GET["sort"]) ? $_GET["sort"] : null;

if (!empty($search_input)) {
    $category = ErrorHandler::handle(fn() => $category_model->get([
        $category_model->getColumnName() => strtolower($search_input)
    ]));
} else {
    $category = ErrorHandler::handle(fn() => $category_model->get([
        $category_model->getColumnId() => $category_input ?? $category_model->getAll()["records"][0]["id"]
    ]));
}

//fetch events
// $events = ErrorHandler::handle(fn () => $event_model->getEverything(
//     select: [
//         ["column" => $event_model->getColumnName()],
//         ["column" => $event_model->getColumnDiscount()]
//     ],
//     aggregates: [
//         [
//             "column" => $event_product_model->getColumnProductId(), 
//             "function" => "GROUP_CONCAT", 
//             "alias" => "event_products", 
//             "table" => $event_product_model->getTableName()
//         ],

//     ],
//     groupBy: $event_product_model->getTableName() . "." . $event_product_model->getColumnEventId(),
//     joins: [
//         [
//             "type" => "INNER JOIN",
//             "table" => $event_product_model->getTableName(),
//             "on" => "event_products.event_id=events.id"
//         ],
//         [
//             "type" => "INNER JOIN",
//             "table" => $product_model->getTableName(),
//             "on" => "event_products.product_id=products.id"
//         ],
//         [
//             "type" => "INNER JOIN",
//             "table" => $category_model->getTableName(),
//             "on" => "products.category_id=categories.id"
//         ],
//     ],
//     conditions: [
//         [
//             'attribute' => $event_model->getColumnEndAt(),
//             'operator' => ">",
//             'value' => $event_model->getTimestampString(time())
//         ],
//         [
//             'attribute' => $category_model->getTableName() . '.' . $category_model->getColumnId(),
//             'operator' => "=",
//             'value' => $category["id"]
//         ],
//     ]
// ));

// foreach ($events as &$event) $event["event_products"] = explode(",", $event["event_products"]); 

//fetch products
if (is_array($category)) {

    $order_by = null;
    $sort_dir = 'ASC';

    switch ($sort_input) {
        case "lowest":
            $order_by = "min_price";
            $sort_dir = "ASC";
            break;
        case "highest":
            $order_by = "min_price";
            $sort_dir = "DESC";
            break;
        case "new":
            $order_by = $product_model->getTableName() . '.' . $product_model->getColumnCreatedAt();
            $sort_dir = "DESC";
            break;
        case "old":
            $order_by = $product_model->getTableName() . '.' . $product_model->getColumnCreatedAt();
            $sort_dir = "ASC";
            break;
    }

    $products = ErrorHandler::handle(fn() => $product_model->getEverything(
        select: [
            ["column" => $product_model->getColumnId()],
            ["column" => $product_model->getColumnName()],
            ["column" => $product_model->getColumnImg()],
            ["column" => $product_model->getColumnViews()],
        ],
        aggregates: [
            ["column" => $variant_model->getColumnUnitPrice(), "function" => "MIN", "alias" => "min_price", "table" => $variant_model->getTableName()],
            ["column" => $variant_model->getColumnImg(), "function" => "GROUP_CONCAT", "alias" => "variants", "table" => $variant_model->getTableName()],
            ["column" => $inventory_model->getColumnVariantId(), "function" => "GROUP_CONCAT", "alias" => "available_variant_ids", "table" => $inventory_model->getTableName()],
            ["column" => $inventory_model->getColumnStockQuantity(), "function" => "GROUP_CONCAT", "alias" => "available_variant_qty", "table" => $inventory_model->getTableName()]
        ],
        joins: [
            [
                'type' => "INNER JOIN",
                'table' => $variant_model->getTableName(),
                'on' => "variants.product_id = products.id"
            ],
            [
                'type' => "LEFT JOIN",
                'table' => $inventory_model->getTableName(),
                'on' => "inventories.variant_id = variants.id"
            ]
        ],
        groupBy: $product_model->getTableName() . "." . $product_model->getColumnId(),
        conditions: [
            [
                'attribute' => $product_model->getTableName() . '.' . $product_model->getColumnCategoryId(),
                'operator' => "=",
                'value' => $category["id"]
            ]
        ],
        sortField: $order_by,
        sortDirection: $sort_dir
    ));

    if (!empty($products)) {
        $views_array = [];
        foreach ($products as $product)
            $views_array[] = $product['views']; // Store the views in the array
        $max_views = max($views_array);
        $most_popular_product_place_taken = false;

        echo "<pre>";
        var_dump($products);
        echo "</pre>";
    }


} else {
    $category = null;
}


require("./views/ecommerce/products.view.php");

?>