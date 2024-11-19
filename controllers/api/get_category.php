<?php

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

// Fetch the first page of results
$result = ErrorHandler::handle(fn() => $product_model->get(
    select: [
        ["column" => $category_model->getColumnName(), "alias" => "category_name", "table" => $category_model->getTableName()],
        ["column" => $category_model->getColumnSoftware(), "alias" => "category_software", "table" => $category_model->getTableName()],
        ["column" => $category_model->getColumnFirmware(), "alias" => "category_firmware", "table" => $category_model->getTableName()],
        ["column" => $category_model->getColumnManual(), "alias" => "category_manual", "table" => $category_model->getTableName()],
        ["column" => $category_model->getColumnBannerImg(), "alias" => "category_banner_img", "table" => $category_model->getTableName()],
    ],
    join: [
        [
            "type" => "INNER JOIN",
            "table" => $category_model->getTableName(),
            "on" => "products.category_id = categories.id"
        ]
    ],
    conditions: [
        $product_model->getTableName() . '.' . $product_model->getColumnId() => $data["id"]
    ]
));

// Output all categories as JSON
echo json_encode(['record' => is_array($result) ? $result : []]);
exit();