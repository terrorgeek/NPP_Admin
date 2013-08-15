<?php 
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
RoleVerify('18',$db);
$page_out = "";
/*查询所有管理员信息*/
$sql = "SELECT nppadmin_user.*,nppadmin_admin_level.level_name FROM nppadmin_user JOIN nppadmin_admin_level ON nppadmin_user.admin_level = nppadmin_admin_level.level_id AND nppadmin_user.active =0";
$res = $db->query($sql);
$page_put = "";
while($out = $db->fetch_array($res))
{
	/*查询当前管理员拥有的主菜单权限个数*/
	$sql_find_menu_count = "SELECT COUNT(nppadmin_user_page_match.page_id) FROM nppadmin_user_page_match JOIN nppadmin_page ON nppadmin_user_page_match.page_id = nppadmin_page.page_id WHERE nppadmin_user_page_match.page_id IN (SELECT nppadmin_admin_prev_match.page_id FROM nppadmin_admin_prev_match) AND user_id = ".$out['user_id']."";
	$res_find_menu_count = $db->query($sql_find_menu_count);
	$out_find_menu_count = $db->fetch_array($res_find_menu_count);
	$page_put .= "<tr>";
	$page_put .= "<td>".$out['level_name']."</td>";
	$page_put .= "<td>".$out['user_real_name']."</td>";
	$page_put .= "<td>".$out['user_name']."</td>";
	$page_put .= "<td><a href = 'role_match.php?user_id=".$out['user_id']."'>分配</a>&nbsp;&nbsp;&nbsp;&nbsp;";
	$page_put .= "<a href= 'role_view.php?user_id=".$out['user_id']."' >查看</a></td>";
	$page_put .= "</tr>";


	$page_put .= "<div id=\"dwindow".$out['user_id']."\" style=\"position:absolute;background-color:#EBEBEB;cursor:hand;left:0px;top:0px;display:none\" onMousedown=\"initializedrag(event)\" onMouseup=\"stopdrag()\" onSelectStart=\"return false\">";
	$page_put .= "<div style=\"position:absolute;left:15px;top:0px;\">";
		/*查询当前管理员拥有的主菜单权限信息*/
		$sql_find_menu = "SELECT nppadmin_user_page_match.*,nppadmin_page.page_name FROM nppadmin_user_page_match JOIN nppadmin_page ON nppadmin_user_page_match.page_id = nppadmin_page.page_id WHERE nppadmin_user_page_match.page_id IN (SELECT nppadmin_admin_prev_match.page_id FROM nppadmin_admin_prev_match) AND user_id = ".$out['user_id']."";
		$res_find_menu = $db->query($sql_find_menu);
		while($out_find_menu = $db->fetch_array($res_find_menu))
		{	
			$page_put .= "<br><img onclick=\"javascript:view_detail('".$out['user_id']."_".$out_find_menu['page_id']."')\" src=\"../image/u54_original.png\" id=\"views".$out['user_id']."_".$out_find_menu['page_id']."\" name=\"views".$out['user_id']."_".$out_find_menu['page_id']."\" >".$out_find_menu['page_name']."";
			$page_put .= "<br>";
			/*查询当前管理员拥有的当前主菜单的子菜单权限*/
			$sql_role_detail = "SELECT nppadmin_user_page_match.*,nppadmin_page.page_name FROM nppadmin_user_page_match JOIN nppadmin_page ON nppadmin_user_page_match.page_id = nppadmin_page.page_id WHERE nppadmin_page.parent_id = ".$out_find_menu['page_id']." AND user_id = ".$out['user_id']."";
			$res_role_detail = $db->query($sql_role_detail);
			$page_put .= "<span id=\"view".$out['user_id']."_".$out_find_menu['page_id']."\" name=\"view".$out['user_id']."_".$out_find_menu['page_id']."\" style=\"display:none;\">";
			while($out_role_detail = $db->fetch_array($res_role_detail))
			{		
				$page_put .= "<font size=\"2\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$out_role_detail['page_name']."</font>";				
			}
			$page_put .= "</span>";			
		}
	
		//$heights = ($i-1)*21;//每增加一条驳回原因，需增加的高度

	$page_put .= "<br><br><input type=\"button\"  value=\"关闭\" style=\"position:absolute;left:160px;\" onclick=\"javascript:closeit();\" >";
	$page_put .= "</div>";
	$page_put .= "<div id=\"dwindowcontent\" style=\"height:100%\">";
	$page_put .= "</div>";
	$page_put .= "</div>";
}
$smarty->assign("page_put",$page_put);
$smarty->display("system_management/authority_management.htm");
?>