<?php
require_once('../../session_mysql.php');
session_start(); 
require("../../smarty_inc.php");
$session_id = session_id();//��ȡsession_id
$_SESSION = array();//��session�����ÿ�
$ss = sess_destroy($session_id);//ɾ��session
$smarty->display("login/index.htm");
?>
