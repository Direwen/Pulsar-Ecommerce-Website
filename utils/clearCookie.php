<?php

function clearCookie($name, $time_to_reduce) {
    setcookie($name, '', time() - time(), '/');
}