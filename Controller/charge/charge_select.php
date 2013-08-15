<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
RoleVerify('16',$db);
/* $url=$_SERVER["REQUEST_URI"];
$url=parse_url($url);
$url=$url[path]; */
$pagesize=10;
/*获取分页参数*/
if(isset($_REQUEST['currentpage']))
{
	$currentpage=$_POST['currentpage'];
}
else
{
	 $currentpage=1;
}
/*获取总页数*/
if(isset($_POST['sumpage']))
{
	$sumpage=$_POST['sumpage'];
}
else
{
	$sumpage=1;
} 
/*记忆select框选择状态*/
function check_status($check)
{
	if(isset($_REQUEST[$check]))
	{
		return "checked";
	}
}
/*记忆input框输入值*/
function input_value($input)
{
	if(isset($_REQUEST[$input]))
	{
		return $_REQUEST[$input];
	}
}
/*记忆select框选择值*/
function select_value($select_name)
{
	if(isset($_REQUEST[$select_name]))
	{
		echo "<script>";
		echo "document.getElementById('".$select_name."').value='".$_REQUEST[$select_name]."';";
		echo "</script>";
	}
}

//===========显示”支付方式“select框内容============
$sql = "SELECT * FROM npp_payment_type ";
$res = $db->query($sql);
$pay_kind_pageout = "";
while($out = $db->fetch_array($res))
{
	if(isset($_REQUEST['pay_kind'])&&$_REQUEST['pay_kind']==$out['id'])
	{
		$pay_kind_pageout .= "<option value=".$out['id']." selected=\"selected\">".$out['pay_kind']."</option>";
	}
	else
	{
		$pay_kind_pageout .= "<option value=".$out['id']." >".$out['pay_kind']."</option>";
	}
}

//===========显示支付类型的下拉框=============
$out_select_payment_type="";
$sql_select_type="select * from npp_payment_type";
$query_payment_type=mysql_query($sql_select_type);
while($out_payment_type=mysql_fetch_array($query_payment_type))
{
	if(isset($_REQUEST["payment_type"])&&$_REQUEST["payment_type"]==$out_payment_type["id"])
	{
		$out_select_payment_type.="<option value='".$out_payment_type['id']."' selected=\"selected\" >".$out_payment_type['name']."</option>";
	}
	else 
	{
		$out_select_payment_type.="<option value='".$out_payment_type['id']."' >".$out_payment_type['name']."</option>";
	}
}
$smarty->assign("out_select_payment_type",$out_select_payment_type);
//============显示支付渠道的下拉框
$out_select_payment_channel="";
$sql_select_channel="select * from nppadmin_channelinfo";
$query_payment_channel=mysql_query($sql_select_channel);
while($out_payment_channel=mysql_fetch_array($query_payment_channel))
{
	if(isset($_REQUEST["channel"])&&$_REQUEST["channel"]==$out_payment_channel['id'])
	{
		$out_select_payment_channel.="<option value='".$out_payment_channel['id']."' selected=\"selected\" >".$out_payment_channel['channel_name']."</option>";
	}
	else
	{
		$out_select_payment_channel.="<option value='".$out_payment_channel['id']."'>".$out_payment_channel['channel_name']."</option>";
	}
}
$smarty->assign("out_select_payment_channel",$out_select_payment_channel);
//=============显示支付来源的下拉框================
$out_select_payment_source="";
$sql_select_source="select * from npp_payment_source";
$query_payment_source=mysql_query($sql_select_source);
while($out_payment_source=mysql_fetch_array($query_payment_source))
{ 
	if(isset($_REQUEST["source"])&&$_REQUEST["source"]==$out_payment_source["id"])
	{
		$out_select_payment_source.="<option value='".$out_payment_source['id']."' selected=\"selected\"  >".$out_payment_source['name']."</option>";
	}
	else 
	{
		$out_select_payment_source.="<option value='".$out_payment_source['id']."'>".$out_payment_source['name']."</option>";
	}
}
$smarty->assign("out_select_payment_source",$out_select_payment_source);




//===========显示”订单状态“select框内容============
$sql = "SELECT * FROM npp_charge_status ";
$res = $db->query($sql);
$charge_status_pageout = "";
while($out = $db->fetch_array($res))
{
	if(isset($_REQUEST['charge_status'])&&$_REQUEST['charge_status']==$out['id'])
	{
		$charge_status_pageout .= "<option value=".$out['id']." selected=\"selected\" >".$out['charge_status']."</option>";
	}
	else
	{
		$charge_status_pageout .= "<option value=".$out['id']."  >".$out['charge_status']."</option>";
	}
}
//===========显示”产品类别“select框内容============
$sql = "SELECT * FROM nppadmin_app_type ";
$res = $db->query($sql);
$app_type_pageout = "";
while($out = $db->fetch_array($res))
{
	if(isset($_REQUEST['app_type'])&&$_REQUEST['app_type']==$out['id'])
	{
		$app_type_pageout .= "<option value=".$out['id']." selected=\"selected\">".$out['app_type']."</option>";
	}
	else
	{	
		$app_type_pageout .= "<option value=".$out['id']." >".$out['app_type']."</option>";
	}
}
//===========显示”手机型号“select框内容============
$sql = "SELECT * FROM npp_mobile_type ";
$res = $db->query($sql);
$mobile_type_pageout = "";
while($out = $db->fetch_array($res))
{
	if(isset($_REQUEST['mobile_type'])&&$_REQUEST['mobile_type']==$out['id'])
	{
		$mobile_type_pageout .= "<option value=".$out['id']." selected=\"selected\">".$out['mobile_type']."</option>";
	}
	else
	{
		$mobile_type_pageout .= "<option value=".$out['id']." >".$out['mobile_type']."</option>";
	}
}
$smarty->assign("channel_type",$channel_type);
$smarty->assign("channel_type_check",check_status("channel_type_check"));
$smarty->assign("payment_type_check",check_status("payment_type_check"));
$smarty->assign("source_check",check_status("source_check"));
$smarty->assign("pay_kind_pageout",$pay_kind_pageout);
$smarty->assign("charge_status_pageout",$charge_status_pageout);
$smarty->assign("app_type_pageout",$app_type_pageout);
$smarty->assign("mobile_type_pageout",$mobile_type_pageout);
$smarty->assign("date_check_pageout",check_status("date_check"));
$smarty->assign("phone_check_pageout",check_status("phone_check"));
$smarty->assign("pay_kind_check_pageout",check_status("pay_kind_check"));
$smarty->assign("charge_status_check_pageout",check_status("charge_status_check"));
$smarty->assign("charge_num_check_pageout",check_status("charge_num_check"));
$smarty->assign("app_id_check_pageout",check_status("app_id_check"));
$smarty->assign("cp_id_check_pageout",check_status("cp_id_check"));
$smarty->assign("app_type_check_pageout",check_status("app_type_check"));
$smarty->assign("mobile_type_check_pageout",check_status("mobile_type_check"));
$smarty->assign("stime_pageout",input_value("stime"));
$smarty->assign("etime_pageout",input_value("etime"));
$smarty->assign("phone_pageout",input_value("phone"));
$smarty->assign("charge_num_pageout",input_value("charge_num"));
$smarty->assign("app_id_pageout",input_value("app_id"));
$smarty->assign("cp_id_pageout",input_value("cp_id"));

$outpagelist = null;$error = "false";
if(isset($_REQUEST['submit_test']))
{
//$sql_search = "SELECT npp_charge.*,npp_pay_kind.pay_kind,npp_charge_status.charge_status,npp_mobile_type.mobile_type,cp_name,nppadmin_app_info.app_name,nppadmin_cp_info.cp_id FROM npp_charge JOIN npp_pay_kind ON npp_charge.pay_kind=npp_pay_kind.id JOIN npp_charge_status ON npp_charge.charge_status=npp_charge_status.id  JOIN npp_mobile_type ON npp_charge.mobile_type=npp_mobile_type.id  JOIN nppadmin_app_info ON npp_charge.app_id=nppadmin_app_info.app_id JOIN nppadmin_cp_info ON nppadmin_app_info.cp_id=nppadmin_cp_info.cp_id  WHERE ";//查询所有满足条件数据
$sql_search = "SELECT npp_charge.*,npp_payment_type.name,npp_charge_status.charge_status,npp_mobile_type.mobile_type,cp_name,nppadmin_app_info.app_name,nppadmin_cp_info.cp_id FROM npp_charge JOIN npp_payment_type ON npp_charge.pay_kind=npp_payment_type.id JOIN npp_charge_status ON npp_charge.charge_status=npp_charge_status.id  JOIN npp_mobile_type ON npp_charge.mobile_type=npp_mobile_type.id  JOIN nppadmin_app_info ON npp_charge.app_id=nppadmin_app_info.app_id JOIN nppadmin_cp_info ON nppadmin_app_info.cp_id=nppadmin_cp_info.cp_id  WHERE ";//查询所有满足条件数据
$sql_search_sum = "SELECT count(*) FROM npp_charge JOIN npp_charge_status ON npp_charge.charge_status=npp_charge_status.id JOIN npp_payment_type ON npp_charge.pay_kind=npp_payment_type.id  JOIN npp_mobile_type ON npp_charge.mobile_type=npp_mobile_type.id  JOIN nppadmin_app_info ON npp_charge.app_id=nppadmin_app_info.app_id JOIN nppadmin_cp_info ON nppadmin_app_info.cp_id=nppadmin_cp_info.cp_id  WHERE ";//查询总条数
$sql_search_fee = "SELECT sum(fee) FROM npp_charge JOIN npp_charge_status ON npp_charge.charge_status=npp_charge_status.id  JOIN npp_payment_type ON npp_charge.pay_kind=npp_payment_type.id JOIN npp_mobile_type ON npp_charge.mobile_type=npp_mobile_type.id  JOIN nppadmin_app_info ON npp_charge.app_id=nppadmin_app_info.app_id JOIN nppadmin_cp_info ON nppadmin_app_info.cp_id=nppadmin_cp_info.cp_id  WHERE ";//查询总金额
//时间输入条件的检查
if(isset($_POST['date_check']))
{
	if($_POST['stime']&&!$_POST['etime'])
	{
		$stime = $_POST['stime'];

		$sql_search.= " LEFT(npp_charge.charge_time,10) >= '".$stime."' AND ";
		$sql_search_sum.= " LEFT(npp_charge.charge_time,10) >= '".$stime."' AND ";
		$sql_search_fee.= " LEFT(npp_charge.charge_time,10) >= '".$stime."' AND ";

	}
	else if(!$_POST['stime']&&$_POST['etime'])
	{
		$etime = $_POST['etime'];
		
		$sql_search.= " LEFT(npp_charge.charge_time,10) <= '".$etime."' AND ";
		$sql_search_sum.= " LEFT(npp_charge.charge_time,10) <= '".$etime."' AND ";
		$sql_search_fee.= " LEFT(npp_charge.charge_time,10) <= '".$etime."' AND ";
	}
	else if($_POST['stime']&&$_POST['etime'])
	{
		$stime = $_POST['stime'];
		$etime = $_POST['etime'];
		
		if($stime>$etime)
		{
			echo "<script language=\"JavaScript\">alert('日期输入不合法!');</script>";	
			$error = "true";
		}
		else
		{
			$sql_search.= " LEFT(npp_charge.charge_time,10) >= '".$stime."' AND LEFT(charge_time,10) <= '".$etime."' AND";
			$sql_search_sum.= " LEFT(npp_charge.charge_time,10) >= '".$stime."' AND LEFT(charge_time,10) <= '".$etime."' AND";
			$sql_search_fee.= " LEFT(npp_charge.charge_time,10) >= '".$stime."' AND LEFT(charge_time,10) <= '".$etime."' AND";
		}
	}
	else {
		echo "<script language=\"JavaScript\">alert('日期输入不合法!');</script>";	
		$error = "true";
	} 
  }
}
if(isset($_POST['phone_check']))
{
	$phone = $_POST['phone'];
	if(preg_match("/^(1)\d{10}$/",$phone))
	{
		$sql_search.= " npp_charge.phone = '".$phone."' AND ";
		$sql_search_sum.= " npp_charge.phone = '".$phone."' AND ";
		$sql_search_fee.= " npp_charge.phone = '".$phone."' AND ";
	}
	else
	{
		echo "<script language=\"JavaScript\">alert('手机号输入不合法!');</script>";	
		$error = "true";
	}
}
//if(isset($_POST['pay_kind_check']))
//{
//	$pay_kind = $_POST['pay_kind'];
//	if($pay_kind!="1")
//	{
//		$sql_search.= " npp_charge.pay_kind = '".$pay_kind."' AND ";
//		$sql_search_sum.= " npp_charge.pay_kind = '".$pay_kind."' AND ";
//		$sql_search_fee.= " npp_charge.pay_kind = '".$pay_kind."' AND ";
//	}
//}
if(isset($_POST['charge_status_check']))
{
	$charge_status = $_POST['charge_status'];
	if($charge_status!="3")
	{
		$sql_search.= " npp_charge.charge_status = '".$charge_status."' AND ";
		$sql_search_sum.= " npp_charge.charge_status = '".$charge_status."' AND ";
		$sql_search_fee.= " npp_charge.charge_status = '".$charge_status."' AND ";
	}
}
if(isset($_POST['charge_num_check']))
{
	$charge_num = $_POST['charge_num'];
	if(preg_match("/^([[0-9])+$/",$charge_num))
	{
		$sql_search.= " npp_charge.charge_id = '".$charge_num."' AND ";
		$sql_search_sum.= " npp_charge.charge_id = '".$charge_num."' AND ";
		$sql_search_fee.= " npp_charge.charge_id = '".$charge_num."' AND ";
	}
	else
	{
		echo "<script language=\"JavaScript\">alert('订单号输入不合法!');</script>";	
		$error = "true";
	}
}
if(isset($_POST['app_id_check']))
{
	$app_id = $_POST['app_id'];	
	if(preg_match("/^([[0-9])+$/",$app_id))
	{
		$sql_search.= " npp_charge.app_id = '".$app_id."' and ";
		$sql_search_sum.= " npp_charge.app_id = '".$app_id."' and ";
		$sql_search_fee.= " npp_charge.app_id = '".$app_id."' and ";
	}
	else
	{
		echo "<script language=\"JavaScript\">alert('内容ID输入不合法!');</script>";	
		$error = "true";
	}
}
if(isset($_POST['cp_id_check']))
{
	$cp_id = $_POST['cp_id'];
	if(preg_match("/^([[0-9])+$/",$cp_id))
	{
		$sql_search.= " nppadmin_cp_info.cp_id = '".$cp_id."' AND ";
		$sql_search_sum.= " nppadmin_cp_info.cp_id = '".$cp_id."' AND ";
		$sql_search_fee.= " nppadmin_cp_info.cp_id = '".$cp_id."' AND ";	
	}
	else
	{
		echo "<script language=\"JavaScript\">alert('开发者ID输入不合法!');</script>";	
		$error = "true";
	}
}
if(isset($_POST['app_type_check']))
{
	$app_type = $_POST['app_type'];
	if($app_type!="1")
	{
		$sql_search.= " nppadmin_app_info.app_type = '".$app_type."' AND ";
		$sql_search_sum.= " nppadmin_app_info.app_type = '".$app_type."' AND ";
		$sql_search_fee.= " nppadmin_app_info.app_type = '".$app_type."' AND ";
	}
}
if(isset($_POST['mobile_type_check']))
{
	$mobile_type = $_POST['mobile_type'];
	if($mobile_type!="1")
	{
		$sql_search.= " npp_charge.mobile_type = '".$mobile_type."' AND ";
		$sql_search_sum.= " npp_charge.mobile_type = '".$mobile_type."' AND ";
		$sql_search_fee.= " npp_charge.mobile_type = '".$mobile_type."' AND ";
	}
}

//如果渠道有值，则查询
if(isset($_POST["channel"])&&$_POST["channel"]!="default"&&$_POST["channel_type_check"]=="on")
{
	$channel_id=$_POST["channel"];
	$sql_search.=" npp_charge.channel_id='".$channel_id."' and ";
	$sql_search_fee.=" npp_charge.channel_id='".$channel_id."' and ";
	$sql_search_sum.=" npp_charge.channel_id='".$channel_id."' and ";
}
//如果支付类型有值，则查询
if(isset($_POST["payment_type"])&&$_POST["payment_type"]!="default"&&$_POST["payment_type_check"]=="on")
{
	$sql_search.=" npp_charge.pay_kind='".$_POST['payment_type']."' and ";
	$sql_search_fee.=" npp_charge.pay_kind='".$_POST['payment_type']."' and ";
	$sql_search_sum.=" npp_charge.pay_kind='".$_POST['payment_type']."' and ";
}
if(isset($_POST["source"])&&$_POST["source"]!="default"&&$_POST["source_check"]=="on")
{
	$sql_search.=" npp_charge.pay_source='".$_POST['source']."' and ";
	$sql_search_fee.=" npp_charge.pay_source='".$_POST['source']."' and ";
	$sql_search_sum.=" npp_charge.pay_source='".$_POST['source']."' and ";
}
$sql_search.=" 1=1 ";
$sql_search_sum.=" 1=1 ";
$sql_search_fee.=" 1=1 ";
//die($sql_search);
if($error == "true")
{
	$sql_search_sum = "";
}
/*取总查询条数*/
$res_search_sum = $db->query($sql_search_sum);
$out_search_sum = $db->fetch_array($res_search_sum);
/*取总页数*/
$sumpage=ceil($out_search_sum[0]/$pagesize);
/*取总金额*/
if($error == "true")
{
	$sql_search_fee = "";
}
$res_search_fee = $db->query($sql_search_fee);
$out_search_fee = $db->fetch_array($res_search_fee);
/*查询按搜索条件的结果，带分页*/
$sql_search.=" ORDER BY npp_charge.charge_time DESC limit ".($currentpage-1)*$pagesize.",".$pagesize." ";
//die($sql_search."aa");
if($error == "true")
{
	$sql_search =" ";
}
$res_search = $db->query($sql_search);

	while($out_search = $db->fetch_array($res_search))
	{
		$outpagelist .= "<tr>";
			$outpagelist .= "<td>".$out_search['charge_time']."</td>";
			$outpagelist .= "<td>".$out_search['charge_id']."</td>";
			$outpagelist .= "<td>".$out_search['phone']."</td>";
			//$outpagelist .= "<td>".$out_search['mobile_type']."</td>";
			$outpagelist .= "<td>".$out_search['app_id']."</td>";
			$outpagelist .= "<td>".$out_search['app_name']."</td>";
			$outpagelist .= "<td title=\"".$out_search['cp_name']."\">".$out_search['cp_id']."</td>";
			//这里要把channel_id对应的渠道名称查出来显示
			$sql_find_channel_name="select * from nppadmin_channelinfo where id='".$out_search['channel_id']."'";
			$query_channel_name=mysql_query($sql_find_channel_name);
			$result_channel_name=mysql_fetch_array($query_channel_name);
			//$outpagelist .= "<td>".$out_search['channel_id']."</td>";
			$outpagelist .= "<td>".$result_channel_name['channel_name']."</td>";
			//这里要把source_id对应的来源名称查出来
			$sql_find_source_name="select * from npp_payment_source where id='".$out_search['pay_source']."'";
			$query_source_name=mysql_query($sql_find_source_name);
			$result_source_name=mysql_fetch_array($query_source_name);
			//$outpagelist .= "<td>".$out_search['pay_source']."</td>";
			$outpagelist .= "<td>".$result_source_name['name']."</td>";
			//这里要把支付类型id对应的名称查出来
			$sql_find_payment_type_name="select * from npp_payment_type where id='".$out_search['pay_kind']."'";
			$query_type_name=mysql_query($sql_find_payment_type_name);
			$result_type_name=mysql_fetch_array($query_type_name);
			//$outpagelist .= "<td>".$out_search['pay_kind']."</td>";
			$outpagelist .= "<td>".$result_type_name['name']."</td>";
			$outpagelist .= "<td>".($out_search['fee']/100)."</td>";
			$outpagelist .= "<td>".$out_search['charge_status']."</td>";

            
		$outpagelist .= "</tr>";
	}


if(isset($_REQUEST['submit_test']))
{
$smarty->assign("outpagelist",$outpagelist);
$smarty->assign("out_search_sum",$out_search_sum[0]);
$smarty->assign("out_search_fee",$out_search_fee[0]);
$smarty->assign("currentpage",$currentpage);
$smarty->assign("sumpage",$sumpage);
}
$smarty->display("charge/charge_select.htm");
?>