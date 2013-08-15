<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
RoleVerify('18',$db);
if(isset($_GET['user_id']))
{
	$user_id = $_GET['user_id'];
	$sql_adminname = "SELECT user_name FROM nppadmin_user WHERE user_id = '".$user_id."'";
	$res_adminname = $db->query($sql_adminname);
	$out_adminname = $db->fetch_array($res_adminname);
	$admin_name = $out_adminname[0];
}
$sql_del_match = "DELETE FROM nppadmin_user_page_match WHERE user_id='".$user_id."'";
$db->query($sql_del_match);
$sql_count_pagesum = "SELECT COUNT(*) FROM nppadmin_page ";
$res_count_pagesum = $db->query($sql_count_pagesum);
$out_count_pagesum = $db->fetch_row($res_count_pagesum);
$k = 0;
for($i=1;$i<=$out_count_pagesum[0];$i++)
{
	if(isset($_POST[$i]))
	{
	 	$sql_insert = "INSERT INTO nppadmin_user_page_match(id,user_id,page_id) VALUES ('null','".$user_id."','".$i."')";
		$db->query($sql_insert);
		$k++;
	}
}
if($k==0)
{
	echo "<script language=\"JavaScript\">alert('勾选不能为空!');";
	echo "window.history.go(-1);</script>";	
}
else
{
	LogRecord($_SESSION ["userid"],22,"分配\"".$admin_name."\"的权限",$db);
	$db->close();
	echo "<script language=\"JavaScript\">alert('分配权限成功!');";
	echo "var r=confirm(\"返回到权限管理？\");";
	echo "if (r==true)";
	echo "{";
		echo "window.location.href=\"authority_management.php\"";
	echo "}";
	echo "else";
	echo "{";
		echo "window.history.go(-1);";
	echo "}";
	echo "</script>";	
}

?>