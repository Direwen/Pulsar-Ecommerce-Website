<?php

function authMiddleware()
{
    global $auth_service, $root_directory;

    if (!$auth_service->getAuthUser()) {
        setMessage("Please authenticate yourself first", 'info');
        header("Location: ".  $root_directory . "login");
        exit();
    }
}

function guestMiddleware()
{
    global $auth_service;

    if($auth_service->getAuthUser()) {
        header("Location: ./");
        exit();
    }
}

function adminMiddleware()
{
    global $auth_service, $root_directory;

    if (!$auth_service->getAuthUser() || $_SESSION["user_role"] != "admin") {
        setMessage("You require the admin role to have access to the dashboard", 'info');
        header("Location: ".  $root_directory);
        exit();
    }
}

function otpMiddleware()
{
    global $otp_service, $root_directory;

    if(!$otp_service->isActive()) {
        setMessage("OTP isn't generated yet, Please try to enter the email first", 'info');
        header("Location: ".  $root_directory . "login");
        exit();
    }
}

function checkoutMiddleware()
{
    // Check if the CART cookie is not set or is empty
    if (!isset($_COOKIE["CART"]) || empty($_COOKIE["CART"])) {
        setMessage("Please browse around and add the items to the cart first", 'info');
        header("Location: ./");
        exit();
    }

    // Decode the CART cookie
    $cartItems = json_decode($_COOKIE["CART"], true); // Decode as an associative array

    // Check if the count of items in the CART is less than or equal to zero
    if (!is_array($cartItems) || count($cartItems) <= 0) {
        setMessage("Please browse around and add the items to the cart first", 'info');
        header("Location: ./");
        exit();
    }
}

function recentOrderMiddleware()
{
    if (!isset($_SESSION["recent_order"]) || !$_SESSION["recent_order"] || !isset($_SESSION['recent_order_time']) || (time() - $_SESSION['recent_order_time']) >= (60 * 3)) { // 3 minutes
        header("Location: ./");
        exit();
    }
}