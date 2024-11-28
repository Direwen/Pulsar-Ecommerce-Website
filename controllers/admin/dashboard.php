<?php

// Set default to 'user-management' if no dashboard has been selected
if (!isset($_SESSION['selected_dashboard'])) {
    $_SESSION['selected_dashboard'] = 'user-management';
}

// Handle changes to the dashboard via query parameter
if (isset($_GET['view'])) {
    $_SESSION['selected_dashboard'] = $_GET['view'];
}

// Get the current page number from the query parameters for pagination
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

// Add handling for search parameters
$search_attribute = isset($_GET['search_attribute']) ? $_GET['search_attribute'] : null;
$record_search = isset($_GET['record_search']) ? $_GET['record_search'] : null;
$record_search_end_date = isset($_GET['record_search_end_date']) ? $_GET['record_search_end_date'] : null;

function getSearchConditions($search_attribute, $record_search, $record_search_end_date)
{
    $search_conditions = [];

    // Only add conditions if $search_attribute is not null
    if ($search_attribute !== null) {

        // Check if record_search is not null or both start and end dates are not null
        if ($record_search !== null) {
            $search_conditions[] = [
                'attribute' => $search_attribute,
                'value' => $record_search,
                'operator' => 'LIKE' // Specify the operator you want to use
            ];

            return $search_conditions;
        }

        if ($record_search_end_date !== null) {


            $search_conditions[] = [
                'attribute' => $search_attribute,
                'value' => $record_search_end_date,
                'operator' => '<='
            ];

            return $search_conditions;
        }
    }

    return $search_conditions; // Return empty array if no valid conditions
}

?>

<div class="relative py-24 sm:py-20 md:py-24">

    <?php

    // This will be responsible for choosing what management dashboard to render
    switch ($_SESSION['selected_dashboard']) {

        case 'user-management':
            // Fetch data for the current page from the model
            $db_data = ErrorHandler::handle(fn() => $user_model->getAll(
                page: $page,
                select: [
                    ["column" => $user_model->getColumnId()],
                    ["column" => $user_model->getColumnEmail()],
                    ["column" => $user_model->getColumnLastLoggedInAt()],
                    ["column" => $user_model->getColumnIsActive()],
                    ["column" => $user_model->getColumnRole()],
                ],
                conditions: getSearchConditions($search_attribute, $record_search, $record_search_end_date)
            ));

            // Render the create button
            renderDashboardHeader(
                title_name: "Users Management",
                create_btn_desc: "a new user",
                create_user_btn_class: "create-user-button",
                submission_path: "admin/users/create"
            );
            // Render the paginated table to display records
            renderPaginatedTable(
                attributes_data: $DB_METADATA[UserModel::getTableName()],
                fetched_data: $db_data,
                update_submission_file_path: "admin/users/update",
                edit_btn_class: "edit-user-button",
                delete_submission_file_path: "admin/users/delete",
                delete_btn_class: "delete-user-button",
                attribute_to_confirm_deletion: "email"
            );
            break;

        case 'category-management':
            $db_data = ErrorHandler::handle(fn() => $category_model->getAll(
                page: $page,
                conditions: getSearchConditions($search_attribute, $record_search, $record_search_end_date)
            ));

            // Render the create button
            renderDashboardHeader(
                title_name: "Category Management",
                create_btn_desc: "a new category",
                create_user_btn_class: "create-category-button",
                submission_path: "admin/categories/create"
            );

            renderPaginatedTable(
                attributes_data: $DB_METADATA,
                fetched_data: $db_data,
                update_submission_file_path: "admin/categories/update",
                edit_btn_class: "edit-category-button",
                delete_submission_file_path: "admin/categories/delete",
                delete_btn_class: "delete-category-button",
                attribute_to_confirm_deletion: "name"
            );
            break;

        case 'product-management':

            $db_data = ErrorHandler::handle(fn() => $variant_model->getAll(
                page: $page,
                select: [
                    ["column" => $variant_model->getColumnId()],
                    ["column" => $product_model->getColumnName(), "alias" => "Product", "table" => $product_model->getTableName()],
                    ["column" => $variant_model->getColumnType(), "alias" => "Variant_Type"],
                    ["column" => $variant_model->getColumnName(), "alias" => "variant_type_name"],
                    ["column" => $variant_model->getColumnUnitPrice()],
                    ["column" => $variant_model->getColumnImg(), "alias" => "Variant_Image"],
                    ["column" => $variant_model->getColumnImgForAds(), "alias" => "variant_ads_image"],
                    ["column" => $variant_model->getColumnProductId()],

                    ["column" => $category_model->getColumnName(), "alias" => "Category", "table" => $category_model->getTableName()],

                    ["column" => $product_model->getColumnCategoryId(), "alias" => "Category_Id", "table" => $product_model->getTableName()],
                    ["column" => $product_model->getColumnDescription(), "alias" => "Description", "table" => $product_model->getTableName()],
                    ["column" => $product_model->getColumnDimension(), "alias" => "Dimension", "table" => $product_model->getTableName()],
                    ["column" => $product_model->getColumnFeature(), "alias" => "Feature", "table" => $product_model->getTableName()],
                    ["column" => $product_model->getColumnImportantFeature(), "alias" => "Specials", "table" => $product_model->getTableName()],
                    ["column" => $product_model->getColumnRequirement(), "alias" => "Requirement", "table" => $product_model->getTableName()],
                    ["column" => $product_model->getColumnPackageContent(), "alias" => "Package_Content", "table" => $product_model->getTableName()],
                    ["column" => $product_model->getColumnImg(), "alias" => "Product_Image", "table" => $product_model->getTableName()],
                    ["column" => $product_model->getColumnImgForAds(), "alias" => "Ads_Image", "table" => $product_model->getTableName()],
                    ["column" => $product_model->getColumnViews(), "alias" => "views_count", "table" => $product_model->getTableName()],
                ],
                joins: [
                    [
                        'type' => 'INNER JOIN',
                        'table' => $product_model->getTableName(),
                        'on' => "variants.product_id = products.id",
                    ],
                    [
                        'type' => 'INNER JOIN',
                        'table' => $category_model->getTableName(),
                        'on' => "products.category_id = categories.id",
                    ]
                ],
                conditions: getSearchConditions($search_attribute, $record_search, $record_search_end_date)
            ));

            renderDashboardHeader(
                title_name: "Product Management",
                create_btn_desc: "a new product",
                create_user_btn_class: "create-product-button",
                submission_path: "admin/products/create",
                extra_info: [
                    "api-for-categories" => $root_directory . 'api/categories',
                    "api-for-products" => $root_directory . 'api/products'
                ]
            );

            renderPaginatedTable(
                attributes_data: $DB_METADATA,
                fetched_data: $db_data,
                update_submission_file_path: "admin/products/update",
                edit_btn_class: "edit-product-button",
                delete_submission_file_path: "admin/products/delete",
                delete_btn_class: "delete-product-button",
                attribute_to_confirm_deletion: "variant_type_name",
                extra_info: [
                    "path-for-api" => $root_directory . 'api/categories'
                ]
            );

            break;

        case 'inventory-management':

            $db_data = ErrorHandler::handle( fn () => $inventory_model->getAll(
                page: $page,
                select: [
                    ["column" => $inventory_model->getColumnId()],
                    ["column" => $inventory_model->getColumnCode()],
                    ["column" => $variant_model->getColumnName(), "alias" => "variant", "table" => $variant_model->getTableName()],
                    ["column" => $variant_model->getColumnId(), "alias" => "variant_id", "table" => $variant_model->getTableName()],
                    ["column" => $product_model->getColumnName(), "alias" => "product", "table" => $product_model->getTableName()],
                    ["column" => $inventory_model->getColumnStockQuantity()],
                ],
                joins: [
                    [
                        'type' => 'INNER JOIN',
                        'table' => $variant_model->getTableName(),
                        'on' => "inventories.variant_id = variants.id",
                    ],
                    [
                        'type' => 'INNER JOIN',
                        'table' => $product_model->getTableName(),
                        'on' => "variants.product_id = products.id",
                    ]
                ],
                conditions: getSearchConditions($search_attribute, $record_search, $record_search_end_date)
            ));
            
            renderDashboardHeader(
                title_name: "Inventory Management",
                create_btn_desc: "a new inventory",
                create_user_btn_class: "create-inventory-button",
                submission_path: "admin/inventories/create",
                extra_info: [
                    "api-for-variants" => $root_directory . 'api/variants'
                ]
            );

            renderPaginatedTable(
                attributes_data: $DB_METADATA,
                fetched_data: $db_data,
                update_submission_file_path: "admin/inventories/update",
                edit_btn_class: "edit-inventory-button",
                delete_submission_file_path: "admin/inventories/delete",
                delete_btn_class: "delete-inventory-button",
                attribute_to_confirm_deletion: "code",
                extra_info: [
                    "api-for-variants" => $root_directory . 'api/variants'
                ]
            );

            break;

        case 'orders-management':

            $db_data = ErrorHandler::handle( fn () => $order_model->getAll(
                page: $page,
                select: [
                    ["column" => $order_model->getColumnId()],
                    ["column" => $user_model->getColumnEmail(), "alias" => "email", "table" => $user_model->getTableName()],
                    ["column" => $order_model->getColumnOrderCode()],
                    ["column" => $order_model->getColumnStatus()],
                    ["column" => $order_model->getColumnTotalPrice()],
                    ["column" => $discount_model->getColumnCode(), "alias" => "used_discount_code", "table" => $discount_model->getTableName()],
                    
                ],
                joins: [
                    [
                        'type' => 'INNER JOIN',
                        'table' => $user_model->getTableName(),
                        'on' => "orders.user_id = users.id",
                    ],
                    [
                        'type' => 'LEFT JOIN',
                        'table' => $discount_model->getTableName(),
                        'on' => "orders.used_discount_id = discounts.id",
                    ]
                ],
                conditions: getSearchConditions($search_attribute, $record_search, $record_search_end_date)
            ));
            
            renderDashboardHeader(
                title_name: "Order Management",
            );

            renderPaginatedTable(
                attributes_data: $DB_METADATA,
                fetched_data: $db_data,
                update_submission_file_path: "admin/orders/update",
                edit_btn_class: "edit-order-button",
                delete_submission_file_path: "admin/orders/delete",
                delete_btn_class: "delete-order-button",
                attribute_to_confirm_deletion: "order_code",
            );

            break;

        case 'discount-management':

            $db_data = ErrorHandler::handle(fn() => $discount_model->getAll(
                page: $page,
                conditions: getSearchConditions($search_attribute, $record_search, $record_search_end_date)
            ));

            renderDashboardHeader(
                title_name: "Discount Management",
                create_btn_desc: "a new discount",
                create_user_btn_class: "create-discount-button",
                submission_path: "admin/discounts/create",
            );

            renderPaginatedTable(
                attributes_data: $DB_METADATA,
                fetched_data: $db_data,
                update_submission_file_path: "admin/discounts/update",
                edit_btn_class: "edit-discount-button",
                delete_submission_file_path: "admin/discounts/delete",
                delete_btn_class: "delete-discount-button",
                attribute_to_confirm_deletion: "code",
            );

            break;

        case 'analytics':

            $total_sales = $order_model->getTotalSales();
            $total_orders = $order_model->getTotalOrders();
            $pending_orders_count = $order_model->getPendingOrdersCount();
            $total_products_sold = $order_variant_model->getTotalProductsSold();
            $total_refunds = $order_model->getTotalRefunds();
            $total_active_users = $user_model->getTotalActiveUsers();
            require ('./views/admin/data_analytics.view.php');
            break;

        case 'mail':

            $db_data = ErrorHandler::handle(fn () => $support_model->getAll(
                select: [
                    ["column" => $support_model->getColumnId()],
                    ["column" => $support_model->getColumnUserEmail()],
                    ["column" => $support_model->getColumnSubject()],
                    ["column" => $support_model->getColumnMessage()],
                    ["column" => $support_model->getColumnStatus()],
                    ["column" => $support_model->getColumnCreatedAt()],
                ],
                sortField: $support_model->getColumnCreatedAt(),
                sortDirection: "DESC",
                page: $page
            ));

            renderDashboardHeader(
                title_name: "Support Tickets",
            );

            renderPaginatedTable(
                attributes_data: $DB_METADATA,
                fetched_data: $db_data,
                update_submission_file_path: "admin/support-ticket/reply",
                edit_btn_class: "edit-support-button"
            );

            break;

        case 'event-management':

            renderDashboardHeader(
                title_name: "Event Management",
            );


            include("./views/components/under_development_notice.php");

            // $db_data = ErrorHandler::handle(fn () => $event_model->getAll(
            //     select: [
            //         ["column" => $event_model->getColumnId()],
            //         ["column" => $event_model->getColumnCode()],
            //         ["column" => $event_model->getColumnName()],
            //         ["column" => $event_model->getColumnDiscount()],
            //         ["column" => $event_model->getColumnDescription()],
            //         ["column" => $event_model->getColumnBannerImg()],
            //         ["column" => $event_model->getColumnStartAt()],
            //         ["column" => $event_model->getColumnEndAt()],
            //     ],
            //     sortField: $support_model->getColumnCreatedAt(),
            //     sortDirection: "DESC",
            //     page: $page
            // ));

            // renderDashboardHeader(
            //     title_name: "Event Management",
            //     create_btn_desc: "a new event",
            //     create_user_btn_class: "create-event-button",
            //     submission_path: "admin/events/create",
            //     extra_info: [
            //         "api-for-products" => $root_directory . 'api/products',
            //         'root-directory' => $root_directory
            //     ]
            // );

            // renderPaginatedTable(
            //     attributes_data: $DB_METADATA,
            //     fetched_data: $db_data,
            //     update_submission_file_path: "admin/events/update",
            //     edit_btn_class: "edit-event-button",
            //     delete_submission_file_path: "admin/events/delete",
            //     delete_btn_class: "delete-event-button",
            //     attribute_to_confirm_deletion: "code",
            //     extra_info: [
            //         "api-for-event-products" => $root_directory . 'api/event-products',
            //         "api-for-products" => $root_directory . 'api/products'
            //     ]
            // );

            break;

        default:
            echo "Invalid dashboard selection.";
            break;
    }

    ?>

    <!-- easy touch menu -->
    <?= include('./views/components/admin_easytouch.php'); ?>

</div>