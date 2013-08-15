<?php
define('base_path',$_SERVER['DOCUMENT_ROOT']);//定义服务器的绝对路径

//define('smarty_path','/npp_smarty');//定义smarty目录的绝对路径
//define('smarty_path','/npp/NPP_Admin');//定义smarty目录的绝对路径--服务器
define('smarty_path','/NPP_Admin');//定义smarty目录的绝对路径--服务器

require (base_path.smarty_path."/smarty/Smarty.class.php");//加载Smarty类库文件


$smarty = new Smarty(); //建立smarty实例对象$smarty

$smarty->config_dir= base_path.smarty_path."Smarty/Config_File.class.php";  // 目录变量

$smarty->caching=false; //是否使用缓存，项目在调试期间，不建议启用缓存

$smarty->template_dir =  base_path.smarty_path."/View"; //设置模板目录

$smarty->compile_dir =  base_path.smarty_path."/templates_c"; //设置编译目录

$smarty->cache_dir =  base_path.smarty_path."/smarty_cache"; //缓存文件夹

//------------------------------------=----------------

//左右边界符，默认为{}，但实际应用当中容易与JavaScript相冲突

//----------------------------------------------------

$smarty->left_delimiter = "{#";

$smarty->right_delimiter = "#}";

?>
