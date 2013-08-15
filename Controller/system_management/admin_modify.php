<?php 
//生成随机密码函数，密码为大小写字母与数字组合。
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
RoleVerify('18',$db);						
//验证表单录入信息
$flag = 0;$msg = "";$admin_level = "";
if(isset($_GET['userid']))
{
	$userid = mysql_real_escape_string($_GET['userid']);
	$sql_find_info = "SELECT * FROM nppadmin_user WHERE user_id = '".$userid."' and admin_level<>'1' ";
	$res_find_info = $db->query($sql_find_info);
	$out_find_info = $db->fetch_array($res_find_info);
	if($out_find_info["user_id"]=="") {
		echo "<script language=\"JavaScript\">alert('该用户不存在！!');";
		echo "window.location.href=\"role_management.php\"</script>";
	}	
}
if(isset($_POST['loginname']))
{
	$loginname = $_POST['loginname'];
	if (!preg_match("/^[A-Za-z0-9]{1,20}$/", $loginname)) 
	{
		$msg .= "登录名输入错误，请重新输入！";
		$flag = 1;
	}
	else
	{
		if(!preg_match("/.*[A-Za-z].*$/", $loginname))
		{
			$msg .= "用户名必须有英文字符，请重新输入！";
			$flag = 1;
		}
		else
		{
			$sql_samename_check = "SELECT user_name FROM nppadmin_user WHERE user_name = '".$loginname."' AND user_id != ".$userid." AND active = 0";
			$res_samename_check = $db->query($sql_samename_check);
			$out_samename_check = $db->fetch_array($res_samename_check);
			if($out_samename_check[0]!="")
			{
				$msg .= "该用户已经存在，请重新输入！";
				$flag = 1;
			}
		}
	}
}
if(isset($_POST['realname']))
{
	$realname = $_POST['realname'];
	if ((!preg_match("/^[\x80-\xff_a-zA-Z0-9]+$/", $realname))||(preg_match("/[~！@#￥%……&*（）——+·-=；：‘“、？。》，《]+/", $realname))) 
	{
		$msg .= "真实姓名输入错误，请重新输入！";
		$flag = 1;
	}
}
if(isset($_POST['email']))
{
	$email = $_POST['email'];
	if(preg_match ("/[^a-zA-Z0-9@._-]/", $email))
	{
		$msg .= "邮箱格式错误,请重新输入！";
		$flag = 1;
	}
	if (!preg_match ("/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/", $email)) 
	{
		$msg .= "邮箱格式错误,请重新输入！";
		$flag = 1;
	} 
	else
	{
		$sql_sameemail_check = "SELECT user_email FROM nppadmin_user WHERE user_email = '".$email."'  AND user_id != ".$userid." AND active = 0";
		$res_sameemail_check = $db->query($sql_sameemail_check);
		$out_sameemail_check = $db->fetch_array($res_sameemail_check);
		if($out_sameemail_check[0]!="")
		{
			$msg .= "该邮箱已被使用，请更改邮箱！";
			$flag = 1;
		}
	}
}
if(isset($_POST['admin_level']))
{
	$admin_level = $_POST['admin_level'];
}
if($admin_level==1)
{
		echo "<script language=\"JavaScript\">alert('超级管理员不可修改！!');";
		echo "window.location.href=\"role_management.php\"</script>";	
}
else
{
	if($msg != "")
	{
		echo "<script langusge = \"javascript\">alert(\"".$msg."\");";
		echo "window.location.href=\"admin_modify.php?userid=".$userid."\"</script>";	
	}

	if(isset($_GET['userid'])&&isset($_POST['loginname'])&&isset($_POST['realname'])&&isset($_POST['email'])&&isset($_POST['admin_level'])&&$flag==0)
	{
		$sql_update = "UPDATE nppadmin_user SET user_name = '".$loginname."' , user_real_name = '".$realname."' , user_email = '".$email."' , admin_level = '".$admin_level."' WHERE user_id = '".$userid."'";
		$res_update = $db->query($sql_update);
		
		$sql_role = "SELECT level_name FROM nppadmin_admin_level WHERE level_id = '".$_POST['admin_level']."'";
		$res_role = $db->query($sql_role);
		$out_role = $db->fetch_array($res_role);
		$role_name = $out_role[0];
		
		LogRecord($_SESSION ["userid"],"系统管理-添加管理员","修改\'".$role_name."\'\"".$_POST['loginname']."\"",$db);
		$db->close();
		echo "<script language=\"JavaScript\">alert('修改成功!');";
		echo "window.location.href=\"role_management.php\"</script>";	
	}
}
$smarty->assign("out_find_info",$out_find_info);
$smarty->display("system_management/admin_modify.htm");
?> 
