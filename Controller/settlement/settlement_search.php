<?php 
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
RoleVerify('12',$db);
//页面数据量大小
$pagesize=100;
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
function check_status($check)
{
	if(isset($_REQUEST[$check])&&$_REQUEST[$check]=="on")
	{
		return "checked";
	}
}
//先把支付渠道下拉框显示出来
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
if(isset($_POST["submit_test"])&&$_POST["submit_test"]=="yes")
{
//现在开始真正的查询
$sql="select nppadmin_app_info.app_name, nppadmin_cp_info.cp_name, npp_charge.fee, npp_payment_type.name type_name, 
      npp_payment_source.name source_name, nppadmin_channelinfo.channel_name from nppadmin_app_info, nppadmin_cp_info,
      npp_charge, npp_payment_source, npp_payment_type, nppadmin_channelinfo where nppadmin_app_info.cp_id=nppadmin_cp_info.cp_id and 
      nppadmin_app_info.app_id=npp_charge.app_id and npp_charge.pay_source=npp_payment_source.id and 
      npp_charge.pay_kind=npp_payment_type.id and npp_charge.channel_id=nppadmin_channelinfo.id and ";
$sql_sum_fee="select SUM(npp_charge.fee) from nppadmin_app_info, nppadmin_cp_info,
      npp_charge, npp_payment_source, npp_payment_type, nppadmin_channelinfo where nppadmin_app_info.cp_id=nppadmin_cp_info.cp_id and 
      nppadmin_app_info.app_id=npp_charge.app_id and npp_charge.pay_source=npp_payment_source.id and 
      npp_charge.pay_kind=npp_payment_type.id and npp_charge.channel_id=nppadmin_channelinfo.id and ";


if(isset($_REQUEST["cp_name_or_id"])&&$_REQUEST["cp_name_or_id"]!="")
{
	$sql.=" nppadmin_cp_info.cp_id='".$_REQUEST['cp_name_or_id']."' or nppadmin_cp_info.cp_name='".$_REQUEST['cp_name_or_id']."' and ";
	$sql_sum_fee.=" nppadmin_cp_info.cp_id='".$_REQUEST['cp_name_or_id']."' or nppadmin_cp_info.cp_name='".$_REQUEST['cp_name_or_id']."' and ";
}
if(isset($_REQUEST["payment_channel"])&&$_REQUEST["payment_channel"]!=""&&$_REQUEST["checkbox_payment_channel"]!="")
{
	if($_REQUEST["payment_channel"]=="all")
	{
		
	}
	else
	{
		$sql.=" nppadmin_channelinfo.id='".$_REQUEST['payment_channel']."' and ";
		$sql_sum_fee.=" nppadmin_channelinfo.id='".$_REQUEST['payment_channel']."' and ";
	}
	
}
if(isset($_REQUEST["start_time"])&&$_REQUEST["start_time"]!=""&&isset($_REQUEST["end_time"])&&$_REQUEST["end_time"]!="")
{
	$sql.=" (npp_charge.charge_time>'".$_REQUEST['start_time']."' and npp_charge.charge_time<'".$_REQUEST['end_time']."') and ";
	$sql_sum_fee.=" (npp_charge.charge_time>'".$_REQUEST['start_time']."' and npp_charge.charge_time<'".$_REQUEST['end_time']."') and ";
}
$sql.=" 1=1 ";
$sql_sum_fee.=" 1=1 ";
//总记录条数和分页
$query_apply_sum=mysql_query($sql);
$out_apply_sum=mysql_num_rows($query_apply_sum);
$sumpage=ceil($out_apply_sum/$pagesize);
$sql.=" ORDER BY nppadmin_cp_info.cp_id DESC limit ".($currentpage-1)*$pagesize.",".$pagesize."";

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
	$outpagelist.="<td>".$out['app_name']."</td>";
	$outpagelist.="<td>".$out['channel_name']."</td>";
	$outpagelist.="<td>".$out['type_name']."</td>";
	$outpagelist.="<td>".$out['source_name']."</td>";
	$outpagelist.="<td>".$out['fee']."</td>";
	$outpagelist.="</tr>";
}

}
$smarty->assign("outpagelist",$outpagelist);
$smarty->assign("sum",$sum);
$smarty->assign("out_apply_sum",$out_apply_sum);
$smarty->assign("outpage_channel_select",$outpage_channel_select);
$smarty->assign("cp_name_or_id",check_text("cp_name_or_id"));
$smarty->assign("start_time",check_text("start_time"));
$smarty->assign("end_time",check_text("end_time"));
$smarty->assign("sumpage",$sumpage);
$smarty->assign("currentpage",$currentpage);
$smarty->assign("checkbox_payment_channel",check_status("checkbox_payment_channel"));

$smarty->display("settlement/settlement_search.html");
?>
</body>
</html>