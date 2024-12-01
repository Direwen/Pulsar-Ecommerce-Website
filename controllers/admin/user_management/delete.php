<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST["id"] ?? null;

    if ($user_id !== null) {
        $result = ErrorHandler::handle(fn () => $user_model->delete([
            $user_model->getColumnId() => $user_id
        ]));

        // Set messages using the setMessage function
        if ($result) {
            setMessage("Record Deleted", "success"); // Success message
        }
    } else {
        setMessage("User ID missing in request", "error"); // Error message if ID is missing
    }
}

// Redirect to the previous page
header("Location: " . $_SERVER['HTTP_REFERER']);
exit(); // Good practice to exit after a header redirect
