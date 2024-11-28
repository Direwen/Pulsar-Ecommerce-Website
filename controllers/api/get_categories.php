<?php

header('Content-Type: application/json');

$categories = ErrorHandler::handle(fn() => $category_model->getAll());;

// Output all categories as JSON
echo json_encode(['categories' => $categories]);
