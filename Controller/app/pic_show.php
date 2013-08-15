<?php
//require_once("../../config.php");
//require_once("../../connector.ini.php");
if(isset($_GET['app_id']))
{
	//$result = get_new_app_all($_GET['app_id'],$cp_id);
	//if($result == false)
	//{
		//echo "<script language=\"javascript\">alert(该应用不属于你);";
		//echo "location.href = \"app_search.php\"
		//exit;
	//}
	//$conn	=	mysql_connect("10.219.38.185", "root", "a3I+QG?xN9j") or die("Could not connect");
	$conn	=	mysql_connect("10.219.57.72", "root", "a3I+QG?xN9j") or die("Could not connect");
	@mysql_select_db("NokiaPaymentPlat");
	//$url=$_SERVER["REQUEST_URI"];
	//$url=parse_url($url);
	//$url=$url['path']; 
	//$dir=dirname($url);
	$app_id = $_GET['app_id'];
	//$app_url =$dir.$app_url;
	//die($app_url);
	/**
	 **从数据库中取出图片数据
	 */
	$sql = "SELECT * FROM npp_app_pic WHERE app_id ='".$app_id."'";
	$query = mysql_query($sql);
	$result = $row = mysql_fetch_array($query,MYSQL_ASSOC);
	//取类型
	$type = $result['icon_type'];
	$type = ($type == '.png')?'image/png':'image/jpeg';
	header("Content-type:".$type);
	echo $result['icon_256_data'];
	mysql_close($conn);
}
?>