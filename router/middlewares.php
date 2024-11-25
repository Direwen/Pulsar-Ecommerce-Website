<?php

function authMiddleware()
{
    global $auth_service, $root_directory;

    if (!$auth_service->getAuthUser()) {
        setMessage("Authentication required. Please log in to continue.", 'info');
        header("Location: ".  $root_directory . "login");
        exit();
    }
}

function guestMiddleware()
{
    global $auth_service, $root_directory;

    if($auth_service->getAuthUser()) {
        setMessage("This page is only accessible to guests. Please log out to access it.", 'info');
        header("Location: ".  $root_directory);
        exit();
    }
}

function adminMiddleware()
{
    global $auth_service, $root_directory;

    if (!$auth_service->getAuthUser() || $_SESSION["user_role"] != "admin") {
        setMessage("Access restricted to administrators only.", 'info');
        header("Location: ".  $root_directory);
        exit();
    }
}

function otpMiddleware()
{
    global $otp_service, $root_directory;

    if(!$otp_service->isActive()) {
        setMessage("OTP verification required. Please provide your email to generate an OTP.", 'info');
        header("Location: ".  $root_directory . "login");
        exit();
    }
}

function checkoutMiddleware()
{
    // Check if the CART cookie is not set or is empty
    if (!isset($_COOKIE["CART"]) || empty($_COOKIE["CART"])) {
        setMessage("Your cart is empty. Please add items before proceeding to checkout.", 'info');
        header("Location: ./");
        exit();
    }

    // Decode the CART cookie
    $cartItems = json_decode($_COOKIE["CART"], true); // Decode as an associative array

    // Check if the count of items in the CART is less than or equal to zero
    if (!is_array($cartItems) || count($cartItems) <= 0) {
        setMessage("Your cart is empty. Please add items before proceeding to checkout.", 'info');
        header("Location: ./");
        exit();
    }
}

function recentOrderMiddleware()
{
    if (!isset($_SESSION["recent_order"]) || !$_SESSION["recent_order"] || !isset($_SESSION['recent_order_time']) || (time() - $_SESSION['recent_order_time']) >= (60 * 3)) { // 3 minutes
        setMessage("No recent orders found. Please place an order to view details.", 'info');
        header("Location: ./");
        exit();
    }
}
