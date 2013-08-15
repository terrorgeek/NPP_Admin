<?php 
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');

//通过审核
if(isset($_GET["cp_id"]))
{
	$cp_id=$_GET["cp_id"];
	$submit_time=$_GET["submit_time"];
	$currentpage1=$_GET["currentpage1"];
	$sql_update_developer="update nppadmin_cp_info set cp_status=1 where cp_id='".$cp_id."'";
	$result=$db->query($sql_update_developer);
	if($result)
	{
		//开始发送邮件
		//首先要查出这个人的邮件
		$sql_find_developer="select cp_email from nppadmin_cp_info where cp_id='".$cp_id."'";
		$query=$db->query($sql_find_developer);
		$email=$db->fetch_array($query);
		$mailsubject = "诺基亚商店本地支付平台开发者审核结果";
				$mailsubject = "=?UTF-8?B?".base64_encode($mailsubject)."?=";
				$content="尊敬的开发者：<br><br>您在诺基亚商店本地支付平台提交的注册信息已经审核通过，请登录平台上传内容。<br><br>感谢您的支持！<br><br><div style=\"text-align:right;\"><span>诺基亚内容审核团队</span></div><div style=\"text-align:right;\"><span>".date('Y年m月d日')."</span></div>";
				sendmail($content,$email["cp_email"],$mailsubject);	
		echo "<script language=\"JavaScript\">alert('操作成功!');";
		echo "window.location.href=\"developer_check.php?div1=0&submit_test1=yes&submit_time=$submit_time&currentpage1=$currentpage1\"</script>";		
	}
	else
	{
		echo "<script language=\"JavaScript\">alert('操作失败!');";
		echo "window.location.href=\"developer_check.php?div1=0&submit_test1=yes&submit_time=$submit_time&currentpage1=$currentpage1\"</script>";	
	}
}
?>