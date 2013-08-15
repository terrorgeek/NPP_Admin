<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');

function check_status($check)
{
	if(isset($_POST[$check]))
	{
		return "checked";
	}
}

function check_text($text)
{
	if(isset($_POST[$text]))
	{
		return $_POST[$text];
	}
}
$developer_type=array("enterprise_developer"=>"企业开发者","personal_developer"=>"个人开发者","all"=>"不限");
foreach($developer_type as $k=>$v)
{
    if(isset($_POST["developer_type"])&&$_POST["developer_type"]==$k)
    {
	    $developer_type_pageout.="<option value=".$k." selected=\"selected\">".$v."</option>";
    }
    else
    {
    	$developer_type_pageout.="<option value=".$k.">".$v."</option>";
    }
}
$smarty->assign("developer_type_pageout",$developer_type_pageout);
$smarty->display("developer_management/developer_management.html");
?>