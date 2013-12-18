<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 8/11/13
 * Time: 9:54 AM
 * To change this template use File | Settings | File Templates.
 */

use classes\core\helpers\Request;
use classes\core\helpers\SessionHelper;
use classes\core\helpers\UserdataHelper;

ini_set('display_errors', 1);
include 'cgi-bin/start_of_page.php';
include 'cgi-bin/submitPost.php';

// perform required includes
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './forum/';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
/** @noinspection PhpIncludeInspection */
include($phpbb_root_path . 'common.' . $phpEx);
/** @noinspection PhpIncludeInspection */
include($phpbb_root_path . 'includes/functions_display.' . $phpEx);
/** @noinspection PhpIncludeInspection */
include($phpbb_root_path . 'includes/functions_posting.' . $phpEx);
/** @noinspection PhpIncludeInspection */
include($phpbb_root_path . 'includes/message_parser.' . $phpEx);
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
$page_title = "";
$top_image = "";
$page_content = "";
$java_script = "";
$extra_headers = "";
$template_name = 'main_ww4.tpl';
$contentHeader = "";

require_once('user_panel.php');
include 'menu_bar.php';
include 'menu_bar_player_content.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'list':
            include 'includes/request_list.php';
            break;
        case 'create':
            include 'includes/request_create.php';
            break;
        case 'view':
            include 'includes/request_view.php';
            break;
        case 'edit':
            include 'includes/request_edit.php';
            break;
        case 'delete':
            include 'includes/request_delete.php';
            break;
        case 'add_note':
            include 'includes/request_add_note.php';
            break;
        case 'add_character':
            include 'includes/request_add_character.php';
            break;
        case 'character_search':
            include 'includes/request_character_search.php';
            break;
        case 'attach_request':
            include 'includes/request_attach_request.php';
            break;
        case 'attach_bluebook':
            include 'includes/request_attach_bluebook.php';
            break;
        case 'st_list':
            if(UserdataHelper::IsSt($userdata))
            {
                include 'includes/request_st_list.php';
            }
            else
            {
                include 'includes/index_redirect.php';
            }
            break;
        case 'st_view':
            if(UserdataHelper::IsSt($userdata))
            {
                include 'includes/request_st_view.php';
            }
            else
            {
                include 'includes/index_redirect.php';
            }
            break;
        case 'st_approve':
            if(UserdataHelper::IsSt($userdata))
            {
                include 'includes/request_st_approve.php';
            }
            else
            {
                include 'includes/index_redirect.php';
            }
            break;
        case 'st_return':
            if(UserdataHelper::IsSt($userdata))
            {
                include 'includes/request_st_return.php';
            }
            else
            {
                include 'includes/index_redirect.php';
            }
            break;
        case 'st_deny':
            if(UserdataHelper::IsSt($userdata))
            {
                include 'includes/request_st_deny.php';
            }
            else
            {
                include 'includes/index_redirect.php';
            }
            break;
        case 'submit':
            include 'includes/request_submit.php';
            break;
        case 'close':
            include 'includes/request_close.php';
            break;
        case 'update_request_character':
            include 'includes/request_update_request_character.php';
            break;
        default:
            include 'includes/index_redirect.php';
            break;
    }
}

$template->set_custom_template('templates', 'main_ww4');

$template->assign_vars(array(
        "PAGE_TITLE" => $page_title,
        "JAVA_SCRIPT" => $java_script,
        "TOP_IMAGE" => $page_image,
        "MENU_BAR" => $menu_bar,
        "PAGE_CONTENT" => $page_content,
        "EXTRA_HEADERS" => $extra_headers,
        "USER_PANEL" => $user_panel,
        "CONTENT_HEADER" => $contentHeader,
        "FLASH_MESSAGE" => SessionHelper::GetFlashMessage()
    )
);

if(Request::IsAjax())
{
    $template_name = 'main_ww4.tpl';
}
// initialize template
$template->set_filenames(array(
        'body' => $template_name)
);
$template->display('body');

