<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Read the JSON data from the body of the request
$data = json_decode(file_get_contents("php://input"), true);

// Check if required fields are present
if (!isset($data["id"]) || !isset($data["quantity"])) {
    echo json_encode(["error" => "ID and quantity are required."]);
    exit;
}

// Fetch the variant based on the provided ID
$variant = ErrorHandler::handle(fn () => $variant_model->get([
    $variant_model->getColumnId() => $data["id"]
]));

if (!$variant) {
    // Send error response if variant not found
    echo json_encode(["error" => "Variant not found."]);
    exit;
}

// Get the quantity from the request
$quantity = (int)$data["quantity"];

// Get the current cart from the cookie, if it exists
$cart = isset($_COOKIE["CART"]) ? json_decode($_COOKIE["CART"], true) : [];

// If quantity is zero, remove the item from the cart if it exists
if ($quantity <= 0) {
    if (isset($cart[$data["id"]])) {
        unset($cart[$data["id"]]);
    }
} else {
    // Otherwise, update the quantity or add the item if it doesn’t exist
    $cart[$data["id"]] = $quantity;
}

// Update the cart cookie with the modified cart data
setcookie("CART", json_encode($cart), time() + (30 * 24 * 60 * 60), "/");

// Send success response
echo json_encode(["success" => "Cart updated successfully.", "cart" => $cart]);
