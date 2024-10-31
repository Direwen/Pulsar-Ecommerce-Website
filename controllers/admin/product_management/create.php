<?php

global $product_model;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// prepare form data for first stage of validation
$product_data_without_img = [
    "name" => $_POST["product-name"],
    "category_id" => $_POST["category"],
    "description" => $_POST["description"],
    "dimension" => $_POST["dimension"],
    "feature" => array_map('trim', explode(',', $_POST["feature"])),
    "important_feature" => array_combine(
        $_POST['special-feature-title'] ?? [],
        array_map(fn($details) => array_unique(explode(', ', $details)), $_POST['special-feature-details'] ?? [])
    ),
    "requirement" => array_map('trim', explode(',', $_POST["requirement"])),
    "package_content" => array_map('trim', explode(',', $_POST["package-content"]))
];

echo "<pre>";

$_FILES[$product_model->getColumnImgForAds()] = $_FILES["img"];
unset($_FILES["img"]);
$_FILES[$product_model->getColumnImg()] = $_FILES["main-img"];
unset($_FILES["main-img"]);


// var_dump($_FILES);
echo "</pre>";
// exit();

// Validate the data from post request along $_FILES for images
if (!$product_model->validateFormData($product_data_without_img, $_FILES ?? [])) {
    // if validation fails, exit
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}
// Validate the main-img and images for ads by looping
// if smt is invalid, exit
$details_for_main_img = ErrorHandler::handle(fn() => ImageHandler::prepareImageForStorage(
    $_FILES[$product_model->getColumnImg()]["tmp_name"],
    $_FILES[$product_model->getColumnImg()]["name"],
    "./assets/products"
));
if (empty($details_for_main_img)) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

$details_for_ads_img = [];
for ($i = 0; $i < count($_FILES[$product_model->getColumnImgForAds()]["name"]); $i++) {
    $temp = ErrorHandler::handle(function () use ($i, $product_model) {
        return ImageHandler::prepareImageForStorage(
            $_FILES[$product_model->getColumnImgForAds()]["tmp_name"][$i],
            $_FILES[$product_model->getColumnImgForAds()]["name"][$i],
            "./assets/products"
        );
    });
    if (empty($temp)) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    } else $details_for_ads_img[] = $temp;
}
// prepare all data attributes using attribute getter methods from model class
$result = [];
foreach ($details_for_ads_img as $each) {
    $result[] = $each["name"];
}

$finalized_data = [
    $product_model->getColumnName() => $product_data_without_img["name"],
    $product_model->getColumnCategoryId() => $product_data_without_img["category_id"],
    $product_model->getColumnDescription() => $product_data_without_img["description"],
    $product_model->getColumnDimension() => $product_data_without_img["dimension"],
    $product_model->getColumnFeature() => array_unique($product_data_without_img["feature"]),
    $product_model->getColumnImportantFeature() => $product_data_without_img["important_feature"],
    $product_model->getColumnRequirement() => array_unique($product_data_without_img["requirement"]),
    $product_model->getColumnPackageContent() => array_unique($product_data_without_img["package_content"]),
    $product_model->getColumnImg() => $details_for_main_img["name"],
    $product_model->getColumnImgForAds() => $result,
];

// var_dump($finalized_data);

$isCreated = ErrorHandler::handle(fn () => $product_model->create($finalized_data));

if (!$isCreated) {
    setMessage("Failed to create a new product.", "error"); // Set error message
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

//Move the img
$img_to_upload = array_merge([$details_for_main_img], $details_for_ads_img);

$success = true;
foreach($img_to_upload as $each) {
    $success = move_uploaded_file($each["temp_name"], $each["destination"]) ? true : false;
}

var_dump($success);
//Move the img_for_ads
// if (!$result) {
//     setMessage("Failed to prepare the image for ads.", "error"); // Set error message
//     header("Location: " . $_SERVER['HTTP_REFERER']);
//     exit;
// }

// // Extract prepared image details
// [$unique_ads_file_name, $path_to_ads_submit] = $result;

// // Handle main image preparation
// $resultMain = ErrorHandler::handle(fn() => ImageHandler::prepareImageForStorage(
//     $_FILES["main-img"]["tmp_name"], 
//     $_FILES["main-img"]["name"], 
//     "./assets/products/main"
// ));

// if (!$resultMain) {
//     setMessage("Failed to prepare the main product image.", "error"); // Set error message
//     header("Location: " . $_SERVER['HTTP_REFERER']);
//     exit;
// }

// // Extract prepared main image details
// [$unique_main_file_name, $path_to_main_submit] = $resultMain;

// // Create product
// $isCreated = ErrorHandler::handle(fn() => $product_model->create([
//     $product_model::getColumnName() => $_POST["name"],
//     $product_model::getColumnCategoryId() => $_POST["category_id"],
//     $product_model::getColumnDescription() => $_POST["description"],
//     $product_model::getColumnDimension() => $_POST["dimension"] ?? null,
//     $product_model::getColumnFeature() => $_POST["feature"] ?? [],
//     $product_model::getImportantFeatures() => $_POST["important_features"] ?? [],
//     $product_model::getColumnRequirement() => $_POST["requirement"] ?? [],
//     $product_model::getColumnPackageContent() => $_POST["package_content"] ?? [],
//     $product_model::getColumnImgForAds() => $unique_ads_file_name,
//     $product_model::getColumnImg() => $unique_main_file_name,
// ]));

// if (!$isCreated) {
//     setMessage("Failed to create a new product.", "error"); // Set error message
//     header("Location: " . $_SERVER['HTTP_REFERER']);
//     exit;
// }

// // Move uploaded files
// $messageAds = move_uploaded_file($_FILES["img_for_ads"]["tmp_name"], $path_to_ads_submit) ?
//     "Ads image uploaded successfully." :
//     "Failed to save ads image.";

// $messageMain = move_uploaded_file($_FILES["main-img"]["tmp_name"], $path_to_main_submit) ?
//     "Main product image uploaded successfully." :
//     "Failed to save main product image.";

// setMessage("Created a new product. " . $messageAds . " " . $messageMain, "success"); // Set success message

// header("Location: " . $_SERVER['HTTP_REFERER']);
// exit;
