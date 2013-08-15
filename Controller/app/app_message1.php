<?php
require_once('../../session_mysql.php');
session_start(); 
require_once('../../connector.ini.php');
//三位三位加小数点的函数
function tran($num)
    {
        $v = explode('.',$num);//把整数和小数分开
        $rl = $v[1];//小数部分的值
        $j = strlen($v[0]) % 3;//整数有多少位
        $sl = substr($v[0], 0, $j);//前面不满三位的数取出来
        $sr = substr($v[0], $j);//后面的满三位的数取出来
        $i = 0;
        while( $i <= strlen($sr) ){
            $rvalue = $rvalue.','.substr($sr, $i, 3);//三位三位取出再合并，按逗号隔开
            $i = $i + 3;
        }
        $rvalue = $sl.$rvalue;
        $rvalue = substr($rvalue,0,strlen($rvalue)-1);//去掉最后一个逗号
        $rvalue = explode(',',$rvalue);//分解成数组
     
        if($rvalue[0]==0){
            array_shift($rvalue);//如果第一个元素为0，删除第一个元素
        }
     
        $rv = $rvalue[0];//前面不满三位的数
        for($i = 1; $i < count($rvalue); $i++){
            $rv = $rv.','.$rvalue[$i];
        }
        if(!empty ($rl)){
            $rvalue = $rv.'.'.$rl;//小数不为空，整数和小数合并
        }else{
            $rvalue = $rv;//小数为空，只有整数
        }
        return $rvalue;
    }


function display_app_size($app_sizes)
{
	$KB_value=floor($app_sizes/1024);
	if($KB_value<1)
	{
		return 1;
	}
	else if($KB_value>=1&&$KB_value<1000)
	{
		return $KB_value;
	}
	else if($KB_value>=1000)
	{
		return tran($KB_value);
	}
}
if(isset($_GET['id']))
{
	$id=$_GET['id'];
	$sql = "SELECT nppadmin_app_info.*,nppadmin_cp_info.cp_name FROM nppadmin_app_info JOIN nppadmin_cp_info ON nppadmin_app_info.cp_id=nppadmin_cp_info.cp_id  WHERE nppadmin_app_info.app_id = '".$id."'";
	$res = $db->query($sql);
	$out = $db->fetch_array($res);
}
$app_point_detial = get_point_detial($id);
//取图片
$sql = "SELECT icon_256_data,icon_type FROM npp_app_pic WHERE app_id = '".$id."'";
$query = $db -> query($sql);
$result = $db -> fetch_array($query);
$app_type = get_type_and_label($out['app_type'], $out['app_type_label']);
$app_type = explode('/', $app_type);
$smarty -> assign('app_name', $out['app_name']);
$smarty -> assign('app_id', $id);
$smarty -> assign('cp_name', $out['cp_name']);
$smarty -> assign('f_type', $app_type[0]);
$smarty -> assign('s_type', $app_type[1]);
//$smarty -> assign('app_size', $out['app_size']/1024);
$smarty -> assign('app_size',display_app_size($out['app_size']));
$smarty -> assign('descri', $out['descri']);
$smarty -> assign('icon', $result['icon_256_data']);
$smarty -> assign("app_point_detial", $app_point_detial);

$type = ($result['icon_type'] == '.png')?'image/png':'image/jpeg';
//header("Content-type:".$type);
//header("Content-Disposition:attachment;filename=".$app_id."_main".$result[$pic_type.'_type']);

//$smarty->assign("outlist",$out);
$smarty->display("app/app_message1.htm");

function get_type_and_label($app_type,$app_type_label)
{
	$app_type_array = array("1"=>"应用程序 - 电子书","2"=>"应用程序 - 商业&金融","3"=>"应用程序 - 城市指南&地图","4"=>"应用程序 - 传媒","5"=>"应用程序 - 教育&参考","6"=>"应用程序 - 娱乐","7"=>"应用程序 - 健康&健身","8"=>"应用程序 - 生活方式&休闲","9"=>"应用程序 - 音乐","10"=>"应用程序 - 资讯&天气","11"=>"应用程序 - 图片&视频","12"=>"应用程序 - 生产力","13"=>"应用程序 - 社交网络","14"=>"应用程序 - 运动","15"=>"应用程序 - 工具");
	$f_type_out = $app_type_array["$app_type"];
	$aryTree = file("classify.txt");
	$app_type_label = unserialize($app_type_label);
	foreach($app_type_label as $key => $value)
	{
		$s_type["$key"] = $app_type_label["$key"];
	}
	foreach($aryTree as $key => $value)
	{
		$type_tmp = explode(",",$value);
		$f_type_tmp = $type_tmp[0];
		if($app_type == $f_type_tmp)
		{
			for($i=1;$i<=(sizeof($type_tmp)-1);$i++)
			{
				if(in_array($i,$s_type))
				{
					$s_type_out.= "<br>".$type_tmp["$i"];
				}
			}
		}
	}
	return $f_type_out."/".$s_type_out;
}
?>
