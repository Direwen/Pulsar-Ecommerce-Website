<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    global $category_model;

    $result = ErrorHandler::handle(fn () => ImageHandler::prepareImageForStorage($_FILES["image"]["tmp_name"], $_FILES["image"]["name"], "./assets/"));
    $unique_file_name = $result[0];
    $path_to_submit = $result[1];

    $result = $category_model->create([
        $category_model->getColumnName() => $_POST["name"],
        $category_model->getColumnSoftware() => $_POST["software"],
        $category_model->getColumnFirmware() => $_POST["firmware"],
        $category_model->getColumnManual() => $_POST["manual"],
        $category_model->getColumnImg() => $unique_file_name,
    ]);

    if ($result) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $path_to_submit)) $_SESSION["message"] = "Created a new category";
        else $_SESSION["message"] = "Failed to save a new category image";
        
    }
    else $_SESSION["message"] = "Failed to create a new category";
}

header("Location: " . $_SERVER['HTTP_REFERER']);