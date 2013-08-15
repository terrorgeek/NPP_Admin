<?php
require_once('../../session_mysql.php');
require_once('../../connector.ini.php');

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
$curpage = 1;
$resultlist = "";
$rate_type = "";
if(isset($_GET["curpage"])&&$_GET["curpage"]!="")
{
	$curpage = $_GET["curpage"];
}

if(isset($_GET["rate_type"])&&$_GET["rate_type"]!="") {
	$rate_type = $_GET["rate_type"];
	$start = ($curpage-1)*5;
	$end = $curpage*5;
	$sql = "SELECT oper_date, ".$rate_type." FROM NokiaPaymentPlat.npp_payment_type_history order by oper_date limit $start ,5";
	$result = $db->query($sql);
	while($out_app=$db->fetch_array($result)) {
		$resultlist .= "<tr>";
		$resultlist .= "<td>".substr($out_app['oper_date'],0,10)."</td>";
		$resultlist .= "<td>".change_number($out_app["$rate_type"]/100)."</td>";
		$resultlist .= "</tr>";
	}
}
echo $resultlist;
?>
