<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
RoleVerify('12',$db);

//先输出支付渠到
$outpage_channel_select="<option value=\"all\" selected=\"selected\" >全部</option>";
$sql_channel_select="select id, channel_name from nppadmin_channelinfo";
$query_channel_select=mysql_query($sql_channel_select);
while($out=mysql_fetch_array($query_channel_select))
{
	if(isset($_REQUEST["payment_channel"])&&$_REQUEST["payment_channel"]==$out["id"])
	{
		$outpage_channel_select.="<option value='".$out['id']."' selected=\"selected\">".$out['channel_name']."</option>";
	}
	else 
	{
		$outpage_channel_select.="<option value='".$out['id']."' >".$out['channel_name']."</option>";
	}
}

$smarty->display("outpage_channel_select",$outpage_channel_select);
$smarty->display("settlement/settlement_month_report.html");
?>