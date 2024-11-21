<?php

header('Content-Type: application/json');

if (isset($_SESSION["review_later"]) && $_SESSION["review_later"]) {
    echo json_encode([
        'records' => []
    ]);
    exit();
}

//Check if the user refuses to give ratings
$result = $review_model->getUnreviewedVariants();

echo json_encode([
    "records" => $result ?? []
]);
exit();