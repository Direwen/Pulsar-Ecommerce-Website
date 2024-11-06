<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

$id = $_POST["id"] ?? null;

// Validate form data, including the image and banner image
if (!$category_model->validateFormData($_POST, $_FILES ?? [], false)) {
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

$newBannerImageName = $_FILES["banner_img"]["name"] ?? null;
$oldBannerImageName = $category["banner_img"] ?? null;
$bannerImgChanged = ($newBannerImageName && $newBannerImageName !== $oldBannerImageName);

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
    $bannerImgChanged,
    $newImageName,
    $oldImageName,
    $newBannerImageName,
    $oldBannerImageName,
    $name,
    $software,
    $firmware,
    $manual
) {
    // Prepare new images if they have changed
    if ($imgChanged) {
        $img_details = ImageHandler::prepareImageForStorage(
            $_FILES["img"]["tmp_name"],
            $newImageName,
            "./assets/categories"
        );

        if (!$img_details) {
            throw new Exception("Image preparation failed.");
        }
        $uniqueFileName = $img_details['name'];
        $pathToSubmit = $img_details['destination'];
        $tempName = $img_details['temp_name'];
    } else {
        $uniqueFileName = $oldImageName;
    }

    if ($bannerImgChanged) {
        $banner_img_details = ImageHandler::prepareImageForStorage(
            $_FILES["banner_img"]["tmp_name"],
            $newBannerImageName,
            "./assets/categories"
        );

        if (!$banner_img_details) {
            throw new Exception("Banner image preparation failed.");
        }
        $uniqueBannerFileName = $banner_img_details['name'];
        $bannerPathToSubmit = $banner_img_details['destination'];
        $bannerTempName = $banner_img_details['temp_name'];
    } else {
        $uniqueBannerFileName = $oldBannerImageName;
    }

    // Prepare data for update
    $updateData = [
        $category_model::getColumnName() => $name,
        $category_model::getColumnSoftware() => $software,
        $category_model::getColumnFirmware() => $firmware,
        $category_model::getColumnManual() => $manual,
        $category_model::getColumnImg() => $uniqueFileName,
        $category_model::getColumnBannerImg() => $uniqueBannerFileName,
    ];

    // Perform the update
    if (!$category_model->update($updateData, [$category_model->getColumnId() => $id])) throw new Exception("Failed to update the category.");

    // Move new image files if changed
    if ($imgChanged) {
        $oldFilePath = "./assets/categories/" . $oldImageName;
        if (file_exists($oldFilePath)) unlink($oldFilePath);
        if (!move_uploaded_file($tempName, $pathToSubmit)) throw new Exception("Failed to save the new category image.");
    }

    if ($bannerImgChanged) {
        $oldBannerFilePath = "./assets/categories/" . $oldBannerImageName;
        if (file_exists($oldBannerFilePath)) unlink($oldBannerFilePath);
        if (!move_uploaded_file($bannerTempName, $bannerPathToSubmit)) throw new Exception("Failed to save the new category banner image.");
    }

    return true;
});

// Set appropriate success or error message
if ($updateResult) {
    setMessage("Category updated successfully.", "success");
} else {
    setMessage("Failed to update the category.", "error");
}

// Redirect to the previous page
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
