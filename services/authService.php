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
        $this->mailService->sendMail(
            to: $email,
            details: [
                "subject" => "Pulsar Login OTP Code",
                "body" => "<p>Your one-time password (OTP) for logging into your Pulsar account is:</p>
                            <h2 style='text-align: center; font-size: 24px; color: #1878b8;'>$otp</h2>
                            <p>Please use this code within the next 10 minutes. For your security, do not share this OTP with anyone.</p>
                            <p>If you did not request this code, please ignore this email or contact our support team.
                        </p>",
            ]
        );
        return true;
    }

    public function verifyOtp($userOtp)
    {

        if ($this->otpService->validateOtp($userOtp)) {

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
        $user = $this->userModel->getOrCreate(
            [
                $this->userModel->getColumnEmail() => $email,
                $this->userModel->getColumnRole() => 'user'
            ], 
            [$this->userModel->getColumnEmail() => $email]
        );
        return $user['id'];
    }
}
