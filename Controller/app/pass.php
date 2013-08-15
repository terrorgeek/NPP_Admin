<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
//require_once('../../Model/mail_usual.php');
if(isset($_GET['app_id'])&&isset($_GET['time']))
{
	$now = date("Y-m-d H:i:s");
	$app_id = $_GET['app_id'];
	$time = $_GET['time'];
	$sql_check = "SELECT appinfo,status,imei from nppadmin_app_info where app_id='".$app_id."'";
	$res_check = $db->query($sql_check);
	$out_check = $db->fetch_array($res_check);
	if(($out_check['appinfo']=='')||($out_check['appinfo']=='\'\'')||($out_check['status']!=1)||($out_check['imei']=='')||($out_check['imei']=='\'\''))
	{
		echo "<script language=\"JavaScript\">alert('不能“通过”该应用！,请检查是否进行过测试！');";
		echo "window.location.href=\"app_audit.php?time=".$time."\"</script>";	
	}
	else
	{
		$sql_update = "UPDATE nppadmin_app_info SET status='2',audit_by='',imei = '' ,change_status_time ='$now' WHERE app_id='".$app_id."'";
		$res_update = $db->query($sql_update);

		$sql_find_cp = "SELECT nppadmin_app_info.app_uptime,cp_name,app_name,nppadmin_cp_info.cp_email FROM nppadmin_app_info JOIN nppadmin_cp_info ON nppadmin_app_info.cp_id = nppadmin_cp_info.cp_id WHERE nppadmin_app_info.app_id = ".$app_id."";
		$res_find_cp = $db->query($sql_find_cp);
		$out_find_cp = $db->fetch_array($res_find_cp);
		date_default_timezone_set('PRC'); 
		$pass_time = date("Y-m-d H:i:s");
		$mailsubject = "诺基亚商店本地支付平台内容审核结果";
		$mailsubject = "=?UTF-8?B?".base64_encode($mailsubject)."?=";
		$content = "尊敬的开发者：<br><br>您在诺基亚商店本地支付平台提交的内容".$out_find_cp["app_name"]."已经审核通过。如果您希望该内容在诺基亚应用商店中上线，请将该内容提交到诺基亚发布平台网站https://publish.ovi.com/<br><br>感谢您的支持！<br><br><div style=\"text-align:right;\"><span>诺基亚内容审核团队</span></div><div style=\"text-align:right;\"><span>".date('Y年m月d日')."</span></div>";
		//send_mail($out_find_cp['cp_email'],$mailsubject, $content, "../app/app_audit.php");
		
		sendmail($content,$out_find_cp['cp_email'],$mailsubject);
		//postmail($out_find_cp['cp_email'], $mailsubject, $content);
		LogRecord($_SESSION ["userid"],18,"通过\"".$out_find_cp['app_name']."\"",$db);
		
		echo "<script language=\"JavaScript\">alert('操作成功!');";
		echo "history.go(-1);</script>";	
	}

}
function postmail($to,$subject,$body)
{
	require("../../Model/class.phpmailer.php");

$mail = new PHPMailer();

$mail->IsSMTP();					// 启用SMTP
$mail->Host = "smtp.163.com";
$mail->CharSet = "UTF-8";			//SMTP服务器
$mail->SMTPAuth = true;					//开启SMTP认证
$mail->Username = "mxlandahj";			// SMTP用户名
$mail->Password = "520213";				// SMTP密码

$mail->From = "mxlandahj@163.com";			//发件人地址
$mail->FromName = "NOKIA";				//发件人
$mail->AddAddress($to, "开发者");	//添加收件人
//$mail->AddAddress("ellen@example.com");
//$mail->AddReplyTo("info@example.com", "Information");	//回复地址
$mail->WordWrap = 50;					//设置每行字符长度
/** 附件设置
$mail->AddAttachment("/var/tmp/file.tar.gz");		// 添加附件
$mail->AddAttachment("/tmp/image.jpg", "new.jpg");	// 添加附件,并指定名称
*/
$mail->IsHTML(true);					// 是否HTML格式邮件

$mail->Subject = $subject;			//邮件主题
$mail->Body    = $body;		//邮件内容
//$mail->AltBody = "This is the body in plain text for non-HTML mail clients";	//邮件正文不支持HTML的备用显示

if(!$mail->Send())
{
   echo "<script language=\"JavaScript\">alert('发送邮件失败');</script>";
}
}
?>