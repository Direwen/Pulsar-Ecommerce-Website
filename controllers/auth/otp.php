<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    global $auth_service;
    $userOtp = $_POST["user_otp"];

    // Validate the user-entered OTP
    if (ErrorHandler::handle(fn () => $auth_service->verifyOtp($userOtp))) {
        // Redirect to the home page on successful verification
        header("Location: ./");
        exit();
    } else {
        // Set error message for wrong OTP
        setMessage("Wrong Login Code", "error"); // Assuming 'error' is a type for styling
        header("Location: ./otp");
        exit();
    }

} else {
    // If it's not a POST request, display the OTP form
    require("./views/auth/otp.view.php");
}