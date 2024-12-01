<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    global $user_model;

    $user_email = $_POST["email"];

    $result = ErrorHandler::handle(fn () => $user_model->create([
        $user_model->getColumnEmail() => $user_email 
    ]));

    // Set messages using the setMessage function
    if ($result) {
        setMessage("Welcome, $user_email", "success"); // Use setMessage function for success
    }
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit();