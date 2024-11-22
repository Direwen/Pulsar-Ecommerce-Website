<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

if (!$inventory_model->validateFormData($_POST, $_FILES ?? [])) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

$variant = ErrorHandler::handle(fn () => $variant_model->get(
    conditions: [
        [$variant_model->getColumnId() => $_POST["variant_id"] ?? '0']
    ]
));

if (!$variant) {
    setMessage("Provide a valid product variant", "error");
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

$isCreated = ErrorHandler::handle(fn () => $inventory_model->create([
    $inventory_model->getColumnVariantId() => $_POST["variant_id"],
    $inventory_model->getColumnCode() => $_POST["code"],
    $inventory_model->getColumnStockQuantity() => $_POST["stock_quantity"],
]));

if (!$isCreated) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Success message
setMessage("Created a new inventory", "success");
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;

