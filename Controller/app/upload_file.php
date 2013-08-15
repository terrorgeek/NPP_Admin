<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
if (is_uploaded_file($_FILES['upfile']['tmp_name']))
{
	$upfile=$_FILES["upfile"];
	$name = $upfile["name"];
	$type = $upfile["type"];
	$size = ($upfile["size"]/1024);
	$tmp_name = $upfile["tmp_name"];
	$error = $upfile["error"];
	$names = substr($name,0,10);
	$types = substr($name,10,20);
	if(($types==".csv"||$types==".CSV")&&$size<=1024&&!preg_match("/^(((?:19|20)[\d]{2})-((0?[1-9]{1})|(1[1-2]{1}))-(([012]?[1-9]{1})|(3[0-1]{1})|([1-2]0))/d{2}$/",$names))
	{
		if ($error> 0)
		{
			echo "Error:".$error."<br/>";
		}
		else
		{
			//echo "Upload:".$name."<br />";
			//echo "Type:".$type."<br />";
			//echo "Size:".$size." Kb<br />";
			//echo "Stored in:".$tmp_name;
			$listfile = file_get_contents($tmp_name);
			//$listfile=preg_replace("/\r/", ",", $listfile);
			//die($listfile."aa");
			if(preg_match("/[^,\s0-9]+/",$listfile))
			{
				echo "<script language=\"JavaScript\">alert('内容格式错误!');";
				echo "window.location.href=\"blacklist_upload.php\"</script>";
			}
			$black_list = preg_split ("/[\s,;]+/",$listfile); //过滤号码
			$j=0;
			for($k=0;$k<(count($black_list)-1);$k++)
			{
				if(!preg_match("/^(13[0-9]|15[0|3|6|7|8|9]|18[7|8|9])\d{8}$/",$black_list[$k]))//匹配号码格式
				{
					echo "<script language=\"JavaScript\">alert('号码：".$black_list[$k]."格式错误！');";
					echo "window.location.href=\"blacklist_upload.php\"</script>";
				}
			}
			for($i=0;$i<count($black_list);$i++)
			{
				if(preg_match("/^(13[0-9]|15[0|3|6|7|8|9]|18[7|8|9])\d{8}$/",$black_list[$i]))//匹配号码格式
				{
					$sql_insert = "INSERT INTO npp_black_list(black_phone_num) VALUES ('".$black_list[$i]."')";//将正确手机号码入库
					$db->query($sql_insert);
					$j++;
				}
			}
			LogRecord($_SESSION ["userid"],20,"添加黑名单",$db);
			$db->close();
			

			echo "<script language=\"JavaScript\">alert('导入黑名单成功！');";
			echo "window.location.href=\"blacklist_upload.php\"</script>";	
		}
	}
	else
	{
		echo "<script language=\"JavaScript\">alert('文件格式错误，或大小超过限制，请重新上传!');";
		echo "window.location.href=\"blacklist_upload.php\"</script>";	
	}
}
else
{
	echo "<script language=\"JavaScript\">alert('上传失败，请重新上传!');";
	echo "window.location.href=\"blacklist_upload.php\"</script>";	
}
?>