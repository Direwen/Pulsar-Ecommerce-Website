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

if (!$order_model->validateOrderStatus($order, $_POST["status"] ?? '')) {
    setMessage("Invalid to Update", "error");
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

$isUpdated = ErrorHandler::handle(fn() => $order_model->update(
    [
        $order_model->getColumnStatus() => $_POST["status"],
        $order_model->getColumnIsRefunded() => ($_POST["status"] == 'cancelled') ? true : false
    ],
    [
        $order_model->getColumnId() => $_POST["id"],
    ]
));

if (!$isUpdated) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Success message
setMessage("Updated the order", "success");
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;