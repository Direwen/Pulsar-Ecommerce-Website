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
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

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

<div class="relative py-24 sm:py-20 md:py-16">

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

        case 'product-management':

            // $db_data = Errorhandler::handle(fn() => $variant_model->getAll(
            //     page: $page,
            //     select: [
            //         ["column" => "variants.id"],
            //         ["column" => "variants.name"],
            //         ["column" => "variants.unit_price"],
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
            //     conditions: getSearchConditions($search_attribute, $record_search, $record_search_end_date),
            // ));

            $db_data = ErrorHandler::handle(fn () => $category_model->getAll(
                page: $page
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

            renderDashboardHeader(
                title_name: "Product Management",
                create_btn_desc: "a new product",
                create_user_btn_class: "create-product-button",
                submission_path: "admin/products/create",
                extra_info: [
                    "path-for-api" => $root_directory . 'api/categories'
                ]
            );


            break;

        case 'orders-management':
            echo "Order management dashboard";
            // require('views/admin/orders-management.view.php');
            break;

        default:
            echo "Invalid dashboard selection.";
            break;
    }

    ?>

    <span
        id="draggable"
        class="material-symbols-outlined fixed bottom-40 right-5 z-50 flex w-fit h-fit px-3 py-2 cursor-pointer justify-center items-center rounded-full bg-accent/70 shadow hover:bg-accent text-primary transition-all duration-100 ease-in-out user-select-none touch-action-none"
        style="user-select: none; touch-action: none;">
        widgets
    </span>

</div>