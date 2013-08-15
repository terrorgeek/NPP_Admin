<?php
header("content-type:text/html; charset=utf-8");
include("../../smarty_inc.php");
require_once('../../Model/mysql_class.php');
require_once('../../config_inc.php');
require_once('../home/header.php');
require_once('../home/sidebar.php');
require_once('../home/footer.php');
$smarty->assign("header",$header);
$smarty->assign("sidebar",$sidebar);
$smarty->assign("footer",$footer);
$db =  new mysql(CONN,USER,PASS,DB_NAME,UTF8_SET);
//$db =  new mysql('localhost','root','','NokiaPaymentPlat',"utf8");
if(isset($_SESSION['username'])&&isset($_SESSION['admin_level']))
{
	$username = $_SESSION['username'];
	$admin_level = $_SESSION ["admin_level"];
}
else
{
	$username = "";
	$admin_level = "";
}
$sql_role = "SELECT level_name FROM nppadmin_admin_level WHERE level_id = '".$admin_level."'";
$res_role = $db->query($sql_role);
$out_role = $db->fetch_array($res_role);
$role_name = $out_role[0];

$sql_pageinfo = "SELECT page_path FROM nppadmin_page where page_id = 20";
$res_pageinfo = $db->query($sql_pageinfo);
$out_pageinfo = $db->fetch_array($res_pageinfo);
$smarty->assign("pageinfo",$out_pageinfo[0]);
$smarty->assign("rolename",$role_name);
$smarty->assign("username",$username);
function LogRecord($user_id,$modename,$content,$db)
{
      date_default_timezone_set('PRC');
	//$date = date("Y-m-d H:i:s");
	$insert_log="INSERT INTO nppadmin_log VALUES (null, '".$user_id."', '".$modename."','".$content."','".date("Y-m-d H:i:s")."')";
	$query=$db->query($insert_log);
}
function RoleVerify($_pageId,$db)
{
	if(isset($_SESSION["userid"]))
	{
		$userid = $_SESSION ["userid"];
	}
	else
	{
		$userid = null;
	}
	if($userid == null)
	{
		echo "<script language=\"javascript\">";
		echo "alert(\"用户名失效，请重新登录！\");";
		echo ("parent.window.location.href='../login/index.php';");
		echo "</script>";
	}
	else
	{
		//mysql_connect($serverhost,$dbusername,$dbpassword);
		//mysql_select_db($dbname); 
		$_role_ss = "SELECT id FROM nppadmin_user_page_match WHERE user_id=".$userid." and page_id=".$_pageId."";
		$_role_req = $db->query($_role_ss);
		$_role_result = $db->fetch_row($_role_req);

		if(!$_role_result)
		{
			$url = $_SERVER['HTTP_REFERER'];		
			echo "<script language=\"javascript\">";	  
			echo "alert('您无权访问该页面!');";	
			echo "parent.window.location.href='".$url."';"; 
			echo "</script>";
		}
	}
}
function get_point_detial($app_id)
{
	global $db;
	$sql = "SELECT * FROM npp_app_point WHERE app_id='".$app_id."'";
	$query = $db -> query($sql);
	while($result = $db -> fetch_array($query))
	{
		$app_point_detial[$result['app_point_num']] = $result; 
	}
	return $app_point_detial;
}
function sendmail($msg,$sendto,$subject)
{	
	$headers  = "MIME-Version: 1.0\n";
	$headers .= "Content-type: text/html; charset=GB2312\n";
	$headers .= "FROM:  gateway@resoure \n";
	$subject = iconv('utf-8','gb2312',$subject);
    $msg = iconv('utf-8','gb2312',$msg);
    //$sendto = 'zhenfei@staff.sina.com.cn';
	mail($sendto,$subject,$msg,$headers);
}
?>