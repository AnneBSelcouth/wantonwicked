<?php
include 'cgi-bin/start_of_page.php';

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './forum/';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
/** @noinspection PhpIncludeInspection */
include($phpbb_root_path . 'common.' . $phpEx);
/** @noinspection PhpIncludeInspection */
include($phpbb_root_path . 'includes/functions_display.' . $phpEx);
//
// Start session management
//

$user->session_begin();
$auth->acl($user->data);
$userdata = $user->data;
//
// End session management
//

// check page actions
$page_template = 'main_layout4.tpl';
$page_title = "";
$menu_bar = "";
$top_image = "";
$page_content = "";
$java_script = "";
$contentHeader = "";

include 'user_panel.php';
include 'menu_bar.php';

if ((!$userdata['is_asst']) && (!$userdata['is_gm']) && (!$userdata['is_head']) && (!$userdata['is_admin'])) {
    include 'includes/index_redirect.php';
}
else {
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'permissions_view':
                if ($userdata['is_head'] || $userdata['is_admin']) {
                    include 'includes/storyteller_permissions_view.php';
                }
                else {
                    include 'includes/storyteller_index.php';
                }
                break;

            case 'permissions_add':
                if ($userdata['is_head'] || $userdata['is_admin']) {
                    include 'includes/storyteller_permissions_add.php';
                }
                else {
                    include 'includes/storyteller_index.php';
                }
                break;

            case 'permissions':
                if ($userdata['is_head'] || $userdata['is_admin']) {
                    include 'includes/storyteller_permissions.php';
                }
                else {
                    include 'includes/storyteller_index.php';
                }
                break;

            case 'plot_list':
                include 'includes/storyteller_plot_list.php';
                break;

            case 'plot_types_admin':
                if ($userdata['is_head'] || $userdata['is_admin']) {
                    $page_template = "blank_layout.tpl";
                    include 'includes/storyteller_plot_types_admin.php';
                }
                else {
                    include 'includes/storyteller_index.php';
                }
                break;

            case 'plot_add':
                include 'includes/storyteller_plot_add.php';
                break;

            case 'plot_view':
                include 'includes/storyteller_plot_view.php';
                break;

            case 'plot_search':
                include 'includes/storyteller_plot_search.php';
                break;

            case 'plot_events':
                $page_template = "blank_layout.tpl";
                include 'includes/storyteller_plot_events.php';
                break;

            case 'plot_events_add':
                $page_template = "blank_layout.tpl";
                include 'includes/storyteller_plot_events_add.php';
                break;

            case 'plot_events_view':
                $page_template = "blank_layout.tpl";
                include 'includes/storyteller_plot_events_view.php';
                break;

            case 'plot_characters':
                $page_template = "blank_layout.tpl";
                include 'includes/storyteller_plot_characters.php';
                break;

            case 'plot_characters_add':
                $page_template = "blank_layout.tpl";
                include 'includes/storyteller_plot_characters_add.php';
                break;

            case 'plot_characters_view':
                $page_template = "blank_layout.tpl";
                include 'includes/storyteller_plot_characters_view.php';
                break;

            case 'profile_lookup':
                include 'includes/storyteller_profile_lookup.php';
                break;

            default:
                include 'includes/storyteller_index.php';
                break;
        }
    }
    else {
        include 'includes/storyteller_index.php';
    }
}

$template->set_custom_template('templates', substr($page_template, 0, strlen($page_template) - 4));
$template->assign_vars(array(
        "PAGE_TITLE" => $page_title,
        "CSS_URL" => $css_url,
        "JAVA_SCRIPT" => $java_script,
        "USER_PANEL" => $user_panel,
        "MENU_BAR" => $menu_bar,
        "TOP_IMAGE" => $page_image,
        "PAGE_CONTENT" => $page_content,
        "EXTRA_TAGS" => $extra_tags,
        "CONTENT_HEADER" => $contentHeader
    )
);


// initialize template
$template->set_filenames(array(
        'body' => $page_template)
);
$template->display('body');