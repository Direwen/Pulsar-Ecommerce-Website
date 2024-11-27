<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Validate form data
if (!$event_model->validateFormData($_POST, $_FILES ?? [], false)) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

$new_image_uploaded = !empty($_FILES["banner_img"]["name"]);
$img_details = null;

// Handle image preparation
if ($new_image_uploaded) {
    $img_details = ErrorHandler::handle(fn() => ImageHandler::prepareImageForStorage(
        $_FILES["banner_img"]["tmp_name"],
        $_FILES["banner_img"]["name"],
        "./assets/events"
    ));
    
    if (!$img_details) {
        setMessage("Failed to prepare the image.", "error");
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
}

$result = $error_handler->handleDbOperation(function () use ($img_details, $event_model, $new_image_uploaded, $event_product_model, $product_model) {
    $event = $event_model->get(
        [
            $event_model->getColumnId() => $_POST["id"]
        ]
    );

    if (!$event) throw new Exception("Event is not found");

    $is_updated = $event_model->update(
        [
            $event_model->getColumnName() => $_POST[$event_model->getColumnName()],
            $event_model->getColumnDescription() => $_POST[$event_model->getColumnDescription()],
            $event_model->getColumnBannerImg() => empty($img_details) ? null : $img_details["name"],
            $event_model->getColumnStartAt() => $_POST[$event_model->getColumnStartAt()],
            $event_model->getColumnEndAt() => $_POST[$event_model->getColumnEndAt()],
            $event_model->getColumnDiscount() => $_POST["discount"]
        ],
        [
            $event_model->getColumnId() => $_POST["id"]
        ]
    );

    if (!$is_updated) throw new Exception("Failed to Update the Event");
    
    if ($new_image_uploaded) {
        $oldFilePath = "./assets/events/" . $event[$event_model->getColumnBannerImg()];
        if (file_exists($oldFilePath)) unlink($oldFilePath);
        if (!move_uploaded_file($img_details["temp_name"], $img_details["destination"])) throw new Exception("Failed to save the new event banner image.");
    }

    //get all records from event products table
    $products = [];
    $page = 1;
    $hasMore = false;

    do {
        // Fetch the current page of results
        $result = ErrorHandler::handle(fn() => $event_product_model->getAll(page: $page));
        $hasMore = $result["hasMore"];

        // Collect the products from the current page
        $products = array_merge($products, $result["records"] ?? []);

        // Increment the page number for the next iteration
        $page++;
    } while ($hasMore);
    //delete those by looping
    foreach ($products as $each) {
        $event_product_model->delete([
            $event_product_model->getColumnEventId() => $each[$event_product_model->getColumnEventId()],
            $event_product_model->getColumnProductId() => $each[$event_product_model->getColumnProductId()],
        ]);
    }
    //loop products
    foreach ($_POST["products"] as $id) {
        //Check existence of product id
        $product = $product_model->get([$product_model->getColumnId() => $id]);
        
        //If valid, create
        if (is_array($product)) {
            //create 
            $eventProductCreated = $event_product_model->create([
                $event_product_model->getColumnEventId() => $event[$event_model->getColumnId()],
                $event_product_model->getColumnProductId() => $product[$product_model->getColumnId()],
            ]);

            if (!$eventProductCreated) {
                throw new Exception("Encounrtered an error while creating Event Items");
            }
        }
    }

});

// Success message
if ($result) setMessage("Updated the event", "success");

header("Location: " . $_SERVER['HTTP_REFERER']);
exit();