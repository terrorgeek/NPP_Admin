<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');

/* $url=$_SERVER["REQUEST_URI"];
$url=parse_url($url);
$url=$url[path]; */
if(isset($_POST["submit_check"])&&$_POST["submit_check"]=="submit_check")
{
	$channel_name=$_POST["channel_name"];
    $account=$_POST["account"];
    $key=$_POST["key"];
    //$rate=$_POST["rate"];
    $others=$_POST["others"];
//剥去html标签
    $channel_name=strip_tags($channel_name);
    $account=strip_tags($account);
    $key=strip_tags($key);
    $rate=strip_tags($rate);
    $others=strip_tags($others);

    $channel_name=preg_replace("/[\'\"]/", "",$channel_name);
    $account=preg_replace("/[\'\"]/","", $account);
    $key=preg_replace("/[\"\']/","", $key);
    //$rate=preg_replace("/[\'\"]/", "", $rate);
    $others=preg_replace("/[\'\"]/", "", $others);
	$others=trim($others);

    $sql_insert_channel="insert into nppadmin_channelinfo (account,channel_name,key_pattern,others) values ('".$account."',
                       '".$channel_name."','".$key."','".$others."' )";
					 //  die($sql_insert_channel);
    $query=mysql_query($sql_insert_channel);
    if($query)
    {
    	$sql_id = "select id from nppadmin_channelinfo where channel_name ='".$channel_name."'";
		$channel_id = "";
		$result_id = $db->query($sql_id);
		while($out_app=$db->fetch_array($result_id)) {
			$channel_id = $out_app['id'];
			
		}
		//将新增的支付渠道信息插入到日志中
    	$now=date("Y-m-d H:m:s");
    	$sql_insert_log="insert into nppadmin_log (user_id,action,result,time) values 
    	                 ('".$_SESSION['userid']."',44,'新增支付渠道-".$channel_name."','".$now."')";
    //	mysql_query($sql_insert_log);
      LogRecord($_SESSION['userid'],44,"新增支付渠道-".$channel_name."",$db);
	  echo "<script>alert('新支付渠道添加成功!');</script>";
   	  echo "<script>window.location.href='payment_channel.php?channel_id=".$channel_id."'</script>";
    }
    else
    {
	  echo "<script>alert('添加失败!');</script>";
	  echo "<script>window.location.href='payment_channel.php'</script>";
    }
}
else
{
	$smarty->display("developer_management/add_payment_channel.html");
}

?>
