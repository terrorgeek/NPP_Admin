<?php 
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
RoleVerify('14',$db);
/* $url=$_SERVER["REQUEST_URI"];
$url=parse_url($url);
$url=$url[path]; */
$_SESSION["fee_point"]=null;
$_SESSION["fee_times"]=null;
$_SESSION["fee"]=null;
$_SESSION["is_same"]=null;
$_SESSION["cp_email"]=null;
function check_status($check)
{
	if(isset($_REQUEST[$check])&&$_REQUEST[$check]=="on")
	{
		return "checked";
	}
}
//页面数据量大小
$pagesize=10;
//分页（开发者审核）
if(isset($_REQUEST["currentpage1"]))
{
	$currentpage2=$_REQUEST["currentpage1"];
}
else
{
	$currentpage2=1;
}
if(isset($_REQUEST["sumpage1"]))
{
	$sumpage2=$_REQUEST["sumpage1"];
}
else 
{
	$sumpage2=1;
}
//分页（开发者查询）
if(isset($_REQUEST["currentpage2"]))
{
	$currentpage=$_REQUEST["currentpage2"];
}
else
{
	$currentpage=1;
}
if(isset($_REQUEST["sumpage2"]))
{
	$sumpage=$_REQUEST["sumpage2"];
}
else 
{
	$sumpage=1;
}

function check_text($text)
{
	if(isset($_REQUEST[$text]))
	{
		return $_REQUEST[$text];
	}
}

//$developer=array("apply_developer"=>"申请成为开发者","apply_fee"=>"申请计费类型");
$time=array("today"=>"当天","two_day"=>"最近两天","one_week"=>"最近一周","all"=>"不限");
$apply_pageout="";
$time_pageout="";
//开发者类型下拉框
foreach($developer as $k=>$v)
{
    if(isset($_REQUEST["check_class"])&&$_REQUEST["check_class"]==$k)
    {
	    $apply_pageout.="<option value=".$k." selected=\"selected\">".$v."</option>";
    }
    else
    {
    	$apply_pageout.="<option value=".$k.">".$v."</option>";
    }
}
//时间下拉框
foreach ($time as $k=>$v) 
{
	if(isset($_REQUEST["submit_time"])&&$_REQUEST["submit_time"]==$k)
	{
		$time_pageout.="<option value=".$k." selected=\"selected\">".$v."</option>";
	}
	else
	{
		$time_pageout.="<option value=".$k.">".$v."</option>";
	}
}

//开发者查询里的开发者类型
$developer_type=array("enterprise_developer"=>"企业开发者","personal_developer"=>"个人开发者","all"=>"不限");
foreach($developer_type as $k=>$v)
{
    if(isset($_REQUEST["developer_type"])&&$_REQUEST["developer_type"]==$k)
    {
	    $developer_type_pageout.="<option value=".$k." selected=\"selected\">".$v."</option>";
    }
    else
    {
    	$developer_type_pageout.="<option value=".$k.">".$v."</option>";
    }
}
//开发者查询里的开发者状态下拉框
$developer_status=array("checked"=>"已审核","not_checked"=>"未审核");
foreach ($developer_status as $k=>$v) 
{
	if(isset($_REQUEST["developer_status"])&&$_REQUEST["developer_status"]==$k)
	{
		$developer_status_pageout.="<option value=".$k." selected=\"selected\">".$v."</option>";
	}
	else
	{
		$developer_status_pageout.="<option value=".$k.">".$v."</option>";
	}
}

//申请计费类型的SQL语句
$sql_search_fee="select nppadmin_cp_info.cp_id,nppadmin_cp_info.cp_class,nppadmin_cp_info.cp_name,
                 nppadmin_cp_info.cp_status,npp_point_type_relation.point_type_id from npp_point_type_relation,
                 nppadmin_cp_info where nppadmin_cp_info.cp_id=npp_point_type_relation.cp_id";
//开发者申请的SQL语句
if(isset($_REQUEST["submit_test1"]))
{
$sql_search_apply="select reg_date,cp_class,cp_name,cp_status,cp_id from nppadmin_cp_info where";
}
//开发者类型的post
if(isset($_REQUEST["check_class"])&&$_REQUEST["check_class"]=="apply_developer")
{
	$sql_search_apply.=" cp_status=0 and";
}
//所有的时间
date_default_timezone_set('PRC'); 
$date = date("Y-m-d");
$date_force=date("Y-m-d",strtotime("-1 day"));
$date_force_week=date("Y-m-d",strtotime("-6 day"));
if(isset($_REQUEST["submit_time"]))
{
	$time=$_REQUEST["submit_time"];
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
//开发者申请查询语句的结尾
$sql_search_apply.=" 1=1 and cp_status=0";

//===================================取总查询条数(开发者审核的分页)
$res_apply_sum=$db->query($sql_search_apply);
$out_apply_sum=$db->num_rows($res_apply_sum);
$sumpage2=ceil($out_apply_sum/$pagesize);
$sql_search_apply.=" ORDER BY nppadmin_cp_info.reg_date DESC limit ".($currentpage2-1)*$pagesize.",".$pagesize."";

//执行开发者申请查询语句的结尾
$result=$db->query($sql_search_apply);
$outpagelist="";
date_default_timezone_set('PRC'); 
while($out_app=$db->fetch_array($result))
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
	$outpagelist.="<td><a href=\"developer_details.php?cp_id=".$out_app['cp_id']."&submit_time=".$_REQUEST["submit_time"]."&currentpage1=".$_REQUEST["currentpage1"]."\">".$out_app["cp_name"]."</a></td>";
	$outpagelist.="<td>未审核</td>";
	$outpagelist.="</tr>";
}
$errors = "0";
//==============================开发者查询的东西 (包括计费类型审核)==============================
//开发者查询的标签的SQL语句
if(isset($_REQUEST["submit_test2"]))
{
    $sql_search_developer="select * from nppadmin_cp_info where";
//$sql_search_developer="select nppadmin_cp_info.cp_id,nppadmin_cp_info.cp_class,nppadmin_cp_info.cp_name,
//                 nppadmin_cp_info.cp_status,npp_point_type_relation.point_type_relation from npp_point_type_relation,
//               nppadmin_cp_info where nppadmin_cp_info.cp_id=npp_point_type_relation.cp_id and";
}

//拼接sql语句
if(isset($_REQUEST["developer_type"])&&$_REQUEST["developer_type_check"]!="")
{
	$developer_var=$_REQUEST["developer_type"];
	if($developer_var=="enterprise_developer")
	{
		$sql_search_developer.=" nppadmin_cp_info.cp_class=2 and ";
	}	
	if($developer_var=="personal_developer")
	{
		$sql_search_developer.=" nppadmin_cp_info.cp_class=1 and ";
	}
	if($developer_var=="all")
	{
		$sql_search_developer.=" 1=1 and";
	}
}

if(isset($_REQUEST["developer_status"])&&$_REQUEST["developer_status_check"]!="")
{
	$developer_status_var=$_REQUEST["developer_status"];
	if($developer_status_var=="checked")
	{
		$sql_search_developer.=" nppadmin_cp_info.cp_status=1 or nppadmin_cp_info.cp_status=2 and ";
	}
	if($developer_status_var=="not_checked")
	{
		$sql_search_developer.=" nppadmin_cp_info.cp_status=0 and ";
	}
}

if(isset($_REQUEST["cp_name"])&&$_REQUEST["developer_name_check"]=="on")
{ 
	if(preg_match("/^[\x80-\xff_a-zA-Z0-9]+$/",$_REQUEST["cp_name"]))
	{
		//$sql_search_developer.=" nppadmin_cp_info.cp_name like '%".$_REQUEST["cp_name"]."%' and ";
		$sql_search_developer.=" nppadmin_cp_info.cp_name='".$_REQUEST["cp_name"]."' and ";
	}
  	elseif($_REQUEST["cp_name"]=='')
	{
		echo "<script language=\"JavaScript\">alert('开发者名称输入不合法!');</script>";
		$errors = "1";
	}
	else
	{
		echo "<script language=\"JavaScript\">alert('开发者名称输入不合法!');</script>";
		$errors = "1";
	}
}


if(isset($_REQUEST["cp_id"])&&$_REQUEST["developer_id_check"]!="")
{
	if(preg_match("/[0-9]/",$_REQUEST["cp_id"]))
	{
	    $sql_search_developer.=" nppadmin_cp_info.cp_id='".$_REQUEST["cp_id"]."' and ";
	}
	else
	{
		echo "<script language=\"JavaScript\">alert('开发者ID输入不合法!');</script>";
		$errors = "1";
	}
}

$sql_search_developer.=" 1=1 ";

//取总查询条数(开发者查询的分页)
if($errors=="1")
{
	$sql_search_developer = "";
}

$res_search_sum=$db->query($sql_search_developer);
$out_search_sum=$db->num_rows($res_search_sum);
$sumpage=ceil($out_search_sum/$pagesize);
$sql_search_developer.=" ORDER BY nppadmin_cp_info.cp_id DESC limit ".($currentpage-1)*$pagesize.",".$pagesize."";
if($errors=="1")
{
	$sql_search_developer = "";
}
//如果有之前记忆的sql语句，则执行
 // if(isset($_GET["remember_data"])&&$_GET["remember_data"]=="remember_data")
// {
	// $sql_search_developer=$_SESSION["remember_sql"];
	// $currentpage=$_SESSION["currentpage"];
	// $sumpage=$_SESSION["sumpage"];
	// $out_search_sum=$_SESSION["out_search_sum"];
	// echo "<script>alert('".$_SESSION['remember_sql']."');</script>";
	// unset($_SESSION["currentpage"]);
	// unset($_SESSION["sumpage"]);
	// unset($_SESSION["remember_sql"]);
	// unset($_SESSION["out_search_sum"]);
// } 
//如果有发邮件时记忆的sql语句，则执行
if(isset($_SESSION["email_flag"])&&$_SESSION["email_flag"]=="email_flag")
{
	$sql_search_developer=$_SESSION["email_sql"];
	$currentpage=$_SESSION["email_currentpage"];
	$sumpage=$_SESSION["email_sumpage"];
	unset($_SESSION["email_sql"]);
	unset($_SESSION["email_currentpage"]);
	unset($_SESSION["email_sumpage"]);
	unset($_SESSION["email_flag"]);
}
//die($sql_search_developer);
$result2=$db->query($sql_search_developer);
$outpagelist2="";
while($out_app2=$db->fetch_array($result2))
{
	$outpagelist2.="<tr>";
	$outpagelist2.="<td>".$out_app2["cp_id"]."</td>";
	if($out_app2["cp_class"]==1)
	{
		$outpagelist2.="<td>个人开发者</td>";
	}
	else
	{
		$outpagelist2.="<td>企业开发者</td>";
	}
	if(isset($_REQUEST["cp_id_search"]))
	{
	    $outpagelist2.="<td><a href=\"developer_details.php?cp_id=".$out_app2['cp_id']."&flag=1&developer_type_check=".$_REQUEST["developer_type_check"]."&developer_type=".$_REQUEST["developer_type"]."&developer_name=".$_REQUEST["developer_name"]."&cp_name=".$_REQUEST["cp_name"]."&developer_id_check=".$_REQUEST["developer_id_check"]."&cp_id_search=".$_REQUEST["cp_id_search"]."&currentpage2=".$_REQUEST["currentpage2"]."&developer_name_check=".$_REQUEST["developer_name_check"]." \">".$out_app2["cp_name"]."</a></td>";
	}
	else
	{
	    $outpagelist2.="<td><a href=\"developer_details.php?cp_id=".$out_app2['cp_id']."&flag=1&developer_type_check=".$_REQUEST["developer_type_check"]."&developer_type=".$_REQUEST["developer_type"]."&developer_name=".$_REQUEST["developer_name"]."&cp_name=".$_REQUEST["cp_name"]."&developer_id_check=".$_REQUEST["developer_id_check"]."&cp_id_search=".$_REQUEST["cp_id"]."&currentpage2=".$_REQUEST["currentpage2"]."&developer_name_check=".$_REQUEST["developer_name_check"]." \">".$out_app2["cp_name"]."</a></td>";
	}
	
	if($out_app2["cp_status"]==0)
	{
		//$outpagelist2.="<td>未审核</td>";
	}
	else if($out_app2["cp_status"]==1)
	{
		//$outpagelist2.="<td>通过</td>";
	}
	else
	{
		//$outpagelist2.="<td>驳回</td>";
	}
	//进行对checkbox的检查
	$sql_per_developer="select * from npp_point_type_relation where cp_id='".$out_app2["cp_id"]."'"; //查出该用户所对应的计费类型
	$sql_point_type="select * from npp_point_type"; //取所有计费类型
	$res_per=$db->query($sql_per_developer);
	$res_per=$db->fetch_array($res_per); //对其重新进行赋值
//	$result_per=$db->fetch_array($res_per);
	$res_point_type=$db->query($sql_point_type);
	$outpagelist2.="<td><form action=\"pass_fee_type.php\" method=\"post\" id='".$out_app2["cp_id"]."' >";
	         if(!$res_per)
	         {
	         	$sql_insert="insert into npp_point_type_relation(cp_id,point_type_id) 
	         	             values ('".$out_app2["cp_id"]."',1)";
	         	$db->query($sql_insert);
	         	$outpagelist2.="<select name=\"fee_point\">";
	         	$outpagelist2.="<option value=\"single_point\" selected=\"selected\">单计费点</option>";
	         	$outpagelist2.="<option value=\"multiple_point\">多计费点</option>";
	         	$outpagelist2.="</select>";
	         	$outpagelist2.="&nbsp;&nbsp;&nbsp;&nbsp;";
	         	$outpagelist2.="<select name=\"fee_times\">";
	         	$outpagelist2.="<option value=\"single_times\" selected=\"selected\">单次计费</option>";
	         	$outpagelist2.="<option value=\"multiple_times\">多次计费</option>";
	         	$outpagelist2.="</select>";
	         	$outpagelist2.="<input type=\"hidden\" value=\"single_single\" name=\"is_same\" />";
	         	$outpagelist2.="<input type=\"hidden\" value='".$out_app2["cp_email"]."' name=\"cp_email\" />";
	         }
	         else
	         {
	         	if($res_per["point_type_id"]==1)
	         	{
	         		$outpagelist2.="<select name=\"fee_point\">";
	         	    $outpagelist2.="<option value=\"single_point\" selected=\"selected\">单计费点</option>";
	         	    $outpagelist2.="<option value=\"multiple_point\">多计费点</option>";
	         	    $outpagelist2.="</select>";
	         	    $outpagelist2.="&nbsp;&nbsp;&nbsp;&nbsp;";
	         	    $outpagelist2.="<select name=\"fee_times\">";
	         	    $outpagelist2.="<option value=\"single_times\" selected=\"selected\">单次计费</option>";
	         	    $outpagelist2.="<option value=\"multiple_times\">多次计费</option>";
	         	    $outpagelist2.="</select>";
	         	    $outpagelist2.="<input type=\"hidden\" value=\"single_single\" name=\"is_same\" />";
	         	    $outpagelist2.="<input type=\"hidden\" value='".$out_app2["cp_email"]."' name=\"cp_email\" />";
	         	}
	         	else if($res_per["point_type_id"]==2)
	         	{
	         		$outpagelist2.="<select name=\"fee_point\">";
	         	    $outpagelist2.="<option value=\"single_point\" selected=\"selected\">单计费点</option>";
	         	    $outpagelist2.="<option value=\"multiple_point\">多计费点</option>";
	         	    $outpagelist2.="</select>";
	         	    $outpagelist2.="&nbsp;&nbsp;&nbsp;&nbsp;";
	         	    $outpagelist2.="<select name=\"fee_times\">";
	         	    $outpagelist2.="<option value=\"single_times\">单次计费</option>";
	         	    $outpagelist2.="<option value=\"multiple_times\" selected=\"selected\">多次计费</option>";
	         	    $outpagelist2.="</select>";
	         	    $outpagelist2.="<input type=\"hidden\" value=\"single_multiple\" name=\"is_same\" />";
	         	    $outpagelist2.="<input type=\"hidden\" value='".$out_app2["cp_email"]."' name=\"cp_email\" />";
	         	}
	         	else if($res_per["point_type_id"]==3)
	         	{
	         		$outpagelist2.="<select name=\"fee_point\">";
	         	    $outpagelist2.="<option value=\"single_point\">单计费点</option>";
	         	    $outpagelist2.="<option value=\"multiple_point\" selected=\"selected\">多计费点</option>";
	         	    $outpagelist2.="</select>";
	         	    $outpagelist2.="&nbsp;&nbsp;&nbsp;&nbsp;";
	         	    $outpagelist2.="<select name=\"fee_times\">";
	         	    $outpagelist2.="<option value=\"single_times\" selected=\"selected\">单次计费</option>";
	         	    $outpagelist2.="<option value=\"multiple_times\">多次计费</option>";
	         	    $outpagelist2.="</select>";
	         	    $outpagelist2.="<input type=\"hidden\" value=\"multiple_single\" name=\"is_same\" />";
	         	    $outpagelist2.="<input type=\"hidden\" value='".$out_app2["cp_email"]."' name=\"cp_email\" />";
	         	}
	         	else if($res_per["point_type_id"]==4)
	         	{
	         		$outpagelist2.="<select name=\"fee_point\">";
	         	    $outpagelist2.="<option value=\"single_point\" >单计费点</option>";
	         	    $outpagelist2.="<option value=\"multiple_point\" selected=\"selected\">多计费点</option>";
	         	    $outpagelist2.="</select>";
	         	    $outpagelist2.="&nbsp;&nbsp;&nbsp;&nbsp;";
	         	    $outpagelist2.="<select name=\"fee_times\">";
	         	    $outpagelist2.="<option value=\"single_times\" >单次计费</option>";
	         	    $outpagelist2.="<option value=\"multiple_times\" selected=\"selected\">多次计费</option>";
	         	    $outpagelist2.="</select>";
	         	    $outpagelist2.="<input type=\"hidden\" value=\"multiple_multiple\" name=\"is_same\" />";
	         	    $outpagelist2.="<input type=\"hidden\" value='".$out_app2["cp_email"]."' name=\"cp_email\" />";
	         	}
	         }
//           $array=array();
//           $array2=array();
//           while($result_per=$db->fetch_array($res_per))
//           {
//           	   if($result_per["point_type_id"]!=0)
//           	   {
//           	      array_push($array,$result_per["point_type_id"]);
//           	   }
//           }
//           while($result_point_type=$db->fetch_array($res_point_type))
//           {
//           	     if(in_array($result_point_type["id"],$array))
//           	     {
//           	     	if($result_point_type["id"]==1)
//           	     	{
//           	     	   $outpagelist2.="<input type=\"checkbox\" value=1 name=\"single_single\" checked=\"checked\" />".$result_point_type["point_type"]."";
//           	     	}
//           	     	else if($result_point_type["id"]==2)
//           	     	{
//           	     	   $outpagelist2.="<input type=\"checkbox\" value=2 name=\"single_multiple\" checked=\"checked\" />".$result_point_type["point_type"]."";
//           	     	}
//           	     	else if($result_point_type["id"]==3)
//           	     	{
//           	     	   $outpagelist2.="<input type=\"checkbox\" value=3 name=\"multiple_single\" checked=\"checked\" />".$result_point_type["point_type"]."";
//           	     	}
//           	     	else if($result_point_type["id"]==4)
//           	     	{
//           	     	   $outpagelist2.="<input type=\"checkbox\" value=4 name=\"multiple_multiple\" checked=\"checked\" />".$result_point_type["point_type"]."";
//           	     	}
//           	     }
//           	     
//           	     else
//           	     {
//           	        if($result_point_type["id"]==1)
//           	     	{
//           	     	   $outpagelist2.="<input type=\"checkbox\" value=1 name=\"single_single\" />".$result_point_type["point_type"]."";
//           	     	}
//           	     	else if($result_point_type["id"]==2)
//           	     	{
//           	     	   $outpagelist2.="<input type=\"checkbox\" value=2 name=\"single_multiple\" />".$result_point_type["point_type"]."";
//           	     	}
//           	     	else if($result_point_type["id"]==3)
//           	     	{
//           	     	   $outpagelist2.="<input type=\"checkbox\" value=3 name=\"multiple_single\" />".$result_point_type["point_type"]."";
//           	     	}
//           	     	else if($result_point_type["id"]==4)
//           	     	{
//           	     	   $outpagelist2.="<input type=\"checkbox\" value=4 name=\"multiple_multiple\" />".$result_point_type["point_type"]."";
//           	     	}
//           	     }
//           }
           
	                                      
	           $outpagelist2.="<input type=\"hidden\" value='".$out_app2["cp_id"]."' name=\"fee\" />";
	           $outpagelist2.="<input type=\"hidden\" value='".$out_app2["cp_email"]."' name=\"cp_email\" />";
	           $outpagelist2.="<input type=\"hidden\" id='reason_".$out_app2['cp_id']."' name='reason_".$out_app2['cp_id']."' value=\"\" />";
	           //隐藏层
	        //   $outpagelist2.="<div id='div_".$out_app2["cp_id"]."' style=\"height:100px;width:300px;background-color:#EBEBEB;display:none;\">";
	        //   $outpagelist2.="<input type=\"text\" value=\"\" name='reason_".$out_app2["cp_id"]."' id='reason_".$out_app2["cp_id"]."' /><br/><br/>";
	        //   $outpagelist2.="原因(小于20字):<br/><br/>";
	        //   $outpagelist2.="<input type=\"button\" value=\"确定\" />";
	        //   $outpagelist2.="</div>";
	$outpagelist2.="<td><input type=\"button\" value=\"确定\" onclick=\"javascript:send_reason('".$out_app2['cp_id']."');\" /></form></td>";
	//$outpagelist2.="<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"payment_detail.php?app_id=".$out_app2['cp_id']."\">查看</a></td>";
	$outpagelist2.="<td>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"button\" value=\"查看\" onclick=\"javascript:modify_period('".$out_app2['cp_id']."','".$out_app2['settlement_type']."');\" /></td>";
	$outpagelist2.="</tr>";
}
//==============================开发者查询的东西完=============================

//判断哪个表单提交
if(isset($_REQUEST["div1"])&&$_REQUEST["div1"]==0)
{
	$div1=$_REQUEST["div1"];
	$div1=1;
	$smarty->assign("div1",$div1);
}
if(isset($_REQUEST["div2"])&&$_REQUEST["div2"]==0)
{
	$div2=$_REQUEST["div2"];
	$div2=2;
	$smarty->assign("div2",$div2);
}

//整个页面刷完后，用session记当时的sql语句的模样
// $_SESSION["remember_sql"]=$sql_search_developer;
// $_SESSION["currentpage"]=$currentpage;
// $_SESSION["sumpage"]=$sumpage;
// $_SESSION["out_search_sum"]=$out_search_sum;
//这个是记录发邮件时的sql语句
$_SESSION["email_sql"]=$sql_search_developer;
$_SESSION["email_currentpage"]=$currentpage;
$_SESSION["email_sumpage"]=$sumpage;

$smarty->assign("developer_type_check",check_status("developer_type_check"));
$smarty->assign("developer_name_check",check_status("developer_name_check"));
$smarty->assign("developer_id_check",check_status("developer_id_check"));
//if(isset($_REQUEST["cp_id_search"])&&$_REQUEST["cp_id_search"]!="")
//{
    $smarty->assign("cp_id",$_REQUEST["cp_id_search"]);
//}
//else
//{
 //   $smarty->assign("cp_id",check_text("cp_id")); 
//}
if(!isset($_REQUEST["cp_id_search"]))
{
    
    $smarty->assign("cp_id",check_text("cp_id"));
}

$smarty->assign("cp_name",check_text("cp_name"));
$smarty->assign("developer_type_pageout",$developer_type_pageout);
$smarty->assign("apply_pageout",$apply_pageout);
$smarty->assign("time_pageout",$time_pageout);
$smarty->assign("outpagelist2",$outpagelist2);
$smarty->assign("outpagelist",$outpagelist);
$smarty->assign("developer_status_check",check_status("developer_status_check"));
$smarty->assign("developer_status_pageout",$developer_status_pageout);

$smarty->assign("out_search_sum",$out_search_sum);
$smarty->assign("out_search_sum",$out_search_sum);
$smarty->assign("out_apply_sum",$out_apply_sum);
$smarty->assign("currentpage",$currentpage);
$smarty->assign("sumpage",$sumpage);
$smarty->assign("currentpage2",$currentpage2);
$smarty->assign("sumpage2",$sumpage2);
$smarty->display("developer_management/developer_management.html");
?>
</body>
</html>