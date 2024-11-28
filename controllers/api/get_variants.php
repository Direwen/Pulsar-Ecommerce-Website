<?php

header('Content-Type: application/json');

$variants = ErrorHandler::handle(fn () => $variant_model->getEverything(
    select: [
        ["column" => $variant_model->getColumnId()],
        ["column" => $variant_model->getColumnName()],
        ["column" => $product_model->getColumnName(), "alias" => "product_name", "table" => $product_model->getTableName()],
        ["column" => $inventory_model->getColumnCode(), "alias" => "inventory_code", "table" => $inventory_model->getTableName()]
    ],
    joins: [
        [
            'type' => 'INNER JOIN',
            'table' => $product_model->getTableName(),
            'on' => "variants.product_id = products.id",
        ],
        [
            'type' => 'LEFT JOIN',
            'table' => $inventory_model->getTableName(),
            'on' => "inventories.variant_id = variants.id",
        ],

    ],
    conditions: [
        [
            'attribute' => $inventory_model->getTableName() . '.' . $inventory_model->getColumnId(),
            'operator' => "IS",
            'value' => null
        ]
    ]
));

echo json_encode(['variants' => $variants]);
exit();