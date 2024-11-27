<?php

header('Content-Type: application/json');

$products = [];
$page = 1;
$hasMore = false;

do {
    // Fetch the current page of results
    $result = ErrorHandler::handle(fn() => $event_product_model->getAll(page: $page));
    $hasMore = $result["hasMore"];

    // Collect the products from the current page
    $products = array_merge($products, $result["records"] ?? []);

    // Increment the page number for the next iteration
    $page++;
} while ($hasMore);


// Output all categories as JSON
echo json_encode(['event_products' => $products]);
