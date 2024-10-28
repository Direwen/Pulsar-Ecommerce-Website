<?php

global $auth_service;

$auth_service->logout();
header("Location: ./");