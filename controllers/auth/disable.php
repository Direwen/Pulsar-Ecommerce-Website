<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

//Check if user is logged in
if (!$auth_service->getAuthUser() || empty($_SESSION["user_id"])) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
//update is_active
$result = ErrorHandler::handle(fn () => $user_model->update(
    [
        $user_model->getColumnIsActive() => "false"
    ],
    [
        $user_model->getColumnId() => $_SESSION["user_id"]
    ]
));

if (!$result) {
    setMessage("Failed to disable", 'error');
} else {
    $auth_service->logout();
    setMessage("Logged out", 'success');
    // $mail_service->sendMail()
}
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();