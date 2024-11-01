<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Prepare form data for initial validation
$product_data_without_img = [
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

// Assign images based on model's column names
$_FILES[$product_model->getColumnImgForAds()] = $_FILES["img"];
unset($_FILES["img"]);
$_FILES[$product_model->getColumnImg()] = $_FILES["main-img"];
unset($_FILES["main-img"]);

// Validate form data and images
if (!$product_model->validateFormData($product_data_without_img, $_FILES)) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Function to validate and prepare images for storage
function prepareImage($file, $index = null) {
    $tmp_name = $index === null ? $file["tmp_name"] : $file["tmp_name"][$index];
    $name = $index === null ? $file["name"] : $file["name"][$index];
    return ErrorHandler::handle(fn() => ImageHandler::prepareImageForStorage($tmp_name, $name, "./assets/products"));
}

// Attempt to create product in database using a transaction
$error_handler->handleDbOperation(function() use ($product_model, $variant_model, $product_data_without_img, &$product, &$details_for_main_img, &$details_for_ads_img, &$finalized_data, &$details_for_variant_img) {
    
    
    // Prepare main image
    $details_for_main_img = prepareImage($_FILES[$product_model->getColumnImg()]);
    if (!$details_for_main_img) throw new Exception("Failed to prepare main image");


    // Prepare ad images
    $details_for_ads_img = array_filter(array_map(
        fn($i) => prepareImage($_FILES[$product_model->getColumnImgForAds()], $i),
        array_keys($_FILES[$product_model->getColumnImgForAds()]["name"])
    ));
    if (count($details_for_ads_img) !== count($_FILES[$product_model->getColumnImgForAds()]["name"])) {
        throw new Exception("Failed to prepare ad images");
    }

    // Finalize data for database
    $finalized_data = array_merge($product_data_without_img, [
        $product_model->getColumnImg() => $details_for_main_img["name"],
        $product_model->getColumnImgForAds() => array_column($details_for_ads_img, "name")
    ]);

    // Create product in database
    if (!$product_model->create($finalized_data)) {
        throw new Exception("Failed to create product in database");
    }

    $product = $product_model->get([
        $product_model->getColumnName() => $finalized_data[$product_model->getColumnName()]
    ]);

    // Handle Variants
    foreach ($_POST["variants"] as $each) {
        $each["product_id"] = $product[$product_model->getColumnId()];

        if (!$variant_model->validateFormData($each, $_FILES)) {
            throw new Exception("Invalid variant data");
        }
    }

    $details_for_variant_img = [];
    for ($i = 0; $i < count($_FILES["variants"]["name"]); $i++) {
        $temp = ErrorHandler::handle(fn() => ImageHandler::prepareImageForStorage(
            temp_name: $_FILES["variants"]["tmp_name"][$i],
            file_name: $_FILES["variants"]["name"][$i],
            dir_to_save: "./assets/products"
        ));
        if (!$temp) throw new Exception("Failed to prepare variant image");
        $details_for_variant_img[] = $temp;
    }

    for ($i = 0; $i < count($_POST["variants"]); $i++) {
        $_POST["variants"][$i][$variant_model->getColumnProductId()] = $product[$product_model->getColumnId()];
        $_POST["variants"][$i][$variant_model->getColumnImg()] = $details_for_variant_img[$i]["name"];

        $result = $variant_model->create([
            $variant_model->getColumnProductId() => $_POST["variants"][$i]["product_id"],
            $variant_model->getColumnType() => $_POST["variants"][$i]["type"],
            $variant_model->getColumnName() => $_POST["variants"][$i]["name"],
            $variant_model->getColumnUnitPrice() => $_POST["variants"][$i]["unit_price"],
            $variant_model->getColumnImg() => $_POST["variants"][$i]["img"]
        ]);

        if (!$result) throw new Exception("Failed to create variant in database");
    }

    // Move images to final destinations
    foreach ([$details_for_main_img, ...$details_for_ads_img, ...$details_for_variant_img] as $each) {
        if (!move_uploaded_file($each["temp_name"], $each["destination"])) {
            throw new Exception("Failed to move images to final destination");
        }
    }

}, "Failed to create product");

setMessage("Created a new product along with variants", 'success');

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
