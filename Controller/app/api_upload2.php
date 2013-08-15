<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
$type="";
$q=$_GET["q"];
//============取version======
$a_version = array();
$sql="select api_version from npp_api where api_type='".$q."'";
$res= $db->query($sql);
$n=0;
while($out = $db->fetch_array($res))
{
    $a_version[$n]=$out['api_version'];
    $n++;
}
rsort($a_version);
if(isset($a_version[0]))
{$d_version=$a_version[0];
}else{
if($q>0){
$d_version="未上传文件";
}else{
$d_version="";
}

}



//==============获取最近更新时间=======
$d_uptime = array();
$sql="select api_uptime from npp_api where api_version='".$d_version."' and api_type='".$q."'";
$res= $db->query($sql);
$out = $db->fetch_array($res);
if(isset($out['api_uptime']))
{ $uptime=$out[0];
  $n=strrpos($uptime,"_");
  $rtime=substr($uptime,0,$n);
  $newest_uptime=$rtime;
}else{
if($q>0){
$newest_uptime="未上传文件";
}else{
$newest_uptime="";
}}

switch ($q) {
	case "1": $type="仅限JAR格式";break;
	case "2": $type="仅限DLL格式";break;
	case "3": $type="仅限SIS(X)格式";break;
	case "4": $type="仅限SIS(X)格式";break;
	}

	$db->close();

   
     $version=" 当前版本号： &nbsp <input class=\"text-input small-input\" type=\"test\" name=\"d_version\" disabled=\"disabled\" value=\"".$d_version."\" />
                           <input type=\"hidden\" name=\"h_version\"  value=\"".$d_version."\" />
				<br/>    
                <br/>		   
                &nbsp更新时间：&nbsp&nbsp&nbsp&nbsp&nbsp <input class=\"text-input small-input\" type=\"test\" name=\"newest_uptime\" disabled=\"disabled\"  value=\"".$newest_uptime."\"/>  ";
		
	  $type="	&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
	          <span class=\"smltxt\"  name=\"type\" id=\"type\">".$type."</span>";
			  
echo $version."@".$type."@".$d_version;	  
?>

