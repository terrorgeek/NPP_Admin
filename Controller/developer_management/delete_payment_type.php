<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');

/* $url=$_SERVER["REQUEST_URI"];
$url=parse_url($url);
$url=$url[path]; */

// $id = "";
// $cpid = 0;
// if(isset($_GET["id"])) {
	// $id = $_GET["id"];
// }
// 
// if(isset($_GET["cpid"])) {
	// $cpid = $_GET["cpid"];
	// echo $cpid;
// }
// query_weight($bid, $eid);
// exchange_weight($bid, $eid);
// echo "<script language='javascript' type='text/javascript'>";
// echo "window.location.href=\"payment_detail.php?cp_id=".$cpid."\"";
// echo "</script>";
// 
// function delete($ids) {
	// global $db;
	// $sql = "delete * FROM NokiaPaymentPlat.npp_method_to_cp where id in ( ".$ids." )";
	// $result = $db->query($sql);
	// echo "<script>alert('删除成功！');</script>";
// }
$id="";
if(isset($_GET["id"])&&!eregi('or|and|select|union|update|delete', $_GET["id"]))
{
	$id=$_GET["id"];
}
else
{
	echo "<script>alert('请不要输入非法参数!');</script>";
	echo "<script>window.location.href='payment_channel.php'</script>";
}
//先把要删的支付类型名称查出来，好写入日志
$sql_find_type_name="select name,channel_id from nppadmin_paymentmethod where id='".$id."'";
$query_type_name=mysql_query($sql_find_type_name);
$result_type_name=mysql_fetch_array($query_type_name);

//查出支付渠道
$sql_find_channel_name="select channel_name from nppadmin_channelinfo where id='".$result_type_name['channel_id']."'";
$query_channel_name=mysql_query($sql_find_channel_name);
$result_channel_name=mysql_fetch_array($query_channel_name);

$sql_delete_type="delete from nppadmin_paymentmethod where id='".$id."'";
$query=mysql_query($sql_delete_type);
if($query)
{
	//将删除的计费类型写入日志
	date_default_timezone_set('PRC');
	$now=date("Y-m-d H:m:s");
	$sql_delete_log="insert into nppadmin_log (user_id,action,result,time) values ('".$_SESSION['userid']."',49,'删除计费类型-".$result_type_name['name']."(".$result_channel_name['channel_name'].")','".$now."')";
  //  mysql_query($sql_delete_log);
    LogRecord($_SESSION['userid'],49,"删除支付类型-".$result_type_name['name']."(".$result_channel_name['channel_name'].")",$db);
	echo "<script>alert('删除成功');</script>";
	echo "<script>window.location.href='payment_channel.php?id=".$result_type_name['channel_id']."'</script>";
}
else
{
	echo "<script>alert('删除失败');</script>";
	echo "<script>window.location.href='payment_channel.php?id=".$result_type_name['channel_id']."'</script>";
}
?>
