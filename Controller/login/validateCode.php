<?php
require_once('../../session_mysql.php');
session_start();
Function getRandNumber($fMin,$fMax){
    srand((double)microtime()*1000000);
    $fLen="%0".strlen($fMax)."d ";
    Return sprintf($fLen,rand($fMin,$fMax));
}
$str=getRandNumber(1000,9999);

//生成随机数
//for ($num = 0; $num < 4; $num++) {
//	$str.= dechex(rand(0, 15));//dechex()方法将十进制数转换为十六进制
//}
//随机数转换为字符串，并将字符串保存到session
$_SESSION["validateCoder"] = $str + "";//
//die("aaaaaaa");
//生成图片
$width = 50; //图片宽度
$height = 18; //图片高度 
$im=imagecreate($width,$height);
//图片背景
$_bgR = rand(200, 255);
$_bgG = rand(150, 255);
$_bgB = rand(200, 255);
$back=imagecolorallocate($im,0xFF,0xFF,0xFF);
$pix=imagecolorallocate($im,187,230,247);
//文字颜色
$font=imagecolorallocate($im,41,163,238); 
for($i=0;$i<1000;$i++)
{//雪花效果
imagesetpixel($im,mt_rand(0,$width),mt_rand(0,$height),$pix);
}
//字符写在图像上
//s@header("Content-Type:image/png");
imagestring($im, rand(0, 50), rand(0, 15), rand(0, 5),$str, $font);
imagerectangle($im,0,0,$width-1,$height-1,$font);
imagepng($im);
imagedestroy($im);
?>