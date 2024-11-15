<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

if (!$discount_model->validateFormData($_POST)) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

$discount = ErrorHandler::handle(fn() => $discount_model->get(
    [
        $discount_model->getColumnId() => $_POST["id"] ?? '0'
    ]
));

if (!$discount) {
    setMessage("Discount is not found", "error");
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

$isUpdated = ErrorHandler::handle(fn() => $discount_model->update(
    [
        $discount_model->getColumnCode() => $_POST["code"],
        $discount_model->getColumnAmount() => $_POST["amount"],
        $discount_model->getColumnMaxUsage() => $_POST["max_usage"],
        $discount_model->getColumnExpiredAt() => $_POST["expired_at"],
    ],
    [
        $inventory_model->getColumnId() => $_POST["id"],
    ]
));

if (!$isUpdated) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Success message
setMessage("Updated", "success");
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;