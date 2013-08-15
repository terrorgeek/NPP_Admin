<?php
require_once('../../session_mysql.php');
session_start();
require_once('../../connector.ini.php');
RoleVerify('11',$db);
header("Cache-control: private");
$cp_name_pageout ='';
$api_tupe='';
$filename ='';
$tversion='';
$stime="";
$d_version="";
$n_version="";

//===========显示”操作系统“select框内容============   
 $npp_api_type = "";
$sql = "SELECT * FROM npp_api_type_check";
$res = $db->query($sql);
while($out = $db->fetch_array($res))
{
		$cp_name_pageout .= "<option value=".$out['type_id']."  >".$out['type_name']."</option>";
} 
$smarty->assign("cp_name_pageout",$cp_name_pageout);

//==========获取select选项传参====
if(isset($_POST['type_id']))
{
    $api_type=$_POST['type_id'];
}

if(isset($_POST['h_version']))
{ 
   
	$n_version = $_POST['h_version'];
	if(preg_match("/^[1-9]{1}.[0-9]{1}.[0-9]{1}$/", $n_version))
	{
	     $d_version = $n_version ;
	}else
    {
	    $d_version = "0.0.0";
    }


}
else
{
	$d_version = "0.0.0";
}




//=======版本号传参======
if(isset($_POST['version']))
{ 
	$tversion = $_POST['version'];

}
else
{
	$tversion = "";
}


//======获取打开页面时的时间=====
if(isset($_POST['stime']))
	{
		$stime = $_POST['stime'];
    }
else 
{    
   date_default_timezone_set('PRC');
   $t=time();
   $stime=date("Y-m-d",$t);
}
$smarty->assign("stime",$stime);




//========判断是否点击发布======
if(isset($_POST['submit_test']))
{
        $click=$_POST['submit_test'];
}
else
{
    $click="";
}



//============判断是否提交表单===========
if($click)
{

  if($tversion&&$api_type&&$stime)
  {     
    if($n_version)
  {  
     $rank=array($tversion,$d_version);  
	   rsort($rank);
	 
        //========匹配版本号=======
     if(preg_match("/^[1-9]{1}[.]{1}[0-9]{1}[.]{1}[0-9]{1}$/", $tversion)&&$tversion!=$d_version&&$rank[0]==$tversion)
    {  
        $version=$tversion;
	  
	   if(preg_match("/^((?:19|20)[\d]{2})-((0?[1-9]{1})|(1[0-2]{1}))-(([012]?[1-9]{1})|(3[0-1]{1})|([1-2]0))$/",$stime))
	  {  
	      date_default_timezone_set('PRC');
               $t=time();
               $time=date("Y-m-d H:i:s",$t);
		       $ntime = $stime."_".$time;
		
  		//======上传文件========= 
     
          if (is_uploaded_file($_FILES['file']['tmp_name']))
         { 	 
		    $file=$_FILES["file"];
			$name = $file["name"];
			$error = $file["error"];
			//$_FILES["error"];

        //检验上传文件是否符合格式的函数。
        function check_type($api_type){
            $file_name=$_FILES["file"]["name"];
            $findtype=strtolower(strrchr($file_name,"."));
           // $allow=strpos($upload_type,$findtype);
		   if($api_type==1)
		   {
				if($findtype==".jar"){
										return true;
									}else{
											return false;
										 }
							}
			if($api_type==2)
		   {
				if($findtype==".dll"){
										return true;
									}else{
											return false;
										 }
							}				
							
		   if($api_type==3||$api_type==4)
		   {
		       
				if($findtype==".sis"||$findtype==".sisx"){
										return true;
									   }else{
											 return false;
												}
		   }
		  } 
		    if ($error> 0)
		   {
			  echo "Error:".$error."<br/>";
		   }
		   else 
		   {   //check_type($upload_type);
		      
			   //转换文件名编码，能判断中文名
		       //$fileadd="../../API_upload/".$name;
		       //$nfileadd=iconv('UTF-8','GB2312',$fileadd);
                //判断是否存在
		     // if (file_exists($nfileadd)===false)
              // {  
			    if(check_type($api_type))
		       {
			   //更改存储文件的名称
				 $filename =$_FILES["file"]["name"];
				 $n=strrpos($filename,".");
				 $fname=substr($filename,0,$n);
				 $lname=substr($filename,$n);
				 $ename=$fname.".".$version.$lname;
				//转码，防止中文存入文件夹名称乱码
           		 //$keepname = iconv('utf-8' , 'gbk' ,$ename);
				 
               //  move_uploaded_file($_FILES["file"]["tmp_name"],
               // "../../API_upload/".$ename);
               //  $upload_path= "../../API_upload/" .$ename;
				   
                    
                     //===========将数据存入数据库================== 
                     //现在改为将文件的内容存入数据库
                     //首先获取文件数据
              //       $file=$_FILES["file"];
	
        //设置超时限制时间,缺省时间为 30秒,设置为0时为不限时
        $time_limit=60;          
        set_time_limit($time_limit); //

        //把文件内容读到字符串中
        $fp=fopen($file['tmp_name'],  "rb");
        if(!$fp) die("file open error");
        $file_data = addslashes(fread($fp, filesize($file['tmp_name'])));
	                 //   $sql="Insert into npp_api(api_type,api_version,api_url,api_uptime) Values('$api_type','$version','$upload_path',' $ntime')";
	                 $sql="Insert into npp_api(api_type,api_version,api_url,api_uptime,file_type,file_name) Values('$api_type','$version','$file_data',' $ntime','".$file["type"]."','".$file["name"]."')";
	                       $res = $db->query($sql);
	                       fclose($fp);
                           unlink($file['tmp_name']); 
                           //这里要将api_type所对应的应用类型名称写入日志，而不是他的id
                           $sql_find_api_name="select type_name from npp_api_type_check where type_id='".$api_type."'";
                           $query_api_name=mysql_query($sql_find_api_name);
                           $result_api_name=mysql_fetch_array($query_api_name);
						   LogRecord($_SESSION ["userid"],"31","上传版本号为\'".$version."\'的\"".$result_api_name['type_name']."\"应用",$db);
						   	$db->close();
						     echo "<script language=\"JavaScript\">alert('上传文件成功!');";
			                 echo "window.location.href=\"api_upload.php\"</script>";
			    }
				//else{echo "<script language=\"JavaScript\">alert('文件已存在，请勿重复上传!');
		      //}
			  else{echo "<script language=\"JavaScript\">alert('文件格式不对!');";
			       echo "history.back(-1);</script>";
			      }
	      }
         }  
		 else{
		     echo "<script language=\"JavaScript\">alert('请选择上传文件!');";
			 echo "window.location.href=\"api_upload.php\"</script>";
		 }
      }	
	    else
		{
			echo "<script language=\"JavaScript\">alert('日期输入不合法!');";	
			echo "history.back(-1);</script>";
		}
  }
      else
     {
        echo "<script language=\"JavaScript\">alert('版本号输入非法!');";
		echo "history.back(-1);</script>";
     }
  }else
	 {
	    echo "<script language=\"JavaScript\">alert('请选择API类型!');";
		echo "history.back(-1);</script>";
	 }
  }         
   else { echo "<script language=\"JavaScript\">alert('请输入全部信息!');";
          echo "history.back(-1);</script>";
        } 
   		
}   
$smarty->display("app/api2_upload.htm");       
?>