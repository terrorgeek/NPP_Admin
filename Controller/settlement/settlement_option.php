<?php 
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
RoleVerify('12',$db);

$smarty->display("settlement/settlement_option.html");
?>