<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Validate form data
if (!$category_model->validateFormData($_POST, $_FILES ?? [])) {
    setMessage("Invalid form data.", "error");
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
    setMessage("Failed to prepare the image.", "error");
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Create category and move file within a transaction
$isCreated = $error_handler->handleDbOperation(function () use ($category_model, $result) {
    // Create the category in the database
    $categoryCreated = $category_model->create([
        $category_model::getColumnName() => $_POST["name"],
        $category_model::getColumnSoftware() => $_POST["software"] ?? null,
        $category_model::getColumnFirmware() => $_POST["firmware"] ?? null,
        $category_model::getColumnManual() => $_POST["manual"] ?? null,
        $category_model::getColumnImg() => $result["name"],
    ]);

    // Throw exception if database creation fails
    if (!$categoryCreated) {
        throw new Exception("Failed to create a new category.");
    }

    // Attempt to move the file and throw an exception if it fails
    if (!move_uploaded_file($result["temp_name"], $result["destination"])) {
        throw new Exception("Failed to save the new category image.");
    }

    // If file move fails, return false to trigger rollback
    return true;
});

if (!$isCreated) {
    setMessage("Failed to create a new category or save image.", "error");
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Success message
setMessage("Created a new category", "success");
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
