<?php

class OtpService
{
    private const SESSION_OTP = 'otp';
    private const SESSION_OTP_EMAIL = 'otp_email';
    private const SESSION_OTP_EXPIRATION = 'otp_expiration';
    private const OTP_EXPIRATION_TIME = 3600; // 1 hour

    public function generateOtp()
    {
        return random_int(100000, 999999);
    }

    public function isActive()
    {
        return isset($_SESSION[self::SESSION_OTP]) ? true : false;
    }

    public function storeOtp($email, $otp)
    {
        $_SESSION[self::SESSION_OTP_EMAIL] = $email;
        $_SESSION[self::SESSION_OTP] = $otp;
        $_SESSION[self::SESSION_OTP_EXPIRATION] = time() + self::OTP_EXPIRATION_TIME;
    }

    public function validateOtp($userOtp)
    {
        $storedOtp = $_SESSION[self::SESSION_OTP] ?? null;
        $expiration = $_SESSION[self::SESSION_OTP_EXPIRATION] ?? null;

        if ($storedOtp && $userOtp == $storedOtp && time() < $expiration) {
            // $this->clearOtpSession();
            return true;
        }
        
        return false;
    }

    public function getStoredEmail()
    {
        return $_SESSION[self::SESSION_OTP_EMAIL] ?? null;
    }

    public function clearOtpSession()
    {
        unset($_SESSION[self::SESSION_OTP]);
        unset($_SESSION[self::SESSION_OTP_EXPIRATION]);
        unset($_SESSION[self::SESSION_OTP_EMAIL]);
    }
}
