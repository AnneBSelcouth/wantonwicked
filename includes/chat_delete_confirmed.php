<?php
$page_title = "Character Deleted";
$contentHeader = $page_title;

// get character id
$character_id = $_POST['character_id'] + 0;

// get character information
$character_query = <<<EOQ
SELECT wod.Character_ID, Character_Name
FROM wod_characters as wod INNER JOIN login_character_index as lci ON wod.character_id = lci.character_id
WHERE lci.login_id = $userdata[user_id]
 AND wod.is_deleted = 'N'
 AND wod.character_id = $character_id;
EOQ;
$character = ExecuteQueryItem($character_query);

if($character)
{
  // get # of characters with the same name
  $temp_name = addslashes($character['Character_Name']);
  $id_query = "select * from wod_characters where character_name like '$temp_name%';";
  $id_result = mysql_query($id_query) or die(mysql_error());
  $id = mysql_num_rows($id_result);
  
  // mark the character as deleted
  $update_query = "update wod_characters set is_deleted='Y', character_name = '${temp_name}_$id' where character_id = $character_id;";
  //echo "$update_query<br>";
  $update_result = mysql_query($update_query) or die(mysql_error());
  
  $page_content = <<<EOQ
$character[Character_Name] has been deleted. This is a permanent action. It can not and will not be undone.<br>
<br>
<a href="$_SERVER[PHP_SELF]">Return to Chat Interface</a>
EOQ;
}