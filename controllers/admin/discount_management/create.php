<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

if (!$discount_model->validateFormData($_POST)) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

$is_created = ErrorHandler::handle(fn () => $discount_model->create([
    $discount_model->getColumnCode() => $_POST["code"],
    $discount_model->getColumnAmount() => $_POST["amount"],
    $discount_model->getColumnMaxUsage() => $_POST["max_usage"],
    $discount_model->getColumnExpiredAt() => $_POST["expired_at"],
]));

if ($is_created) {
    setMessage("Created", "success");
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;