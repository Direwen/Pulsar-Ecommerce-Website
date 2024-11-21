<?php

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);


if (!isset($data["ratings"]) || !is_array($data["ratings"]) || !$_SESSION["user_id"]) {
    echo json_encode([
        'success' => false
    ]);
    exit();
}


$result = $error_handler->handleDbOperation(function () use ($data) {
    
    global $variant_model, $review_model;

    foreach($data['ratings'] as $id => $rating)
    {
        //Check whether the variant exists
        $variant = $variant_model->get(
            [$variant_model->getColumnId() => $id]
        );
        if ($variant && is_array($variant)) {
            //if so, create a review record
            $review_created = $review_model->create(
                [
                    $review_model->getColumnUserId() => $_SESSION["user_id"],
                    $review_model->getColumnVariantId() => $id,
                    $review_model->getColumnRating() => $rating
                ]
            );

            if (!$review_created) {
                return false;
            }
        }
    }
});


if (!$result) {
    echo json_encode([
        "success" => false
    ]);
    exit();
}



echo json_encode([
    "success" => true,
    "code" => $discount_model->generateDiscount()
]);
exit();