<?php 
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');

$id="";
$channel_name="";
$account="";
$others="";
$key_pattern="";

if(isset($_POST["submit_check"])&&$_POST["submit_check"]=="submit_check"&&isset($_POST["id"]))
{
    $id=$_POST["id"];
    $channel_name=$_POST["channel_name"];
    $account=$_POST["account"];
    $key_pattern=$_POST["key"];
    $others=$_POST["others"];
    $others=strip_tags($others);
    $others=preg_replace("<script>","",$others);
    $others=preg_replace("</script>","",$others);
    
    $sql_update_channel="update nppadmin_channelinfo set channel_name='".$channel_name."', account='".$account."' 
                         ,key_pattern='".$key_pattern."', others='".$others."' 
                         where id='".$id."'";
    $query=mysql_query($sql_update_channel);
    if($query)
    {
    	//将修改的支付渠道类型写道日志中
    	$now=date("Y-m-d H:m:s");
    	$sql_insert_log="insert into nppadmin_log (user_id,action,result,time) values 
    	                 ('".$_SESSION['userid']."',46,'修改支付渠道-".$channel_name."','".$now."')";
    //	mysql_query($sql_insert_log);
        LogRecord($_SESSION['userid'],46,"修改支付渠道-".$channel_name."",$db);
    	echo "<script>alert('修改成功!');</script>";
    	echo "<script>window.location.href='payment_channel.php'</script>";	
    }
	else
    {
	  	echo "<script>alert('修改失败!');</script>";
	  	echo "<script>window.location.href='payment_channel.php'</script>";
    }
}
else
{
	echo "<script>window.location.href='payment_channel.php'</script>";
}

?>