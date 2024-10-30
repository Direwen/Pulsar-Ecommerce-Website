<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    global $user_model;

    $user_id = $_POST["id"];
    $user_role = $_POST["role"];
    $result = $user_model->get([
        $user_model->getColumnId() => $user_id
    ]);

    // Check if $result is an array before accessing its elements
    if (is_array($result) && $result[$user_model->getColumnRole()] != $user_role) {
        $update_result = $user_model->update(
            [
                $user_model->getColumnRole() => $user_role
            ],
            [
                $user_model->getColumnId() => $user_id
            ]
        );

        // Check if $update_result indicates success
        if ($update_result) {
            setMessage("Successfully Updated", "success"); // Use setMessage for success
        } else {
            setMessage("Failed to Update", "error"); // Use setMessage for failure
        }
    } else {
        setMessage("No need to Update", "info"); // Use setMessage for no change
    }
}

// Redirect to the previous page
header("Location: " . $_SERVER['HTTP_REFERER']);
exit(); // Good practice to exit after a header redirect
