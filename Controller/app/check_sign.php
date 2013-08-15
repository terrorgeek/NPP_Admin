<?php 
require_once('../../connector.ini.php');
    //检查nppadmin_app_info表里的imei字段和appinfo和status
    $app_id="";
    if(isset($_POST["app_id"]))
    {
    	$app_id=$_POST["app_id"];
    	$sql_check="select imei,appinfo from nppadmin_app_info where app_id='".$app_id."'";
    	$result=$db->query($sql_check);
    	$result1=$db->fetch_array($result);
    	echo "<script>alert('".$result1["appinfo"]."');</script>";
    	die($result1["imei"]);
    	if($result1["imei"]==""||$result1["appinfo"]==""||$result1["appinfo"]==''||$result1["imei"]==NULL||$result1["appinfo"]==NULL)
    	{
    		echo "<script>alert('asfkjhaskfhaskfkkj');</script>";
    		echo "no";
    	}
    	else
    	{
    		echo "yes";
    	}
    }
    else
    {
    	echo "error";
    }
    
?>