<?php
require_once('../../session_mysql.php');
session_start();
header("content-type:text/html; charset=utf-8");
require_once('../../connector.ini.php');
require_once('../../Model/mail.php');
RoleVerify('18',$db);
$msg = "";
if(isset($_GET['user_id']))
{
	$user_id = $_GET['user_id'];
	$sql_find_info = "SELECT user_email,password,user_name FROM nppadmin_user where user_id = '".$user_id."'";
	$res_find_info = $db->query($sql_find_info);
	$out = mysql_fetch_array($res_find_info);
}
if(isset($_POST['identify']))
{
	$identify = $_POST['identify'];
}
else
{
	$identify = "";
}
$_se_iden = $_SESSION["validateCoder"];
if((strcmp($identify,$_se_iden)!=0)&&isset($_POST['identify']))
{
	$msg .= "验证码输入错误！";
} 
if(isset($_POST['pass_old']))
{
	$pass_old = $_POST['pass_old'];
}
else
{
	$pass_old = "";
}
if(isset($_POST['pass_new']))
{
	$pass_new = $_POST['pass_new'];
}
else
{
	$pass_new = "";
}
if(isset($_POST['pass_new_con']))
{
	$pass_new_con = $_POST['pass_new_con'];
}
else
{
	$pass_new_con = "";
}
$pass_old_md5 = md5($pass_old);
if($pass_old_md5!=$out['password']&&isset($_POST['pass_old']))
{
	$msg .= "原密码输入错误！";
}
if($pass_new_con!=$pass_new)
{
	$msg .= "两次新密码输入不一致！";
}
if ((!preg_match("/^[A-Za-z0-9]{6,22}$/", $pass_new_con))&&isset($_POST['pass_new_con'])) 
{
	$msg .= "密码必须为6到22位数字和英文字母的组合，请重新输入！";
}
if($msg != "")
{
	echo "<script langusge = \"javascript\">alert(\"".$msg."\");";
	echo "window.location.href=\"pass_modify.php?user_id=".$user_id."\"</script>";	
}
if($pass_old_md5==$out['password']&&$pass_new_con==$pass_new&&$msg=="")
{
		$pass_new_md5 = md5($pass_new);
		$sql_update = "UPDATE nppadmin_user SET password = '".$pass_new_md5."' WHERE user_id = '".$user_id."'";
		$res_update = $db->query($sql_update);
		echo "<script language=\"JavaScript\">alert('密码修改成功!');";
		echo "window.location.href=\"role_management.php\"</script>";	
}
$smarty->assign("user_id",$user_id);
$smarty->display("system_management/pass_modify.htm");
?>