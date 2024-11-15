<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

$discount = ErrorHandler::handle(fn () => $discount_model->get(
    [
        $discount_model->getColumnId() => $_POST["id"] ?? '0'
    ]
));

if (!$discount) {
    setMessage("Discount is not found", "error");
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

$isDeleted = ErrorHandler::handle(fn () => $discount_model->delete([
    $discount_model->getColumnId() => $_POST["id"]
]));

if (!$isDeleted) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Success message
setMessage("Deleted", "success");
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;