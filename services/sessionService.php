<?php

class SessionService
{

    private $tokenService;

    public function __construct($tokenService)
    {
        $this->tokenService = $tokenService;
    }

    public function isSessionActive()
    {
        return $_SESSION['is_active'] ?? false;
    }

    public function initializeSession($sessionData)
    {
        $_SESSION['user_id'] = $sessionData['id'];
        $_SESSION['user_email'] = $sessionData['email'];
        $_SESSION['user_role'] = $sessionData['role'];
        $_SESSION['is_active'] = true;
    }

    public function clearSession()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_role']);
        $_SESSION['is_active'] = false;
    }

    public function loadSessionFromToken($token)
    {
        if ($this->isSessionActive()) {
            return;
        }

        $sessionData = $this->validateToken($token);
        if ($sessionData) {
            $this->initializeSession($sessionData);
        } else {
            $this->clearSession();
        }
    }

    private function validateToken($token)
    {
        return $this->tokenService->validateSessionToken($token);
    }
}
