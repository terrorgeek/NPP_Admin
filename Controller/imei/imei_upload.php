<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
RoleVerify('13',$db);

$smarty->display("imei/imei_upload.htm");
?>