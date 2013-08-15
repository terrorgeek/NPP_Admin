<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
if(isset($_GET['id'])&&isset($_GET['type']))
{
	$id = $_GET['id'];
	$type = $_GET['type'];
	$sql_update = "UPDATE nppadmin_billing_type SET descri='".$type."' WHERE id='".$id."'";
	 $db->query($sql_update);
	echo "<script language=\"JavaScript\">alert('修改成功!');";
	echo "window.location.href=\"billing_type.php\"</script>";	
}
?>