<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
RoleVerify('12',$db);
$cp_name_or_id="";
$outpagelist="";
if(isset($_REQUEST["cp_name_or_id"])&&$_REQUEST["cp_name_or_id"])
{
	if(eregi("and|or|select|update|delete|union",$_REQUEST["cp_name_or_id"]))
	{
		echo "<script>alert('请输入合法的查询参数!');</script>";
		echo "<script>history.go(-1);</script>";
		exit;
	}
	$cp_name_or_id=$_REQUEST["cp_name_or_id"];
	//先查找有没有该开发者
	$sql_find_cp="select cp_id, cp_name from nppadmin_cp_info where cp_id='".$cp_name_or_id."' or cp_name='".$cp_name_or_id."'";
	$query_cp=mysql_query($sql_find_cp);
	if(mysql_num_rows($query_cp)>0)
	{
		//再查找该开发者是否在黑名单中
	    $sql_black="select npp_settlement_blacklist.cp_id, npp_settlement_blacklist.status, nppadmin_cp_info.cp_name,nppadmin_cp_info.cp_class,
	          nppadmin_cp_info.reg_date from npp_settlement_blacklist,nppadmin_cp_info where nppadmin_cp_info.cp_id=npp_settlement_blacklist.cp_id 
	          and (npp_settlement_blacklist.cp_id='".$cp_name_or_id."' or nppadmin_cp_info.cp_name='".$cp_name_or_id."') ";
	    $query_black_list=mysql_query($sql_black);
        //输出这个开发者, 另开一条sql语句
        $sql_output="select cp_id, cp_name, cp_class, reg_date from nppadmin_cp_info where cp_id='".$cp_name_or_id."' or cp_name='".$cp_name_or_id."'";
        $query_output=mysql_query($sql_output);
	    $result=mysql_fetch_array($query_output);
	//    $outpagelist.="<tr>";
	    $outpagelist.="<td>".$result['cp_id']."</td>";
	   	$outpagelist.="<td>".$result['cp_name']."</td>";
	   	if($result["cp_class"]==1)
	   	{
	   		$outpagelist.="<td>个人开发者</td>";
	   	}
	    elseif($result["cp_class"]==2)
	    {
	    	$outpagelist.="<td>企业开发者</td>";
	    }
	    $outpagelist.="<td>".$result['reg_date']."</td>";
	    if(mysql_num_rows($query_black_list)>0)
	    {
	    	$outpagelist.="<td><a href='delete_from_blacklist.php?cp_id=".$result['cp_id']."&operation=delete'>删除</a></td>";
	    }    
	    else
	    {
	    	$outpagelist.="<td><a href='delete_from_blacklist.php?cp_id=".$result['cp_id']."&operation=insert'>添加</a></td>";
	    }
	}
	else
	{
		echo "<script>alert('此开发者还未注册,系统中没有此开发者,请重新核实后再查询!');</script>";
		echo "<script>history.go(-1);</script>";
		exit;
	}
}
$smarty->assign("outpagelist",$outpagelist);
$smarty->display("settlement/settlement_control.html");
?>