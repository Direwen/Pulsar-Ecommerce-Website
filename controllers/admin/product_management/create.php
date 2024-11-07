<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

if ($_POST["action-type"] == "create") {
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

    // Attempt to create product in database using a transaction
    $create_result = $error_handler->handleDbOperation(function () use ($product_model, $variant_model, $product_data_without_img, &$product, &$details_for_main_img, &$details_for_ads_img, &$finalized_data, &$details_for_variant_img) {

        //Prepare main Image
        $details_for_main_img = ImageHandler::prepareImageForStorage(
            temp_name: $_FILES[$product_model->getColumnImg()]["tmp_name"],
            file_name: $_FILES[$product_model->getColumnImg()]["name"],
            dir_to_save: "./assets/products"
        );

        // Prepare main image
        if (!$details_for_main_img) throw new Exception("Failed to prepare main image");


        // // Prepare ad images
        for ($i = 0; $i < count($_FILES[$product_model->getColumnImgForAds()]["name"]); $i++) {
            $temp = ImageHandler::prepareImageForStorage(
                temp_name: $_FILES[$product_model->getColumnImgForAds()]["tmp_name"][$i],
                file_name: $_FILES[$product_model->getColumnImgForAds()]["name"][$i],
                dir_to_save: "./assets/products"
            );

            if (!$temp) throw new Exception("Failed to prepare ad images");

            $details_for_ads_img[] = $temp;
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
        for ($i = 0; $i < count($_FILES["variants_img"]["name"]); $i++) {
            //Looping through images of each variant
            $temp = ErrorHandler::handle(fn () => ImageHandler::prepareImageForStorage(
                temp_name: $_FILES["variants_img"]["tmp_name"][$i],
                file_name: $_FILES["variants_img"]["name"][$i],
                dir_to_save: "./assets/products"
            ));
            if (!$temp) throw new Exception("Failed to prepare variant image");
            $details_for_variant_img[] = $temp;
        }

        $details_for_variant_ads_img = [];
        //Looping through each variant
        for ($i = 0; $i < count($_FILES["variants_img_for_ads"]["name"]); $i++) {
            //Looping through images of each variant
            for ($img_index = 0; $img_index < count($_FILES["variants_img_for_ads"]["name"][$i]); $img_index++) {
                $temp = ErrorHandler::handle(fn() => ImageHandler::prepareImageForStorage(
                    temp_name: $_FILES["variants_img_for_ads"]["tmp_name"][$i][$img_index],
                    file_name: $_FILES["variants_img_for_ads"]["name"][$i][$img_index],
                    dir_to_save: "./assets/products"
                ));
                if (!$temp) throw new Exception("Failed to prepare variant image");
                $details_for_variant_ads_img[$i][] = $temp;
            }
        }

        for ($i = 0; $i < count($_POST["variants"]); $i++) {
            $_POST["variants"][$i][$variant_model->getColumnProductId()] = $product[$product_model->getColumnId()];
            $_POST["variants"][$i][$variant_model->getColumnImg()] = $details_for_variant_img[$i]["name"];
            $_POST["variants"][$i][$variant_model->getColumnImgForAds()] = array_column($details_for_variant_ads_img[$i], 'name');

            $result = $variant_model->create([
                $variant_model->getColumnProductId() => $_POST["variants"][$i]["product_id"],
                $variant_model->getColumnType() => $_POST["variants"][$i]["type"],
                $variant_model->getColumnName() => $_POST["variants"][$i]["name"],
                $variant_model->getColumnUnitPrice() => $_POST["variants"][$i]["unit_price"],
                $variant_model->getColumnImg() => $_POST["variants"][$i]["img"],
                $variant_model->getColumnImgForAds() => $_POST["variants"][$i]["img_for_ads"]
            ]);

            if (!$result) throw new Exception("Failed to create variant in database");
        }

        // Move images to final destinations
        foreach ([$details_for_main_img, ...$details_for_ads_img] as $each) {
            if (!move_uploaded_file($each["temp_name"], $each["destination"])) {
                throw new Exception("Failed to move images to final destination");
            }
        }

        foreach ([$details_for_variant_img, ...$details_for_variant_ads_img] as $variant) {
            foreach ($variant as $each) {
                if (!move_uploaded_file($each["temp_name"], $each["destination"])) {
                    throw new Exception("Failed to move images to final destination");
                }
            }
        }

    }, "Failed to create product");

    if ($create_result) setMessage("Created", 'success');

} else if ($_POST["action-type"] == "add-variant") {

    // Handle Variants
    if (empty($_POST["product-id"])) {
        throw new Exception("Choose one of the products");
    }

    foreach ($_POST["variants"] as $variant) {
        $variant[$variant_model->getColumnProductId()] = $_POST["product-id"];
        if (!$variant_model->validateFormData($variant, $_FILES)) {
            throw new Exception("Invalid variant data");
        }
    }

    $details_for_variant_img = [];
    for ($i = 0; $i < count($_FILES["variants_img"]["name"]); $i++) {
        //Looping through images of each variant
        $temp = ErrorHandler::handle(fn () => ImageHandler::prepareImageForStorage(
            temp_name: $_FILES["variants_img"]["tmp_name"][$i],
            file_name: $_FILES["variants_img"]["name"][$i],
            dir_to_save: "./assets/products"
        ));
        if (!$temp) throw new Exception("Failed to prepare variant image");
        $details_for_variant_img[] = $temp;
    }

    $details_for_variant_ads_img = [];
    //Looping through each variant
    for ($i = 0; $i < count($_FILES["variants_img_for_ads"]["name"]); $i++) {
        //Looping through images of each variant
        for ($img_index = 0; $img_index < count($_FILES["variants_img_for_ads"]["name"][$i]); $img_index++) {
            $temp = ErrorHandler::handle(fn() => ImageHandler::prepareImageForStorage(
                temp_name: $_FILES["variants_img_for_ads"]["tmp_name"][$i][$img_index],
                file_name: $_FILES["variants_img_for_ads"]["name"][$i][$img_index],
                dir_to_save: "./assets/products"
            ));
            if (!$temp) throw new Exception("Failed to prepare variant image");
            $details_for_variant_ads_img[$i][] = $temp;
        }
    }

    $create_result = $error_handler->handleDbOperation(function () use ($variant_model, $details_for_variant_img, $details_for_variant_ads_img) {

        for ($i = 0; $i < count($_POST["variants"]); $i++) {
            $_POST["variants"][$i][$variant_model->getColumnProductId()] = $_POST["product-id"];
            $_POST["variants"][$i][$variant_model->getColumnImg()] = $details_for_variant_img[$i]["name"];
            $_POST["variants"][$i][$variant_model->getColumnImgForAds()] = array_column($details_for_variant_ads_img[$i], 'name');

            $result = $variant_model->create([
                $variant_model->getColumnProductId() => $_POST["variants"][$i]["product_id"],
                $variant_model->getColumnType() => $_POST["variants"][$i]["type"],
                $variant_model->getColumnName() => $_POST["variants"][$i]["name"],
                $variant_model->getColumnUnitPrice() => $_POST["variants"][$i]["unit_price"],
                $variant_model->getColumnImg() => $_POST["variants"][$i]["img"],
                $variant_model->getColumnImgForAds() => $_POST["variants"][$i]["img_for_ads"]
            ]);

            if (!$result) throw new Exception("Failed to create variant in database");
        }

        // Move images to final destinations
        foreach ([$details_for_variant_img, ...$details_for_variant_ads_img] as $variant) {
            foreach ($variant as $each) {
                if (!move_uploaded_file($each["temp_name"], $each["destination"])) {
                    throw new Exception("Failed to move images to final destination");
                }
            }
        }

        return true;
    });

    if ($create_result) setMessage("Created", 'success');
}


header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
