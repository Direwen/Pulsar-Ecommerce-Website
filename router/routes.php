<?php

return [
    '' => [
        'controller' => 'controllers/home.php',
        'middleware' => [],
    ],
    'login' => [
        'controller' => 'controllers/auth/login.php',
        'middleware' => ['guest'],
    ],
    'logout' => [
        'controller' => 'controllers/auth/logout.php',
        'middleware' => ['auth'],
    ],
    'otp' => [
        'controller' => 'controllers/auth/otp.php',
        'middleware' => ['guest', 'otp'],
    ],
    'admin/dashboard' => [
        'controller' => 'controllers/admin/dashboard.php',
        'middleware' => []
    ],
    'admin/users/update' => [
        'controller' => 'controllers/admin/user_management/update.php',
        'middleware' => []
    ],
    'admin/users/delete' => [
        'controller' => 'controllers/admin/user_management/delete.php',
        'middleware' => []
    ],
    'admin/users/create' => [
        'controller' => 'controllers/admin/user_management/create.php',
        'middleware' => []
    ],
    '404' => [
        'controller' => 'controllers/404.php',
        'middleware' => [],
    ],
    'test' => [
        'controller' => 'controllers/test.php',
        'middleware' => []
    ]
];
