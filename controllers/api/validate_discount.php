<?php

header('Content-Type: application/json');

// Read the JSON data from the body of the request
$data = json_decode(file_get_contents("php://input"), true);

$result = ErrorHandler::handle(fn () => $discount_model->get(
    conditions: [
        $discount_model->getColumnCode() => strtoupper(trim($data["code"]))
    ]
));

// Check if the result is true (no record found) or contains a record
if ($result === true) {
    // No record found
    echo json_encode(["valid" => false, "message" => "Invalid discount code."]);
} else {
    // Record found
    echo json_encode([
        "valid" => true,
        "data" => $result,
        "message" => "Discount applied successfully!"
    ]);
}