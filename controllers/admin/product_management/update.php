<?php

// Redirect if the request method is not POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}


// Handle the database operation
$update_result = $error_handler->handleDbOperation(function () use ($variant_model, $product_model) {

    // Collect variant and product IDs from POST
    $variant_id = $_POST["id"];
    $product_id = $_POST["product-id"];

    // Track uploaded images
    $imgUploaded = [];
    foreach ($_FILES as $key => $each) {
        if (!(is_array($each["error"]) && $each["error"][0] == 4 || $each["error"] == 4 || $each["error"][0][0] == 4)) {
            $imgUploaded[] = $key;
        }
    }

    // Prepare product data, excluding images
    $product_data = [
        $product_model->getColumnName() => $_POST["product-name"],
        $product_model->getColumnCategoryId() => $_POST["category"],
        $product_model->getColumnDescription() => $_POST["description"],
        $product_model->getColumnDimension() => $_POST["dimension"],
        $product_model->getColumnFeature() => array_unique(array_map('trim', explode(',', $_POST["feature"]))),
        $product_model->getColumnImportantFeature() => array_combine(
            $_POST['special-feature-title'] ?? [],
            array_map(fn($details) => array_unique(explode(', ', $details)), $_POST['special-feature-details'] ?? [])
        ),
        $product_model->getColumnRequirement() => array_unique(array_map('trim', explode(',', $_POST["requirement"]))),
        $product_model->getColumnPackageContent() => array_unique(array_map('trim', explode(',', $_POST["package-content"])))
    ];

    // Validate product data and images
    if (!$product_model->validateFormData($product_data, $_FILES, false)) {
        throw new Exception("Product data validation failed.");
    }

    // Initialize finalized data with validated product data
    $finalized_data = $product_data;

    // Handle main product image if uploaded
    if (in_array($product_model->getColumnImg(), $imgUploaded)) {
        $details_for_main_img = ImageHandler::prepareImageForStorage(
            temp_name: $_FILES[$product_model->getColumnImg()]["tmp_name"],
            file_name: $_FILES[$product_model->getColumnImg()]["name"],
            dir_to_save: "./assets/products"
        );
        if (!$details_for_main_img) throw new Exception("Failed to prepare main image");
        $finalized_data[$product_model->getColumnImg()] = $details_for_main_img["name"];
    }

    // Handle advertisement images if uploaded
    $details_for_ads_img = [];
    if (in_array($product_model->getColumnImgForAds(), $imgUploaded)) {
        foreach ($_FILES[$product_model->getColumnImgForAds()]["name"] as $i => $name) {
            $temp = ImageHandler::prepareImageForStorage(
                temp_name: $_FILES[$product_model->getColumnImgForAds()]["tmp_name"][$i],
                file_name: $name,
                dir_to_save: "./assets/products"
            );
            if (!$temp) throw new Exception("Failed to prepare ad images");
            $details_for_ads_img[] = $temp;
        }
        $finalized_data[$product_model->getColumnImgForAds()] = array_column($details_for_ads_img, "name");
    }

    // Update product in database
    if (!$product_model->update($finalized_data, [$product_model->getColumnId() => $product_id])) {
        throw new Exception("Failed to update product in database");
    }

    // Process variants
    foreach ($_POST["variants"] as $variant_data) {
        $variant_data["product_id"] = $product_id;
        if (!$variant_model->validateFormData($variant_data, $_FILES, false)) {
            throw new Exception("Invalid variant data");
        }
    }

    // Handle variant images if uploaded
    $details_for_variant_img = [];
    if (in_array("variants", $imgUploaded)) {

        //Looping through each variant
        for ($i = 0; $i < count($_FILES["variants"]["name"]); $i++) {
            //Looping through images of each variant
            for ($img_index = 0; $img_index < count($_FILES["variants"]["name"][$i]); $img_index++) {
                $temp = ErrorHandler::handle(fn() => ImageHandler::prepareImageForStorage(
                    temp_name: $_FILES["variants"]["tmp_name"][$i][$img_index],
                    file_name: $_FILES["variants"]["name"][$i][$img_index],
                    dir_to_save: "./assets/products"
                ));
                if (!$temp) throw new Exception("Failed to prepare variant image");
                $details_for_variant_img[$i][] = $temp;
            }
        }

    }

    // Cleanup and deletion of images if necessary
    $variant = $variant_model->get([$variant_model->getColumnId() => $variant_id]);
    $product = $product_model->get([$product_model->getColumnId() => $product_id]);

    // Update variants in database
    foreach ($_POST["variants"] as $i => $variant_data) {
        $variant_update_data = [
            $variant_model->getColumnType() => $variant_data["type"],
            $variant_model->getColumnName() => $variant_data["name"],
            $variant_model->getColumnUnitPrice() => $variant_data["unit_price"],
        ];
        if (!empty($details_for_variant_img[$i])) {
            $variant_update_data[$variant_model->getColumnImg()] = array_column($details_for_variant_img[$i], "name");
        }
        if (!$variant_model->update($variant_update_data, [$variant_model->getColumnId() => $variant_id])) {
            throw new Exception("Failed to update variant in database");
        }
    }

    if (in_array($product_model->getColumnImg(), $imgUploaded)) {
        // Only delete and move the main product image if a new one is uploaded
        ErrorHandler::handle(fn() => file_exists('./assets/products/' . $product["img"]) && unlink('./assets/products/' . $product["img"]));
    }
    
    if (in_array($product_model->getColumnImgForAds(), $imgUploaded)) {
        // Only delete and move ad images if new ones are uploaded
        foreach (json_decode($product[$product_model->getColumnImgForAds()]) as $img) {
            ErrorHandler::handle(fn() => file_exists('./assets/products/' . $img) && unlink('./assets/products/' . $img));
        }
    }
    
    // Similarly, check for variants
    if (in_array("variants", $imgUploaded)) {
        $variant_img = json_decode($variant[$variant_model->getColumnImg()]);
        foreach ($variant_img as $each) {
            ErrorHandler::handle(fn() => file_exists('./assets/products/' . $each) && unlink('./assets/products/' . $each));
        }
    }

    // Move images to final destinations
    if (in_array($product_model->getColumnImg(), $imgUploaded) &&
        !move_uploaded_file($details_for_main_img["temp_name"], $details_for_main_img["destination"])) {
        throw new Exception("Failed to move main image to final destination");
    }
    foreach ($details_for_ads_img as $each) {
        if (!move_uploaded_file($each["temp_name"], $each["destination"])) {
            throw new Exception("Failed to move images to final destination");
        }
    }

    foreach ($details_for_variant_img as $variant) {
        foreach($variant as $each) {
            if (!move_uploaded_file($each["temp_name"], $each["destination"])) {
                throw new Exception("Failed to move images to final destination");
            }
        }
    }
});

if ($update_result) {
    setMessage("Updated Successfully", "success");
}

// Redirect back to the referring page
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
