<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');


//初始化变量
$channel_name="";
$payment_type="";
$payment_name = "";
$payment_source="";
$max_amount="";
$min_amount="";
$key="";  //支付网关代收
$account="";//坏账率
$nokia_rate = "";
$channel_rate = "";
$apper_rate = "";
$status="";
$others="";

if(isset($_POST["submit_test"])&&$_POST["submit_test"]=="submit_test")
{
	$channel_name = $_POST["channel_name"];
	$channel_id=$_POST["channel_id"];
    $payment_type=$_POST["payment_type"];
	$payment_name=$_POST["payment_name"];
    $payment_source=$_POST["payment_source"];
    $max_amount=$_POST["max_amount"];
    $min_amount=$_POST["min_amount"];
    $key=$_POST["key"];  //支付网关代收
    $account=$_POST["account"];//坏账率
	$nokia_rate = $_POST["nokia_rate"];
	$channel_rate = $_POST["channel_rate"];
	$apper_rate = $_POST["apper_rate"];
    $status=$_POST["status"];
    $others=$_POST["others"];
	//过虑一下传过来的参数
	$channel_name=strip_tags($channel_name);
	$max_amount=strip_tags($max_amount);
	$min_amount=strip_tags($min_amount);
	$key=strip_tags($key);
	$account=strip_tags($account);
	$others=strip_tags($others);
	//去掉单引号和双引号
	$channel_name=preg_replace("/[\'\"]/","", $channel_name);
	$max_amount=preg_replace("/[\'\"]/","", $max_amount);
	$min_amount=preg_replace("/[\'\"]/","", $min_amount);
	$key=preg_replace("/[\'\"]/","", $key);
	$account=preg_replace("/[\'\"]/", "", $account);
	$others=preg_replace("/[\'\"]/", "", $others);
	$others=trim($others);
	
	if(!preg_match("/[0-9]/", $max_amount)||!preg_match("/[0-9]/", $min_amount))
	{
		echo "<script>alert('金额必须是数字,请重新插入!');</script>";
		echo "<script>window.location.href='payment_channel.php'</script>";
		die();
	}
	
	if($max_amount<$min_amount)
	{
		echo "<script>alert('最大金额必须大于最小金额,请重新插入!');</script>";
		echo "<script>window.location.href='payment_channel.php'</script>";
		die();
	}

	//payment_type和payment_source传来的是id，所以以下要将它们对应的明称查出来
	//先查payment_type
	$sql_find_type="select name from npp_payment_type where id='".$payment_type."'";
	$query_payment_type=mysql_query($sql_find_type);
	$result_payment_type=mysql_fetch_array($query_payment_type);//这个结果集中含有payment_type的name
	//再查payment_source
	$sql_find_source="select name from npp_payment_source where id='".$payment_source."'";
	$query_payment_source=mysql_query($sql_find_source);
	$result_payment_source=mysql_fetch_array($query_payment_source);//这个结果集中含有payment_source的name
	
	//以下是真正的插入
	$max_amount=$max_amount*100;
	$min_amount=$min_amount*100;
	$key = $key*100;
	$account = $account*100;
	$nokia_rate = $nokia_rate*100;
	$channel_rate = $channel_rate*100;
	$apper_rate = $apper_rate*100;
	$sql_insert_payment_type="insert into nppadmin_paymentmethod (name,channel_id,kind,support,min_amount,max_amount,status,others,net,net_bad_rate,nokia_rate,payment_rate,apper_rate) 
	                          values ('".$payment_name."','".$channel_id."',
	                          '".$payment_type."','".$payment_source."','".$min_amount."','".$max_amount."','".$status."','".$others."','".$key."','".$account."','".$nokia_rate."','".$channel_rate."','".$apper_rate."')";
	$insert_channel=mysql_query($sql_insert_payment_type);
	//插完nppadmin_paymentmethod表后还要网history表里插
	//获的刚插入method表里的id,把这个id插到history表的method_id中
	$method_id=mysql_insert_id();
	date_default_timezone_set('PRC');
	$now=date("Y-m-d H:m:s");
	$sql_insert_history="insert into npp_payment_type_history (method_id,net,net_bad_rate,nokia_rate ,payment_rate ,apper_rate ,user_id,oper_date) values ('".$method_id."',
	                     '".$key."','".$account."','".$nokia_rate."','".$channel_rate."','".$apper_rate."','".$_SESSION["userid"]."','".$now."')";
	$insert_history=mysql_query($sql_insert_history);
	if($insert_channel&&$insert_history)
	{
		
	//	mysql_query($sql_insert_log);
	    LogRecord($_SESSION['userid'],47,"新增支付类型-".$payment_name."(".$channel_name.")",$db);
		echo "<script>alert('新计费类型添加成功!');</script>";
		echo "<script>window.location.href='payment_channel.php?id=".$channel_id."'</script>";
	}
    else
    {
    	echo "<script>alert('添加失败!');</script>";
		echo "<script>window.location.href='payment_channel.php?id=".$channel_id."'</script>";
    }
}
?>