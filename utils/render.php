<?php

function renderCategories($cssForCategoryName=null, $showImages=false, $cssForImg=null, $cssForContainer=null)
{
    global $categories;
    include("./views/components/categories.php");
}

function renderMediumBanner($mainTitle, $subTitle=null, $buttonText, $buttonUrl, $backgroundImage, $center=false) 
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

function renderPaginatedTable($attributes_data, $fetched_data, $update_submission_file_path = null, $edit_btn_class = null, $delete_submission_file_path = null, $delete_btn_class = null, $attribute_to_confirm_deletion=null)
{
    global $root_directory;
    include("./views/components/paginated_record_table.php");
}

function renderDashboardHeader(String $title_name, String $create_btn_desc, String $create_user_btn_class, String $submission_path, array $extra_info = [])
{
    global $root_directory;
    include("./views/components/admin_dashboard_header.php");
}
?>