<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
RoleVerify('21',$db);
$pagesize=10;

$outpagelist = "";
$sql_search="select * from nppadmin_paymentmethod ";


	$res_search = $db->query($sql_search);

	while($out_search = $db->fetch_array($res_search))
	{
		$outpagelist .= "<tr>";
//			$outpagelist .= "<td><div align=\"center\">".$out_search['id']."</div></td>";
//			$outpagelist .= "<td><div align=\"center\">".$out_search['name']."</div></td>";
//			$outpagelist .= "<td><div align=\"center\">".$out_search['channel_id']."</div></td>";
//			$outpagelist .= "<td><div align=\"center\">".$out_search['kind']."</div></td>";
//			$outpagelist .= "<td><div align=\"center\">".$out_search['support']."</div></td>";
//			$outpagelist .= "<td><div align=\"center\">".$out_search['min_amount']."</div></td>";
//			$outpagelist .= "<td><div align=\"center\">".$out_search['max_amount']."</div></td>";
//			$outpagelist .= "<td><div align=\"center\">".$out_search['status']."</div></td>";
//			$outpagelist .= "<td><div align=\"center\">".$out_search['net']."</div></td>";
//			$outpagelist .= "<td><div align=\"center\">".$out_search['net_bad_rate']."</div></td>";
//			$outpagelist .= "<td><div align=\"center\">".$out_search['nokia_rate']."</div></td>";
//			$outpagelist .= "<td><div align=\"center\">".$out_search['payment_rate']."</div></td>";
//			$outpagelist .= "<td><div align=\"center\">".$out_search['apper_rate']."</div></td>";
			$outpagelist .= "<td><div align=\"center\">".$out_search['id']."</div></td>";
			$outpagelist .= "<td><div align=\"center\">".$out_search['channel_id']."</div></td>";
		//	$outpagelist .= "<td><div align=\"center\">".$out_search['method_id']."</div></td>";
		//	$outpagelist .= "<td><div align=\"center\">".$out_search['weight']."</div></td>";
		//	$outpagelist .= "<td><div align=\"center\">".$out_search['rate']."</div></td>";
		$outpagelist .= "</tr>"."\n";
	}
if($query)
{
	echo "<script>alert('yes');</script>";
}

$smarty->assign("outpagelist",$outpagelist);
$smarty->display("log/nppadmin_log_info.htm");
?>
