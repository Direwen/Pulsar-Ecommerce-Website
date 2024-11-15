<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

$order = ErrorHandler::handle(fn () => $order_model->get(
    [
        $order_model->getColumnId() => $_POST["id"] ?? '0'
    ]
));

if (!$order) {
    setMessage("Order is not found", "error");
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

$isDeleted = ErrorHandler::handle(fn () => $order_model->delete([
    $order_model->getColumnId() => $_POST["id"]
]));

if (!$isDeleted) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Success message
setMessage("Deleted", "success");
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;