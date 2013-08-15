<?php
require_once("../../connector.ini.php");
$q = $_GET["q"];
if (!$q) return;
$imei = array();
$i= 0;
$sql = "SELECT * FROM npp_testimei WHERE imei LIKE '".$q."%'";
$query = $db -> query($sql);
while($result = $db -> fetch_array($query))
{
	$imei[$i] = $result['imei'];
	$i++;
}
$db -> close();
if(sizeof($imei))
{
	foreach($imei as $value)
	{
		echo "$value\n";
	}
}
else
{
	echo "此imei号未导入!";
}
?>