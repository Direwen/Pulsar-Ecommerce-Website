<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

$id = $_POST["id"];

// Retrieve the category record and handle errors
$category = ErrorHandler::handle(fn() => $category_model->get([
    $category_model->getColumnId() => $id
]));

// Check if the record was found, if not, exit early
if (!$category) {
    setMessage("Record not found to delete", "error"); // Set error message
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

$img_path = "./assets/categories/" . $category["img"];
$banner_img_path = "./assets/categories/" . $category["banner_img"];

// Attempt to delete the record from the database
$deleteResult = ErrorHandler::handle(fn() => $category_model->delete([
    $category_model->getColumnId() => $id
]));

// Set session message based on deletion and file status
if (!$deleteResult) {
    setMessage("Failed to delete the record", "error"); // Set error message
} elseif (file_exists($img_path) & file_exists($banner_img_path)) {
    unlink($img_path);
    unlink($banner_img_path);
    setMessage("Record and image deleted successfully", "success"); // Set success or error message
} else {
    setMessage("Record deleted, image not found", "info"); // Set info message
}

// Redirect to the previous page
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
