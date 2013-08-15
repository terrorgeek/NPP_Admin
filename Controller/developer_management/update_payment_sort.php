<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');

//此文件来更新支付类型的权重
if(isset($_POST["app_id"])&&$_POST["app_id"]!="")
{
	$post_vars=$_POST;
  //这里从$_POST中先把app_id取出来
  $app_id=$post_vars["app_id"];
  unset($post_vars["app_id"]);
  //将npp_app_paymentmethod_match表中的app_id对应的method_id全删掉
  $sql_delete_match="delete from npp_app_paymentmethod_match where app_id='".$app_id."'";
  $query_delete_match=mysql_query($sql_delete_match);
  //在这里key为paymentmethod的id，而value为用户给的text值
  if(count($post_vars)>0)
  {
    foreach ($post_vars as $key => $value) 
    {
    	//删掉之后重新插入
	  $sql_insert_match="insert into npp_app_paymentmethod_match (app_id,method_id,weight,rate) 
	                     values ('".$app_id."','".$key."','".$value."','0')";
	  $query_insert=mysql_query($sql_insert_match);
    }
    //这里要将新配置的支付渠道写到日志中
    $now=date("Y-m-d H:m:s");
    //将app_id对应的应用名查处来
    $sql_find_app_name="select app_name from nppadmin_app_info where app_id='".$app_id."'";
    $query_app_name=mysql_query($sql_find_app_name);
    $result_app_name=mysql_fetch_array($query_app_name);
    //真正的插入日志语句
    $sql_insert_log="insert into nppadmin_log (user_id,action,result,time) 
                     values ('".$_SESSION['userid']."',43,'为".$result_app_name['app_name']."配置支付渠道','".$now."')";
   // mysql_query($sql_insert_log);
    LogRecord($_SESSION['userid'],43,"为".$result_app_name['app_name']."配置支付渠道",$db);
  }
  else
  {
  	 //将app_id对应的应用名查处来
    $sql_find_app_name="select app_name from nppadmin_app_info where app_id='".$app_id."'";
    $query_app_name=mysql_query($sql_find_app_name);
    $result_app_name=mysql_fetch_array($query_app_name);
  	LogRecord($_SESSION['userid'],43,"为".$result_app_name['app_name']."配置支付渠道",$db);
  }
}

if(isset($_POST["sort_var"])&&$_POST["sort_var"]!="")
{
	$app_id=$_POST["app_id_sort"];
	//获取排序方法，看看是正序还是倒序
	$sort_method=$_POST["sort_method"];
  //这里查出该应用所对应的计费类型的id，之后放进一个数组
  $app_payment_method_array=array();
  $sql_search_payment_type="select method_id,weight from npp_app_paymentmethod_match where app_id='".$app_id."'";
  $query_payment_type=mysql_query($sql_search_payment_type);
  while($out=mysql_fetch_array($query_payment_type))
  {
	  array_push($app_payment_method_array,$out["method_id"]);
  }
  //np为nppadmin_paymentmethod,  npsource为npp_payment_source
  $sql = "select nppadmin_channelinfo.channel_name, npp_payment_type.name, np.name npname, np.id npid, npsource.name source_name from nppadmin_channelinfo, npp_payment_type, nppadmin_paymentmethod np,npp_payment_source npsource  where nppadmin_channelinfo.id=np.channel_id and np.kind=npp_payment_type.id and np.status=1 and npsource.id=np.support ";
  if($_POST["sort_var"]=="channel")
  {
  	if($sort_method=="ASC")
  	{
  		$sql.=" order by nppadmin_channelinfo.channel_name ";
  	}
  	if($sort_method=="DESC")
  	{
  		$sql.=" order by nppadmin_channelinfo.channel_name DESC ";
  	}
  }
  if($_POST["sort_var"]=="paymentmethod")
  {
  	if($sort_method=="ASC")
  	{
  		$sql.="order by npp_payment_type.name ";
  	}
  	if($sort_method=="DESC")
  	{
  		$sql.="order by npp_payment_type.name DESC ";
  	}	 
  }
  if($_POST["sort_var"]=="p_name")
  {
  	if($sort_method=="ASC")
  	{
  		$sql.="order by npname ";
  	}
  	if($sort_method=="DESC")
  	{
  		$sql.="order by npname DESC ";
  	}
  	 
  }
  if($_POST["sort_var"]=="source")
  {
  	if($sort_method=="ASC")
  	{
  		$sql.=" order by source_name ";
  	}
  	if($sort_method=="DESC")
  	{
  		$sql.=" order by source_name DESC ";
  	}
  }
  $result = $db->query($sql);
  $tablelist = "";
  $sum = mysql_num_rows($result);
  $count = 0;
  while($out_app=$db->fetch_array($result)) 
  {
	$count++;
	$weight="";
	$tablelist .= "<tr>";
	if(in_array($out_app['npid'],$app_payment_method_array))
	{
		$tablelist.="<td><input type=\"checkbox\" id='".$sum."_checkbox' name='".$sum."_checkbox' value='".$out_app['npid']."' checked=\"checked\" /></td>";
		$sql_find_weight="select weight from npp_app_paymentmethod_match where app_id='".$app_id."' and method_id='".$out_app['npid']."'";
		$query_weight=mysql_query($sql_find_weight);
		$result_weight=mysql_fetch_array($query_weight);
		$weight=$result_weight["weight"];
	}
	else
	{
		$tablelist.="<td><input type=\"checkbox\" id='".$sum."_checkbox' name='".$sum."_checkbox' value='".$out_app['npid']."' /></td>";
	}
	$tablelist .= "<td>".$out_app['channel_name']."</td>";
	$tablelist .= "<td>".$out_app['name']."</td>";
	$tablelist .= "<td>".$out_app['npname']."</td>";
	$tablelist .= "<td>".$out_app['source_name']."</td>";
	$tablelist .= "<td><a href=\"javascript:void(0)\" onclick=\"loadwindow2(500,800,'".$out_app['npid']."');return false\">查看详情</a></td>";
	//权重文本框后面的要跟一个hidden来存放以前的weight值
	$tablelist .= "<td><input type=\"text\" id='".$sum."' name='".$sum."' size=1 value='".$weight."' /></td>";
	$tablelist .= "</tr>";
	$sum--;
  }
  echo $tablelist;
}

?>