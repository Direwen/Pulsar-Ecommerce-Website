<?php

// Redirect if the request method is not POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Determine if the product should be removed based on variant count
$removeProduct = ErrorHandler::handle(fn() => $variant_model->getTotalCount(
    conditions: [
        [
            "attribute" => $variant_model->getColumnProductId(),
            "operator" => "=",
            "value" => $_POST["product_id"]
        ]
    ]
)) > 1 ? false : true;

// Begin transaction for variant and possibly product deletion
$variantDeletion = $error_handler->handleDbOperation(function () use ($variant_model, $product_model, $removeProduct) {

    // Retrieve the variant to be deleted
    $variant = $variant_model->get(
        conditions: [
            $variant_model->getColumnId() => $_POST["id"]
        ]
    );

    // Exit if the variant doesn't exist
    if (!$variant) {
        setMessage("Record not found to delete", "error");
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    // Delete the specified variant
    $variant_model->delete(
        conditions: [
            $variant_model->getColumnId() => $_POST["id"]
        ]
    );

    // Remove the variant image from the filesystem if it exists
    $variant_img = json_decode($variant["img"]);
    foreach ($variant_img as $each) {
        $img_file_path = './assets/products/' . $each;
        if (file_exists($img_file_path)) unlink($img_file_path);
    }
    
    // If this is the last variant, delete the associated product
    if ($removeProduct) {
        // Retrieve the product details
        $product = $product_model->get(
            conditions: [
                $product_model->getColumnId() => $_POST["product_id"]
            ]
        );

        // Delete the product record
        $product_model->delete(
            conditions: [
                $product_model->getColumnId() => $_POST["product_id"]
            ]
        );

        // Prepare the list of images to delete (main and additional images)
        $product_img_list = ['./assets/products/' . $product["img"]];
        foreach (json_decode($product[$product_model->getColumnImgForAds()]) as $img) {
            $product_img_list[] = './assets/products/' . $img;
        }

        // Delete each product image from the filesystem
        foreach ($product_img_list as $img) {
            if (file_exists($img)) {
                unlink($img);
            }
        }
    }

    return true;
});

// Set success or error message based on the result of the deletion process
if ($variantDeletion) {
    setMessage("Successfully removed the variant and related product if needed", "success");
} else {
    setMessage("Failed to remove variant/product", "error");
}

// Redirect back to the referring page
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
