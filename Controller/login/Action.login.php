<?php
require_once('../../session_mysql.php');
session_start(); 
//require_once('../../session_user_start.php');
header("content-type:text/html; charset=utf-8");
require_once('../../connector.ini.php');
$name = $_POST["name"];
$password = $_POST["password"];
$identify = $_POST["identify"];
$_se_iden = $_SESSION["validateCoder"];

$safe_name = mysql_real_escape_string($name);
$password_md5 = md5($password);
//print_r($_SESSION);
//die("session".$_se_iden."input".$identify);
/*登录信息验证*/
if(strcmp($identify,$_se_iden)==0)//验证验证码
{
	$_user_tab = "nppadmin_user";//账号名密码表
	$sql = "SELECT user_name,user_id FROM ".$_user_tab." WHERE user_name='".$safe_name."' and active='0' ";
	$res = $db->query($sql);
	$out = $db->fetch_array($res);
	/*验证用户名是否有效*/
	if($out['user_id']=="")
	{
		echo "<script language=\"JavaScript\">alert('用户名或密码错误，请重新登录！');";
		echo "window.location.href=\"index.php\"</script>";	
	}
	else
	{
		///====================查询用户信息赋值给session=======================
		$_sql = "SELECT * FROM $_user_tab WHERE user_name = '$safe_name' AND password = '$password_md5'";	
		$res = $db->query($_sql);
		$out = $db->fetch_array($res);
		if($out['user_id']!=''&&$out['admin_level']!='')
		{
			$_SESSION ["userid"] = $out ['user_id'];
			$_SESSION ["username"] = $out ['user_name'];
			$_SESSION ["admin_level"] = $out ['admin_level'];
			
			$sql_role = "SELECT level_name FROM nppadmin_admin_level WHERE level_id = '".$_SESSION ["admin_level"]."'";
			$res_role = $db->query($sql_role);
			$out_role = $db->fetch_array($res_role);
			$role_name = $out_role[0];
			$_SESSION ["role_name"] = $role_name;
			LogRecord($_SESSION ["userid"],16,"登录",$db);
			echo "<script language=\"JavaScript\">";
			echo "window.location.href=\"../home/home.php\"</script>";	
			exit;
		}
		else
		{
			echo "<script language=\"JavaScript\">alert('用户名或密码错误，请重新登录！');";
			echo "window.location.href=\"index.php\"</script>";
			exit;
		}		
	}
}
else
{
	echo "<script language=\"JavaScript\">alert('验证码有误，请重新输入！');";
	echo "window.location.href=\"index.php\"</script>";
	exit;
}
?>