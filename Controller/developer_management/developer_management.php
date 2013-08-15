<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');

/* $url=$_SERVER["REQUEST_URI"];
$url=parse_url($url);
$url=$url[path]; */
function select_value($select_name)
{
	if(isset($_POST[$select_name]))
	{
		echo "<script>";
		echo "document.getElementById('".$select_name."').value='".$_POST[$select_name]."';";
		echo "</script>";
	}
}
function check_class($status)
{
	
}
$developer=array("apply_developer"=>"申请成为开发者","apply_fee"=>"申请计费类型");
$time=array("today"=>"当天","two_day"=>"最近两天","one_week"=>"最近一周","all"=>"不限");
$apply_pageout="";
$time_pageout="";
foreach($developer as $k=>$v)
{
    if(isset($_POST["check_class"])&&$_POST["check_class"]==$k)
    {
	    $apply_pageout.="<option value=".$k." selected=\"selected\">".$v."</option>";
    }
    else
    {
    	$apply_pageout.="<option value=".$k.">".$v."</option>";
    }
}

foreach ($time as $k=>$v) 
{
	if(isset($_POST["submit_time"])&&$_POST["submit_time"]==$k)
	{
		$time_pageout.="<option value=".$k." selected=\"selected\">".$v."</option>";
	}
	else
	{
		$time_pageout.="<option value=".$k.">".$v."</option>";
	}
}

//开发者申请的SQL语句
$sql_search_apply="select reg_date,cp_class,cp_name,cp_status from nppadmin_cp_info where";
//开发者类型的post
if(isset($_POST["check_class"])&&$_POST["check_class"]=="apply_developer")
{
	$sql_search_apply.=" cp_status=0 and";
}
//所有的时间
date_default_timezone_set('PRC'); 
$date = date("Y-m-d");
$date_force=date("Y-m-d",strtotime("-1 day"));
$date_force_week=date("Y-m-d",strtotime("-6 day"));
if(isset($_POST["submit_time"]))
{
	$time=$_POST["submit_time"];
	if($time=="today")
	{
		//$sql_search_apply.=" reg_date>='".$date."' and";
		$sql_search_apply.=" LEFT(nppadmin_cp_info.reg_date,10)>='".$date."' and";
	}
	else if($time=="two_day")
	{
		//$sql_search_apply.=" reg_date>='".$date_force."' and";
		$sql_search_apply.=" LEFT(nppadmin_cp_info.reg_date,10)>='".$date_force."' and";
	}
	else if($time=="one_week")
	{
		//$sql_search_apply.=" reg_date>='".$date_force_week."' and";
		$sql_search_apply.=" LEFT(nppadmin_cp_info.reg_date,10)>='".$date_force_week."' and";
	}
	else if($time=="all")
	{
		$sql_search_apply.=" 1=1 and ";
	}
}
//查询语句的结尾
$sql_search_apply.=" 1=1 and cp_status=0";
$resault=$db->query($sql_search_apply);
$outpagelist="";
while($out_app=$db->fetch_array($resault))
{
	$outpagelist.="<tr>";
	$outpagelist.="<td>".$out_app["reg_date"]."</td>";
	if($out_app["cp_class"]==1)
	{
		$outpagelist.="<td>个人开发者</td>";
	}
	if($out_app["cp_class"]==2)
	{
		$outpagelist.="<td>企业开发者</td>";
	}
	//$outpagelist.="<td>".$out_app["cp_name"]."</td>";
	$outpagelist.="<td><a href=\"developer_details.php?cp_id=".$out_app['cp_id']."\">".$out_app["cp_name"]."</a></td>";
	$outpagelist.="<td>未审核</td>";
	$outpagelist.="</tr>";
}
$smarty->assign("apply_pageout",$apply_pageout);
$smarty->assign("time_pageout",$time_pageout);
$smarty->assign("outpagelist",$outpagelist);
$smarty->display("developer_management/developer_management.html");
?>
</body>
</html>