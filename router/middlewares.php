<?php

function authMiddleware()
{
    global $auth_service;

    if (!$auth_service->getAuthUser()) {
        header("Location: ./login");
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

function otpMiddleware()
{
    global $otp_service;

    if(!$otp_service->isActive()) {
        header("Location: ./login");
        exit();
    }
}

function checkoutMiddleware()
{
    // Check if the CART cookie is not set or is empty
    if (!isset($_COOKIE["CART"]) || empty($_COOKIE["CART"])) {
        header("Location: ./");
        exit();
    }

    // Decode the CART cookie
    $cartItems = json_decode($_COOKIE["CART"], true); // Decode as an associative array

    // Check if the count of items in the CART is less than or equal to zero
    if (!is_array($cartItems) || count($cartItems) <= 0) {
        header("Location: ./");
        exit();
    }
}