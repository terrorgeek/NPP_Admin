<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
RoleVerify('18',$db);
/* $url=$_SERVER["REQUEST_URI"];
$url=parse_url($url);
$url=$url[path]; */

$b_weight = 0;
$e_weight = 0;
$direction = "";
$bid = "";
$cpid = "";

if(isset($_GET["id"])) {
	$bid = $_GET["id"];
}
if(isset($_GET["direction"])) {
	$direction = $_GET["direction"];
}

if(isset($_GET["cpid"])) {
	$cpid = $_GET["cpid"];
}
$eid = get_eid($bid, $direction);
query_weight($bid, $eid);
exchange_weight($bid, $eid);
echo "<script language='javascript' type='text/javascript'>";
echo "window.location.href=\"payment_detail.php?cp_id=".$cpid."\"";
echo "</script>";

function get_eid($id, $direction) {
	global $db;
	$eid = "";
	$sql = "select * from NokiaPaymentPlat.npp_app_paymentmethod order by weight desc";
	$result = $db->query($sql);
	if($direction == "up") {
		$sql = "select * from NokiaPaymentPlat.npp_app_paymentmethod_match order by weight asc";
		$result = $db->query($sql);
		$count = 0 ;
		$i = 0;
		while($out_app=$db->fetch_array($result)) {
			$i++;
			if($id == $out_app['id']) {
				$count = $i + 1;
			}
			if($count == $i) {
				$eid = $out_app['id'];
			}
		}
	}else if($direction == "down") {
		$sql = "select * from NokiaPaymentPlat.npp_app_paymentmethod_match order by weight desc";
		$result = $db->query($sql);
		$count = 0 ;
		$i = 0;
		while($out_app=$db->fetch_array($result)) {
			$i++;
			if($id == $out_app['id']) {
				$count = $i + 1;
			}
			if($count == $i) {
				$eid = $out_app['id'];
			}
		}
	}
	return $eid;
}

function query_weight($bid ,$eid) {
	global $b_weight;
	global $e_weight;
	global $db;
	$sql_b = "SELECT weight FROM NokiaPaymentPlat.npp_app_paymentmethod_match where id = ".$bid;
	$sql_e = "SELECT weight FROM NokiaPaymentPlat.npp_app_paymentmethod_match where id = ".$eid;
	$result_b = $db->query($sql_b);
	while($out_app=$db->fetch_array($result_b)) {
		$b_weight = $out_app['weight'];
	}
	$result_e = $db->query($sql_e);
	while($out_app=$db->fetch_array($result_e)) {
		$e_weight = $out_app['weight'];
	}
}

function exchange_weight($bid, $eid) {
	global $b_weight;
	global $e_weight;
	global $db;
	$sql_b = "update NokiaPaymentPlat.npp_app_paymentmethod_match set weight = ".$e_weight." where id =".$bid;
	$sql_e = "update NokiaPaymentPlat.npp_app_paymentmethod_match set weight = ".$b_weight." where id =".$eid;
	$result_b = $db->query($sql_b);
	$result_e = $db->query($sql_e);
}



?>
