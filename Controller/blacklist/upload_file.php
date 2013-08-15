<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
if(!is_uploaded_file($_FILES['upfile']['tmp_name']))
{
	error_post('请选择上传文件!');
}

$upfile=$_FILES["upfile"];
$name = $upfile["name"];
$type = $upfile["type"];
$size = ($upfile["size"]/1024);
$tmp_name = $upfile["tmp_name"];
$error = $upfile["error"];
$names = check_filename($name);

//判断文件大小
if($size>1024)
{

	error_post('文件过大，请重新上传!');
}

if(!ereg("^[0-9a-z]*$",$names))
{
	error_post('文件名格式错误，请重新上传!');
}

if ($error> 0)
{		
	error_post($error);
}
	
$listfile = file_get_contents($tmp_name);

$black_list = preg_split ("/[\s,;]+/",$listfile); //过滤号码
//成功导入的个数
$j_s=0;
//失败导入的个数
$j_f=0;

//判断文件的类型，看看是否有恶意上传
//if($type=="image/gif"||$type=="image/jpeg")
//{
//	echo "<script>alert('请上传合法的csv文件!');";
//	echo "window.location.href=\"blacklist_upload.php\"</script>";	
//	exit();
//}
if(!preg_match("/^[0-9]+$/",$black_list[0]))
{
		echo "<script>alert('请上传合法的csv文件!');";
	    echo "window.location.href=\"blacklist_upload.php\"</script>";	
	    exit();
}
//两次循环，第一次检查所有的是否合法，不合法一条都不插，报错并跳出
//第一次
for($k=0;$k<(count($black_list));$k++)
{
	if(trim($black_list[$k])=='')
	  continue;
	
	if(strlen($black_list[$k])==11 && substr($black_list[$k],0,1)=='1' && preg_match("/^[0-9]+$/",$black_list[$k]))
	{
		
	} 
	else
	{
		 echo "<script>alert('存在非法数据,导入失败!');</script>";
         echo "<script>window.location.href=\"blacklist_upload.php\"</script>";
         exit();
		 $j_f++;
	}
}
//第二次
for($k=0;$k<(count($black_list));$k++)
{
	if(trim($black_list[$k])=='')
	  continue;
	
	if(strlen($black_list[$k])==11 && substr($black_list[$k],0,1)=='1' && preg_match("/^[0-9]+$/",$black_list[$k]))
	{
		$sql_insert = "INSERT INTO npp_black_list(black_phone_num) VALUES ('".$black_list[$k]."')";//将正确手机号码入库
		$db->query($sql_insert);
		$j_s++;
	} 
	else
	{
		$j_f++;
	}
}

LogRecord($_SESSION ["userid"],20,"添加黑名单",$db);
$db->close();


echo "<script language=\"JavaScript\">alert('导入黑名单完成，成功导入".$j_s."条！');";
echo "window.location.href=\"blacklist_upload.php\"</script>";	



function error_post($error_message)
{
	echo "<script language=\"JavaScript\">alert('$error_message');";
	echo "window.location.href=\"blacklist_upload.php\"</script>";	
	exit;
}
function check_filename($name)
{
	$name = trim(strtolower($name));	
	if(!strstr($name,'.csv'))
	{
		error_post('文件格式错误，请重新上传!');
	}
	$len = strlen($name)-4;
	if($len<3 || $len>10)
	{
		error_post('文件名格式错误!');
	}
	return substr($name,0,$len);
}
?>