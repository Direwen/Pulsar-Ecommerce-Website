<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    global $user_model;
    $user_id = $_POST["id"];

    $result = ErrorHandler::handle(fn () => $user_model->delete([
        $user_model->getColumnId() => $user_id
    ]));

    if ($result) $_SESSION["message"] = "Record Deleted";
    else $_SESSION["message"] = "Failed to Delete the Record";
}

header("Location: " . $_SERVER['HTTP_REFERER']);
