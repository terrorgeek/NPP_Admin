<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
RoleVerify('12',$db);
//页面数据量大小
$pagesize=10;
//分页
if(isset($_REQUEST["currentpage"]))
{
	$currentpage=$_REQUEST["currentpage"];
}
else
{
	$currentpage=1;
}
if(isset($_REQUEST["sumpage"]))
{
	$sumpage=$_REQUEST["sumpage"];
}
else 
{
	$sumpage=1;
}
$time=0;
 //记住文本框值的函数
function check_text($text)
{
	if(isset($_REQUEST[$text]))
	{
		return $_REQUEST[$text];
	}
}
/*记忆select框选择状态*/
function check_status($check)
{
	if(isset($_REQUEST[$check]))
	{
		return "checked";
	}
}
$order_type_array=array("---请选择---","当期应结订单","待退款订单","延迟坏账订单");
//输出下拉框的变量
$order_type_pageout="";

//记住下拉框的循环
foreach ($order_type_array as $k=>$v)
{
	if(isset($_REQUEST["order_type"])&&$_REQUEST["order_type"]==$k)
	{
		$order_type_pageout.="<option value=".$k." selected=\"selected\">".$v."</option>";
	}
	else
	{
		$order_type_pageout.="<option value=".$k." >".$v."</option>";
	}
}

//真正的查询开始了
$sql="select nppadmin_cp_info.cp_name, nppadmin_cp_info.cp_id, nppadmin_app_info.app_id, nppadmin_app_info.app_name, 
      npp_charge.charge_id, npp_charge.fee, substr(npp_charge_result.business_linkid,9), npp_charge_result.nokia_charge_time,
      npp_charge.charge_id, npp_charge.channel_id, nppadmin_channelinfo.channel_name, npp_payment_type.name type_name, 
      npp_payment_source.name source_name, npp_charge_result.sina_status, npp_charge_result.npp_status from nppadmin_cp_info,
      nppadmin_app_info, npp_charge, npp_charge_result, nppadmin_channelinfo, npp_payment_type, npp_payment_source where 
      nppadmin_cp_info.cp_id=nppadmin_app_info.cp_id and npp_charge.app_id=nppadmin_app_info.app_id and 
      substr(npp_charge_result.business_linkid,9)=npp_charge.charge_id and npp_payment_type.id=npp_charge_result.pay_kind 
      and npp_charge_result.pay_source=npp_payment_source.id and nppadmin_channelinfo.id=npp_charge.channel_id and ";
$sql_sum_fee="select SUM(npp_charge.fee) from nppadmin_cp_info,
      nppadmin_app_info, npp_charge, npp_charge_result, nppadmin_channelinfo, npp_payment_type, npp_payment_source where 
      nppadmin_cp_info.cp_id=nppadmin_app_info.cp_id and npp_charge.app_id=nppadmin_app_info.app_id and 
      substr(npp_charge_result.business_linkid,9)=npp_charge.charge_id and npp_payment_type.id=npp_charge_result.pay_kind 
      and npp_charge_result.pay_source=npp_payment_source.id and nppadmin_channelinfo.id=npp_charge.channel_id and ";
if(isset($_REQUEST["charge_time"])&&$_REQUEST["charge_time"]!=""&&isset($_REQUEST["checkbox_charge_time"])&&$_REQUEST["checkbox_charge_time"]!="")
{
	$charge_time=$_REQUEST["charge_time"];
	$sql.=$charge_time."npp_charge.charge_time='".$charge_time."' and ";
	$sql_sum_fee.=$charge_time."npp_charge.charge_time='".$charge_time."' and ";
}

if(isset($_REQUEST["cp_name_or_id"])&&$_REQUEST["cp_name_or_id"]!=""&&isset($_REQUEST["checkbox_cp_name_or_id"])&&$_REQUEST["checkbox_cp_name_or_id"]!="")
{
	$cp_name_or_id=$_REQUEST["cp_name_or_id"];
	$sql.=" ( nppadmin_cp_info.cp_name='".$cp_name_or_id."' or nppadmin_cp_info.cp_id='".$cp_name_or_id."' ) and ";
	$sql_sum_fee.=" ( nppadmin_cp_info.cp_name='".$cp_name_or_id."' or nppadmin_cp_info.cp_id='".$cp_name_or_id."' ) and ";
}

if(isset($_REQUEST["order_type"])&&$_REQUEST["order_type"]!=""&&isset($_REQUEST["checkbox_order_type"])&&$_REQUEST["checkbox_order_type"]!="")
{
	$order_type=$_REQUEST["order_type"];
	$sql.=" npp_charge_result.order_type='".$order_type."' and ";
	$sql_sum_fee.=" npp_charge_result.order_type='".$order_type."' and ";
}
else 
{
	$sql.=" npp_charge_result.order_type=1 and ";
	$sql_sum_fee.=" npp_charge_result.order_type='".$order_type."' and ";
}
$sql.=" 1=1 ";
$sql_sum_fee.=" 1=1 ";

$query=mysql_query($sql);
$outpagelist="";
$sum="";
//查出总金额
$query_sum_fee=mysql_query($sql_sum_fee);
$result_sum_fee=mysql_fetch_array($query_sum_fee);
$sum=$result_sum_fee[0];
while($out=mysql_fetch_array($query))
{
	$outpagelist.="<tr>";
	$outpagelist.="<td>".$out['cp_name']."</td>";
	$outpagelist.="<td>".$out['charge_id']."</td>";
	$outpagelist.="<td>".$out['app_name']."</td>";
	$outpagelist.="<td>".$out['fee']."</td>";
	$outpagelist.="<td>".$out['channel_name']."</td>";
	$outpagelist.="<td>".$out['type_name']."</td>";
	$outpagelist.="<td>".$out['source_name']."</td>";
	if($out["npp_status"]==0)
	{
		$outpagelist.="<td>失败</td>";
	}
	else if($out["npp_status"]==1)
	{
		$outpagelist.="<td>成功</td>";
	}
	else
	{
		$outpagelist.="<td>--</td>";
	}
    if($out["sina_status"]==0)
	{
		$outpagelist.="<td>失败</td>";
	}
	else if($out["npp_status"]==1)
	{
		$outpagelist.="<td>成功</td>";
	}
	else
	{
		$outpagelist.="<td>--</td>";
	}
	$outpagelist.="<td>".$out['nokia_charge_time']."</td>";
	$outpagelist.="</tr>";
}
$smarty->assign("outpagelist",$outpagelist);
$smarty->assign("sum",$sum);
$smarty->assign("cp_name_or_id",check_text("cp_name_or_id"));
$smarty->assign("charge_time",check_text("charge_time"));
$smarty->assign("order_type_pageout",$order_type_pageout);
$smarty->assign("time",$time);
$smarty->assign("sumpage",$sumpage);
$smarty->assign("currentpage",$currentpage);

$smarty->assign("checkbox_cp_name_or_id",check_status("checkbox_cp_name_or_id"));
$smarty->display("settlement/settlement_ensure.html");
//if(isset($_REQUEST["download"])&&$_REQUEST["download"]=="yes")
//{
//	echo "<script>window.location.href=\"download.php?download_charge_time=".$_REQUEST["download_charge_time"]."&download_cp_name_or_id=".$_REQUEST["download_cp_name_or_id"]."&download_order_type=".$_REQUEST["download_order_type"]."\";</script>";
//}
//else
//{
//	echo "<script>alert('dulala');</script>";
//}
?>
</body>
</html>