<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    global $user_model;

    $user_email = $_POST["email"];

    $result = $user_model->create([
        $user_model->getColumnEmail() => $user_email 
    ]);

    // Set messages using the setMessage function
    if ($result) {
        setMessage("Welcome, $user_email", "success"); // Use setMessage function for success
    } else {
        setMessage("Failed to create a new user named $user_email", "error"); // Use setMessage function for error
    }
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit();