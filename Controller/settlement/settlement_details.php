<?php 
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
RoleVerify('18',$db);
$cp_id="";
if(isset($_GET["cp_id"]))
{
	$cp_id=$_GET["cp_id"];
}
if(isset($_GET["single_fee2"]))
{
	$single_fee2=$_GET["single_fee2"];
}
if(isset($_GET["currentpage2"]))
{
	$currentpage2=$_GET["currentpage2"];
}
//这条SQL语句会同时查出四条数据
$sql_developer="select nppadmin_cp_info.cp_name, npp_settlement.cp_id,npp_settlement.fee,npp_settlement.st_stime,
                npp_settlement.st_etime,npp_settlement.pay_kind from npp_settlement,nppadmin_cp_info where 
                npp_settlement.cp_id=nppadmin_cp_info.cp_id and npp_settlement.cp_id='".$cp_id."' and npp_settlement.st_status=0";
$result=$db->query($sql_developer);
$developer=$db->fetch_array($result);


$developer2=$db->fetch_array($result);
$developer3=$db->fetch_array($result);
$developer4=$db->fetch_array($result);
$array=array();
array_push($array,$developer);
array_push($array,$developer2);
array_push($array,$developer3);
array_push($array,$developer4);

$outpagelist="";
//查npp_pay_kind这个表，取出全部收费类型
$sql_pay_kind="select * from npp_pay_kind order by id ASC";
$pay_kind_result=$db->query($sql_pay_kind);
$array2=array();
while($pay_kind_out=$db->fetch_array($pay_kind_result))
{
	array_push($array2,$pay_kind_out["id"]);
}
for($i=0;$i<count($array);$i++)
{
	if($array[$i]["pay_kind"]==$array2[0])
	{
		$outpagelist.="<tr><td>充值卡支付</td><td>".$array[$i]["fee"]."</td></tr>";
	}
	if($array[$i]["pay_kind"]==$array2[1])
	{
		$outpagelist.="<tr><td>短信支付</td><td>".$array[$i]["fee"]."</td></tr>";
	}
	if($array[$i]["pay_kind"]==$array2[2])
	{
		$outpagelist.="<tr><td>手机银行支付</td><td>".$array[$i]["fee"]."</td></tr>";
	}
	if($array[$i]["pay_kind"]==$array2[3])
	{
		$outpagelist.="<tr><td>支付宝支付</td><td>".$array[$i]["fee"]."</td></tr>";
	}
}



$smarty->assign("cp_name",$developer["cp_name"]);
$smarty->assign("fee",sprintf("%01.2f",$single_fee2));
$smarty->assign("st_stime",$developer["st_stime"]);
$smarty->assign("st_etime",$developer["st_etime"]);
$smarty->assign("currentpage2",$currentpage2);
$smarty->assign("outpagelist",$outpagelist);

$smarty->display("settlement/settlement_details.html");
?>