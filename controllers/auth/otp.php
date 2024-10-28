<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    global $auth_service;
    $userOtp = $_POST["user_otp"];
    
    // Validate the user-entered OTP
    if (ErrorHandler::handle(fn () => $auth_service->verifyOtp($userOtp))) {
        header("Location: ./");
        exit();
    } else {
        $_SESSION["message"] = "Wrong Login Code";
        header("Location: ./otp");
        exit();
    }

} else {
    // If it's not a POST request, display the OTP form
    require("./views/auth/otp.view.php");
}
