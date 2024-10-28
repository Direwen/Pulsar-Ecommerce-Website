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
        // Check if $update_result is an array before accessing its elements
        if ($update_result == true) $_SESSION["message"] = "Successfully Updated";
    } else {
        $_SESSION["message"] = "No need to Update";
    }

    // Set session message before redirecting
}
header("Location: " . $_SERVER['HTTP_REFERER']);
