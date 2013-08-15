<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
RoleVerify('20',$db);
$channel_sql = "select id ,channel_name from nppadmin_channelinfo";
$outpagelist = "";
$result=$db->query($channel_sql);
$count = 0;
$channel_id = "";
if(isset($_GET['id'])) {
	$channel_id = $_GET['id'];
}
if(isset($_GET['channel_id'])) {
	$channel_id = $_GET['channel_id'];
}
while($out_app=$db->fetch_array($result)) {
	$count++;
	if($count == 1 && $channel_id=="") $channel_id =  $out_app['id'];
	if($channel_id == $out_app['id']) {
		$outpagelist .= "<option value='".$out_app['id']."' selected=true >".$out_app['channel_name']."</option>";
	}else {
		$outpagelist .= "<option value='".$out_app['id']."'>".$out_app['channel_name']."</option>";
	}
}

$type_sql = "select id ,name,kind,support,status from nppadmin_paymentmethod  where channel_id = '".$channel_id."' order by status, kind, support ";
$result2 = $db->query($type_sql);
$tablelist = "";
while($out_app=$db->fetch_array($result2)) {
	$tablelist .= "<tr>";
	
	//支付类型
	$sql_select_payment_type="select name from npp_payment_type where id='".$out_app["kind"]."'";
	$query_payment_type=mysql_query($sql_select_payment_type);
	$result_payment_type=mysql_fetch_array($query_payment_type);
	$tablelist.="<td>".$result_payment_type["name"]."</td>";
	//支付类型名称
	$tablelist .= "<td>".$out_app['name']."</td>";
	//支付来源
	$sql_select_source="select name from npp_payment_source where id='".$out_app["support"]."'";
	$query_payment_source=mysql_query($sql_select_source);
	$result_payment_source=mysql_fetch_array($query_payment_source);
	$tablelist.="<td>".$result_payment_source["name"]."</td>";
	if($out_app["status"]==1)
	{
		$tablelist.="<td>启用</td>";
	}
	else if($out_app["status"]==0)
	{
		$tablelist.="<td>未启用</td>";
	}
	
	$tablelist .= "<td><a href=\"modify_payment_type.php?id=".$out_app['id']."\">修改</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"javascript:void(0);\" onclick=\"delete_payment_type('".$out_app['id']."')\" >删除</a></td>";
	$tablelist .= "</tr>";
}

$smarty->assign("channel_id",$channel_id);
$smarty->assign("outpagelist",$outpagelist);
$smarty->assign("tablelist",$tablelist);
$smarty->display("developer_management/payment_channel.html");
?>