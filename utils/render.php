<?php

function renderCategories($cssForCategoryName = null, $showImages = false, $cssForImg = null, $cssForContainer = null)
{
    global $categories, $root_directory;
    include("./views/components/categories.php");
}

function renderMediumBanner($mainTitle, $subTitle = null, $buttonText = null, $buttonUrl = "", $backgroundImage, $center = false)
{
    include("./views/components/medium_banner.php");
}

function renderMiniBanner($mainTitle, $subTitle = null, $redirectUrl, $imageUrl)
{
    include("./views/components/mini_banner.php");
}

function renderLinkButton($link_button_name, $url)
{
    include("./views/components/link_button.php");
}

function renderPaginatedTable($attributes_data, $fetched_data, $update_submission_file_path = null, $edit_btn_class = null, $delete_submission_file_path = null, $delete_btn_class = null, $attribute_to_confirm_deletion = null, $extra_info = [])
{
    global $root_directory;
    include("./views/components/paginated_record_table.php");
}

function renderDashboardHeader(string $title_name, string $create_btn_desc = '', string $create_user_btn_class = '', string $submission_path = '', array $extra_info = [])
{
    global $root_directory;
    include("./views/components/admin_dashboard_header.php");
}

function renderProductCard(array $product, bool $is_popular)
{
    global $root_directory;

    // Parse available_variant_ids
    $available_variant_ids = isset($product['available_variant_ids']) 
    ? explode(',', $product['available_variant_ids']) 
    : [];

    $available_variant_id = null;
    foreach ($available_variant_ids as $index => $id) {
        if (isset($product['variant_qty'][$index]) && $product['variant_qty'][$index] > 0) $available_variant_id = $id;
    }
    
    include("./views/components/product_card.php");
}

function renderHeroSection(string $title, string $img)
{
    global $root_directory;
    include("./views/components/hero.php");
}

function renderSpecsToggleBox(string $title, $details, string $data_toggle_attr, array $extra_info = [])
{
    include("./views/components/specification_toggle_box.php");
}

function renderDataSummaryCard(string $title, $value, $show_dollar_sign = false)
{
    include("./views/components/data_summary_card.php");
}

function renderRecentlyViewedSection()
{
    global $root_directory, $browsing_history_service;

    $products = [];

    foreach ($browsing_history_service->getViewedItems() as $product_id) {
        global $product_model;

        // Pass $products by reference
        $result = ErrorHandler::handle(function () use ($product_id, $product_model, &$products) {
            $product = $product_model->get(
                select: [
                    ["column" => $product_model->getColumnId()],
                    ["column" => $product_model->getColumnName()],
                    ["column" => $product_model->getColumnImg()]
                ],
                conditions: [
                    $product_model->getColumnId() => $product_id
                ]
            );

            if ($product && is_array($product)) {
                // var_dump can be useful for debugging            // Add the product to the array by reference
                $products[] = $product;
            }
        });
    }

    require("./views/components/recently_viewed_container.php");
}
?>