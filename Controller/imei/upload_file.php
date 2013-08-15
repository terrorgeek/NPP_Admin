<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
$msg="";
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

if(!ereg("^[ a-z0-9]*$",$names))
{
	error_post('文件名格式错误，请重新上传!');
}

if ($error> 0)
{		
	error_post($error);
}
	
$listfile = file_get_contents($tmp_name);

$imei_list = preg_split ("/[\s,;]+/",$listfile); //过滤号码
//成功导入的个数
$j_s=0;
//失败导入的个数
$j_f=0;

////判断文件的类型，看看是否有恶意上传
//if($type=="image/gif"||$type=="image/jpeg")
//{
//	echo "<script>alert('请上传合法的csv文件!');";
//	echo "window.location.href=\"imei_upload.php\"</script>";	
//	exit();
//}
if(!preg_match("/^[0-9]+$/",$imei_list[0]))
{
		echo "<script>alert('请上传合法的csv文件!');</script>";
	    echo "<script>window.location.href=\"imei_upload.php\"</script>";	
	    exit();
}
//两次循环，第一次检查所有的是否合法，不合法一条都不插，报错并跳出
//第一次
for($k=0;$k<(count($imei_list));$k++)
{
	if(trim($imei_list[$k])=='')
	  continue;
	  
	if(strlen($imei_list[$k])==15 && preg_match("/^[0-9]+$/",$imei_list[$k]))
	{
	   
	} 
	else
	{
      echo "<script>alert('存在非法数据,导入失败!');";
      echo "window.location.href=\"imei_upload.php\"</script>";
      exit();
      $j_f++;
	}
}
//第二次
for($k=0;$k<(count($imei_list));$k++)
{
	if(trim($imei_list[$k])=='')
	  continue;
	  
	if(strlen($imei_list[$k])==15 && preg_match("/^[0-9]+$/",$imei_list[$k]))
	{
	   $sql_insert = "INSERT INTO npp_testimei VALUES ('".$imei_list[$k]."')";//将正确IMEI号入库
	   $db->query($sql_insert);
		$j_s++;
	} 
	else
	{
      $msg=$msg."	".$imei_list[$k];
      $j_f++;
	}
}

LogRecord($_SESSION ["userid"],28,"导入IMEI号",$db);
$db->close();

if($msg=="")
  {
	echo "<script language=\"JavaScript\">alert('测试手机IMEI全部成功导入！');";
	echo "window.location.href=\"imei_upload.php\"</script>";	
  }else
  {
    echo "<script language=\"JavaScript\">alert('导入测试手机IMEI完成，成功导入".$j_s."条！');";
	echo "</script>";	
//	echo "<script language=\"JavaScript\">alert('".$msg."格式错误！');";
    echo "<script language=\"JavaScript\">alert('".$j_s."条导入成功!');";		
    echo "<script language=\"JavaScript\">alert('".$j_f."条导入失败!');";	
	echo "window.location.href=\"imei_upload.php\"</script>";	
  }
function error_post($error_message)
{
	echo "<script language=\"JavaScript\">alert('$error_message');";
	echo "window.location.href=\"imei_upload.php\"</script>";	
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