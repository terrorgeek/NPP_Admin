<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
if(isset($_GET['type']))
{
	$type = $_GET['type'];
}
$db->query("INSERT INTO nppadmin_billing_type(id,descri) VALUES(null,'".$type."')");
$db->close();
echo "<script language=\"JavaScript\">alert('新增成功!');";
echo "window.location.href=\"billing_type.php\"</script>";	
?>