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
    'admin/users/create' => [
        'controller' => 'controllers/admin/user_management/create.php',
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
    'admin/categories/create' => [
        'controller' => 'controllers/admin/category_management/create.php',
        'middleware' => []
    ],
    'admin/categories/update' => [
        'controller' => 'controllers/admin/category_management/update.php',
        'middleware' => []
    ],
    'admin/categories/delete' => [
        'controller' => 'controllers/admin/category_management/delete.php',
        'middleware' => []
    ],
    'admin/products/create' => [
        'controller' => 'controllers/admin/product_management/create.php',
        'middleware' => []
    ],
    'admin/products/update' => [
        'controller' => 'controllers/admin/product_management/update.php',
        'middleware' => []
    ],
    'admin/products/delete' => [
        'controller' => 'controllers/admin/product_management/delete.php',
        'middleware' => []
    ],
    'admin/inventories/create' => [
        'controller' => 'controllers/admin/inventory_management/create.php',
        'middleware' => []
    ],
    'admin/inventories/update' => [
        'controller' => 'controllers/admin/inventory_management/update.php',
        'middleware' => []
    ],
    'admin/inventories/delete' => [
        'controller' => 'controllers/admin/inventory_management/delete.php',
        'middleware' => []
    ],
    'admin/orders/update' => [
        'controller' => 'controllers/admin/order_management/update.php',
        'middleware' => []
    ],
    'admin/orders/delete' => [
        'controller' => 'controllers/admin/order_management/delete.php',
        'middleware' => []
    ],
    'admin/discounts/create' => [
        'controller' => 'controllers/admin/discount_management/create.php',
        'middleware' => []
    ],
    'admin/discounts/update' => [
        'controller' => 'controllers/admin/discount_management/update.php',
        'middleware' => []
    ],
    'admin/discounts/delete' => [
        'controller' => 'controllers/admin/discount_management/delete.php',
        'middleware' => []
    ],
    'products' => [
        'controller' => 'controllers/ecommerce/products.php',
        'middleware' => [],
    ],
    'product/view' => [
        'controller' => 'controllers/ecommerce/product_details.php',
        'middleware' => [],
    ],
    'checkout' => [
        'controller' => 'controllers/ecommerce/checkout.php',
        'middleware' => ['auth', 'checkout'],
    ],
    'order' => [
        'controller' => 'controllers/ecommerce/order.php',
        'middleware' => [],
    ],
    'cancel-order' => [
        'controller' => 'controllers/admin/order_management/update.php',
        'middleware' => [],
    ],
    'history' => [
        'controller' => 'controllers/ecommerce/history.php',
        'middleware' => [],
    ],
    '404' => [
        'controller' => 'controllers/404.php',
        'middleware' => [],
    ],
    'api/categories' => [
        'controller' => 'controllers/api/get_categories.php',
        'middleware' => [],
        'isApi' => true
    ],
    'api/products' => [
        'controller' => 'controllers/api/get_products.php',
        'middleware' => [],
        'isApi' => true
    ],
    'api/variants' => [
        'controller' => 'controllers/api/get_variants.php',
        'middleware' => [],
        'isApi' => true
    ],
    'api/cart' => [
        'controller' => 'controllers/api/add_to_cart.php',
        'middleware' => [],
        'isApi' => true
    ],
    'api/cart-items' => [
        'controller' => 'controllers/api/get_cart_items.php',
        'middleware' => [],
        'isApi' => true
    ],
    'api/discount' => [
        'controller' => 'controllers/api/validate_discount.php',
        'middleware' => [],
        'isApi' => true
    ],
    'api/order-details' => [
        'controller' => 'controllers/api/get_order.php',
        'middleware' => [],
        'isApi' => true
    ],
    'test' => [
        'controller' => 'controllers/test.php',
        'middleware' => []
    ]
];
