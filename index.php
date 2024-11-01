<?php

session_start();


require_once './services/mailService.php';
require_once './services/authService.php';
require_once './services/tokenService.php';
require_once './services/sessionService.php';
require_once './services/otpService.php';

require_once './utils/database.php';
require_once './utils/errorHandler.php';
require_once './utils/imageHandler.php';
require_once './utils/messageHandler.php';

require_once './models/userModel.php';
require_once './models/sessionModel.php';
require_once './models/categoryModel.php';
require_once './models/productModel.php';
require_once './models/variantModel.php';
require_once './models/inventoryModel.php';

$error_handler = null; 
$user_model = null; 
$session_model = null;
$category_model = null;
$product_model = null; 
$variant_model = null;
$inventory_model = null; 
$DB_METADATA = null;

$root_directory = "/E-Commerce%20Assignment%20Project/";
$pdo = ErrorHandler::handle(fn() => Database::getInstance());
$error_handler = ErrorHandler::getInstance($pdo);

$result = $error_handler->handleDbOperation(function () use ($pdo) {

    global $user_model, $session_model, $category_model, $product_model, $variant_model, $inventory_model, $DB_METADATA;
    $user_model = new UserModel($pdo);
    $session_model = new SessionModel($pdo);
    $category_model = new CategoryModel($pdo);
    $product_model = new ProductModel($pdo);
    $variant_model = new VariantModel($pdo);
    $inventory_model = new InventoryModel($pdo);
    $DB_METADATA = [
        UserModel::getTableName() => $user_model->getColumnMetadata(),
        SessionModel::getTableName() => $session_model->getColumnMetadata(),
        CategoryModel::getTableName() => $category_model->getColumnMetadata(),
        ProductModel::getTableName() => $product_model->getColumnMetadata(),
        VariantModel::getTableName() => $variant_model->getColumnMetadata(),
        InventoryModel::getTableName() => $inventory_model->getColumnMetadata(),
    ];

    return true;
});

//INITIALIZING SERVICES
$mail_service = MailService::getInstance();
$otp_service = new OtpService();
$token_service = new TokenService($session_model);
$session_service = new SessionService($token_service);
$auth_service = new AuthService($mail_service, $otp_service, $session_service, $token_service, $user_model);

// echo "<pre>";
// echo "diddy";
// echo "</pre>";
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
$categories = [
    ['name' => 'Mice', 'link' => null, 'image' => $root_directory . "assets/mouse_pad.webp"],
    ['name' => 'Mouse Accessories', 'link' => null, 'image' => null],
    ['name' => 'Superglides', 'link' => null, 'image' => null],
    ['name' => 'SUPPORT', 'link' => null, 'image' => null],
    ['name' => 'WHERE TO BUY', 'link' => null, 'image' => null],
    ['name' => 'eSports', 'link' => null, 'image' => null],
    ['name' => 'RELEASE', 'link' => null, 'image' => null],
    ['name' => 'PULSAR BY YOU', 'link' => null, 'image' => null]
];

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