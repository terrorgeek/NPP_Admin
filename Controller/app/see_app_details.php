<?php 
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
RoleVerify('9',$db);
//应用的id
$app_id="";
if(isset($_GET["app_id"]))
{
	$app_id=$_GET["app_id"];
}
//首先把应用名称查出来。显示在页面上
$sql_find_app_name="select * from nppadmin_app_info where app_id='".$app_id."'";
$query_find_app_name=mysql_query($sql_find_app_name);
$result_find_app_name=mysql_fetch_array($query_find_app_name);

//下面是列表的查询
$sql_search="select npp_app_paymentmethod_match.*, nppadmin_paymentmethod.* from npp_app_paymentmethod_match, 
             nppadmin_paymentmethod where npp_app_paymentmethod_match.method_id=nppadmin_paymentmethod.id 
             and npp_app_paymentmethod_match.app_id='".$app_id."'";
$query=mysql_query($sql_search);
$outpagelist="<tr>";
while($out=mysql_fetch_array($query))
{
	//首先把checkbox遍历输出来
	$outpagelist.="<td><input type=\"checkbox\" name=\"sort_checkbox\" id=\"sort_checkbox\"/></td>";
	//在循环中，先把渠道id对应的channel_name查出来
	$sql_select_paymentchannel="select channel_name from nppadmin_channelinfo where id='".$out["channel_id"]."'";
	$query_paymentchannel=mysql_query($sql_select_paymentchannel);
	$result_paymentchannel=mysql_fetch_array($query_paymentchannel);
	$outpagelist.="<td>".$result_paymentchannel["channel_name"]."</td>";
	//接着，查询支付类型
	$sql_select_payment_type="select name from npp_payment_type where id='".$out["kind"]."'";
	$query_payment_type=mysql_query($sql_select_payment_type);
	$result_payment_type=mysql_fetch_array($query_payment_type);
	$outpagelist.="<td>".$result_payment_type["name"]."</td>";
	//接着，查支付类型名称,由于支付类型名称在nppadmin_paymentmethod已经存在，因此不用查了
	$outpagelist.="<td>".$out["name"]."</td>";
	//最后，跟据support来查出对应的支付来源的名称
	$sql_select_source="select name from npp_payment_source where id='".$out["support"]."'";
	$query_payment_source=mysql_query($sql_select_source);
	$result_payment_source=mysql_fetch_array($query_payment_source);
	$outpagelist.="<td>".$result_payment_source["name"]."</td>";
	$outpagelist.="<td>查看详情</td>";
	$outpagelist.="<td><input type=\"text\" name=\"sort_text\" id=\"sort_text\" /></td>";
}
$outpagelist.="</tr>";
$smarty->assign("outpagelist",$outpagelist);
$smarty->assign("app_name",$result_find_app_name["app_name"]);
$smarty->display("app/see_app_details.html");
?>