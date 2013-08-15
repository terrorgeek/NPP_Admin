<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
RoleVerify('12',$db);
$initial_money="";
if(isset($_REQUEST["initial_money"])&&$_REQUEST["initial_money"]!="")
{
	$initial_money=$_REQUEST["initial_money"];
	//设置所有的开发者的起结金额
	//先查查表里有无数据
	$sql_find="select id from npp_settlement_money";
	$query_nums=mysql_query($sql_find);
	if(mysql_num_rows($query_nums)==0)
	{
		//如果没有，则插入第一条
		$sql_insert_first_one="insert into npp_settlement_money(money) values ('".$initial_money."')";
		$query_insert=mysql_query($sql_insert_first_one);
		if($query_insert)
		{
			echo "<script>alert('修改成功!');</script>";
			echo "<script>window.location.href='settlement_initial_money.php';</script>";
			exit;
		}
		else
		{
			echo "<script>alert('修改失败!');</script>";
			echo "<script>window.location.href='settlement_initial_money.php';</script>";
			exit;
		}
	}
	else
	{
		//如果有，先删除，再插入，这个表只有一条数据
		$sql_delete="delete from npp_settlement_money";
		$q1=mysql_query($sql_delete);
		$sql_insert_again="insert into npp_settlement_money(money) values ('".$initial_money."')";
		$q2=mysql_query($sql_insert_again);
		if($q1&&$q2)
		{
		    echo "<script>alert('修改成功!');</script>";
			echo "<script>window.location.href='settlement_initial_money.php';</script>";
			exit;
		}
		else
		{
			echo "<script>alert('修改失败!');</script>";
			echo "<script>window.location.href='settlement_initial_money.php';</script>";
			exit;
		}
	}
}
$smarty->display("settlement/settlement_initial_money.html");
?>