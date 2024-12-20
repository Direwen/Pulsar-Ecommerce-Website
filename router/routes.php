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
    'disable' => [
        'controller' => 'controllers/auth/disable.php',
        'middleware' => ['auth'],
    ],
    'admin/dashboard' => [
        'controller' => 'controllers/admin/dashboard.php',
        'middleware' => ['auth', 'admin']
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
    'admin/support-ticket/reply' => [
        'controller' => 'controllers/admin/support_management/update.php',
        'middleware' => []
    ],
    'admin/events/create' => [
        'controller' => 'controllers/admin/event_management/create.php',
        'middleware' => ["development"]
    ],
    'admin/events/update' => [
        'controller' => 'controllers/admin/event_management/update.php',
        'middleware' => ["development"]
    ],
    'admin/events/delete' => [
        'controller' => 'controllers/admin/event_management/delete.php',
        'middleware' => ["development"]
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
        'middleware' => ['auth'],
    ],
    'cancel-order' => [
        'controller' => 'controllers/admin/order_management/update.php',
        'middleware' => ['auth'],
    ],
    'history' => [
        'controller' => 'controllers/ecommerce/history.php',
        'middleware' => ['auth'],
    ],
    'thank-you' => [
        'controller' => 'controllers/ecommerce/thank_you.php',
        'middleware' => ['auth', 'recentOrder'],
    ],
    'support' => [
        'controller' => 'controllers/ecommerce/support.php',
        'middleware' => [],
    ],
    'about' => [
        'controller' => 'controllers/ecommerce/about.php',
        'middleware' => [],
    ],
    '404' => [
        'controller' => 'controllers/404.php',
        'middleware' => [],
    ],
    'auth/terms-conditions' => [
        'controller' => 'controllers/terms_conditions/authentication.php',
        'middleware' => [],
    ],
    'api/categories' => [
        'controller' => 'controllers/api/get_categories.php',
        'middleware' => [],
        'isApi' => true
    ],
    'api/category' => [
        'controller' => 'controllers/api/get_category.php',
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
    'api/revenue-trend' => [
        'controller' => 'controllers/api/get_revenue_trend.php',
        'middleware' => [],
        'isApi' => true
    ],
    'api/order-status' => [
        'controller' => 'controllers/api/get_order_status_count.php',
        'middleware' => [],
        'isApi' => true
    ],
    'api/top-selling-products' => [
        'controller' => 'controllers/api/get_top_selling_products.php',
        'middleware' => [],
        'isApi' => true
    ],
    'api/ticket-submission' => [
        'controller' => 'controllers/api/submit_ticket.php',
        'middleware' => [],
        'isApi' => true
    ],
    'api/get-products-to-rate' => [
        'controller' => 'controllers/api/get_variants_to_rate.php',
        'middleware' => [],
        'isApi' => true
    ],
    'api/review-submission' => [
        'controller' => 'controllers/api/submit_review.php',
        'middleware' => [],
        'isApi' => true
    ],
    'api/review-later' => [
        'controller' => 'controllers/api/review_later.php',
        'middleware' => [],
        'isApi' => true
    ],
    'api/event-products' => [
        'controller' => 'controllers/api/get_event_products.php',
        'middleware' => [],
        'isApi' => true
    ],
    'api/unreplied-tickets' => [
        'controller' => 'controllers/api/get_unreplied_ticket_count.php',
        'middleware' => [],
        'isApi' => true
    ],
    'test' => [
        'controller' => 'controllers/test.php',
        'middleware' => []
    ]
];
