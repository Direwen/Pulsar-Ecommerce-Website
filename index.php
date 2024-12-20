<?php

session_start();


require_once './services/mailService.php';
require_once './services/authService.php';
require_once './services/tokenService.php';
require_once './services/sessionService.php';
require_once './services/otpService.php';
require_once './services/browsingHistoryService.php';

require_once './utils/database.php';
require_once './utils/errorHandler.php';
require_once './utils/imageHandler.php';
require_once './utils/messageHandler.php';
require_once './utils/clearCookie.php';
require_once './utils/generateRating.php';
require_once './utils/formatViewCount.php';
require_once './utils/dashboardUtils.php';


require_once './models/userModel.php';
require_once './models/sessionModel.php';
require_once './models/categoryModel.php';
require_once './models/productModel.php';
require_once './models/variantModel.php';
require_once './models/inventoryModel.php';
require_once './models/orderModel.php';
require_once './models/orderVariantModel.php';
require_once './models/addressModel.php';
require_once './models/discountModel.php';
require_once './models/supportTicketModel.php';
require_once './models/reviewModel.php';

$error_handler = null; 
$user_model = null; 
$session_model = null;
$category_model = null;
$product_model = null; 
$variant_model = null;
$inventory_model = null;
$address_model = null;
$discount_model = null;
$order_model = null; 
$order_variant_model = null;
$support_model = null;
$review_model = null;
$DB_METADATA = null;

$root_directory = "/E-Commerce%20Assignment%20Project/";
$pdo = ErrorHandler::handle(fn() => Database::getInstance());

// Check if database is connected
if (empty($pdo)) {

    include './views/server_error.view.php';

    exit();
}

$error_handler = ErrorHandler::getInstance($pdo);

$result = $error_handler->handleDbOperation(function () use ($pdo) {

    global $user_model, $session_model, $category_model, $product_model, $variant_model, $inventory_model, 
    $address_model, $discount_model, $order_model, $order_variant_model, $support_model, $review_model, $event_model, $event_product_model, $DB_METADATA;

    $user_model = new UserModel($pdo);
    $session_model = new SessionModel($pdo);
    $category_model = new CategoryModel($pdo);
    $product_model = new ProductModel($pdo);
    $variant_model = new VariantModel($pdo);
    $inventory_model = new InventoryModel($pdo);
    $discount_model = new DiscountModel($pdo);
    $address_model = new AddressModel($pdo);
    $order_model = new OrderModel($pdo);
    $order_variant_model = new OrderVariantModel($pdo);
    $support_model = new SupportTicketModel($pdo);
    $review_model = new ReviewModel($pdo);
    $DB_METADATA = [
        UserModel::getTableName() => $user_model->getColumnMetadata(),
        SessionModel::getTableName() => $session_model->getColumnMetadata(),
        CategoryModel::getTableName() => $category_model->getColumnMetadata(),
        ProductModel::getTableName() => $product_model->getColumnMetadata(),
        VariantModel::getTableName() => $variant_model->getColumnMetadata(),
        InventoryModel::getTableName() => $inventory_model->getColumnMetadata(),
        OrderModel::getTableName() => $order_model->getColumnMetadata(),
        OrderVariantModel::getTableName() => $order_variant_model->getColumnMetadata(),
        AddressModel::getTableName() => $address_model->getColumnMetadata(),
        DiscountModel::getTableName() => $discount_model->getColumnMetadata(),
        SupportTicketModel::getTableName() => $support_model->getColumnMetadata(),
        ReviewModel::getTableName() => $review_model->getColumnMetadata(),
    ];

    return true;
});

//INITIALIZING SERVICES
$mail_service = MailService::getInstance();
$otp_service = new OtpService();
$token_service = new TokenService($session_model);
$session_service = new SessionService($token_service);
$auth_service = new AuthService($mail_service, $otp_service, $session_service, $token_service, $user_model);
$browsing_history_service = new BrowsingHistoryService();

// ErrorHandler::handle(fn () => $otp_service->clearOtpSession());

// Function to check if the request is for an API route
function isApiRequest() {
    return strpos($_SERVER['REQUEST_URI'], '/api/') !== false;
}

// If it's an API request, only load the router and exit
if (isApiRequest()) {
    require("./router/router.php");
    exit;
}

ErrorHandler::handle(fn() => $auth_service->maintainUserSession());
$website_title = "Pulsar";
$categories = ErrorHandler::handle(fn () => $category_model->getEverything());

require("./utils/render.php");
require("./views/components/head.php");
require("./router/router.php");
require("./views/components/end.php");

?>

<div class="fixed hidden w-fit bottom-5 left-10 z-50 bg-secondary border-2 border-red-900 text-red-900 px-4 py-8 rounded">

    <h3>Session Data:</h3>
    <ul>
        <?php foreach ($_SESSION as $key => $value): ?>
            <li><?php echo htmlspecialchars($key) . " => " . htmlspecialchars($value); ?></li>
        <?php endforeach; ?>
    </ul>

    <h3>Cookie Data:</h3>
    <ul>
        <?php foreach ($_COOKIE as $key => $value): ?>
            <li><?php echo htmlspecialchars($key) . " => " . htmlspecialchars($value); ?></li>
        <?php endforeach; ?>
    </ul>

</div>