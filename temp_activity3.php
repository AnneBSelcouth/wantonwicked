<?
include 'cgi-bin/dbconnect.php';

$query = <<<EOQ
select 
	City, 
	Character_Type, 
	Splat2, 
	count(character_id) as Num_of_Characters 
from 
	wod_characters 
where 
	is_sanctioned='y' 
	and is_npc='n' 
	and is_deleted='n'
group by 
	city, 
	character_type, 
	Splat2
EOQ;
$result = mysql_query($query) or die(mysql_error());

while($detail = mysql_fetch_array($result))
{
  echo "$detail[City] : $detail[Character_Type] : $detail[Splat2] : $detail[Num_of_Characters]<br>";
}
?>