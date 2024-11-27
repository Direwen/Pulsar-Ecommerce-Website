<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Validate form data
if (!$event_model->validateFormData($_POST, $_FILES ?? [])) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

// Handle image preparation
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


$isCreated = $error_handler->handleDbOperation(function () use ($event_model, $product_model, $event_product_model, $img_details) {
    
    $code = $event_model->generateEventCode();
    //Create Event
    $eventCreated = $event_model->create([
        $event_model->getColumnName() => $_POST[$event_model->getColumnName()],
        $event_model->getColumnDescription() => $_POST[$event_model->getColumnDescription()],
        $event_model->getColumnBannerImg() => $img_details["name"],
        $event_model->getColumnStartAt() => $_POST[$event_model->getColumnStartAt()],
        $event_model->getColumnEndAt() => $_POST[$event_model->getColumnEndAt()],
        $event_model->getColumnCode() => $code,
        $event_model->getColumnDiscount() => $_POST["discount"]
    ]);
    
    //If not, throw an exception
    if (!$eventCreated) {
        throw new Exception("Failed to create a new event");
    }

    //If created, create the image
    // Attempt to move the file and throw an exception if it fails
    if (!move_uploaded_file($img_details["temp_name"], $img_details["destination"])) {
        throw new Exception("Failed to save the new event banner image.");
    }

    $event = $event_model->get(
        conditions: [
            $event_model->getColumnCode() => $code
        ]
    );
    
    // //Create the event item records by looping
    foreach($_POST["products"] as $id) {
        //Check existence of product id
        $product = $product_model->get([$product_model->getColumnId() => $id]);
        
        //If valid, create
        if (is_array($product)) {
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
if ($isCreated) {

    setMessage("Created a new event", "success");
} 
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();