<?php 
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
$sql = "SELECT * FROM nppadmin_billing_type";
$res = $db->query($sql);
$tablelist = "";

while($out = $db->fetch_array($res))
{
	$tablelist .= "<tr>";
	$tablelist .= "<td>".$out['descri']."</td>";
	$tablelist .= "<td><a href=\"#\" onclick=\"javascript:modify_name('".$out['id']."','".$out['descri']."')\" >修改</a></td>";
	$tablelist .= "<td><a href=\"#\" onclick=\"javascript:del_name('".$out['id']."')\" >删除</a></td>";
	$tablelist .= "</tr>";
}
$smarty->assign("tablelist",$tablelist);
$smarty->display("billing_type/billing_type.htm");
