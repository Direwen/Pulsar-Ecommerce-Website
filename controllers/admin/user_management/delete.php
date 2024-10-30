<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    global $user_model;
    $user_id = $_POST["id"];

    $result = ErrorHandler::handle(fn () => $user_model->delete([
        $user_model->getColumnId() => $user_id
    ]));

    // Set messages using the setMessage function
    if ($result) {
        setMessage("Record Deleted", "success"); // Use setMessage function for success
    } else {
        setMessage("Failed to Delete the Record", "error"); // Use setMessage function for error
    }
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit(); // Always good practice to exit after a header redirect
