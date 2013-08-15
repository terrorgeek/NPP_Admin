<?php
require_once('../../session_mysql.php');
session_start();
header("content-type:text/html; charset=utf-8");
require_once('../../connector.ini.php');
require_once('../../Model/mail.php');
RoleVerify('18',$db);

$randpwd = getRandomNum();
if(isset($_GET['user_id']))
{
	$user_id = $_GET['user_id'];
	$sql_find_info = "SELECT user_email,password,user_name,last_sendpasswd_time FROM nppadmin_user where user_id = '".$user_id."'";
	$res_find_info = $db->query($sql_find_info);
	$out = mysql_fetch_array($res_find_info);
} 
if($out['user_email']&&$out['password']&&$out['user_name']&&$out['last_sendpasswd_time'])
{
	date_default_timezone_set('PRC'); 
	$now_time = date("Y-m-d H:i:s");
	$last_time = $out['last_sendpasswd_time'];
	$times=date("Y-m-d H:i:s",strtotime("-600 seconds")); 
	if($times>=$last_time)
	{
		$md5_reset = md5($randpwd);
		$sql_reset_pwd = "update nppadmin_user set password='$md5_reset' where user_id = '".$user_id."'";
		$pwd_res = $db->query($sql_reset_pwd);
		
		$smtpserver = "smtp.163.com";//SMTP服务器
		$smtpserverport =25;//SMTP服务器端口
		$smtpusermail = "mxlandahj@163.com";//SMTP服务器的用户邮箱
		$smtpemailto = $out['user_email'];//发送给谁
		$smtpuser = "mxlandahj";//SMTP服务器的用户帐号
		$smtppass = "520213";//SMTP服务器的用户密码
		$mailsubject = "NOKIA OVI-Store系统管理平台密码";//邮件主题
		$mailsubject = "=?UTF-8?B?".base64_encode($mailsubject)."?=";
		$mailbody = "<h1>您的NPP管理系统登录密码为：".$randpwd ."请妥善保管！</h1>";//邮件内容
		$mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
		$smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
		//$smtp->debug = FALSE;//是否显示发送的调试信息
	//	$smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype);
		
		$mailsubject="诺基亚商店本地支付平台登录密码";
		$content="管理员您好：<br><br>您在诺基亚商店本地支付管理平台的密码为：<br/>".$randpwd ."<br><br>感谢您的支持！<div style=\"text-align:right;\"><span>诺基亚内容审核团队</span></div><div style=\"text-align:right;\"><span>".date('Y年m月d日')."</span></div>";
		sendmail($content,$out["user_email"],$mailsubject);
		
		LogRecord($_SESSION ["userid"],23,"为\"".$out['user_name']."\"下发密码",$db);

		$sql_update = "UPDATE nppadmin_user SET last_sendpasswd_time = '".$now_time."' WHERE user_id = '".$user_id."'";
		$res_update = $db->query($sql_update);
		echo "<script language=\"JavaScript\">alert('已将密码发送至邮箱!');";
		echo "window.location.href=\"role_management.php\"</script>";	
	}
	else
	{
		echo "<script language=\"JavaScript\">";
		echo "window.location.href=\"role_management.php?user_id=".$user_id."\"</script>";	
	}
}

function getRandomNum() {
	srand((double)microtime()*1000000);//create a random number feed.
	$ychar="0,1,2,3,4,5,6,7,8,9";
	$list=explode(",",$ychar);
	$authnum = "";
	for($i=0;$i<6;$i++){
		$randnum=rand(0,9); // 10+26;
		$authnum.=$list[$randnum];
	}
	return $authnum;
}
?>