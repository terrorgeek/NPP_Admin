<?php
require_once('../../session_mysql.php');
session_start();
header("content-type:text/html; charset=utf-8");
require_once('../../connector.ini.php');
require_once('../../Model/mail_usual.php');

if(isset($_GET['app_id'])&&isset($_GET['new_status'])&&isset($_GET['old_status']))
{
	$app_id = $_GET['app_id'];
	$new_status = $_GET['new_status'];
	$old_status = $_GET['old_status'];
	if(isset($_GET['confirm_status']))
	{
		$confirm_status = $_GET['confirm_status'];
	}
	else
	{
		$confirm_status = 0;
	}
	//===============根据app_id查找其状态id（更改前）==================
	$sql_find_status = "SELECT status FROM nppadmin_app_info WHERE app_id = ".$app_id."";
	$res_find_status = $db->query($sql_find_status);
	$out_find_status = $db->fetch_array($res_find_status);
	//===============根据产品状态id查找其对应状态（更改前）==================
	$sql_status_info = "SELECT status FROM nppadmin_app_status WHERE id = ".$out_find_status[0]."";
	$res_status_info = $db->query($sql_status_info);
	$out_status_info = $db->fetch_array($res_status_info);	

	//===============若有人修改过该内容，则进行选择提示。选择“确定”，则重新载入页面，并将修改位--confirm_status置为“1”=============================
	if($old_status != $out_find_status[0]&&$confirm_status==0)
	{
		echo "<script language=\"JavaScript\">";
		echo "var s=confirm(\"已有人修改过该内容，是否继续修改?\");	";
		echo "if (s==true)	";
		echo "{	";
		echo "window.location.href=\"change_status.php?app_id=".$app_id."&new_status=".$new_status."&old_status=".$old_status ."&confirm_status=1\";";
		echo "}	";
		echo "else	";
		echo "{	";
		echo "window.location.href=\"app_select_edit.php\";";
		echo "}	";
		echo "</script>";	 
	}
	if(($old_status == $out_find_status[0])||$confirm_status == 1)
	{
		//===============根据传递过来的参数'new_status'查找其对应状态（更改后）==================
		$sql_status_info_modify = "SELECT status FROM nppadmin_app_status WHERE id = ".$new_status."";
		$res_status_info_modify = $db->query($sql_status_info_modify);
		$out_status_info_modify = $db->fetch_array($res_status_info_modify);
			
		//================根据传递过来的参数'new_status'来修改其状态===================================
		$now = date("Y-m-d H:i:s");
		$sql_update = "UPDATE nppadmin_app_info SET status='".$new_status."' ,change_status_time ='$now' WHERE app_id='".$app_id."'";
	
		$res_update = $db->query($sql_update);
		
		$sql_find_cp = "SELECT nppadmin_app_info.app_uptime,cp_name,app_name,nppadmin_cp_info.cp_email FROM nppadmin_app_info JOIN nppadmin_cp_info ON nppadmin_app_info.cp_id = nppadmin_cp_info.cp_id WHERE nppadmin_app_info.app_id = ".$app_id."";
		$res_find_cp = $db->query($sql_find_cp);
		$out_find_cp = $db->fetch_array($res_find_cp);
		date_default_timezone_set('PRC'); 
		$pass_time = date("Y-m-d H:i:s");
	//	$content = "<h1>".$out_find_cp['cp_name']."：您于".$out_find_cp['app_uptime']."上传的".$out_find_cp['app_name']."已于".$pass_time."由".$out_status_info[0]."变更为".$out_status_info_modify[0]."。<br>NOKIA</h1>";
		$content="尊敬的开发者：<br><br>您在诺基亚商店本地支付平台提交的内容".$out_find_cp['app_name']."状态已经从".$out_status_info[0]."变更为".$out_status_info_modify[0]."。<br><br>感谢您的支持！<div style=\"text-align:right;\"><span>诺基亚内容审核团队</span></div><div style=\"text-align:right;\"><span>".date('Y年m月d日')."</span></div>";
		//send_mail($out_find_cp['cp_email'], "来自NOKIA——您上传的应用状态已变更！", $content, "../app/app_audit.php");
        $mailsubject="诺基亚商店本地支付平台内容状态变更";
		sendmail($content,$out_find_cp['cp_email'],$mailsubject);
		
		LogRecord($_SESSION ["userid"],19,"\"".$out_find_cp['app_name']."\"由\"".$out_status_info[0]."\"修改为\"".$out_status_info_modify[0]."\"",$db);

		echo "<script language=\"JavaScript\">alert('内容状态修改成功!');";
		echo "history.go(-1);";
		echo "</script>";
	}

}
//function sendmail($msg,$sendto)
//{	
//	$headers  = "MIME-Version: 1.0\n";
//	$headers .= "Content-type: text/html; charset=GB2312\n";
//	$headers .= "FROM:  gateway@resoure \n";
//	$subject = iconv('utf-8','gb2312','来自NOKIA:您上传的内容状态已变更！');
//    $msg = iconv('utf-8','gb2312',$msg);
//   // $sendto = 'zhenfei@staff.sina.com.cn';
//	mail($sendto,$subject,$msg,$headers);
//}
?>