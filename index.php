<?php

session_start();


require_once './utils/database.php';
require_once './services/mailService.php';
require_once './services/authService.php';
require_once './services/tokenService.php';
require_once './services/sessionService.php';
require_once './services/otpService.php';
require_once './utils/errorHandler.php';
require_once './models/userModel.php';
require_once './models/sessionModel.php';
require_once './models/categoryModel.php';
require_once './models/productModel.php';
require_once './models/variantModel.php';
require_once './models/inventoryModel.php';


$pdo = ErrorHandler::handle(fn() => Database::getInstance());

//Initializing Database Tables' Models
$user_model = ErrorHandler::handle(fn() => new UserModel($pdo));
$session_model = ErrorHandler::handle(fn() => new SessionModel($pdo));
$category_model = ErrorHandler::handle(fn() => new CategoryModel($pdo));
$product_model = ErrorHandler::handle(fn() => new ProductModel($pdo));
$variant_model = ErrorHandler::handle(fn() => new VariantModel($pdo));
$inventory_model = ErrorHandler::handle(fn() => new InventoryModel($pdo));

//INITIALIZING SERVICES
$mail_service = MailService::getInstance();
$otp_service = new OtpService();
$token_service = new TokenService($session_model);
$session_service = new SessionService($token_service);
$auth_service = new AuthService($mail_service, $otp_service, $session_service, $token_service, $user_model);
$DB_METADATA = ErrorHandler::handle(fn() => [
    UserModel::getTableName() => $user_model->getColumnMetadata(),
    SessionModel::getTableName() => $session_model->getColumnMetadata(),
    CategoryModel::getTableName() => $category_model->getColumnMetadata(),
    ProductModel::getTableName() => $product_model->getColumnMetadata(),
    VariantModel::getTableName() => $variant_model->getColumnMetadata(),
    InventoryModel::getTableName() => $inventory_model->getColumnMetadata(),
]);


// echo "<pre>";

// var_dump(ErrorHandler::handle(fn () => $variant_model->getAll(
//     select: [
//         ["column" => "variants.*"],
//         ["column" => "products.name", "alias" => "product"],
//         ["column" => "categories.name", "alias" => "category"],
//         ["column" => "inventories.inventory_id", "alias" => "inventory"],
//     ],
//     joins: [
//         [
//             'type' => 'INNER JOIN',
//             'table' => ProductModel::getTableName(),
//             'on' => "variants.product_id = products.id",
//         ],
//         [
//             'type' => 'INNER JOIN',
//             'table' => CategoryModel::getTableName(),
//             'on' => "products.category_id = categories.id",
//         ],
//         [
//             'type' => 'INNER JOIN',
//             'table' => InventoryModel::getTableName(),
//             'on' => "inventories.variant_id = variants.id",
//         ]
//     ],
//     conditions: [
//         [
//             'attribute' => 'products.name',
//             'value' => 'phone',
//             'operator' => 'LIKE'
//         ]
//     ]
// )));

// echo "</pre>";

// ErrorHandler::handle(fn () => $otp_service->clearOtpSession());



ErrorHandler::handle(fn() => $auth_service->maintainUserSession());
$website_title = "Pulsar";
$root_directory = "/E-Commerce%20Assignment%20Project/";
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