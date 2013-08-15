<?php ob_start();
require_once('../../Model/mysql_class.php');
require_once('../../config_inc.php');
$db =  new mysql(CONN,USER,PASS,DB_NAME,UTF8_SET);
if(isset($_GET['time'])&&isset($_GET['id']))
{
	$id = $_GET['id'];
	$time = $_GET['time'];
	if(isset($_GET['page']) && $_GET['page']!='')
	{
	  $page=$_GET['page'];
	}
	else 
	{
	  $page='1';  
	}
	//通过app_id找应用
	
	$sql_search_app="select content_types from nppadmin_app_info where app_id='".$id."'";
	$temp=$db->query($sql_search_app);
	$result=$db->fetch_array($temp);
	if($result['content_types']=="Java")//如果是java版的，下载jad的版本
	{
		$sql_search="select * from npp_app_application where app_id='".$id."' AND application_suffix = 'jar'";
		$temp1=$db->query($sql_search);
		$result1=$db->fetch_array($temp1);
	}
	else
	{
		$sql_search="select * from npp_app_application where app_id='".$id."'";
		$temp1=$db->query($sql_search);
		$result1=$db->fetch_array($temp1);
	}
	if(!$result1)
	{ 
		echo "<script>alert(\"下载的文件不存在!\");";
		echo "history.go(-1);</script>";	
		exit;
	} 
	else 
	{
	    $data = $result1["application_data"];
	    $type = $result1["application_type"];
        $name = $result1["application_name"];
		header("Content-type:$type");
		//header('Content-Transfer-Encoding: binary' );
        header("Content-Disposition: attachment; filename=$name");
        echo $data;
	}
}
?>