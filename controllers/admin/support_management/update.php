<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}


if (!isset($_POST["id"]) || empty($_POST["id"]) ) {
    setMessage("Id is required", "error");
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

if (!isset($_POST["email"]) || empty($_POST["email"]) ) {
    setMessage("Email is required", "error");
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

if (!isset($_POST["reply"]) || empty($_POST["reply"]) ) {
    setMessage("Reply value is required", "error");
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

$result = $error_handler->handleDbOperation(function () {

    global $support_model, $mail_service;

    $record = $support_model->get(
        [
            $support_model->getColumnId() => $_POST["id"]
        ]
    );

    if (!$record) return false;
    

    $support_model->update(
        [
            $support_model->getColumnStatus() => "replied"
        ],
        [
            $support_model->getColumnId() => $_POST["id"]
        ]
    );
    //Sending Mail to the Customer
    $mail_result = $mail_service->sendMail(
        to: $_POST["email"],
        details: [
            "subject" => "Resolving Customer Issue",
            "body" => $_POST["reply"]
        ]
    );

    return $mail_result;
});

setMessage( $result ? "Replied Successfully" : "Failed to Reply", $result ? "success" : "error");
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;

