<?php 
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
RoleVerify('12',$db);
//if(isset($_REQUEST["download_charge_time"])&&isset($_REQUEST["download_cp_name_or_id"])&&isset($_REQUEST["download_order_type"]))
//{
	$charge_time=$_REQUEST["download_charge_time"];
	$cp_name_or_id=$_REQUEST["download_cp_name_or_id"];
	$order_type=$_REQUEST["download_order_type"];
	//列出复和传过来的条件的内容
    $sql="select nppadmin_cp_info.cp_name, nppadmin_cp_info.cp_id, nppadmin_app_info.app_id, nppadmin_app_info.app_name, 
          npp_charge.charge_id, npp_charge.fee, substr(npp_charge_result.business_linkid,9), npp_charge_result.nokia_charge_time,
          npp_charge.charge_id, npp_charge.channel_id, nppadmin_channelinfo.channel_name, npp_payment_type.name type_name, 
          npp_payment_source.name source_name, npp_charge_result.sina_status, npp_charge_result.npp_status from nppadmin_cp_info,
          nppadmin_app_info, npp_charge, npp_charge_result, nppadmin_channelinfo, npp_payment_type, npp_payment_source where 
          nppadmin_cp_info.cp_id=nppadmin_app_info.cp_id and npp_charge.app_id=nppadmin_app_info.app_id and 
          substr(npp_charge_result.business_linkid,9)=npp_charge.charge_id and npp_payment_type.id=npp_charge_result.pay_kind 
          and npp_charge_result.pay_source=npp_payment_source.id and nppadmin_channelinfo.id=npp_charge.channel_id and ";
  if($charge_time!="")
  {
 	 $sql.=$charge_time."npp_charge.charge_time='".$charge_time."' and ";
  }

  if($cp_name_or_id!="")
  {
  	 $sql.=" ( nppadmin_cp_info.cp_name='".$cp_name_or_id."' or nppadmin_cp_info.cp_id='".$cp_name_or_id."' ) and ";
  }

  if($order_type!=0)
  {
	 $sql.=" npp_charge_result.order_type='".$order_type."' and ";
  }
  else 
  {
	 $sql.=" npp_charge_result.order_type=1 and ";
  }
  $sql.=" 1=1 ";
  $query=mysql_query($sql);
  //开始构成excel表格
  
//输出内容如下：
   header("Content-type:application/vnd.ms-excel");
  header("Content-Disposition:attachment;filename=test_data.xls");
   echo "开发者名称".chr(9);
   echo "订单号".chr(9);
   echo "内容名称".chr(9);
   echo "订单金额".chr(9);
   echo "支付渠道".chr(9);
   echo "支付类型".chr(9);
   echo "支付来源".chr(9);
   echo "订单状态(Sina)".chr(9);
   echo "订单状态(NPP)".chr(9);
   echo "时间".chr(9);
   echo chr(13);
 while($out=mysql_fetch_array($query))
 {
	echo $out['cp_name'].chr(9);
	echo $out['charge_id'].chr(9);
	echo $out['app_name'].chr(9);
	echo $out['fee'].chr(9);
	echo $out['channel_name'].chr(9);
	echo $out['type_name'].chr(9);
	echo $out['source_name'].chr(9);
	if($out["npp_status"]==0)
	{
		echo "失败".chr(9);
	}
	else if($out["npp_status"]==1)
	{
		echo "成功".chr(9);
	}
	else
	{
		echo "--".chr(9);
	}
    if($out["sina_status"]==0)
	{
		echo "失败".chr(9);
	}
	else if($out["npp_status"]==1)
	{
		echo "成功".chr(9);
	}
	else
	{
		echo "--".chr(9);
	}
	echo $out['nokia_charge_time'].chr(9);
    echo chr(13);
 }
//}
//else
//{
//	die("nb");
//}
?>