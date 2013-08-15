<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
RoleVerify('15',$db);
$smarty->display("blacklist/blacklist_upload.htm");
?>