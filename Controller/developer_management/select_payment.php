<?php
require_once('../../session_mysql.php');
require_once('../../connector.ini.php');


$payment_type = "";
$payment_source = "";
$resultlist = "";
if(isset($_GET["type"])&&$_GET["type"]!="")
{
	$payment_type = $_GET["type"];
}

if(isset($_GET["source"])&&$_GET["source"]!="")
{
	$payment_source = $_GET["source"];
}

if($payment_type!="") {
	$sql_check = "select * from NokiaPaymentPlat.npp_payment_type where name='".$payment_type."'";
	$check_result = $db->query($sql_check);
	if(mysql_num_rows($check_result)==0) {
		$add_sql = "insert into NokiaPaymentPlat.npp_payment_type(name,type) values('".$payment_type."','".$payment_type."')";
//		mysql_query("set character set gb2312");
		$add_result = $db->query($add_sql);
		$sql = "select id,name from NokiaPaymentPlat.npp_payment_type GROUP by name ";
		$result = $db->query($sql);
		while($out_app=$db->fetch_array($result)) {
			$resultlist .= $out_app['name'];
			$resultlist .= ",";
		}
		//需要再来一遍循环，把id加上
		$result2=mysql_query($sql);
		$resultlist.="@";
		while($out_app2=mysql_fetch_array($result2))
		{
			$resultlist.=$out_app2["id"];
			$resultlist.=",";
		}
	}else {
		$resultlist .= "trepeat";
	}
	
}
else if($payment_source!="") {
	$sql_check = "select * from NokiaPaymentPlat.npp_payment_source where name='".$payment_source."'";
	$check_result = $db->query($sql_check);
	if(mysql_num_rows($check_result)==0) {
		$add_sql = "insert into NokiaPaymentPlat.npp_payment_source(name) values('".$payment_source."')";
	//	mysql_query("set character set gb2312");
		$add_result = $db->query($add_sql);
		$sql = "select id,name from NokiaPaymentPlat.npp_payment_source";
		$result = $db->query($sql);
		while($out_app=$db->fetch_array($result)) {
			$resultlist .= $out_app['name'];
			$resultlist .= ",";
		}
		$result2=mysql_query($sql);
		$resultlist.="@";
		//需要再来一遍循环，把id加上
		while($out_app2=mysql_fetch_array($result2))
		{
			$resultlist.=$out_app2["id"];
			$resultlist.=",";
		}
	}else {
		$resultlist .= "srepeat";
	}
}

echo $resultlist;
?>
