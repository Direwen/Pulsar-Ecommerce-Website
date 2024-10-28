<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    global $user_model;

    $user_email = $_POST["email"];
    $new_user = [
        $user_model->getColumnEmail() => $user_email 
    ];

    $resutl = $user_model->create($new_user);

    if ($result) $_SESSION["message"] = "Welcome, $user_email";
    else $_SESSION["message"] = "Failed to create a new user namely $user_email";
}

header("Location: " . $_SERVER['HTTP_REFERER']);