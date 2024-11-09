<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

$inventory = ErrorHandler::handle(fn () => $inventory_model->get(
    [
        $inventory_model->getColumnId() => $_POST["id"] ?? '0'
    ]
));

if (!$inventory) {
    setMessage("Provide a valid inventory", "error");
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

$isDeleted = ErrorHandler::handle(fn () => $inventory_model->delete([
    $inventory_model->getColumnId() => $_POST["id"]
]));

if (!$isDeleted) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Success message
setMessage("Deleted", "success");
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;