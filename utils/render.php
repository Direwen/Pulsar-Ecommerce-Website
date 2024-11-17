<?php

function renderCategories($cssForCategoryName=null, $showImages=false, $cssForImg=null, $cssForContainer=null)
{
    global $categories, $root_directory;
    include("./views/components/categories.php");
}

function renderMediumBanner($mainTitle, $subTitle=null, $buttonText=null, $buttonUrl="", $backgroundImage, $center=false) 
{
    include("./views/components/medium_banner.php");
}

function renderMiniBanner($mainTitle, $subTitle=null, $redirectUrl, $imageUrl) 
{
    include("./views/components/mini_banner.php");
}

function renderLinkButton($link_button_name, $url){
    include("./views/components/link_button.php");
}

function renderPaginatedTable($attributes_data, $fetched_data, $update_submission_file_path = null, $edit_btn_class = null, $delete_submission_file_path = null, $delete_btn_class = null, $attribute_to_confirm_deletion=null, $extra_info = [])
{
    global $root_directory;
    include("./views/components/paginated_record_table.php");
}

function renderDashboardHeader(String $title_name, String $create_btn_desc = '', String $create_user_btn_class = '', String $submission_path = '', array $extra_info = [])
{
    global $root_directory;
    include("./views/components/admin_dashboard_header.php");
}

function renderProductCard(array $product)
{
    global $root_directory;
    include("./views/components/product_card.php");
}

function renderHeroSection(String $title, String $img)
{
    global $root_directory;
    include("./views/components/hero.php");
}

function renderSpecsToggleBox(String $title, $details, String $data_toggle_attr, array $extra_info = [])
{
    include("./views/components/specification_toggle_box.php");
}

function renderDataSummaryCard(String $title, $value, $show_dollar_sign = false) {
    include("./views/components/data_summary_card.php");
}
?>