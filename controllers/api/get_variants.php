<?php

header('Content-Type: application/json');

$variants = [];
$page = 1;
$result = ErrorHandler::handle(fn () => $variant_model->getAll(
    page : $page,
    select: [
        ["column" => $variant_model->getColumnId()],
        ["column" => $variant_model->getColumnName()],
        ["column" => $product_model->getColumnName(), "alias" => "product_name", "table" => $product_model->getTableName()]
    ],
    joins: [
        [
            'type' => 'INNER JOIN',
            'table' => $product_model->getTableName(),
            'on' => "variants.product_id = products.id",
        ]
    ]
));
$hasMore = $result["hasMore"];
$variants = $result["records"] ?? [];
while ($hasMore) {
    $result = ErrorHandler::handle(fn () => $variant_model->getAll(page : ++$page));
    $hasMore = $result["hasMore"];
    $variants = array_merge($variants, $result["records"] ?? []);
}

echo json_encode(['variants' => $variants]);