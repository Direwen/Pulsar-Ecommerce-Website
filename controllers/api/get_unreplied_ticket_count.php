<?php

header('Content-Type: application/json');

$records = ErrorHandler::handle(fn () => $support_model->getEverything(
    conditions: [
        [
            'attribute' => $support_model->getColumnStatus(),
            'operator' => "=",
            'value' => "open"
        ]
    ]
));

echo json_encode(["count" => count($records)]);