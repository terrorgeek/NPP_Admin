<?php
require_once('../../session_mysql.php');
session_start(); 
//require_once('../../session_user_start.php');
require_once('../../connector.ini.php');
RoleVerify('8',$db);
$pagesize=10;
$time = '';
/*获取分页参数*/
if(isset($_REQUEST['currentpage']))
{
	$currentpage=$_REQUEST['currentpage'];
}
else
{
	$currentpage=1;
}
/*获取总页数*/
if(isset($_REQUEST['sumpage']))
{
	$sumpage=$_REQUEST['sumpage'];
}
else
{
	$sumpage=1;
} 
$time = $_REQUEST['time'];
if(isset($_REQUEST['id'])&&isset($_REQUEST['time']))
{
	$userid=$_SESSION['userid'];
	$sql_update = "UPDATE nppadmin_app_info SET audit_by='".$userid."' WHERE app_id=".$_REQUEST['id']."";
	$res_update = $db->query($sql_update);
}
if(isset($_REQUEST['imei'])&&isset($_REQUEST['app_id']))
{
	if(preg_match("/^\d{15}$/",$_REQUEST['imei']))
	{
		$sql = "SELECT * FROM npp_testimei WHERE imei = '".$_REQUEST['imei']."'";
        $query = $db -> query($sql);
        if(mysql_num_rows($query)==0)
        {
        	echo "<script language=\"javascript\">alert('imei不再测试手机内，请在IMEI录入更新信息!');window.location.href=\"app_audit.php?currentpage=$currentpage&sumpage=$sumpage&time=$time\";</script>";	       	
        }
        else 
		{
			$sql_update = "UPDATE nppadmin_app_info SET imei='".$_REQUEST['imei']."' WHERE app_id=".$_REQUEST['app_id']."";
			$res_update = $db->query($sql_update);
			echo "<script language=\"javascript\">window.location.href=\"app_audit.php?currentpage=$currentpage&sumpage=$sumpage&time=$time\";</script>";
        }
	}	
	else
	{
		echo "<script language=\"javascript\">alert('imei号输入不合法，请重新输入!');window.location.href=\"app_audit.php?currentpage=$currentpage&sumpage=$sumpage&time=$time\";</script>";	
	}
}
/*查看是否已经提交过查询*/

/*记忆select框选择值*/
//function select_value($select_name)
//{
	if(isset($_REQUEST['time']))
	{
		//echo "<script>";
		//echo "document.getElementById('".$select_name."').value='".$_REQUEST[$select_name]."';";
		//echo "
		$smarty->assign("time",$_REQUEST['time']);
	}
//} 
//===============生成驳回条件的隐藏div========================
$sql_find_reason = "SELECT reason FROM nppadmin_reject_reason ";//查询所有满足条件数据
$sql_find_reason_sum = "SELECT COUNT(*) FROM nppadmin_reject_reason ";//查询总条数
$res_find_reason = $db->query($sql_find_reason);
$res_find_reason_sum = $db->query($sql_find_reason_sum);
$out_find_reason_sum = $db->fetch_array($res_find_reason_sum);
$reason_num = $out_find_reason_sum[0];$i=1;
$hidediv = "";
$hidediv .= "<div id=\"dwindow\" style=\"position:absolute;background-color:#EBEBEB;cursor:hand;left:0px;top:0px;display:none\" onMousedown=\"initializedrag(event)\" onMouseup=\"stopdrag()\" onSelectStart=\"return false\" src=\"javascript:false\" >";
$hidediv .= "<div style=\"position:absolute;left:30px;top:10px;\">";
$hidediv .= "<span id=\"instruction\"><font size=\"2\" ><b>请选择驳回原因:</b></font></span>";
$hidediv .= "</div>";
$hidediv .= "<div style=\"position:absolute;left:30px;top:30px;\">";
while($out_find_reason = mysql_fetch_array($res_find_reason))
{
	$hidediv .= "<input name=\"c".$i."\" type=\"checkbox\" id=\"c".$i."\" value=\"".$out_find_reason['reason']."\" /> <font size=\"2\" >".$out_find_reason['reason']."</font><br>";
	$i++;
}	
$heights = ($i-1)*21;//每增加一条驳回原因，需增加的高度
$hidediv .= "";
$hidediv .= "<input name=\"c".$i."\" type=\"checkbox\" id=\"c".$i."\" value=\"cc".$i."\" onclick=\"javascript:textdis(this);\"/> <font size=\"2\" >其他</font>";
$hidediv .= "<div id=\"tex1\" name=\"tex1\"  style=\"position:absolute;left:60px;top:".($heights+10)."px;width:180px;height:40px;display:none;zIndex:1000;\">";
$hidediv .= "<textarea  style=\"width:180px;height:40px; resize:none;\" id=\"textarea1\"></textarea><br>";
$hidediv .= "<div  id=\"texrcontent\" style=\"zIndex:1000;\" ><font size=\"2\">限20个字符</font></div>";
$hidediv .= "</div>";
$hidediv .= "<input type=\"button\" style=\"position:absolute;left:50px;top:".($heights+100)."px;\" value=\"确 定\" onclick=\"javascript:validates(".$i.");\">";
$hidediv .= "<input type=\"button\" style=\"position:absolute;left:160px;top:".($heights+100)."px;\" value=\"取 消\" onclick=\"javascript:closeit();\" >";
$hidediv .= "</div>";
$hidediv .= "<div id=\"dwindowcontent\" style=\"height:100%\">";
$hidediv .= "</div>";
$hidediv .= "</div>";

$smarty->assign("ii",$i);
$smarty->assign("hidediv",$hidediv);
$smarty->assign("heights",$heights);



//select_value('time');
date_default_timezone_set('PRC'); 
$date = date("Y-m-d");
$date_force=date("Y-m-d",strtotime("-1 day")); 
$date_force_week=date("Y-m-d",strtotime("-6 day"));
$tablelist = "";
if(isset($_REQUEST['time']))
{
	$time = $_REQUEST['time'];
	$sql_app = "SELECT nppadmin_app_info.*,nppadmin_cp_info.cp_name,nppadmin_user.user_name FROM nppadmin_app_info JOIN nppadmin_cp_info ON nppadmin_app_info.cp_id=nppadmin_cp_info.cp_id  LEFT JOIN nppadmin_user  ON nppadmin_app_info.audit_by = 
	nppadmin_user.user_id WHERE status=1 AND ";
	$sql_app_sum = "SELECT COUNT(*) FROM nppadmin_app_info JOIN nppadmin_cp_info ON nppadmin_app_info.cp_id=nppadmin_cp_info.cp_id LEFT JOIN nppadmin_user  ON nppadmin_app_info.audit_by = 
	nppadmin_user.user_id WHERE status=1 AND ";//查询总条数
	if($time==1)
	{
		$sql_app.= " LEFT(nppadmin_app_info.app_uptime,10) = '".$date."' ";
		$sql_app_sum.= " LEFT(nppadmin_app_info.app_uptime,10) = '".$date."' ";
	}
	else if($time==2)
	{
		$sql_app.= " LEFT(nppadmin_app_info.app_uptime,10) >= '".$date_force."' ";
		$sql_app_sum.= " LEFT(nppadmin_app_info.app_uptime,10) >= '".$date_force."' ";
	}
	else if($time==3)
	{
		$sql_app.= " LEFT(nppadmin_app_info.app_uptime,10) >= '".$date_force_week."' ";
		$sql_app_sum.= " LEFT(nppadmin_app_info.app_uptime,10) >= '".$date_force_week."' ";
	}
	else if($time==4)
	{
		$sql_app.= " 1=1 ";
		$sql_app_sum.= " 1=1 ";
	}
	//die($sql_app_sum);
	$res_app_sum = $db->query($sql_app_sum);
	$out_app_sum = $db->fetch_array($res_app_sum);
	/*取总页数*/
	$sumpage=ceil($out_app_sum[0]/$pagesize);
	
	$sql_app.=" ORDER BY nppadmin_app_info.app_id limit ".($currentpage-1)*$pagesize.",".$pagesize." ";
	//die($sql_app."aa");
	$res_app = $db->query($sql_app);
	
	while($out_app = $db->fetch_array($res_app))
	{
		$tablelist .= "<tr>";
			$tablelist .= "<td><div align=\"center\">".$out_app['app_id']."</div></td>";
			$tablelist .= "<td><div align=\"center\"><a href = 'app_message1.php?id=".$out_app['app_id']."'>".$out_app['app_name']."</a></div></td>";
			$tablelist .= "<td><div align=\"center\">".$out_app['app_uptime']."</div></td>";
			$tablelist .= "<td><div align=\"center\">".$out_app['cp_name']."</div></td>";
//隐藏DIV				
			$sql_search_app="select content_types from nppadmin_app_info where app_id='".$out_app['app_id']."'";
			$temp=$db->query($sql_search_app);
			$result=$db->fetch_array($temp);
			
			/*根据session取到的登录用户与数据库中正在审核用户作匹配，来动态显示相应的功能按钮。*/
			if($out_app['audit_by']=="0")
			{
				if($result['content_types']=="Java")
				{
					$tablelist .= "<td><div align=\"center\"><input type=\"button\" value=\"下载jar\" onclick=\"javascript:download1('".$out_app['app_id']."',$currentpage)\"></div><div align=\"center\"><input type=\"button\" value=\"下载jad\" onclick=\"javascript:download('".$out_app['app_id']."',$currentpage)\"></div></td>";			
				}
				else
				{
					$tablelist .= "<td><div align=\"center\"><input type=\"button\" value=\"下载\" onclick=\"javascript:download('".$out_app['app_id']."',$currentpage)\"></div></td>";
				}
				
				
			//	$tablelist .= "<td><div align=\"center\"><input type=\"button\" value=\"下载\" onclick=\"javascript:download('".$out_app['app_id']."',$currentpage)\"></div></td>";
			}
			else if($out_app['audit_by']==$_SESSION['userid'])
			{
				if($result['content_types']=="Java")
				{
					$tablelist .= "<td title=\"正在由您审核！\"><div align=\"center\"><input type=\"button\" value=\"重新下载jar\" onclick=\"javascript:download1('".$out_app['app_id']."',$currentpage)\" ></div><div align=\"center\"><input type=\"button\" value=\"重新下载jad\" onclick=\"javascript:download('".$out_app['app_id']."',$currentpage)\" ></div></td>";
				}
				else
				{
					$tablelist .= "<td title=\"正在由您审核！\"><div align=\"center\"><input type=\"button\" value=\"重新下载\" onclick=\"javascript:download('".$out_app['app_id']."',$currentpage)\" ></div></td>";
				}
			}
			else
			{
				$tablelist .= "<td title=\"正在由".$out_app['user_name']."审核！\"><div align=\"center\"><input type=\"button\" value=\"正在审核\" onclick=\"javascript:download('".$out_app['id']."')\" disabled=\"disabled\" ></div></td>";
			}
			if($out_app['audit_by']==$_SESSION['userid'])
			{
				if($out_app['imei']=='')
				{
					$tablelist .= "<td><div align=\"center\"><input  type=\"button\" value=\"开始测试\" onclick=\"javascript:showDiv('".$out_app['app_id']."',$currentpage)\"></div></td>";
				}
				else
				{
					$tablelist .= "<td><div align=\"center\"><input  type=\"button\" value=\"开始测试\" onclick=\"javascript:showDiv('".$out_app['app_id']."',$currentpage)\"></div></td>";
				}
				if($out_app['imei']=='')
				{
					$tablelist .= "<td><div align=\"center\"><input type=\"button\" value=\"通过\" disabled=\"disabled\" onclick=\"javascript:pass('".$out_app['app_id']."','".$out_app['id']."')\"></div></td>";
					$tablelist .= "<td><div align=\"center\"><input type=\"button\" value=\"驳回\" disabled=\"disabled\" onclick=\"javascript:reject('".$out_app['app_id']."','".$out_app['id']."')\"></div></td>";
				}
				else
				{
					$tablelist .= "<td><div align=\"center\"><input type=\"button\" value=\"通过\" onclick=\"javascript:pass('".$out_app['app_id']."','".$out_app['id']."')\"></div></td>";
					$tablelist .= "<td><div align=\"center\"><input type=\"button\" value=\"驳回\" onclick=\"javascript:reject('".$out_app['app_id']."','".$out_app['id']."')\"></div></td>";
				}
			}
			else
			{
				$tablelist .= "<td><div align=\"center\"><input  type=\"button\" value=\"开始测试\" onclick=\"javascript:showDiv('".$out_app['app_id']."')\" disabled=\"disabled\"></div></td>";
				$tablelist .= "<td><div align=\"center\"><input type=\"button\" value=\"通过\" onclick=\"javascript:pass('".$out_app['app_id']."','".$out_app['id']."')\" disabled=\"disabled\" ></div></td>";
				$tablelist .= "<td><div align=\"center\"><input type=\"button\" value=\"驳回\" onclick=\"javascript:reject('".$out_app['app_id']."','".$out_app['id']."')\" disabled=\"disabled\" ></div></td>";
			}
		$tablelist .= "</tr>"."\n";
	}
}
else
{
	$time="";
}
$smarty->assign("tablelist",$tablelist);

if(isset($_REQUEST['time']))
{
$smarty->assign("out_app_sum",$out_app_sum[0]);
$smarty->assign("currentpage",$currentpage);
$smarty->assign("sumpage",$sumpage);
$smarty->assign("nextpage","app_audit.php?time=".$time."&currentpage=".($currentpage+1)."&number=0.29387723984");
$smarty->assign("prepage","app_audit.php?time=".$time."&currentpage=".($currentpage-1)."&number=0.29387723984");
$smarty->assign("nowpage","app_audit.php?time=".$time."&number=0.29387723984");
//$smarty->display("app/page.htm");
}
$smarty->display("app/app_audit.htm");
if(isset($_REQUEST['id'])&&isset($_REQUEST['time']))
{
	if(isset($_REQUEST['jar']))
	{
		echo "<script language=\"javascript\">window.location.href=\"download_jar.php?id=".$_REQUEST['id']."&time=".$_REQUEST['time']."\";</script>";
	}
	else
	{
		echo "<script language=\"javascript\">window.location.href=\"download.php?id=".$_REQUEST['id']."&time=".$_REQUEST['time']."\";</script>";
	}

}

?>
</body>
</html>