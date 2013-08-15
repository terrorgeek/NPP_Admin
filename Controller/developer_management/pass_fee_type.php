<?php 
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
//require_once('../../Model/mail_usual.php');

$fee_point=$_POST["fee_point"];
$fee_times=$_POST["fee_times"];
$cp_id=$_POST["fee"];
$is_same=$_POST["is_same"];
$cp_email=$_POST["cp_email"];
$reason=$_POST["reason_".$cp_id];

//if(isset($_GET["reason"]))
//{
//	$reason=$_GET["reason"];
//}
//if(isset($_SESSION["fee_point"]))
//{
//	$fee_point=$_SESSION["fee_point"];
//}
//if(isset($_SESSION["fee_times"]))
//{
//	$fee_times=$_SESSION["fee_times"];
//}
//if(isset($_SESSION["cp_id"]))
//{
//	$cp_id=$_SESSION["cp_id"];
//}
//if(isset($_SESSION["is_same"]))
//{
//	$is_same=$_SESSION["is_same"];
//}
//if(isset($_SESSION["cp_email"]))
//{
//	$cp_email=$_SESSION["cp_email"];
//}

//$_SESSION["fee_point"]=null;
//$_SESSION["fee_times"]=null;
//$_SESSION["fee"]=null;
//$_SESSION["is_same"]=null;
//$_SESSION["cp_email"]=null;
$previous_fee_type="";
$later_fee_type="";

//if(isset($_POST["single_single"]))
//{
//	$single_single=$_POST["single_single"];
//}
//if(isset($_POST["single_multiple"]))
//{
//	$single_multiple=$_POST["single_multiple"];
//}
//if(isset($_POST["multiple_single"]))
//{	
//	$multiple_single=$_POST["multiple_single"];
//}
//if(isset($_POST["multiple_multiple"]))
//{
//	$multiple_multiple=$_POST["multiple_multiple"];
//}
//总判断语句
$sql_pass_fee="select cp_id,id from npp_point_type_relation where cp_id='".$cp_id."'";
$result_search_cp_id=$db->query($sql_pass_fee);
$result_search_cp_id2=$db->query($sql_pass_fee);
$result=$db->fetch_array($result_search_cp_id);
//=============================在该开发者在npp_point_type_relation表中无数据时
//                             开始往npp_point_type_relation里插入数据===============================
//if(!$result)
//{   //如果没有，那就加上
//
//	$sql_insert1="insert into npp_point_type_relation (cp_id,point_type_id) 
//	             values ('".$cp_id."','".$single_single."')";
//	$db->query($sql_insert1);
//    
//   
//    $sql_insert2="insert into npp_point_type_relation (cp_id,point_type_id) 
//	             values ('".$cp_id."','".$single_multiple."')";	
//    $db->query($sql_insert2);
//    
//    
//    $sql_insert3="insert into npp_point_type_relation (cp_id,point_type_id) 
//	             values ('".$cp_id."','".$multiple_single."')";	
//    $db->query($sql_insert3);
//    
//    
//    $sql_insert4="insert into npp_point_type_relation (cp_id,point_type_id) 
//	             values ('".$cp_id."','".$multiple_multiple."')";	
//    $db->query($sql_insert4);
//    
//}
//==========================插完了===========================



//====================下面是当开发者在npp_point_type_relation表中有数据时要
//                    进行的更新计费类型的操作===================================
//如果该开发者在表中有数据的话。。。。
//$array=array();
//while($cp_id_and_id=$db->fetch_array($result_search_cp_id2))
//{
//	array_push($array,$cp_id_and_id["id"]);
//}
if($result)
{
    if($fee_point=="single_point"&&$fee_times=="single_times")
    {
    	$temp1="single_single";
        if($is_same!=$temp1)
        {
        	$sql_update1="update npp_point_type_relation set point_type_id=1 where cp_id='".$cp_id."'";
     		$db->query($sql_update1);
        }
    }
    if($fee_point=="single_point"&&$fee_times=="multiple_times")
    {
    	$temp2="single_multiple";
    	if($is_same!=$temp2)
    	{
    		$sql_update2="update npp_point_type_relation set point_type_id=2 where cp_id='".$cp_id."'";
     		$db->query($sql_update2);
    	}
    }
    if($fee_point=="multiple_point"&&$fee_times=="single_times")
    {
    	$temp3="multiple_single";
    	if($is_same!=$temp3)
    	{
    		$sql_update3="update npp_point_type_relation set point_type_id=3 where cp_id='".$cp_id."'";
     		$db->query($sql_update3);
    	}
    }
    if($fee_point=="multiple_point"&&$fee_times=="multiple_times")
    {
    	$temp4="multiple_multiple";
    	if($is_same!=$temp4)
    	{
    		$sql_update4="update npp_point_type_relation set point_type_id=4 where cp_id='".$cp_id."'";
     		$db->query($sql_update4);
    	}
    }
//		$sql_update1="update npp_point_type_relation set point_type_id='".$single_single."' where cp_id='".$cp_id."' and id='".$array[0]."'";
//		$db->query($sql_update1);
//
//
//		$sql_update2="update npp_point_type_relation set point_type_id='".$single_multiple."' where cp_id='".$cp_id."' and id='".$array[1]."'";
//		$db->query($sql_update2);
//	
//
//		$sql_update3="update npp_point_type_relation set point_type_id='".$multiple_single."' where cp_id='".$cp_id."' and id='".$array[2]."'";
//		$db->query($sql_update3);
//		
//		
//		$sql_update4="update npp_point_type_relation set point_type_id='".$multiple_multiple."' where cp_id='".$cp_id."' and id='".$array[3]."'";
//		$db->query($sql_update4);
	
}
//==========================更新完了=============================

//判断之前计费类型
if($is_same=="single_single")
{
	$previous_fee_type="单计费点 单次计费";
}
else if($is_same=="single_multiple")
{
	$previous_fee_type="单计费点 多次计费";
}
else if($is_same=="multiple_single")
{
	$previous_fee_type="多计费点 单次计费";
}
else if($is_same=="multiple_multiple")
{
	$previous_fee_type="多计费点 多次计费";
}
//判断之后的计费类型
if($fee_point=="single_point"&&$fee_times=="single_times")
{
	$later_fee_type="单计费点 单次计费";
}
else if($fee_point=="single_point"&&$fee_times=="multiple_times")
{
	$later_fee_type="单计费点 多次计费";
}
else if($fee_point=="multiple_point"&&$fee_times=="single_times")
{
	$later_fee_type="多计费点 单次计费";
}
else if($fee_point=="multiple_point"&&$fee_times=="multiple_times")
{
	$later_fee_type="多计费点 多次计费";
}
//向日志中插入操作信息，但要先查出该开发人员
$sql_select_developer="select cp_name,cp_user_name from nppadmin_cp_info where cp_id='".$cp_id."'";
$user=$db->query($sql_select_developer);
$out_user=$db->fetch_array($user);
$date=date("Y-m-d H:i:s");
$sql_insert_log="insert into nppadmin_log (user_id,action,result,time) values ('".$_SESSION["userid"]."','26','开发者".$out_user["cp_name"]."的计费类型由".$previous_fee_type."变为:".$later_fee_type."','".$date."')";
$flag=$db->query($sql_insert_log);

//向数据库中插入原因
$sql_insert_reason="insert into npp_point_type_change_reason (cp_id,type_change_reason) values ('".$cp_id."','".$reason."')";
$db->query($sql_insert_reason);

//$subject="来自NOKIA——计费类型变更通知";

$mailsubject = "诺基亚商店本地支付平台开发者计费类型变更";
$mailsubject = "=?UTF-8?B?".base64_encode($mailsubject)."?=";

//$content="您的计费类型发生变更，具体如下：
//由(".$previous_fee_type.")变更为(".$later_fee_type.")
//原因：".$reason."
  //                                   NOKIA";

//$content = addslashes("<h1>您的计费类型发生变更，具体如下：<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;由(".$previous_fee_type.")变更为(".$later_fee_type.")<br> 
$content="尊敬的开发者：<br><br>您在诺基亚商店本地支付平台的开发者计费类型已经从".$previous_fee_type."变更为".$later_fee_type."。<br><br>感谢您的支持！<div style=\"text-align:right;\"><span>诺基亚内容审核团队</span></div><div style=\"text-align:right;\"><span>".date('Y年m月d日')."</span></div>";

//原因：".$reason."<br>NOKIA</h1>");

// postmail($cp_email,$mailsubject,$content);
sendmail($content,$cp_email,$mailsubject);
$_SESSION["email_flag"]="email_flag";
echo "<script language=\"JavaScript\">alert('更新计费类型成功!');";
echo "window.location.href=\"developer_check.php?div2=0\"</script>";


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