<?php

// Redirect if the request method is not POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

//Check if there any images uploaded in $_FILES
$imgUploaded = [];
foreach ($_FILES as $key => $each) {
    if (!((is_array($each["error"]) && $each["error"][0] == 4) || $each["error"] == 4)) {
        $imgUploaded[] = $key;
    }
}

$variant_id = $_POST["id"];
$product_id = $_POST["product-id"];
$finalized_data = null;

//Update Product
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


// Validate form data and images
if (!$product_model->validateFormData($product_data_without_img, $_FILES, false)) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

$finalized_data = $product_data_without_img;

$details_for_main_img = null;

if (in_array($product_model->getColumnImg(), $imgUploaded)) {
    echo "Update " . $product_model->getColumnImg();
    $details_for_main_img = ImageHandler::prepareImageForStorage(
        temp_name: $_FILES[$product_model->getColumnImg()]["tmp_name"],
        file_name: $_FILES[$product_model->getColumnImg()]["name"],
        dir_to_save: "./assets/products"
    );

    if (!$details_for_main_img) throw new Exception("Failed to prepare main image");

    $finalized_data = array_merge($finalized_data, [
        $product_model->getColumnImg() => $details_for_main_img["name"],
    ]);
}

$details_for_ads_img = [];

if (in_array($product_model->getColumnImgForAds(), $imgUploaded)) {
    echo "Update " . $product_model->getColumnImgForAds();
    for ($i = 0; $i < count($_FILES[$product_model->getColumnImgForAds()]["name"]); $i++) {
        $temp = ImageHandler::prepareImageForStorage(
            temp_name: $_FILES[$product_model->getColumnImgForAds()]["tmp_name"][$i],
            file_name: $_FILES[$product_model->getColumnImgForAds()]["name"][$i],
            dir_to_save: "./assets/products"
        );

        if (!$temp) throw new Exception("Failed to prepare ad images");

        $details_for_ads_img[] = $temp;
    }

    $finalized_data = array_merge($finalized_data, [
        $product_model->getColumnImgForAds() => array_column($details_for_ads_img, "name")
    ]);
}



// Update product in database
if (!$product_model->update($finalized_data, [$product_model->getColumnId() => $product_id])) {
    throw new Exception("Failed to create product in database");
}

foreach ($_POST["variants"] as $each) {
    $each["product_id"] = $product_id;

    if (!$variant_model->validateFormData($each, $_FILES, false)) {
        throw new Exception("Invalid variant data");
    }
}

$details_for_variant_img = [];

if (in_array("variants", $imgUploaded)) {
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
}

for ($i = 0; $i < count($_POST["variants"]); $i++) {


    $finalized_data = [
        $variant_model->getColumnType() => $_POST["variants"][$i]["type"],
        $variant_model->getColumnName() => $_POST["variants"][$i]["name"],
        $variant_model->getColumnUnitPrice() => $_POST["variants"][$i]["unit_price"],
    ];

    if (!empty($details_for_variant_img)) {
        $finalized_data[$variant_model->getColumnImg()] = $details_for_variant_img[$i]["name"];
    }


    $result = $variant_model->update(
        $finalized_data,
        [
            $variant_model->getColumnId() => $variant_id
        ]
    );

    if (!$result) throw new Exception("Failed to create variant in database");
}


$variant = $variant_model->get(
    conditions: [
        $variant_model->getColumnId() => $variant_id
    ]
);

$product = $product_model->get(
    conditions: [
        $product_model->getColumnId() => $product_id
    ]
);

// Remove the variant image from the filesystem if it exists
$variant_img_path = './assets/products/' . $variant["img"];
if (file_exists($variant_img_path)) {
    unlink($variant_img_path);
}

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

if (in_array($product_model->getColumnImg(), $imgUploaded) && !move_uploaded_file($details_for_main_img["temp_name"], $details_for_main_img["destination"])) {
    throw new Exception("Failed to move images to final destination");
}

// Move images to final destinations
foreach ([...$details_for_ads_img, ...$details_for_variant_img] as $each) {
    if (!move_uploaded_file($each["temp_name"], $each["destination"])) {
        throw new Exception("Failed to move images to final destination");
    }
}


echo "<pre>";
var_dump($product);
var_dump($variant);
// var_dump($product_data_without_img);
// var_dump($_FILES);
echo "</pre>";
