<?php

header('Content-Type: application/json');

$products = [];
$page = 1;

// Fetch the first page of results
$result = ErrorHandler::handle(fn() => $product_model->getAll(page: $page));
$hasMore = $result["hasMore"];

// Collect the categories from the first page
$products = array_merge($products, $result["records"] ?? []);

// Continue fetching while there are more pages
while ($hasMore) {
    $result = ErrorHandler::handle(fn() => $product_model->getAll(++$page));
    $hasMore = $result["hasMore"];
    $products = array_merge($products, $result["records"] ?? []);
}

// Output all categories as JSON
echo json_encode(['products' => $products]);
