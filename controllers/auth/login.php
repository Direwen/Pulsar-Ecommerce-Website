<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Process the POST request
    $email = $_POST["email"];
    
    // Validate email input
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        
        global $auth_service;

        // Error handling for OTP request
        if (ErrorHandler::handle(fn () => $auth_service->requestOtp($email))) {
            header("Location: ./otp");
            exit();
        } else {
            $_SESSION['message'] = "An error occurred while requesting the OTP.";
        }

    } else {
        $_SESSION['message'] = "Enter a valid Email";
    }

    header("Location: ./login");
    exit();
    
} else {
    // If it's not a POST request, display the login form
    require("./views/auth/login.view.php");
}
