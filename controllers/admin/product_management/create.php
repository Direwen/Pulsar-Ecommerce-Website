<?php

global $product_model;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// echo "<pre>";
// var_dump($_POST);
// var_dump($_FILES);
// echo "</pre>";
// exit();

// Prepare form data for initial validation
// $product_data_without_img = [
//     $product_model->getColumnName() => $_POST["product-name"],
//     $product_model->getColumnCategoryId() => $_POST["category"],
//     $product_model->getColumnDescription() => $_POST["description"],
//     $product_model->getColumnDimension() => $_POST["dimension"],
//     $product_model->getColumnFeature() => array_unique(array_map('trim', explode(',', $_POST["feature"]))),
//     $product_model->getColumnImportantFeature() => array_combine(
//         $_POST['special-feature-title'] ?? [],
//         array_map(fn($details) => array_unique(explode(', ', $details)), $_POST['special-feature-details'] ?? [])
//     ),
//     $product_model->getColumnRequirement() => array_unique(array_map('trim', explode(',', $_POST["requirement"]))),
//     $product_model->getColumnPackageContent() => array_unique(array_map('trim', explode(',', $_POST["package-content"])))
// ];


// // Assign images based on model's column names
// $_FILES[$product_model->getColumnImgForAds()] = $_FILES["img"];
// unset($_FILES["img"]);
// $_FILES[$product_model->getColumnImg()] = $_FILES["main-img"];
// unset($_FILES["main-img"]);

// // Validate form data and images
// if (!$product_model->validateFormData($product_data_without_img, $_FILES)) {
//     header("Location: " . $_SERVER['HTTP_REFERER']);
//     exit;
// }

// // Function to validate and prepare images for storage
// function prepareImage($file, $index = null)
// {
//     global $product_model;
//     $tmp_name = $index === null ? $file["tmp_name"] : $file["tmp_name"][$index];
//     $name = $index === null ? $file["name"] : $file["name"][$index];
//     return ErrorHandler::handle(fn() => ImageHandler::prepareImageForStorage($tmp_name, $name, "./assets/products"));
// }

// // Prepare main image
// $details_for_main_img = prepareImage($_FILES[$product_model->getColumnImg()]);
// if (!$details_for_main_img) {
//     header("Location: " . $_SERVER['HTTP_REFERER']);
//     exit;
// }

// // Prepare ad images
// $details_for_ads_img = array_filter(array_map(
//     fn($i) => prepareImage($_FILES[$product_model->getColumnImgForAds()], $i),
//     array_keys($_FILES[$product_model->getColumnImgForAds()]["name"])
// ));
// if (count($details_for_ads_img) !== count($_FILES[$product_model->getColumnImgForAds()]["name"])) {
//     header("Location: " . $_SERVER['HTTP_REFERER']);
//     exit;
// }

// // Finalize data for database
// $finalized_data = array_merge($product_data_without_img, [
//     $product_model->getColumnImg() => $details_for_main_img["name"],
//     $product_model->getColumnImgForAds() => array_column($details_for_ads_img, "name")
// ]);

// // Attempt to create product in database
// if (!ErrorHandler::handle(fn() => $product_model->create($finalized_data))) {
//     setMessage("Failed to create a new product.", "error");
//     header("Location: " . $_SERVER['HTTP_REFERER']);
//     exit;
// }

// // Move images to final destinations
// foreach ([$details_for_main_img, ...$details_for_ads_img] as $each) {
//     if (!move_uploaded_file($each["temp_name"], $each["destination"])) {
//         header("Location: " . $_SERVER['HTTP_REFERER']);
//         exit;
//     }
// }

// Handle Variants
global $variant_model;
// Step 1: Validate the variants data
// This should involve checking if the variants are provided in the $_POST and ensuring all necessary fields are present.
foreach ($_POST["variants"] as $each) {
    $each["product_id"] = 1;

    if (!$variant_model->validateFormData($each, $_FILES)) {
        echo "Invalid";
        exit();
    }
}

var_dump($_POST["variants"]);

// Step 2: Prepare images for each variant
// This will involve looping through the variant data in $_POST and preparing the corresponding images from $_FILES.
// You can utilize the same `prepareImage` function for variant images, adjusting the index accordingly.
$details_for_variant_img = [];
for ($i = 0; $i < count($_FILES["variants"]["name"]); $i++) {
    $temp = ErrorHandler::handle(fn() => ImageHandler::prepareImageForStorage(
        temp_name: $_FILES["variants"]["tmp_name"][$i],
        file_name: $_FILES["variants"]["name"][$i],
        dir_to_save: "./assets/products"
    ));
    if (!$temp) {
        echo "Invalid Image";
        exit();
    }
    $details_for_variant_img[] = $temp;
}

// Step 3: Create a new array for variants to store the finalized variant data
// Each variant will likely have its own set of fields that need to be validated and structured, similar to the main product data.
for ($i = 0; $i < count($_POST["variants"]); $i++) {
    $_POST["variants"][$i]["product_id"] = 1;

    $_POST["variants"][$i][$variant_model->getColumnImg()] = $details_for_variant_img[$i]["name"];
    // $result = $variant_model->create($_POST["variants"][$i]);
    $result = $variant_model->create([
        $variant_model->getColumnProductId() => $_POST["variants"][$i]["product_id"],
        $variant_model->getColumnType() => $_POST["variants"][$i]["type"],
        $variant_model->getColumnName() => $_POST["variants"][$i]["name"],
        $variant_model->getColumnUnitPrice() => $_POST["variants"][$i]["unit_price"],
        $variant_model->getColumnImg() => $_POST["variants"][$i]["img"]
    ]);
}

// Step 4: Finalize the variant data and attempt to create each variant in the database
// You may want to call a similar create function for variants, ensuring that the correct relationships are set up in the database.
foreach ($details_for_variant_img as $each) {
    if (!move_uploaded_file($each["temp_name"], $each["destination"])) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
}
// Step 5: Handle moving the variant images to their final destinations
// After confirming that the variant data is successfully created, you can loop through the variant images and move them to the appropriate folders.

// header("Location: " . $_SERVER['HTTP_REFERER']);
// exit;
