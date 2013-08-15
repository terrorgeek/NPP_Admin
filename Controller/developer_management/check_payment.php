<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');


$channel_name = "";
$account = "";
$key="";

if(isset($_POST["channel_name_repeat"])&&$_POST["channel_name_repeat"]!="")
{
	$channel_name = $_POST["channel_name_repeat"];
}

if(isset($_POST["account_repeat"])&&$_POST["account_repeat"]!="")
{
	$account = $_POST["account_repeat"];
}

if(isset($_POST["key_repeat"])&&$_POST["key_repeat"]!="")
{
	$key = $_POST["key_repeat"];
}

if($channel_name!="") {
	$sql = "select * from NokiaPaymentPlat.nppadmin_channelinfo where channel_name='".$channel_name."'";
	$result = $db->query($sql);
	if(mysql_num_rows($result)==0) {
		echo "no";
	}else {
		echo "yes";
	}
}
else if($account!="") {
	$sql = "select * from NokiaPaymentPlat.nppadmin_channelinfo where account='".$account."'";
	$result = $db->query($sql);
	if(mysql_num_rows($result)==0) {
		echo "no";
	}else {
		echo "yes";
	}
}
//检查加密方式
if($key!="")
{
	$sql="select * from NokiaPaymentPlat.nppadmin_channelinfo where key_pattern='".$key."'";
	$result=mysql_query($sql);
	if(mysql_num_rows($result)==0)
	{
		echo "no";
	}
	else 
	{
		echo "yes";
	}
}
//检查支付类型名称
if(isset($_POST["payment_name_repeat"])&&$_POST["payment_name_repeat"]!="")
{
	$payment_name=$_POST["payment_name_repeat"];
	$sql="select name from nppadmin_paymentmethod where name='".$payment_name."'";
	$result=mysql_query($sql);
	if(mysql_num_rows($result)==0)
	{
		echo "no";
	}
	else
	{
		echo "yes";
	}
}
if(isset($_POST["source"])&&isset($_POST["kind"])&&isset($_POST["channel"]))
{
	$channel_id=$_POST["channel"];
	$kind=$_POST["kind"];
	$source=$_POST["source"];
	$sql_find_csk="select id,kind,support,channel_id from nppadmin_paymentmethod where kind='".$kind."' and 
	               support='".$source."' and channel_id='".$channel_id."'";
	$result_csk=mysql_query($sql_find_csk);
	$num=mysql_num_rows($result_csk);
	if($num>0)
	{
		echo "exist";
	}
	else
	{
		echo "no data";
	}
}
if(isset($_POST["payment_type_id"])&&$_POST["payment_type_id"]!="")
{
	$payment_type_id=$_POST["payment_type_id"];
	$sql_find_use="select * from npp_app_paymentmethod_match where method_id='".$payment_type_id."'";
	$query=mysql_query($sql_find_use);
	$num=mysql_num_fows($query);
	if($num>0)
	{
		echo "used";
	}
	else
	{
		echo "not used";
	}
}
?>
