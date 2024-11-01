<?php

header('Content-Type: application/json');

$categories = [];
$page = 1;

// Fetch the first page of results
$result = ErrorHandler::handle(fn() => $category_model->getAll(page: $page));
$hasMore = $result["hasMore"];

// Collect the categories from the first page
$categories = array_merge($categories, $result["records"] ?? []);

// Continue fetching while there are more pages
while ($hasMore) {
    $result = ErrorHandler::handle(fn() => $category_model->getAll(++$page));
    $hasMore = $result["hasMore"];
    $categories = array_merge($categories, $result["categories"] ?? []);
}

// Output all categories as JSON
echo json_encode(['categories' => $categories]);
