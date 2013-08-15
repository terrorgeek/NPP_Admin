<?php 
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
RoleVerify('12',$db);
$settlement_type="";
$effective_date="";
if(isset($_REQUEST["settlement_type"])&&isset($_REQUEST["effective_date"])&&$_REQUEST["settlement_type"]!=""&&$_REQUEST["effective_date"]!="")
{
	$cp_id=$_REQUEST["cp_id"];
	$settlement_type=$_REQUEST["settlement_type"];
	$effective_date=$_REQUEST["effective_date"];
	//修改结算周期
	$sql="update nppadmin_cp_info set settlement_type='".$settlement_type."', settle_date='".$effective_date."' where cp_id='".$cp_id."'";
	$query=mysql_query($sql);
	if($query)
	{
		echo "yes";
	}
	else
	{
		echo "no";
	}
}
?>