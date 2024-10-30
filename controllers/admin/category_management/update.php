<?php

global $category_model;

echo "<pre>";
var_dump($_POST);
var_dump($_FILES);
echo "</pre>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST["id"] ?? null; // Ensure $id is set to null if not present

    // Validate form data, ensuring empty values are set to null
    if (!$category_model->validateFormData($_POST, $_FILES["img"] ?? [], false)) {
        setMessage("Invalid form data.", "error"); // Use setMessage function
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    // Retrieve the category record and handle errors
    $category = ErrorHandler::handle(fn() => $category_model->get([$category_model->getColumnId() => $id]));

    // Check if the record was found, if not, exit early
    if (!$category) {
        setMessage("Record not found to update", "error"); // Use setMessage function
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    $newImageName = $_FILES["img"]["name"] ?? null;
    $oldImageName = $category["img"] ?? null; // Set to null if not present

    // Check if new image name is provided
    $imgChanged = ($newImageName && $newImageName !== $oldImageName);

    // Prepare image for storage if it has changed
    if ($imgChanged) {
        $result = ErrorHandler::handle(fn() => ImageHandler::prepareImageForStorage(
            $_FILES["img"]["tmp_name"],
            $newImageName,
            "./assets/categories"
        ));

        if (!$result) {
            setMessage("Image preparation failed.", "error"); // Use setMessage function
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }

    // Assign unique file name or retain old image name if no new image is uploaded
    [$uniqueFileName, $pathToSubmit] = $result ?? [$oldImageName, null];

    // Prepare data for update, ensuring empty attributes are set to null
    $updateData = [
        $category_model::getColumnName() => $_POST["name"] ?: null,
        $category_model::getColumnSoftware() => $_POST["software"] ?: null,
        $category_model::getColumnFirmware() => $_POST["firmware"] ?: null,
        $category_model::getColumnManual() => $_POST["manual"] ?: null,
        $category_model::getColumnImg() => $imgChanged ? $uniqueFileName : $oldImageName,
    ];

    // Update category record
    $updateResult = ErrorHandler::handle(fn() => $category_model->update(
        $updateData,
        [$category_model->getColumnId() => $id]
    ));

    // Handle update result
    if ($updateResult) {
        if ($imgChanged) {
            // Delete old image if it exists
            $oldFilePath = "./assets/categories/" . $oldImageName;
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
            move_uploaded_file($_FILES["img"]["tmp_name"], $pathToSubmit);
        }
        setMessage("Category updated successfully.", "success"); // Use setMessage function
    } else {
        setMessage("Failed to update the category.", "error"); // Use setMessage function
    }
}

// Redirect to the previous page
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
