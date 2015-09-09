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

if(Database::GetInstance()->Query($sql)->Execute())
{
	echo "Successfully removed character";
	$sql = <<<EOQ
SELECT
	character_id
FROM
	characters_territories
WHERE
	id = ?
EOQ;
	$params = array(
		$id
	);
	$detail = Database::GetInstance()->Query($sql)->Single($params);
	
	$abp = new ABP();
	$abp->UpdateABP($detail['character_id']);
}
else
{
	echo "Error removing character.";
}
