<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');

/* $url=$_SERVER["REQUEST_URI"];
$url=parse_url($url);
$url=$url[path]; */

$ids = "";
$cpid = 0;
if(isset($_GET["ids"])) {
	$ids = $_GET["ids"];
}

if(isset($_GET["app_id"])) {
	$app_id = $_GET["app_id"];
}
query_weight($bid, $eid);
exchange_weight($bid, $eid);
echo "<script language='javascript' type='text/javascript'>";
echo "window.location.href=\"payment_detail.php?app_id=".$app_id."\"";
echo "</script>";

function delete($ids) {
	global $db;
	$sql = "delete * FROM NokiaPaymentPlat.npp_app_paymentmethod_match where id in ( ".$ids." )";
	$result = $db->query($sql);
	echo "<script>alert('删除成功！');</script>";
}

?>
