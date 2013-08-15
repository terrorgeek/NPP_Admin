<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
RoleVerify('18',$db);
/* $url=$_SERVER["REQUEST_URI"];
$url=parse_url($url);
$url=$url[path]; */

if(isset($_GET["app_id"])) {
	$app_id = $_GET["app_id"];
}
//这里查出该应用所对应的计费类型的id，之后放进一个数组
$app_payment_method_array=array();
$sql_search_payment_type="select method_id,weight from npp_app_paymentmethod_match where app_id='".$app_id."'";
$query_payment_type=mysql_query($sql_search_payment_type);
while($out=mysql_fetch_array($query_payment_type))
{
	array_push($app_payment_method_array,$out["method_id"]);
}
//$sql = "SELECT n.app_id , t.name tname , s.name sname , m.name mname , i.id cid,  i.channel_name ,p.id, p.method_id pm_id, p.weight pw FROM NokiaPaymentPlat.nppadmin_app_info n , NokiaPaymentPlat.nppadmin_paymentmethod m, NokiaPaymentPlat.npp_app_paymentmethod_match p , NokiaPaymentPlat.nppadmin_channelinfo i, NokiaPaymentPlat.npp_payment_type t, NokiaPaymentPlat.npp_payment_source s WHERE n.app_id = p.app_id and m.id = p.method_id and m.channel_id = i.id and m.kind = t.id and m.support = s.id and n.app_id = ".$app_id." order by p.weight desc";
//np为nppadmin_paymentmethod,  npsource为npp_payment_source
$sql = "select nppadmin_channelinfo.channel_name, npp_payment_type.name, np.name npname, np.id npid, npsource.name source_name from nppadmin_channelinfo, npp_payment_type, nppadmin_paymentmethod np,npp_payment_source npsource  where nppadmin_channelinfo.id=np.channel_id and np.kind=npp_payment_type.id and np.status=1 and npsource.id=np.support order by np.channel_id";
$result = $db->query($sql);
$tablelist = "";
$sum = mysql_num_rows($result);
$count = 0;
while($out_app=$db->fetch_array($result)) {
	$count++;
	$weight="";
	$tablelist .= "<tr>";
	if(in_array($out_app['npid'],$app_payment_method_array))
	{
		$tablelist.="<td><input type=\"checkbox\" id='".$sum."_checkbox' name='".$sum."_checkbox' value='".$out_app['npid']."' checked=\"checked\" /></td>";
		$sql_find_weight="select weight from npp_app_paymentmethod_match where app_id='".$app_id."' and method_id='".$out_app['npid']."'";
		$query_weight=mysql_query($sql_find_weight);
		$result_weight=mysql_fetch_array($query_weight);
		$weight=$result_weight["weight"];
	}
	else
	{
		$tablelist.="<td><input type=\"checkbox\" id='".$sum."_checkbox' name='".$sum."_checkbox' value='".$out_app['npid']."' /></td>";
	}
	$tablelist .= "<td>".$out_app['channel_name']."</td>";
	$tablelist .= "<td>".$out_app['name']."</td>";
	$tablelist .= "<td>".$out_app['npname']."</td>";
	$tablelist .= "<td>".$out_app['source_name']."</td>";
	$tablelist .= "<td><a href=\"javascript:void(0)\" onclick=\"loadwindow2(500,800,'".$out_app['npid']."');return false\">查看详情</a></td>";
	$tablelist .= "<td><input type=\"text\" id='".$sum."' name='".$sum."' size=1 value='".$weight."' /></td>";
	$tablelist .= "</tr>";
	$sum--;
}

$title = "";
$sql_title = "select app_name from nppadmin_app_info where app_id = ".$app_id;
$result2 = $db->query($sql_title);
while($out_app=$db->fetch_array($result2)) {
	$title .= $out_app['app_name'];
}

$channel_sql = "select id ,channel_name from nppadmin_channelinfo";
$channellist = "";
$result3=$db->query($channel_sql);
while($out_app=$db->fetch_array($result3)) {
	$channellist .= "<option value=".$out_app['id'].">".$out_app['channel_name']."</option>";
}
//这里要将隐藏div的两个下拉框显示出来
$sql_select_type="select * from npp_payment_type";
$query_select_type=mysql_query($sql_select_type);
//支付类型的下拉框变量
$drop_type="";
while($out_type=mysql_fetch_array($query_select_type))
{
	$drop_type.="<option value='".$out_type['id']."'>".$out_type['name']."</option>";
}
//支付来源的下拉框
$drop_source="";
$sql_select_source="select * from npp_payment_source";
$query_select_source=mysql_query($sql_select_source);
while($out_source=mysql_fetch_array($query_select_source))
{
	$drop_source.="<option value='".$out_source['id']."'>".$out_source['name']."</option>";
}
$smarty->assign("drop_type",$drop_type);
$smarty->assign("drop_source",$drop_source);
$smarty->assign("title",$title);
$smarty->assign("app_id",$app_id);
$smarty->assign("tablelist",$tablelist);
$smarty->assign("channellist",$channellist);
$smarty->assign("count",$count);
$smarty->display("developer_management/payment_detail.html");
?>
</body>
</html>