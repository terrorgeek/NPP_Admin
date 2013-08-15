<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
RoleVerify('18',$db);
if(isset($_GET['user_id']))
{
	$user_id = $_GET['user_id'];
}

//验证一下是不是数字
if(!preg_match("/[0-9]/", $user_id))
{
	echo "<script>alert('请求非法!');</script>";
	echo "<script>window.location.href='authority_management.php'</script>";
}
if(preg_match("/and|or|select|union/", $user_id))
{
	echo "<script>alert('请求非法!');</script>";
	echo "<script>window.location.href='authority_management.php'</script>";
}
//这里要查询一下user_id是否在数据库中存在
$sql_search_user_id="select * from nppadmin_user where user_id='".$user_id."' and active='0'";
$query=mysql_query($sql_search_user_id);
$result_user_id=mysql_fetch_array($query);
if($result_user_id["user_id"]==""||$result_user_id["user_id"]==null)
{
	echo "<script>alert('请求非法!');</script>";
	echo "<script>window.location.href='authority_management.php'</script>";
}


//查找page表所有信息
$sql_page_info = "SELECT * FROM nppadmin_page";
$res_page_info = $db->query($sql_page_info);
$name[0]="";
$parent[0]="";
while($page_info = $db->fetch_array($res_page_info))
{
	$name[]=$page_info['page_name'];
	$parent[]=$page_info['parent_id'];
}
//查找管理员的角色
$sql_find_level = "SELECT admin_level FROM nppadmin_user WHERE user_id = '".$user_id."'";
$res_find_level = $db->query($sql_find_level);
$out_find_level = $db->fetch_array($res_find_level);
if($out_find_level['admin_level']==1)
{
	echo "<script language=\"JavaScript\">alert('超级管理员不能重新分配权限!');";
	echo "window.location.href=\"authority_management.php\"</script>";	
}

	//根据角色显示其对应页面钩选框
	$sql_find_root_page = "SELECT page_id FROM nppadmin_admin_prev_match WHERE level_id = '".$out_find_level['admin_level']."' ORDER BY page_id ";
	$res_find_root_page = $db->query($sql_find_root_page);
	//显示权限钩选页面
	$page_out = "";
	$page_out .= "<form name = \"role_list\" id = \"role_list\" action=\"role_match_submit.php?user_id=".$user_id."\" method=\"post\">";
	while($root_page = $db->fetch_array($res_find_root_page))
	{
		$page_out .= "<input type=\"checkbox\" name=\"".$root_page['page_id']."\" checked disabled=\"disabled\">".$name[$root_page['page_id']]."<br>";
		$sql_find_chhild_page = "SELECT page_id FROM nppadmin_page WHERE parent_id = '".$root_page['page_id']."'";
		$res_find_chhild_page = $db->query($sql_find_chhild_page);
		while($find_chhild_page = $db->fetch_array($res_find_chhild_page))
		{
			$page_out .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			$page_out .= "<input type=\"checkbox\" name=\"".$find_chhild_page['page_id']."\" ".check_status($user_id,$find_chhild_page['page_id'],$db).">".$name[$find_chhild_page['page_id']]."<br>";
		}
	}
	$db->close();
	$page_out .= "<br>";
	$page_out .="<input type=button name=\"test\" class=\"button\" onclick=\"javascript:role_confirm();\" value=\"提交\">";
	$page_out .= "</form>";
	function check_status($user_id,$page_id,$db)
	{
		//include("../../connector.php");
		$sql_match_user = "SELECT * FROM nppadmin_user_page_match WHERE user_id = '".$user_id."' AND page_id = '".$page_id."'";
		$res_match_user = $db->query($sql_match_user);
		$out_match_user= $db->fetch_array($res_match_user);
		if($out_match_user['id']!='')
		{
			return "checked";
		}
	}
$smarty->assign("page_out",$page_out);
$smarty->display("system_management/role_match.htm");
?>