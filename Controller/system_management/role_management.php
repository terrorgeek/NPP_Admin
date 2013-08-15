<?php 
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
RoleVerify('18',$db);
if(isset($_GET['user_id']))
{
	$user_id = $_GET['user_id'];
} 
else
{
	$user_id = "";
}
$sql = "SELECT nppadmin_user.*,nppadmin_admin_level.level_name FROM nppadmin_user join nppadmin_admin_level ON nppadmin_user.admin_level = nppadmin_admin_level.level_id WHERE nppadmin_user.active = 0";
$res = $db->query($sql);
$table_list = "";
 while($out =  $db->fetch_array($res))
{
	$table_list .=  "<tr>";
	$table_list .= "<td>".$out['level_name']."</td>";
	$table_list .= "<td>".$out['user_real_name']."</td>";
	$table_list .= "<td>".$out['user_name']."</td>";
	if($out['level_name']=="超级管理员")
	{
		$table_list .= "<td><a href = '#' title=\"修改\"></a>";
	}
	else
	{
		$table_list .= "<td><a href = 'admin_modify.php?userid=".$out['user_id']."' title=\"修改\"><img src=\"../../View/resources/images/icons/pencil.png\" alt=\"修改\" /></a>";
	}
	if($out['level_name']=="超级管理员")
	{
		$table_list .= "<a href = '#' onclick=\"javascript:del_confirm('".$out['user_id']."');\" title=\"删除\"></a>";
	}
	else
	{
		$table_list .= "<a href = '#' onclick=\"javascript:del_confirm('".$out['user_id']."');\" title=\"删除\"><img src=\"../../View/resources/images/icons/cross.png\" alt=\"删除\" /></a>";	
	}
	if($out['level_name']=="超级管理员")
	{
		$table_list .= "<a href = '#' onclick=\"javascript:pass_modify('".$out['user_id']."');\" title=\"修改密码\"><img src=\"../../View/resources/images/icons/hammer_screwdriver.png\" alt=\"修改密码\" /></a>";
	}
	else
	{
		if($out['user_id']!=$user_id)
		{
			$table_list .= "<a href = 'send_pass.php?user_id=".$out['user_id']."' title=\"下发密码\"><img src=\"../../View/resources/images/icons/hammer_screwdriver.png\" alt=\"下发密码\" /></a>";
		}
		else
		{
			$table_list .= "<a href = 'send_pass.php?user_id=".$out['user_id']."' title=\"下发密码\"><img src=\"../../View/resources/images/icons/hammer_screwdriver.png\" alt=\"下发密码\" /><font color=\"red\" ><br>密码已发送，请10分钟后再试！</font></a>";
		}
	}
$table_list .= "</tr>";	
}
 $smarty->assign("table_list",$table_list);
 $smarty->display("system_management/role_management.htm");
?>