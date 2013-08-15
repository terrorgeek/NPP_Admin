<?php 
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
RoleVerify('12',$db);
$operation="";
$cp_id="";
if(isset($_REQUEST["cp_id"])&&$_REQUEST["cp_id"]!=""&&isset($_REQUEST["operation"])&&$_REQUEST["operation"]!="")
{
	//判断一下用get传过来的值是删除还是添加 
	if($_REQUEST["operation"]=="delete")
	{
		//如果是删除，则删除
		$cp_id=$_REQUEST["cp_id"];
		$sql="delete from npp_settlement_blacklist where cp_id='".$cp_id."'";
		$query_delete=mysql_query($sql);
		if($query_delete)
		{
			echo "<script>alert('删除成功!');</script>";
		}
		else
		{
			echo "<script>alert('删除失败!');</script>";
		}
	}
	if($_REQUEST["operation"]=="insert")
	{
		$cp_id=$_REQUEST["cp_id"];
		$sql="insert into npp_settlement_blacklist (cp_id, status) values ('".$cp_id."','1')";
		$query_insert=mysql_query($sql);
		if($query_insert)
		{
			echo "<script>alert('添加成功!');</script>";
		}
		else
		{
			echo "<script>alert('添加失败!');</script>";
		}
	}
}
?>