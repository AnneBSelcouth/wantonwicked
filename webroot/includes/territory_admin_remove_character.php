<?php
use classes\core\repository\Database;

$id = $_GET['id'] + 0;

$sql = <<<EOQ
UPDATE 
	characters_territories
SET
	is_active = 0,
	updated_by = $userdata[user_id],
	updated_on = now()
WHERE
	id = $id
EOQ;

if(Database::getInstance()->query($sql)->execute())
{
	echo "Successfully removed character";
}
else
{
	echo "Error removing character.";
}
