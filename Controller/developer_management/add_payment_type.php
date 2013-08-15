<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');

/* $url=$_SERVER["REQUEST_URI"];
$url=parse_url($url);
$url=$url[path]; */
if(isset($_GET['channel_name'])) {
	$channel_name = $_GET['channel_name'];
}
if(isset($_GET['channel_id'])) {
	$channel_id = $_GET['channel_id'];
}

$type_sql = "select name,id from npp_payment_type GROUP by name";
$payment_type_options = "";
$result=$db->query($type_sql);

$payment_type_options.="<option value='default' selected=\"selected\">请选择</option>";
while($out_app=$db->fetch_array($result)) {
	$payment_type_options .= "<option value=".$out_app['id'].">".$out_app['name']."</option>";
}

$source_sql = "select id ,name from npp_payment_source";
$payment_source_options = "";
$result2 = $db->query($source_sql);

$payment_source_options.="<option value='default' selected=\"selected\">请选择</option>";
while($out_app=$db->fetch_array($result2)) {
	$payment_source_options .= "<option value=".$out_app['id'].">".$out_app['name']."</option>";
}


$smarty->assign("channel_id",$channel_id);
$smarty->assign("channel_name",$channel_name);
$smarty->assign("payment_type_options",$payment_type_options);
$smarty->assign("payment_source_options",$payment_source_options);
$smarty->display("developer_management/add_payment_type.html");

?>
</body>
</html>