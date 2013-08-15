<?php 
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
RoleVerify('18',$db);
//生成随机密码函数，密码为数字。
function randomkeys($length)
{
    srand((double)microtime()*1000000);//create a random number feed.
	$ychar="0,1,2,3,4,5,6,7,8,9";
	$list=explode(",",$ychar);
	$authnum = "";
	for($i=0;$i<$length;$i++)
	{
		$randnum=rand(0,9); // 10;
		$authnum.=$list[$randnum];
	}	
	return $authnum;
}						
//验证表单录入信息
$flag = 0;$msg = "";
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
			$sql_samename_check = "SELECT user_name FROM nppadmin_user WHERE user_name = '".$loginname."' AND active = 0";
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
		$sql_sameemail_check = "SELECT user_email FROM nppadmin_user WHERE user_email = '".$email."' AND active = 0";
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
if($msg != "")
{
	echo "<script langusge = \"javascript\">alert(\"".$msg."\");";
	echo "window.location.href=\"add_admin.php\"</script>";	
}
if(isset($_POST['loginname'])&&isset($_POST['realname'])&&isset($_POST['email'])&&isset($_POST['admin_level'])&&$flag==0)
{
	date_default_timezone_set('PRC'); 
	$now_time = date("Y-m-d H:i:s");
	$sql_insert = "INSERT INTO nppadmin_user(user_id,password,user_name,user_real_name,user_email,admin_level,active,last_sendpasswd_time,create_time,delete_time) VALUES ('null','".md5(randomkeys(6))."','".$loginname."','".$realname."','".$email."','".$admin_level."',0,'','".$now_time."','')";
	//die($sql_insert);
	$db->query($sql_insert);
	$sql_role = "SELECT level_name FROM nppadmin_admin_level WHERE level_id = '".$_POST['admin_level']."'";
	$res_role = $db->query($sql_role);
	$out_role = $db->fetch_array($res_role);
	$role_name = $out_role[0];
	LogRecord($_SESSION ["userid"],21,"添加\"".$role_name."\"\"".$_POST['loginname']."\"",$db);
	$db->close();
	echo "<script language=\"JavaScript\">alert('添加成功!');";
	echo "window.location.href=\"role_management.php\"</script>";	
}
else
{
	$smarty->display("system_management/add_admin.htm");
}
?> 