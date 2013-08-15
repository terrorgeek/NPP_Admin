<?php 
require_once('../../session_mysql.php');
session_start();
$fee_point=$_POST["fee_point"];
$fee_times=$_POST["fee_times"];
$cp_id=$_POST["fee"];
$is_same=$_POST["is_same"];
$cp_email=$_POST["cp_email"];

$_SESSION["fee_point"]=$fee_point;
$_SESSION["fee_times"]=$fee_times;
$_SESSION["cp_id"]=$cp_id;
$_SESSION["is_same"]=$is_same;
$_SESSION["cp_email"]=$cp_email;
?>
<script type="text/javascript">
function input_reason()
{
	var content=prompt('原因(限20个字以内)：','');
	if(content!=null&&content.length<20)
	{
        window.location.href="pass_fee_type.php?reason="+content+"";
	}
	else if(content==""||content==null)
		{
		   window.location.href="developer_check.php";
		}
	else if(content.length>20)
		{
		   alert("原因长度不能大于20!");
		   input_reason();
		}
}
 var flag=confirm('确认修改该开发者的计费类型?');
	if(flag)
	{
	    input_reason();
	 }
    else
	 {
	    window.location.href="developer_check.php";
	 }
</script>