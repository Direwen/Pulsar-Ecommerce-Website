<?php

header('Content-Type: application/json');

// Read the JSON data from the body of the request
$data = json_decode(file_get_contents("php://input"), true);

// Fetch the discount record based on the provided code
$result = ErrorHandler::handle(fn() => $discount_model->get(
    conditions: [
        $discount_model->getColumnCode() => strtoupper(trim($data["code"]))
    ]
));

// Check if the result is valid
if ($result === true || !$discount_model->validateDiscount($result)) {
    // No record found or invalid discount
    echo json_encode(["valid" => false, "message" => "Invalid discount code."]);
} else {
    // Discount applied successfully
    echo json_encode([
        "valid" => true,
        "data" => $result,
        "message" => "Discount applied successfully!"
    ]);
}
exit();