<?php
require_once('../../session_mysql.php');
session_start(); 
require("../../smarty_inc.php");
$session_id = session_id();//获取session_id
$_SESSION = array();//将session数组置空
$ss = sess_destroy($session_id);//删除session
$smarty->display("login/index.htm");
?>
