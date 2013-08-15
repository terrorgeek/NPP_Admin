<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');

/* $url=$_SERVER["REQUEST_URI"];
$url=parse_url($url);
$url=$url[path]; */



$channel_name="";
//if(isset($_GET['channel_name'])) {
//	$channel_name = $_GET['channel_name'];
//}



//传过来的id
$id="";//这是nppadmin_paymentmethod的id
if(isset($_GET["id"])&&$_GET["id"]!="")
{
	$id=$_GET["id"];
}
if(eregi('and|or|select|union|update|delete',$id))
{
	echo "<script>alert('您输入的参数有误!');</script>";
	echo "<script>window.location.href='payment_channel.php'</script>";
}

//先将nppadmin_paymentmethod的信息查出来
$sql_find_type="select * from nppadmin_paymentmethod where id='".$id."'";
$query=mysql_query($sql_find_type);
$result3=mysql_fetch_array($query);

//这里把channel_name查出来
$sql_find_channel_name="select channel_name from nppadmin_channelinfo where id='".$result3['channel_id']."'";
$query_channel_name=mysql_query($sql_find_channel_name);
$result_channel_name=mysql_fetch_array($query_channel_name);
$channel_name=$result_channel_name["channel_name"];
//die($channel_name."sdkjhasfjsdlk");


/*再将history表中对应的支付网关代收和坏账率查出来
$sql_find_history="select * from npp_payment_type_history where method_id='".$result3["id"]."' order by oper_date desc";
$query_history=mysql_query($sql_find_history);
$result_history=mysql_fetch_array($query_history);//此结果集是history表的
*/

//以下是遍历下拉框，把该check的check上
$type_sql = "select name,id from npp_payment_type GROUP by name";
$payment_type_options = "";
$result=$db->query($type_sql);

$payment_type_options.="<option value='default' >请选择</option>";
while($out_app=$db->fetch_array($result)) {
	if($out_app["id"]==$result3["kind"])
	{
		$payment_type_options .= "<option value=".$out_app['id']." selected=\"selected\">".$out_app['name']."</option>";
	}
	else
	{
		$payment_type_options .= "<option value=".$out_app['id']." >".$out_app['name']."</option>";
	}
}

$source_sql = "select id ,name from npp_payment_source";
$payment_source_options = "";
$result2 = $db->query($source_sql);

$payment_source_options.="<option value='default'>请选择</option>";
while($out_app=$db->fetch_array($result2)) {
	if($out_app["id"]==$result3["support"])
	{
		$payment_source_options .= "<option value=".$out_app['id']." selected=\"selected\" >".$out_app['name']."</option>";
	}
	else
	{
		$payment_source_options .= "<option value=".$out_app['id'].">".$out_app['name']."</option>";
	}	
}

//输出状态下拉框
$status_options="";
if($result3["status"]==1)
{
	$status_options.="<option value='1' selected=\"selected\">启用</option>";
	$status_options.="<option value='0' >未启用</option>";
}
else
{
	$status_options.="<option value='1' >启用</option>";
	$status_options.="<option value='0' selected=\"selected\" >未启用</option>";
}

$total_sql = "select count(*) from NokiaPaymentPlat.npp_payment_type_history";
$total_result = $db->query($total_sql);
$count_total = 0;
$pages = 1;
while($out_app=$db->fetch_array($total_result)) {
	$count_total = $out_app['count(*)'];
}
if($count_total%5 == 0 ) {
	$pages = $count_total/5;
}else {
	$pages = $count_total/5 + 1;
}

$list_sql = "select oper_date ,net,net_bad_rate ,nokia_rate ,payment_rate ,apper_rate from NokiaPaymentPlat.npp_payment_type_history order by oper_date limit 0, 5";
$list_result = $db->query($list_sql);
$netlist = "";
$badlist = "";
$nokialist = "";
$channellist = "";
$apperlist = "";

//转换为小数的函数
function change_number($number)
   {
   	  $dot_index=stripos($number,".");
   	  if($dot_index==false)
   	  {
   	  	 return $number.".00";
   	  }
   	  $dot_part=substr($number, $dot_index+1);
   	  $int_part=substr($number, 0,$dot_index);
   	  $final_str="";
   	  if(strlen($dot_part)>2)
   	  {
   	  	 $final_str=substr($dot_part, 0,2);
   	  }
   	  elseif(strlen($dot_part)==2)
   	  {
   	  	 $final_str=$dot_part;
   	  }
   	  elseif(strlen($dot_part)==1)
   	  {
   	  	 return $number."0";
   	  }
   	  return $int_part.".".$final_str;
   }
   
   
while($out_app=$db->fetch_array($list_result)) {
	$netlist .= "<tr>";
	$badlist .= "<tr>";
	$nokialist .= "<tr>";
	$channellist .= "<tr>";
	$apperlist .= "<tr>";
	$date_str = substr($out_app['oper_date'], 0, 10);
	$netlist .= "<td>".$date_str."</td>"."<td>".change_number($out_app['net']/100)."</td>";
	$badlist .= "<td>".$date_str."</td>"."<td>".change_number($out_app['net_bad_rate']/100)."</td>";
	$nokialist .= "<td>".$date_str."</td>"."<td>".change_number($out_app['nokia_rate']/100)."</td>";
	$channellist .= "<td>".$date_str."</td>"."<td>".change_number($out_app['payment_rate']/100)."</td>";
	$apperlist .= "<td>".$date_str."</td>"."<td>".change_number($out_app['apper_rate']/100)."</td>";
//    $apperlist .= "<td>".$date_str."</td>"."<td>".$out_app['apper_rate']."</td>";
	
	$netlist .= "</tr>";
	$badlist .= "</tr>";
	$nokialist .= "</tr>";
	$channellist .= "</tr>";
	$apperlist .= "</tr>";
}


//全部赋到html页面上
$smarty->assign("payment_name",$result3["name"]);
//这里需将整数(分)转换成两位小数，这需要判断多种情况
$max_amount=change_decimal($result3["max_amount"]);
$min_amount=change_decimal($result3["min_amount"]);
$net = change_decimal($result3["net"]);
$net_bad_rate = change_decimal($result3["net_bad_rate"]);
$nokia_rate = change_decimal($result3["nokia_rate"]);
$payment_rate = change_decimal($result3["payment_rate"]);
$apper_rate = change_decimal($result3["apper_rate"]);


function change_decimal($number) {
	$str1 = (int)($number/100);
	$str2 = $number%100;
	if($str2 < 10) {
		$str2 = "0".$str2;
	}
	return $str1.".".$str2;
}

$smarty->assign("max_amount",$max_amount);
$smarty->assign("min_amount",$min_amount);
$smarty->assign("key",$net);
$smarty->assign("account",$net_bad_rate);
$smarty->assign("nokia_rate",$nokia_rate);
$smarty->assign("channel_rate",$payment_rate);
$smarty->assign("apper_rate",$apper_rate);
$smarty->assign("others",$result3["others"]);
$smarty->assign("id",$result3["id"]);
$smarty->assign("status_options",$status_options);

$smarty->assign("channel_name",$channel_name);
$smarty->assign("payment_type_options",$payment_type_options);
$smarty->assign("payment_source_options",$payment_source_options);
$smarty->assign("total",$pages);
$smarty->assign("netlist",$netlist);
$smarty->assign("badlist",$badlist);
$smarty->assign("nokialist",$nokialist);
$smarty->assign("channellist",$channellist);
$smarty->assign("apperlist",$apperlist);

$smarty->display("developer_management/modify_payment_type.html");

?>
</body>
</html>