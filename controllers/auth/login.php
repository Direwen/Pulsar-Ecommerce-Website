<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate email input
    $email = trim($_POST["email"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        setMessage("Enter a valid Email", "error");
        header("Location: ./login");
        exit();
    }

    // Process the POST request
    global $auth_service, $user_model;

    // Normalize email
    $email = strtolower($email);

    // Fetch user details
    $user = ErrorHandler::handle(fn () => $user_model->get([
        $user_model->getColumnEmail() => $email
    ]));

    if (is_array($user)) {
        // Check if user is active
        if (!$user[$user_model->getColumnIsActive()]) {
            setMessage("This account is disabled, please send a ticket to get your access to this account", "error");
            header("Location: ./login");
            exit();
        }

        // Request OTP
        if (ErrorHandler::handle(fn () => $auth_service->requestOtp($email))) {
            header("Location: ./otp");
            exit();
        }

        // OTP request failed
        setMessage("An error occurred while requesting the OTP", "error");
    }

    // Fallback if user does not exist
    setMessage("User not found", "error");
    header("Location: ./login");
    exit();
} else {
    // Display the login form for GET request
    require("./views/auth/login.view.php");
}
