<?php

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data["review_later"]) && $data["review_later"]) {
    $_SESSION["review_later"] = true;
}

exit();