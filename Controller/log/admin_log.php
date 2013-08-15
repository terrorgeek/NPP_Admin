<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
RoleVerify('21',$db);
$pagesize=10;
/*获取分页参数*/
if(isset($_REQUEST['currentpage']))
{
	$currentpage=$_POST['currentpage'];
}
else
{
	$currentpage=1;
}
/*获取总页数*/
if(isset($_POST['sumpage']))
{
	$sumpage=$_POST['sumpage'];
}
else
{
	$sumpage=1;
} 
/*记忆select框选择状态*/
function check_status($check)
{
	if(isset($_REQUEST[$check]))
	{
		return "checked";
	}
}
/*记忆input框输入值*/
function input_value($input)
{
	if(isset($_REQUEST[$input]))
	{
		return $_REQUEST[$input];
	}
}
if(isset($_REQUEST['mode_detail']))
{
	$mode_detail = $_REQUEST['mode_detail'];
}
else
{
	$mode_detail = "";
}
if(isset($_REQUEST['mode_status']))
{
	$mode_status = $_REQUEST['mode_status'];
}
else
{
	$mode_status = "";
}
//===========显示”登录名“select框内容============
$admin_level = $_SESSION['admin_level'];
$smarty->assign("admin_level",$admin_level);
$user_name_pageout = "";
if($admin_level==1)
{
	$sql = "SELECT * FROM nppadmin_user WHERE active = 0";
	$res = $db->query($sql);
	while($out = $db->fetch_array($res))
	{
		if(isset($_REQUEST['user_name'])&&$_REQUEST['user_name']==$out['user_name'])
		{
			$user_name_pageout .= "<option value=".$out['user_name']." selected=\"selected\">".$out['user_name']."</option>";
		}
		else
		{
			$user_name_pageout .= "<option value=".$out['user_name']." >".$out['user_name']."</option>";
		}
	}
}
else
{
	$user_name_pageout .= "<option value=".$_SESSION['username']." >".$_SESSION['username']."</option>";
}

//===========显示”操作模块“select框内容============
$sql = "SELECT * FROM nppadmin_log_info WHERE parent_id = 0  and level = 1";
$res = $db->query($sql);
$mode_status_pageout = "";
$mode_detail_outpage = "";

while($out = $db->fetch_array($res))
{	
	if(isset($_REQUEST['mode_status'])&&$_REQUEST['mode_status']==$out['name'])//主模块
	{
		$mode_status_pageout .= "<option value=".$out['name']." selected=\"selected\">".$out['name']."</option>";	
		$sql_detail = "SELECT * FROM nppadmin_log_info WHERE parent_id = ".$out['id']." and level = 2 ";
		$res_detail = $db->query($sql_detail);
		while($out_detail = $db->fetch_array($res_detail))
		{
			if(isset($_REQUEST['mode_detail'])&&$_REQUEST['mode_detail']==$out_detail['name'])//子模块
			{
				$mode_detail_outpage .= "<option value=".$out_detail['name']." selected=\"selected\">".$out_detail['name']."</option>";
			}
			else
			{
				$mode_detail_outpage .= "<option value=".$out_detail['name']." >".$out_detail['name']."</option>";
			}
		}
	}
	else
	{
		$mode_status_pageout .= "<option value=".$out['name']." >".$out['name']."</option>";
	}
}
$son_select_info = "";
$son_select_info = "<script language=\"javascript\">groupName = new Array();";
$sql_fine_detail = "SELECT * FROM nppadmin_log_info WHERE parent_id = 0 ";
$res_fine_detail = $db->query($sql_fine_detail);
while($out_fine_detail = $db->fetch_array($res_fine_detail))
{	
	$m = 0;
	//die($out_fine_detail['page_id']);
	$parentpage_name = $out_fine_detail['name'];	
	$sql_detail = "SELECT * FROM nppadmin_log_info WHERE parent_id = ".$out_fine_detail['id']." ";
	$res_detail = $db->query($sql_detail);
	$son_select_info .= "groupName['".$parentpage_name."'] = new Array();";
	while($out_detail = $db->fetch_array($res_detail))
	{
		$son_select_info .= "groupName['".$parentpage_name."'][".$m."] = '".$out_detail['name']."';";
		$m++;
	}
}
$son_select_info .= "</script>";
//die($son_select_info);
$smarty->assign("son_select_info",$son_select_info);
$outpagelist = "";
if(isset($_REQUEST['submit_test']))
{
	$sql_search = " SELECT nppadmin_log.*,user_name,nppadmin_admin_level.level_name,name FROM nppadmin_log  JOIN nppadmin_log_info ON nppadmin_log_info.id=nppadmin_log.action  JOIN nppadmin_user ON nppadmin_log.user_id=nppadmin_user.user_id  JOIN nppadmin_admin_level ON nppadmin_user.admin_level=nppadmin_admin_level.level_id WHERE ";//查询所有满足条件数据
	$sql_search_sum = "SELECT COUNT(*) FROM nppadmin_log JOIN nppadmin_log_info ON nppadmin_log_info.id=nppadmin_log.action JOIN nppadmin_user ON nppadmin_log.user_id=nppadmin_user.user_id JOIN nppadmin_admin_level ON nppadmin_user.admin_level=nppadmin_admin_level.level_id WHERE ";//查询总条数
	//时间输入条件的检查
	if(isset($_POST['date_check']))
	{
		if($_POST['stime']&&!$_POST['etime'])
		{
			$stime = $_POST['stime'];
			if(preg_match("/^((?:19|20)[\d]{2})-((0?[1-9]{1})|(1[0-2]{1}))-(([012]?[1-9]{1})|(3[0-1]{1})|([1-2]0))$/",$stime))
			{
				$sql_search.= " LEFT(time,10) >= '".$stime."' AND ";
				$sql_search_sum.= " LEFT(time,10) >= '".$stime."' AND ";
			}
			else
			{
				echo "<script language=\"JavaScript\">alert('日期输入不合法!');document.URL=location.href=\"admin_log.php\"; </script>";
				die();
			}
		}
		else if(!$_POST['stime']&&$_POST['etime'])
		{
			$etime = $_POST['etime'];
			if(preg_match("/^((?:19|20)[\d]{2})-((0?[1-9]{1})|(1[0-2]{1}))-(([012]?[1-9]{1})|(3[0-1]{1})|([1-2]0))$/",$etime))
			{
				$sql_search.= " LEFT(time,10) <= '".$etime."' AND ";
				$sql_search_sum.= " LEFT(time,10) <= '".$etime."' AND ";
			}
			else
			{
				echo "<script language=\"JavaScript\">alert('日期输入不合法!');document.URL=location.href=\"admin_log.php\"; </script>";
				die();				
			}
		}
		else if($_POST['stime']&&$_POST['etime'])
		{
			$stime = $_POST['stime'];
			$etime = $_POST['etime'];
			if(preg_match("/^((?:19|20)[\d]{2})-((0?[1-9]{1})|(1[0-2]{1}))-(([012]?[1-9]{1})|(3[0-1]{1})|([1-2]0))$/",$stime)&&preg_match("/^((?:19|20)[\d]{2})-((0?[1-9]{1})|(1[0-2]{1}))-(([012]?[1-9]{1})|(3[0-1]{1})|([1-2]0))$/",$etime))
			{
				if($stime>$etime)
				{
					echo "<script language=\"JavaScript\">alert('日期输入不合法!');document.URL=location.href=\"admin_log.php\"; </script>";	
					die();
				}
				else
				{
					$sql_search.= " LEFT(time,10) >= '".$stime."' AND LEFT(time,10) <= '".$etime."' AND";
					$sql_search_sum.= " LEFT(time,10) >= '".$stime."' AND LEFT(time,10) <= '".$etime."' AND";
				}
			}
			else
			{
				echo "<script language=\"JavaScript\">alert('日期输入不合法!');document.URL=location.href=\"admin_log.php\"; </script>";	
				die();
			}
		}
		else {
			echo "<script language=\"JavaScript\">alert('日期输入不合法!');document.URL=location.href=\"admin_log.php\"; </script>";	
			die();
		}
	}
	if(isset($_POST['name_check']))
	{
		$user_name = $_POST['user_name'];
		$sql_search.= " user_name = '".$user_name."' AND ";
		$sql_search_sum.= " user_name = '".$user_name."' AND ";
	}
	if(isset($_POST['mode_status_check']))
	{
		
		if(isset($_POST['mode_detail'])&&isset($_POST['mode_status']))
		{
			$mode_status = $_POST['mode_status'];		
			$mode_detail = $_POST['mode_detail'];	
			$sql_mode = "SELECT level FROM nppadmin_log_info WHERE id = (SELECT parent_id FROM nppadmin_log_info WHERE name = '".$mode_detail."')";
			$res_mode = $db->query($sql_mode);
			$out_mode = $db->fetch_array($res_mode);
			//die($out_mode['level']);
			//die($mode_detail);
			if($mode_status=='全部')
			{
				$sql_search.= "";
				$sql_search_sum.= "";
			}
			if($mode_status!='全部'&&$mode_detail=='不限')
			{
				$sql_modeid = "SELECT id FROM nppadmin_log_info WHERE name = '".$mode_status."' AND level = 1";
				$res_modeid = $db->query($sql_modeid);
				$out_modeid = $db->fetch_array($res_modeid);
				
				$sql_logid = "SELECT id FROM nppadmin_log_info WHERE parent_id IN (SELECT id FROM nppadmin_log_info WHERE parent_id = ".$out_modeid[0]." AND level = 2) AND level = 3 ";
				$res_logid = $db->query($sql_logid);
				$sql_search.= " (action = ";
				$sql_search_sum.= " (action = ";
				while($out_logid = $db->fetch_array($res_logid))
				{
					$sql_search.= $out_logid['id']." OR action = ";
					$sql_search_sum.= $out_logid['id']." OR action = ";
				}
				$sql_search.= "  1=1) AND ";
				$sql_search_sum.= "  1=1) AND ";
			}
			//die($sql_search);
			if($mode_status!='全部'&&$mode_detail!='不限')
			{		
				$sql_modeid = "SELECT id FROM nppadmin_log_info WHERE name = '".$mode_detail."' AND level = 2";
				$res_modeid = $db->query($sql_modeid);
				$out_modeid = $db->fetch_array($res_modeid);
				
				$sql_logid = "SELECT id FROM nppadmin_log_info WHERE parent_id = ".$out_modeid['id']." AND level = 3 ";
				$res_logid = $db->query($sql_logid);
				$sql_search.= " (action = ";
				$sql_search_sum.= " (action = ";
				while($out_logid = $db->fetch_array($res_logid))
				{
					$sql_search.= $out_logid['id']." OR action = ";
					$sql_search_sum.= $out_logid['id']." OR action = ";
				}
				$sql_search.= "  1=1) AND ";
				$sql_search_sum.= "  1=1) AND ";
			}
		}
	}
	$sql_search.=" 1=1 ";
	//die($sql_search);
	$sql_search_sum.=" 1=1 ";
	/*取总查询条数*/
	$res_search_sum = $db->query($sql_search_sum);
	$out_search_sum = $db->fetch_array($res_search_sum);
	/*取总页数*/
	$sumpage=ceil($out_search_sum[0]/$pagesize);

	/*查询按搜索条件的结果，带分页*/
	$sql_search.=" ORDER BY time DESC limit ".($currentpage-1)*$pagesize.",".$pagesize." ";
	$res_search = $db->query($sql_search);

	while($out_search = $db->fetch_array($res_search))
	{
		$outpagelist .= "<tr>";
			$outpagelist .= "<td><div align=\"center\">".$out_search['time']."</div></td>";
			$outpagelist .= "<td><div align=\"center\">".$out_search['level_name']."</div></td>";
			$outpagelist .= "<td><div align=\"center\">".$out_search['user_name']."</div></td>";
			$outpagelist .= "<td><div align=\"center\">".$out_search['name']."</div></td>";
			$outpagelist .= "<td><div align=\"center\">".$out_search['result']."</div></td>";
		$outpagelist .= "</tr>"."\n";
	}
	$smarty->assign("out_search_sum",$out_search_sum[0]);
	$smarty->assign("currentpage",$currentpage);
	$smarty->assign("sumpage",$sumpage);
}
$smarty->assign("mode_detail",$mode_detail);
$smarty->assign("user_name_pageout",$user_name_pageout);
$smarty->assign("mode_status_pageout",$mode_status_pageout);
$smarty->assign("mode_detail_outpage",$mode_detail_outpage);

$smarty->assign("date_check_pageout",check_status("date_check"));
$smarty->assign("name_check_pageout",check_status("name_check"));
$smarty->assign("mode_status_check_pageout",check_status("mode_status_check"));

$smarty->assign("stime_pageout",input_value("stime"));
$smarty->assign("etime_pageout",input_value("etime"));

$smarty->assign("outpagelist",$outpagelist);
$smarty->display("log/admin_log.htm");
?>
