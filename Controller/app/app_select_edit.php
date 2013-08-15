<?php

require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
RoleVerify('9',$db);
/* $url=$_SERVER["REQUEST_URI"];
$url=parse_url($url);
$url=$url[path];  */
$pagesize=10;
/*获取分页参数*/
if(isset($_REQUEST['currentpage']))
{
	$currentpage=$_REQUEST['currentpage'];
}
else
{
	$currentpage=1;
}
/*获取表格排序参数*/
if(isset($_REQUEST['jiage_click']))
{
	$jiage_click=$_REQUEST['jiage_click'];
	$smarty->assign("jiage_click",$jiage_click);
}
else
{
	$jiage_click="asc";
	$smarty->assign("jiage_click",$jiage_click);
}
/*获取表格排序参数*/
if(isset($_REQUEST['xiazailiang_click']))
{
	$xiazailiang_click=$_REQUEST['xiazailiang_click'];
	$smarty->assign("xiazailiang_click",$xiazailiang_click);
}
else
{
	$xiazailiang_click="desc";
	$smarty->assign("xiazailiang_click",$xiazailiang_click);
}
/*获取表格排序参数*/
if(isset($_REQUEST['current_click']))
{
	$current_click=$_REQUEST['current_click'];
	$smarty->assign("current_click",$current_click);
}
else
{
	$current_click="jiage";
	$smarty->assign("current_click",$current_click);
}

//===========显示支付类型的下拉框=============
$out_select_payment_type="";
$sql_select_type="select * from npp_payment_type GROUP by name ";
$query_payment_type=mysql_query($sql_select_type);
while($out_payment_type=mysql_fetch_array($query_payment_type))
{
	if(isset($_REQUEST["payment_type"])&&$_REQUEST["payment_type"]==$out_payment_type["id"])
	{
		$out_select_payment_type.="<option value='".$out_payment_type['id']."' selected=\"selected\" >".$out_payment_type['name']."</option>";
	}
	else 
	{
		$out_select_payment_type.="<option value='".$out_payment_type['id']."' >".$out_payment_type['name']."</option>";
	}
}
$smarty->assign("out_select_payment_type",$out_select_payment_type);
//============显示支付渠道的下拉框
$out_select_payment_channel="";
$sql_select_channel="select * from nppadmin_channelinfo";
$query_payment_channel=mysql_query($sql_select_channel);
while($out_payment_channel=mysql_fetch_array($query_payment_channel))
{
	if(isset($_REQUEST["channel"])&&$_REQUEST["channel"]==$out_payment_channel['id'])
	{
		$out_select_payment_channel.="<option value='".$out_payment_channel['id']."' selected=\"selected\" >".$out_payment_channel['channel_name']."</option>";
	}
	else
	{
		$out_select_payment_channel.="<option value='".$out_payment_channel['id']."'>".$out_payment_channel['channel_name']."</option>";
	}
}
$smarty->assign("out_select_payment_channel",$out_select_payment_channel);
//=============显示支付来源的下拉框================
$out_select_payment_source="";
$sql_select_source="select * from npp_payment_source";
$query_payment_source=mysql_query($sql_select_source);
while($out_payment_source=mysql_fetch_array($query_payment_source))
{ 
	if(isset($_REQUEST["source"])&&$_REQUEST["source"]==$out_payment_source["id"])
	{
		$out_select_payment_source.="<option value='".$out_payment_source['id']."' selected=\"selected\"  >".$out_payment_source['name']."</option>";
	}
	else 
	{
		$out_select_payment_source.="<option value='".$out_payment_source['id']."'>".$out_payment_source['name']."</option>";
	}
}
$smarty->assign("out_select_payment_source",$out_select_payment_source);
//===========显示”开发者“select框内容============
$cp_name_pageout = "";
$sql = "SELECT cp_name FROM nppadmin_cp_info WHERE cp_status=1 ";
$res = $db->query($sql);
while($out = $db->fetch_array($res))
{
	if(isset($_REQUEST['npp_cp'])&&$_REQUEST['npp_cp']==$out['cp_name'])
	{
		$cp_name_pageout .= "<option value=".$out['cp_name']."  selected=\"selected\" >".$out['cp_name']."</option>";
	}
	else
	{
		$cp_name_pageout .= "<option value=".$out['cp_name']."  >".$out['cp_name']."</option>";
	}	
}
$smarty->assign("cp_name_pageout",$cp_name_pageout);
//===========显示”产品状态“select框内容============
$app_statuse_pageout = "";
$sql = "SELECT * FROM nppadmin_app_status where id!=5";
$res = $db->query($sql);
while($out = $db->fetch_array($res))
{
	if(isset($_REQUEST['app_status'])&&$_REQUEST['app_status']==$out['id'])
	{
		$app_statuse_pageout .= "<option value=".$out['id']."  selected=\"selected\"  >".$out['status']."</option>";
	}
	else
	{
		$app_statuse_pageout .= "<option value=".$out['id']." >".$out['status']."</option>";
	}	
}


$smarty->assign("app_statuse_pageout",$app_statuse_pageout);

$smarty->assign("npp_cp_check",check_status("npp_cp"));
$smarty->assign("app_key_check",check_status("app_key"));
$smarty->assign("app_status_check",check_status("app_status"));
$smarty->assign("app_id_check",check_status("app_id"));
$smarty->assign("app_key",input_value("app_key"));
$smarty->assign("app_id",input_value("app_id"));
$smarty->assign("channel_checkbox",check_status("channel_checkbox"));
$smarty->assign("payment_type_checkbox",check_status("payment_type_checkbox"));
$smarty->assign("source_checkbox",check_status("source_checkbox"));


$tablelist = "";$checkdata = "";$error="";

//	$sql_search = "SELECT nppadmin_app_info.*,nppadmin_cp_info.cp_name FROM nppadmin_app_info JOIN nppadmin_cp_info ON nppadmin_app_info.cp_id=nppadmin_cp_info.cp_id WHERE ";//查询所有满足条件数据
//	$sql_search_sum = "SELECT COUNT(*) FROM nppadmin_app_info JOIN nppadmin_cp_info ON nppadmin_app_info.cp_id=nppadmin_cp_info.cp_id WHERE ";//查询总条数
//$sql_search="SELECT distinct app.*, cp.cp_name FROM NokiaPaymentPlat.nppadmin_app_info app, 
//             NokiaPaymentPlat.nppadmin_cp_info cp , NokiaPaymentPlat.npp_app_paymentmethod_match pmatch 
//             WHERE app.cp_id = cp.cp_id and ";
$sql_search="SELECT distinct app.*, cp.cp_name FROM NokiaPaymentPlat.nppadmin_app_info app, 
             NokiaPaymentPlat.nppadmin_cp_info cp , NokiaPaymentPlat.npp_app_paymentmethod_match pmatch , 
             NokiaPaymentPlat.nppadmin_paymentmethod method where app.cp_id = cp.cp_id and ";

//$sql_search_sum="SELECT distinct app.*, cp.cp_name 
//             FROM NokiaPaymentPlat.nppadmin_app_info app, NokiaPaymentPlat.nppadmin_cp_info cp , 
//             NokiaPaymentPlat.npp_app_paymentmethod_match pmatch 
//             WHERE app.cp_id = cp.cp_id and ";
$sql_search_sum="SELECT distinct app.*, cp.cp_name 
             FROM NokiaPaymentPlat.nppadmin_app_info app, NokiaPaymentPlat.nppadmin_cp_info cp , 
             NokiaPaymentPlat.npp_app_paymentmethod_match pmatch , NokiaPaymentPlat.nppadmin_paymentmethod method
             where app.cp_id = cp.cp_id and ";
	
		$npp_cp = $_REQUEST['npp_cp'];
		if($npp_cp!="不限" && $npp_cp!="")
		{
			$sql_search.= " cp.cp_name = '".$npp_cp."' and ";
			$sql_search_sum.= " cp.cp_name = '".$npp_cp."' and ";
		}
	
		$app_key = $_REQUEST['app_key'];
		if(preg_match("/^[\x80-\xff_a-zA-Z0-9]+$/",$app_key))
		{
			$sql_search.= " app.app_name like '%".$app_key."%' and ";
			$sql_search_sum.= " app.app_name like '%".$app_key."%' and ";
		}
		elseif($app_key=='')
		{
			//continue;
		}
		else
		{
			echo "<script language=\"JavaScript\">alert('关键字输入不合法!');</script>";	
			$error = "true";
		}	
	
		$app_status = $_REQUEST['app_status'];
		if($app_status!="不限" && $app_status!="")
		{
			$sql_search.= " app.status = '".$app_status."' and ";
			$sql_search_sum.= " app.status = '".$app_status."' and ";
		}
	
		$app_id = $_REQUEST['app_id'];
		if(preg_match("/^([[0-9])+$/",$app_id))
		{
			$sql_search.= " app.app_id = '".$app_id."' and ";
			$sql_search_sum.= " app.app_id = '".$app_id."' and ";
		}
		elseif($app_id =='')
		{
			//continue;
		}
		else
		{
			echo "<script language=\"JavaScript\">alert('内容ID输入不合法!');</script>";	
			$error = "true";
		}
		
		if(isset($_REQUEST["payment_type"])&&$_REQUEST["payment_type"]!=""&&$_REQUEST["payment_type_checkbox"]=="on")
		{	
			$sql_search.=" app.app_id = pmatch.app_id and pmatch.method_id = method.id and 
			               method.kind='".$_REQUEST['payment_type']."' and ";
			$sql_search_sum.=" app.app_id = pmatch.app_id and pmatch.method_id = method.id and 
			               method.kind='".$_REQUEST['payment_type']."' and ";
		}
		if($_REQUEST["source"]!=""&&isset($_REQUEST["source"])&&$_REQUEST["source_checkbox"]=="on")
		{
			$sql_search.=" app.app_id = pmatch.app_id and pmatch.method_id = method.id and 
			               method.support='".$_REQUEST['source']."' and ";
			$sql_search_sum.=" app.app_id = pmatch.app_id and pmatch.method_id = method.id and 
			               method.support='".$_REQUEST['source']."' and ";
		}
		if($_REQUEST["channel"]!=""&&isset($_REQUEST["channel"])&&$_REQUEST["channel_checkbox"]=="on")
		{
			$sql_search.=" app.app_id = pmatch.app_id and pmatch.method_id = method.id and 
			               method.channel_id='".$_REQUEST['channel']."' and ";
			$sql_search_sum.=" app.app_id = pmatch.app_id and pmatch.method_id = method.id and 
			               method.channel='".$_REQUEST['channel']."' and ";
		}
	
	$sql_search.=" app.status!=5 ";
	$sql_search_sum.=" app.status!=5 ";	
	
	if($error!="")
	{
		$sql_search_sum ="";
	}
	/*取总查询条数*/

	$res_search_sum = $db->query($sql_search_sum);
//	$out_search_sum = $db->fetch_array($res_search_sum);
	$out_search_sum=mysql_num_rows($res_search_sum);
	/*取总页数*/
	$sumpage=ceil($out_search_sum/$pagesize);
	/*根据不同排序条件，拼接不同查询语句*/
if(isset($_REQUEST['number']))
{	
	
	$sql_search.=" limit ".($currentpage-1)*$pagesize.",".$pagesize." ";
	
	if($error!="")
	{
		$sql_search = "";
	}
	//echo $sql_search;
//	exit;
	//die($sql_search);
	$res_search = $db->query($sql_search);
	//查询结果表格显示
		$tablelist .= "<thead>";
			$tablelist .=  "<tr>";
				$tablelist .=  "<th>内容ID</th>";
				$tablelist .=  "<th>内容名称</th>";
				//$tablelist .=  "<th>价格</th>";				
				$tablelist .=  "<th>开发者</th>";				
				$tablelist .=  "<th colspan=\"2\">内容状态</th>";
				$tablelist .=  "<th>配置支付渠道</th>";

			$tablelist .=  "</tr>";
		$tablelist .= "</thead>";
		$tablelist .= "<tbody>";
			while($out_search = $db->fetch_array($res_search))
			{
				$checkdata .= "y";
				$tablelist .=  "<tr>";
					$tablelist .=  "<td>".$out_search['app_id']."</td>";
					$tablelist .=  "<td><a href = 'app_message.php?id=".$out_search['app_id']."'>".$out_search['app_name']."</a></td>";
					//$tablelist .=  "<td>".$out_search['app_price']."</td>";
					$tablelist .=  "<td>".$out_search['cp_name']."</td>";					
					if($out_search['status']==1||$out_search['status']==3)
					{
						$tablelist .=  "<td><select name=\"'".$out_search['app_id']."'\" disabled = \"disabled\" id=\"'".$out_search['app_id']."'\" >";
					}
					else 
					{
						$tablelist .=  "<td><select name=\"'".$out_search['app_id']."'\" id=\"'".$out_search['app_id']."'\" >";
					}
					$sql = "SELECT * FROM nppadmin_app_status where id!=5";
					$res = $db->query($sql);
					while($out = $db->fetch_array($res))
					{
						if($out['id']==$out_search['status'])
						{							
							$tablelist .=  "<option value=".$out['id']." selected >".$out['status']."</option>";
						}
						else
						{
							if($out_search['status']==1||$out_search['status']==3)
							{
								$tablelist .=  "<option value=".$out['id']." >".$out['status']."</option>";
							}
							else
							{
								if($out['id']==1||$out['id']==3)
								{
									$tablelist .="";
								}
								else
								{
									$tablelist .=  "<option value=".$out['id']." >".$out['status']."</option>";
								}
							}
						}
					}							
					$tablelist .=  "</select></td>";
					if($out_search['status']==1||$out_search['status']==3)
					{
						$tablelist .=  "<td><input type = \"button\" value=\"确定\" disabled = \"disabled\" onclick=\"javascript:change_status('".$out_search['app_id']."','".$out_search['status']."')\" ></td>";
					}
					else
					{
						$tablelist .=  "<td><input type = \"button\" value=\"确定\" onclick=\"javascript:change_status('".$out_search['app_id']."','".$out_search['status']."')\" ></td>";
					}

				$tablelist .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"../developer_management/payment_detail.php?app_id=".$out_search['app_id']."\">查看</a></td>";

				$tablelist .=  "</tr>";	
			}
		$tablelist .= "</tbody>";
}
$prepage = $currentpage-1;
$nextpage = $currentpage+1;
$smarty->assign("out_app_sum",$out_search_sum);
$smarty->assign("currentpage",$currentpage);
$smarty->assign("sumpage",$sumpage);
$smarty->assign("tablelist",$tablelist);
$smarty->assign("checkdata",$checkdata);
$smarty->assign("nextpage",'app_select_edit.php?app_id='.$app_id.'&app_key='.$app_key.'&npp_cp='.$npp_cp.'&app_status='.$app_status.'&currentpage='.$nextpage.'&number=0.29387723984');
$smarty->assign("prepage",'app_select_edit.php?app_id='.$app_id.'&app_key='.$app_key.'&npp_cp='.$npp_cp.'&app_status='.$app_status.'&currentpage='.$prepage.'&number=0.29387723984');
$smarty->assign("nowpage",'app_select_edit.php?app_id='.$app_id.'&app_key='.$app_key.'&npp_cp='.$npp_cp.'&app_status='.$app_status.'&number=0.29387723984');
/*定位下拉选择框已选择的选项*/

//if(isset($_REQUEST['submit_test']))
//{
//$smarty->assign("out_app_sum",$out_search_sum);
//$smarty->display("app/page_1.htm");
//}

$smarty->display("app/app_select_edit.htm");
/*记忆select框选择状态*/
function check_status($check)
{
	if($_REQUEST[$check])
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
/*记忆select框选择值*/
function select_value($select_name)
{
	if(isset($_REQUEST[$select_name]))
	{
		echo "<script>";
		echo "document.getElementById('".$select_name."').value='".$_REQUEST[$select_name]."';";
		echo "</script>";
	}
}
?>
</body>
</html>