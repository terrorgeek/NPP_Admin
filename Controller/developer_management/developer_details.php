<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');

if(isset($_GET["cp_id_search"]))
{
	$cp_id_search = $_GET["cp_id_search"];
}
else
{
	$cp_id_search = '';
}
if(isset($_GET["cp_name"]))
{
	$cp_name1 = $_GET["cp_name"];
}
else
{
	$cp_name1 = '';
}
if(isset($_GET["currentpage2"]))
{
	$currentpage2 = $_GET["currentpage2"];
}
else
{
	$currentpage2 = '';
}
if(isset($_GET["developer_id_check"]))
{
	$developer_id_check = $_GET["developer_id_check"];
}
else
{
	$developer_id_check = '';
}
if(isset($_GET["developer_name"]))
{
	$developer_name = $_GET["developer_name"];
}
else
{
	$developer_name = '';
}
if(isset($_GET["developer_type"]))
{
	$developer_type = $_GET["developer_type"];
}
else
{
	$developer_type = '';
}
if(isset($_GET["developer_type_check"]))
{
	$developer_type_check = $_GET["developer_type_check"];
}
else
{
	$developer_type_check = '';
}
if(isset($_GET["submit_time"]))
{
	$submit_time = $_GET["submit_time"];
}
else
{
	$submit_time = '';
}
if(isset($_GET["currentpage1"]))
{
	$currentpage1 = $_GET["currentpage1"];
}
else
{
	$currentpage1 = '';
}
if(isset($_GET["cp_id"]))
{
	$cp_id=$_GET["cp_id"];
	$sql_developer_details="select * from nppadmin_cp_info where cp_id='".$cp_id."'";
	$result=$db->query($sql_developer_details);
	$array=$db->fetch_array($result);
	$cp_class="";
	$cp_name="";
	$cp_email="";
	//$cp_location="";
	//$cp_address="";
	$address_state="";
	$address_city="";
	$cp_postcode="";
	$cp_website="";
	$cp_realname="";
	$cp_enterprise="";
	$contact="";
	if($array["cp_class"]==1)
	{
		$cp_class="个人开发者";
		$smarty->assign("cp_class",$cp_class);
	}
	else
	{
		$cp_class="企业开发者";
		$smarty->assign("cp_class",$cp_class);
	}
	$developer_name_check=$_REQUEST["developer_name_check"];
	$cp_name=$array["cp_name"];
	$cp_email=$array["cp_email"];
	$address_state=$array["address_state"];
	$address_city=$array["address_city"];
	//$cp_location
	//$cp_address
	$cp_postcode=$array["cp_postcode"];
	$cp_website=$array["cp_website"];
	$cp_realname=$array["cp_realname"];
	$contact=$array["contact"];
	$cp_address = $array["cp_address"];
	$cp_enterprise = $array["cp_enterprise"];
	$phone = $array["phone"];
}

$smarty->assign("cp_id",$cp_id);
$smarty->assign("cp_name",$cp_name);
$smarty->assign("cp_email",$cp_email);
$smarty->assign("cp_postcode",$cp_postcode);
$smarty->assign("cp_website",$cp_website);
$smarty->assign("cp_realname",$cp_realname);
$smarty->assign("cp_enterprise",$cp_enterprise);
$smarty->assign("address_state",$address_state);
$smarty->assign("address_city",$address_city);
$smarty->assign("cp_address", $cp_address);
$smarty->assign("phone", $phone);
$smarty->assign("cp_id_search",$cp_id_search);
$smarty->assign("cp_name1",$cp_name1);
$smarty->assign("currentpage2",$currentpage2);
$smarty->assign("developer_id_check",$developer_id_check);
$smarty->assign("developer_name",$developer_name);
$smarty->assign("developer_type",$developer_type);
$smarty->assign("developer_type_check",$developer_type_check);
$smarty->assign("submit_time",$submit_time);
$smarty->assign("developer_name_check",$developer_name_check);
$smarty->assign("currentpage1",$currentpage1);
if(isset($_GET["flag"]))
{
	$flag=$_GET["flag"];
    $smarty->assign("flag",$flag);
}
$smarty->assign("contact",$contact);
$smarty->display("developer_management/developer_details.html");
?>