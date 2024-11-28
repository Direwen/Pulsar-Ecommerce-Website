<?php

header('Content-Type: application/json');

$products = ErrorHandler::handle(fn() => $event_product_model->getEverything(page: $page));


// Output all categories as JSON
echo json_encode(['event_products' => $products]);
