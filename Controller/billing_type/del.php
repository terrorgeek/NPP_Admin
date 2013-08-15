<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
if(isset($_GET['id']))
{
	$id = $_GET['id'];
}
$db->query("DELETE FROM nppadmin_billing_type WHERE id='".$id."'");
$db->close();
echo "<script language=\"JavaScript\">alert('已删除!');";
echo "window.location.href=\"billing_type.php\"</script>";	
?>