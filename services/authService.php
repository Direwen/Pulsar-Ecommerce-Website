<?php

class AuthService
{
    private $mailService;
    private $otpService;
    private $sessionService;
    private $tokenService;
    private $userModel;

    public function __construct($mailService, $otpService, $sessionService, $tokenService, $userModel)
    {
        $this->mailService = $mailService;
        $this->otpService = $otpService;
        $this->sessionService = $sessionService;
        $this->tokenService = $tokenService;
        $this->userModel = $userModel;
    }

    public function getAuthUser()
    {
        return $this->sessionService->isSessionActive();
    }

    public function logout()
    {
        $this->sessionService->clearSession();
        $this->tokenService->removeSessionToken();
    }

    public function requestOtp($email)
    {
        $otp = $this->otpService->generateOtp();
        $this->otpService->storeOtp($email, $otp);
        $this->mailService->sendOtp($email, $otp);
        return true;
    }

    public function verifyOtp($userOtp)
    {
        // $result = $this->otpService->validateOtp($userOtp);

        if ($this->otpService->validateOtp($userOtp)) {

            unset($_SESSION["else"]);
            $_SESSION["if"] = "triggered";

            $email = $this->otpService->getStoredEmail();
            $userId = $this->getUserId($email);
            $sessionData = $this->tokenService->createSessionToken($userId);
            $this->tokenService->storeSessionTokenInCookie($sessionData['token'], $sessionData['expired_at']);
            $this->sessionService->loadSessionFromToken($sessionData['token']);
            $this->otpService->clearOtpSession();
            $this->userModel->update(
                [$this->userModel->getColumnLastLoggedInAt() => time()],
                [$this->userModel->getColumnEmail() => $email]
            );
            return true;
        }

        unset($_SESSION["if"]);
        $_SESSION["else"] = "triggered";

        return false;
    }

    public function maintainUserSession()
    {
        if (!isset($_COOKIE['session_token'])) 
            $this->sessionService->clearSession();
        else
            $this->sessionService->loadSessionFromToken($_COOKIE['session_token']);
    }

    public function getUserId($email)
    {
        $user = $this->userModel->getOrCreate([$this->userModel->getColumnEmail() => $email], [$this->userModel->getColumnEmail() => $email]);
        return $user['id'];
    }
}
