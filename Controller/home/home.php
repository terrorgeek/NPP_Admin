<?php
require_once('../../session_mysql.php');
session_start(); 
require_once('../../connector.ini.php');
if($_SESSION['username']=="")
{
	echo "<script language=\"JavaScript\">alert('用户名失效，请重新登录！!');";
	echo "window.location.href=\"../login/index.php\"</script>";
}
$admin_level = $_SESSION['admin_level'];
$username = $_SESSION['username'];
$userid = $_SESSION['userid'];
$outpagelist = "";
if($admin_level == 1)
{
	$sql_search = "SELECT nppadmin_log.*,user_name,nppadmin_admin_level.level_name,name FROM nppadmin_log  JOIN nppadmin_log_info ON nppadmin_log_info.id=nppadmin_log.action  JOIN nppadmin_user ON nppadmin_log.user_id=nppadmin_user.user_id  JOIN nppadmin_admin_level ON nppadmin_user.admin_level=nppadmin_admin_level.level_id ORDER BY time DESC LIMIT 0,10";//取”超级管理员“权限最近10条日志信息
}
else
{
	$sql_search = "SELECT nppadmin_log.*,user_name,nppadmin_admin_level.level_name,name FROM nppadmin_log  JOIN nppadmin_log_info ON nppadmin_log_info.id=nppadmin_log.action  JOIN nppadmin_user ON nppadmin_log.user_id=nppadmin_user.user_id  JOIN nppadmin_admin_level ON nppadmin_user.admin_level=nppadmin_admin_level.level_id  WHERE user_name = '".$username."'ORDER BY time DESC LIMIT 0,10";//取”非超级管理员“权限最近10条日志信息
}
$res_search = $db->query($sql_search);
while($out_search = $db->fetch_array($res_search))
{
	$outpagelist .= "<tr>";
		$outpagelist .= "<td>".$out_search['time']."</td>";
		$outpagelist .= "<td>".$out_search['level_name']."</td>";
		$outpagelist .= "<td>".$out_search['user_name']."</td>";
		$outpagelist .= "<td>".$out_search['name']."</td>";
		$outpagelist .= "<td>".$out_search['result']."</td>";
	$outpagelist .= "</tr>";
}
$sql_lasttime = "SELECT time FROM nppadmin_log WHERE user_id = '".$userid."' ORDER BY time DESC LIMIT 1,1";//取上次登录时间
$res_lasttime = $db->query($sql_lasttime);
$out_lasttime = $db->fetch_array($res_lasttime);
$version="select version from npp_version where id='1'";
$query_version=$db->query($version);
$result_query_version=$db->fetch_array($query_version);
$smarty->assign("version",$result_query_version["version"]);
$smarty->assign("lasttime",$out_lasttime[0]);
$smarty->assign("outpagelist",$outpagelist);
$smarty->display("home/home.htm");
?>