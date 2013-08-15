<?php 
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');

//排序的标志位
$flag=$_POST["sort_method"];

//先接收一下参数
$channel_name=$_POST["channel_name"];
$rate=$_POST["rate"];
$account=$_POST["account"];
$key_pattern=$_POST["key_pattern"];
$id=$_POST["id"];

if(isset($_POST["submit_check"])&&$_POST["submit_check"]=="submit_check")
{
//总sql查询语句
$sql_search_channelinfo="select * from nppadmin_channelinfo where ";
if(isset($_POST["id"])&&$_POST["id"]!="")
{
	$sql_search_channelinfo.=" id='".$id."' and ";
}
if(isset($_POST["channel_name"])&&$_POST["channel_name"]!="")
{
	$sql_search_channelinfo.=" channel_name='".$channel_name."' and ";
}
if(isset($_POST["account"])&&$_POST["account"]!="")
{
	$sql_search_channelinfo.=" account='".$account."' and ";
}
if(isset($_POST["rate"])&&$_POST["rate"]!="")
{
	$sql_search_channelinfo.=" rate='".$rate."' and ";
}
if(isset($_POST["key_pattern"])&&$_POST["key_pattern"]!="")
{
	$sql_search_channelinfo.=" key_pattern='".$key_pattern."' and ";
}
$sql_search_channelinfo.=" 1=1";
if(isset($_POST["sort_var"])&&$_POST["sort_var"]!="")
{
	if($_POST["sort_var"]=="channel_id")
	{
		if($flag=="ASC")
		{
			$sql_search_channelinfo.=" order by id ASC";
		}
		else
		{
			$sql_search_channelinfo.=" order by id DESC";
		}
	}
	elseif($_POST["sort_var"]=="channel_name")
	{
		if($flag=="ASC")
		{
			$sql_search_channelinfo.=" order by channel_name ASC";
		}
		else 
		{
			$sql_search_channelinfo.=" order by channel_name DESC";
		}
	}
	elseif($_POST["sort_var"]=="account")
	{
		if($flag=="ASC")
		{
			$sql_search_channelinfo.=" order by account ASC";
		}
		else
		{
			$sql_search_channelinfo.=" order by account DESC";
		}
	}
	elseif($_POST["sort_var"]=="key_pattern")
	{
		if($flag=="ASC")
		{
			$sql_search_channelinfo.=" order by key_pattern ASC";
		}
		else
		{
			$sql_search_channelinfo.=" order by key_pattern DESC";
		}
	}
	elseif($_POST["sort_var"]=="rate")
	{
		if($flag="ASC")
		{
		    $sql_search_channelinfo.=" order by rate ASC";
		}
	   else
	   {
	   	     $sql_search_channelinfo.=" order by rate DESC";
	   }
	}
}

$query=mysql_query($sql_search_channelinfo);
$outpagelist="";
while($out=mysql_fetch_array($query))
{
	$outpagelist.="<tr>";
	$outpagelist.="<td>".$out['id']."</td>";
	$outpagelist.="<td>".$out['channel_name']."</td>";
	$outpagelist.="<td>".$out['account']."</td>";
	$outpagelist.="<td>".$out["key_pattern"]."</td>";
	$outpagelist.="<td>".$out["rate"]."</td>";
	$outpagelist.="</tr>";
}
echo $outpagelist;
}
else {
	$smarty->display("developer_management/nppadmin_channelinfo.html");
}

?>