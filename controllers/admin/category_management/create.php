<?php

global $category_model, $root_directory;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Validate form data
if (!$category_model->validateFormData($_POST, $_FILES ?? [])) {
    setMessage("Invalid form data.", "error"); // Set error message
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Handle image preparation
$result = ErrorHandler::handle(fn() => ImageHandler::prepareImageForStorage(
    $_FILES["img"]["tmp_name"], 
    $_FILES["img"]["name"], 
    "./assets/categories"
));
if (!$result) {
    setMessage("Failed to prepare the image.", "error"); // Set error message
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Create category
$isCreated = ErrorHandler::handle(fn() => $category_model->create([
    $category_model::getColumnName() => $_POST["name"],
    $category_model::getColumnSoftware() => $_POST["software"] ?? null,
    $category_model::getColumnFirmware() => $_POST["firmware"] ?? null,
    $category_model::getColumnManual() => $_POST["manual"] ?? null,
    $category_model::getColumnImg() => $result["name"],
]));

if (!$isCreated) {
    setMessage("Failed to create a new category.", "error"); // Set error message
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Move uploaded file
$message = move_uploaded_file($result["temp_name"], $result["destination"]) ?
    "Created a new category" :
    "Failed to save a new category image";

setMessage($message, "success"); // Set success or error message

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
