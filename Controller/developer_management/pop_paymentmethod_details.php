<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');

//这是
function change_decimal($number) {
	$str1 = (int)($number/100);
	$str2 = $number%100;
	if($str2 < 10) {
		$str2 = "0".$str2;
	}
	return $str1.".".$str2;
}

$paymentmethod_id="";
if(isset($_POST["paymentmethod_id"]))
{
	$paymentmethod_id=$_POST["paymentmethod_id"];
	//pm为nppadmin_paymentmethod,  source为npp_payment_source, type为npp_payment_type, history为npp_payment_type_history
	$sql_see_details="select pm.name pm_name,channelinfo.channel_name channel_name, type.name type_name, source.name source_name, 
	                  pm.max_amount max_amount, pm.min_amount min_amount, history.net history_net, history.net_bad_rate bad_rate, 
	                  history.nokia_rate nokia_rate, history.payment_rate payment_rate, 
	                  history.apper_rate apper_rate, pm.status pm_status, pm.others pm_others from 
	                  nppadmin_paymentmethod pm, npp_payment_source source, npp_payment_type type, 
	                  npp_payment_type_history history,nppadmin_channelinfo channelinfo where pm.support=source.id and pm.kind=type.id and 
	                   pm.id=history.method_id and channelinfo.id=pm.channel_id and pm.id='".$paymentmethod_id."' order by history.oper_date desc ";
	$query=mysql_query($sql_see_details);
	$result=mysql_fetch_array($query);

		//这是一个数组，用来装所有的数据
		$answer_array=array();
		//先把带两位小数的处理一下
		$max_amount=change_decimal($result["max_amount"]);
		$min_amount=change_decimal($result["min_amount"]);
		$bad_rate=change_decimal($result["bad_rate"]);
		$nokia_rate=change_decimal($result["nokia_rate"]);
		$payment_rate=change_decimal($result["payment_rate"]);
		$apper_rate=change_decimal($result["apper_rate"]);
		$net=change_decimal($result["history_net"]);
	    array_push($answer_array,$result["channel_name"]);
	    array_push($answer_array,$result["type_name"]);
	    array_push($answer_array,$result["pm_name"]);
	    array_push($answer_array,$result["source_name"]);
	    array_push($answer_array,$max_amount);
	    array_push($answer_array,$min_amount);
	    array_push($answer_array,$net);
	    array_push($answer_array,$bad_rate);
	    array_push($answer_array,$nokia_rate);
	    array_push($answer_array,$payment_rate);
	    array_push($answer_array,$apper_rate);
	    if($result["pm_status"]==1)
	    {
	    	array_push($answer_array,"启用");
	    }
	    if($result["pm_status"]==0)
	    {
	    	array_push($answer_array,"未启用");
	    }
	    
	    array_push($answer_array,$result["pm_others"]);
	
	echo implode(",",$answer_array);
}
?>