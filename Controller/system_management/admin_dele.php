<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
RoleVerify('18',$db);
if(isset($_GET['user_id']))
{
	$user_id = $_GET['user_id'];
	$sql_adminname = "SELECT user_name,admin_level FROM nppadmin_user WHERE user_id = '".$user_id."'";
	$res_adminname = $db->query($sql_adminname);
	$out_adminname = $db->fetch_array($res_adminname);
	$admin_name = $out_adminname[0];
	$admin_level = $out_adminname[1];
	$sql_role = "SELECT level_name FROM nppadmin_admin_level WHERE level_id = '".$admin_level."'";
	$res_role = $db->query($sql_role);
	$out_role = $db->fetch_array($res_role);
	$role_name = $out_role[0];
}
if($admin_level ==1)
{
	echo "<script language=\"JavaScript\">alert('超级管理员不可删除！!');";
	echo "window.location.href=\"role_management.php\"</script>";
}
else
{
	date_default_timezone_set('PRC'); 
	$now_time = date("Y-m-d H:i:s");
	$sql_del_user = "UPDATE nppadmin_user SET active = 1,delete_time = '".$now_time."' WHERE user_id='".$user_id."'";
	$sql_del_user_page = "DELETE FROM nppadmin_user_page_match WHERE user_id='".$user_id."'";
	$db->query($sql_del_user);//删除管理员信息
	$db->query($sql_del_user_page);//删除管理员权限页面信息
	LogRecord($_SESSION ["userid"],32,"删除\"".$role_name."\"\"".$admin_name."\"信息",$db);
	$db->close();
	echo "<script language=\"JavaScript\">alert('删除成功!');";
	echo "window.location.href=\"role_management.php\"</script>";
}
?>