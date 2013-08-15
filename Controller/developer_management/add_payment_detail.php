<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');

/* $url=$_SERVER["REQUEST_URI"];
$url=parse_url($url);
$url=$url[path]; */

$channel_id = "";
$action = "";
$resultlist = "";
if(isset($_GET["app_id"])&&$_GET["app_id"]!="")
{
	$app_id = $_GET["app_id"];
}

if(isset($_GET["kind"])&&$_GET["kind"]!="")
{
	$kind = $_GET["kind"];
}
if(isset($_GET["support"])&&$_GET["support"]!="")
{
	$support = $_GET["support"];
}

if(isset($_GET["rate"])&&$_GET["rate"]!="")
{
	$rate = $_GET["rate"];
}


$sql = "select DISTINCT t.id ,t.name from NokiaPaymentPlat.nppadmin_paymentmethod m, NokiaPaymentPlat.npp_payment_type t where m.kind = t.id and m.channel_id =".$channel_id;
$result = $db->query($sql);
while($out_app=$db->fetch_array($result)) {
	$resultlist .= $out_app['name'];
	$resultlist .= ",";
}
$resultlist .= "-";	
$sql2 = "select DISTINCT s.id ,s.name from NokiaPaymentPlat.nppadmin_paymentmethod m, NokiaPaymentPlat.npp_payment_source s where m.support = s.id and m.channel_id =".$channel_id;
$result2 = $db->query($sql2);
while($out_app=$db->fetch_array($result2)) {
	$resultlist .= $out_app['name'];
	$resultlist .= ",";
}

?>
</body>
</html>