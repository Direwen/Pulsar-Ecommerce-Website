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