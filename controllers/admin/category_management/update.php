<?php

echo "<pre>";
var_dump($_POST);
var_dump($_FILES);
echo "</pre>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST["id"] ?? null;

    // Validate form data
    if (!$category_model->validateFormData($_POST, $_FILES["img"] ?? [], false)) {
        setMessage("Invalid form data.", "error");
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    // Retrieve the category record
    $category = ErrorHandler::handle(fn() => $category_model->get([$category_model->getColumnId() => $id]));
    if (!$category) {
        setMessage("Record not found to update", "error");
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    $newImageName = $_FILES["img"]["name"] ?? null;
    $oldImageName = $category["img"] ?? null;
    $imgChanged = ($newImageName && $newImageName !== $oldImageName);

    // Extract values from $_POST to avoid using superglobals directly in the closure
    $name = $_POST["name"] ?? null;
    $software = $_POST["software"] ?? null;
    $firmware = $_POST["firmware"] ?? null;
    $manual = $_POST["manual"] ?? null;

    // Handle transaction for update process
    $updateResult = $error_handler->handleDbOperation(function () use (
        $category_model,
        $id,
        $imgChanged,
        $newImageName,
        $oldImageName,
        $name,
        $software,
        $firmware,
        $manual
    ) {
        // Prepare new image if it has changed
        if ($imgChanged) {
            $result = ImageHandler::prepareImageForStorage(
                $_FILES["img"]["tmp_name"],
                $newImageName,
                "./assets/categories"
            );

            if (!$result) {
                throw new Exception("Image preparation failed.");
            }
            [$uniqueFileName, $pathToSubmit] = $result;
        } else {
            $uniqueFileName = $oldImageName;
        }

        // Prepare data for update
        $updateData = [
            $category_model::getColumnName() => $name,
            $category_model::getColumnSoftware() => $software,
            $category_model::getColumnFirmware() => $firmware,
            $category_model::getColumnManual() => $manual,
            $category_model::getColumnImg() => $uniqueFileName,
        ];

        // Perform the update
        if (!$category_model->update($updateData, [$category_model->getColumnId() => $id])) {
            throw new Exception("Failed to update the category.");
        }

        // Move new image file if changed
        if ($imgChanged) {
            $oldFilePath = "./assets/categories/" . $oldImageName;
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
            if (!move_uploaded_file($_FILES["img"]["tmp_name"], $pathToSubmit)) {
                throw new Exception("Failed to save the new category image.");
            }
        }

        return true;
    });

    // Set appropriate success or error message
    if ($updateResult) {
        setMessage("Category updated successfully.", "success");
    } else {
        setMessage("Failed to update the category.", "error");
    }
}

// Redirect to the previous page
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
