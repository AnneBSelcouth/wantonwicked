<?
include 'cgi-bin/start_of_page.php';
// perform required includes
define('IN_PHPBB', true);
$phpbb_root_path = './forum/';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, WEBSITE);
init_userprefs($userdata);
//
// End session management
//

if(!$userdata['is_head'] && !$userdata['is_admin'])
{
	die();
}

// check page actions
$page_title = "Icon List";
$css_url = "www.wantonwicked.net/wicked.css";
$menu_bar = "";
$top_image = "";
$page_content = "";
$java_script = "";

include 'user_panel.php';
include 'menu_bar.php';

// test if updating/delete
if(isset($_POST['action']))
{
	if($_POST['action'] == 'update')
	{
		$skill_list = $_POST['delete'];
		while(list($key, $value) = each($skill_list))
		{
			//echo "delete: $key: $value<br>";
			$delete_query = "delete from icons where id=$value;";
			//echo $delete_query."<br>";
			$delete_result = mysql_query($delete_query);
		}
	}
}

$page_content = <<<EOQ
<script language="JavaScript">
function submitForm ( )
{
	window.document.icon_list.submit();
}
</script>
<form name="icon_list" id="icon_list" method="post" action="$_SERVER[PHP_SELF]">
<a href="icons_add.php" onClick="window.open('icons_add.php', 'addIcon', 'width=300,height=300,resizable,scrollbars');return false;">Add Icon</a>
&nbsp;&nbsp;&nbsp;&nbsp;
<a href="#" onclick="submitForm();">Delete Icon(s)</a>
<input type="hidden" name="action" id="action" value="update">
<br>
<table border="0" cellpadding="2" cellspacing="2" class="normal_text">
  <tr bgcolor="#000000">
    <th>
    </th>
    <th>
      Icon Name
    </th>
    <th>
      Icon ID
    </th>
    <th>
      Player Viewable
    </th>
    <th>
      ST Viewable
    </th>
    <th>
      Head ST Viewable
    </th>
  </tr>
EOQ;

$icon_query = "select * from icons where site_id = $userdata[site_id] order by Icon_Name";
$icon_result = mysql_query($icon_query) or die(mysql_error());;

$row = 0;
while ($icon = mysql_fetch_array($icon_result, MYSQL_ASSOC))
{
	$row_color = (($row++)%2) ? "#443a33" : "";
	$page_content .= <<<EOQ
	<tr bgcolor="$row_color">
	  <th>
	    <input type="checkbox" name="delete[]" id="delete[]" value="$icon[ID]">
	  <td>
	    <a href="#" onClick="window.open('icons_view.php?id=$icon[ID]', 'icon$icon[ID]', 'width=300,height=300,resizable,scrollbars');return false;">$icon[Icon_Name]</a>
	  </td>
	  <td>
	    $icon[Icon_ID]
	  </td>
	  <td>
	    $icon[Player_Viewable]
	  </td>
	  <td>
	    $icon[GM_Viewable]
	  </td>
	  <td>
	    $icon[Admin_Viewable]
	  </td>
	</tr>
EOQ;
}

$page_content .= "</table></form>";

$template->assign_vars(array(
"PAGE_TITLE" => $page_title,
"CSS_URL" => $css_url, 
"JAVA_SCRIPT" => $java_script,
"USER_PANEL" => $user_panel, 
"MENU_BAR" => $menu_bar, 
"TOP_IMAGE" => $page_content_image, 
"PAGE_CONTENT" => $page_content
)
);


// initialize template
$template->set_filenames(array(
		'body' => 'templates/main_layout.tpl')
);
$template->pparse('body');
?>