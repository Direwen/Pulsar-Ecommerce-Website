<?php

header('Content-Type: application/json');

$products = ErrorHandler::handle(fn() => $product_model->getEverything());;

// Output all categories as JSON
echo json_encode(['products' => $products]);
