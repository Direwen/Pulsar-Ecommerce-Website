<?php

class TokenService
{
    private const COOKIE_SESSION_TOKEN = 'session_token';
    private const SESSION_TOKEN_EXPIRATION = 3600 * 24 * 30; // 30 days

    private $sessionModel;

    public function __construct($sessionModel)
    {
        $this->sessionModel = $sessionModel;
    }

    public function createSessionToken($userId)
    {
        $token = bin2hex(random_bytes(32));
        $expiration = time() + self::SESSION_TOKEN_EXPIRATION;

        $sessionData = [
            'user_id' => $userId,
            'token' => $token,
            'expired_at' => $expiration,
        ];

        $this->sessionModel->create($sessionData);
        return $sessionData;
    }

    public function storeSessionTokenInCookie($token, $expiration)
    {
        setcookie(self::COOKIE_SESSION_TOKEN, $token, $expiration);
    }

    public function validateSessionToken($token)
    {
        $sessionData = $this->sessionModel->get([$this->sessionModel->getColumnToken() => $token]);

        if ($sessionData && $sessionData['expired_at'] > time()) {
            return $this->sessionModel->getUserByToken($token);
        }

        return false;
    }

    public function removeSessionToken()
    {
        $this->sessionModel->delete([
            $this->sessionModel->getColumnToken() => $_COOKIE[self::COOKIE_SESSION_TOKEN]
        ]);
        setcookie(self::COOKIE_SESSION_TOKEN, "", time() - self::SESSION_TOKEN_EXPIRATION);
    }
}
