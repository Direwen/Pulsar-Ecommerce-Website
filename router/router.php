<?php

require 'middlewares.php';
// Load routes configuration
$routes = require 'routes.php';

// Process route
routeToController(getCurrentUri(), $routes);

/**
 * Get the current request URI, stripping out the root directory.
 *
 * @return string The cleaned URI path
 */
function getCurrentUri()
{
    global $root_directory;
    $uri = $_SERVER["REQUEST_URI"];
    $cleaned_uri = str_replace($root_directory, "", $uri);
    $cleaned_uri = parse_url($cleaned_uri, PHP_URL_PATH);
    
    return trim($cleaned_uri, '/');
}

/**
 * Route the current URI to the appropriate controller.
 *
 * @param string $uri The cleaned URI
 * @param array $routes Array of routes mapped to controllers
 */
function routeToController($uri, $routes)
{

    if (array_key_exists($uri, $routes)) {
        // Check if this is an API route
        if (!empty($routes[$uri]['isApi'])) {
            // Set header for JSON response
            header('Content-Type: application/json');
        }

        // Call the middleware function
        foreach ($routes[$uri]['middleware'] as $middleware) call_user_func($middleware . 'Middleware');
        
        // Render the script or page from controller
        global $DB_METADATA, $mail_service, $browsing_history_service, $root_directory, $error_handler, $user_model, $category_model, 
        $product_model, $variant_model, $inventory_model, $order_model, $order_variant_model, $address_model, 
        $discount_model, $support_model, $review_model, $event_model, $event_product_model, $auth_service;

        require $routes[$uri]['controller'];
    } else {
        abort(404);
    }
}

/**
 * Handle errors by setting the HTTP response code and routing to the 404 controller.
 *
 * @param int $code HTTP response code (default is 404)
 */
function abort($code = 404)
{
    http_response_code($code);
    require 'controllers/404.php';
}
