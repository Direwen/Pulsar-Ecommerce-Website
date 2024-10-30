<?php

global $category_model;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    $imgFilePath = "./assets/categories/" . $category["img"];

    // Attempt to delete the record from the database
    $deleteResult = ErrorHandler::handle(fn() => $category_model->delete([
        $category_model->getColumnId() => $id
    ]));

    // Set session message based on deletion and file status
    if (!$deleteResult) {
        setMessage("Failed to delete the record", "error"); // Set error message
    } elseif (file_exists($imgFilePath)) {
        setMessage(unlink($imgFilePath) ? "Record and image deleted successfully" : "Record deleted, but failed to delete image", "success"); // Set success or error message
    } else {
        setMessage("Record deleted, image not found", "info"); // Set info message
    }
}

// Redirect to the previous page
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
