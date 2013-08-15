<?php 
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');

//支付渠道类型的id
$id="";
if(isset($_GET["id"]))
{
	$id=$_GET["id"];
}
//先查，然后把此渠道详细信息显示出来
$sql_select_channel="select * from nppadmin_channelinfo where id='".$id."'";
$query=mysql_query($sql_select_channel);
$result=mysql_fetch_array($query);
$smarty->assign("id",$result["id"]);
$smarty->assign("channel_name",$result["channel_name"]);
$smarty->assign("account",$result["account"]);
$smarty->assign("key_pattern",$result["key_pattern"]);
$smarty->assign("rate",$result["rate"]);
$smarty->assign("others",$result["others"]);
$smarty->display("developer_management/modify_payment_channel.html");
?>