<?php

/**
 * Set session data for notifications.
 *
 * @param string $message The message to display.
 * @param string $state The state of the message (success, error, info).
 */
function setMessage($message, $state) {
    $_SESSION['message'] = $message;
    $_SESSION['message-state'] = $state;
}