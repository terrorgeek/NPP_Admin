<?php /* Smarty version 2.6.24, created on 
         compiled from login/index.htm */ ?>
﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="”http://www.w3.org/1999/xhtml”">
<head>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<title>NOKIA支付管理平台</title>
	
	<!--                       CSS                       -->
  
	<!-- Reset Stylesheet -->
	<link rel="stylesheet" href="../../View/resources/css/reset.css" type="text/css" media="screen" />
  
	<!-- Main Stylesheet -->
	<link rel="stylesheet" href="../../View/resources/css/style.css" type="text/css" media="screen" />
	
	<!-- Invalid Stylesheet. This makes stuff look pretty. Remove it if you want the CSS completely valid -->
	<link rel="stylesheet" href="../../View/resources/css/invalid.css" type="text/css" media="screen" />	
	
	<!-- Colour Schemes
  
	Default colour scheme is green. Uncomment prefered stylesheet to use it.
	
	<link rel="stylesheet" href="resources/css/blue.css" type="text/css" media="screen" />
	
	<link rel="stylesheet" href="resources/css/red.css" type="text/css" media="screen" />  
 
	-->
	
	<!-- Internet Explorer Fixes Stylesheet -->
	
	<!--[if lte IE 7]>
		<link rel="stylesheet" href="resources/css/ie.css" type="text/css" media="screen" />
	<![endif]-->
	
	<!--                       Javascripts                       -->
  
	<!-- jQuery -->
	<script type="text/javascript" src="../../View/resources/scripts/jquery-1.3.2.min.js"></script>
	
	<!-- jQuery Configuration -->
	<script type="text/javascript" src="../../View/resources/scripts/simpla.jquery.configuration.js"></script>
	
	<!-- Facebox jQuery Plugin -->
	<script type="text/javascript" src="../../View/resources/scripts/facebox.js"></script>
	
	<!-- jQuery WYSIWYG Plugin -->
	<script type="text/javascript" src="../../View/resources/scripts/jquery.wysiwyg.js"></script>
	
	<!-- Internet Explorer .png-fix -->
	
	<!--[if IE 6]>
		<script type="text/javascript" src="resources/scripts/DD_belatedPNG_0.0.7a.js"></script>
		<script type="text/javascript">
			DD_belatedPNG.fix('.png_bg, img, li');
		</script>
	<![endif]-->
	
</head>
<script type="text/javascript">
	function reloadImg(){
		var verify = document.getElementById('validateCode');
			verify.src = "validateCode.php?rand=" + Math.random();
	}
	function loginOn(){//登录
		var obj = document.getElementById("login-on");
			obj.action='Action.login.php';
			obj.target="_top"; 
			obj.method='POST';
			obj.submit();
	}
	function reset(){//表单重置
		var obj = document.getElementById("login-on");
			obj.reset();
	}
	function Keysubmit(){ 
		if (event.keyCode == 13){
			loginOn();
		} 
	} 
</script>
	<body id="login">
		
		<div id="login-wrapper" class="png_bg">
		  <div id="login-top">
			
				<h1>NOKIA支付平台管理系统</h1>
			  <!-- Logo (221px width) -->
		      <img src="../../View/resources/images/logo3.png" alt="Simpla Admin logo" height="66" id="logo" /></div> 
			<!-- End #logn-top -->
			
			<div id="login-content">
				
				<form  id="login-on" onkeydown="javascript:Keysubmit()">
				
					<div class="notification information png_bg">
						<div style="display:none">
							密码错误，请重新输入。
						</div>
					</div>
					
				   
					<p>
						<label>用户名：</label>
						<input  type="text" name="name"  class="text-input" />
					</p>
					<div class="clear"></div>
					<p>
						<label>密 码：</label>
						<input class="text-input" type="password"  name="password" />
					</p>
					<div class="clear"></div>
					<p>
						<label>验证码：</label>
						<input class="text-input" type="text" name="identify" id="identify"  style="width:100px;" />&nbsp;&nbsp;&nbsp;
						<img id="validateCode" src="validateCode.php" onclick="javascript:reloadImg()" height="28" width="88" title="看不清？换一张"/>
					</p>
					
					<div class="clear"></div>
	
					<div class="clear"></div>
					<p>
					<table>
					   <tr>
					   <td width="128">&nbsp;</td>
					   <td width="76"> <input name="button" type="button" class="button" style="float:right; width:68px;" onclick="javascript:loginOn()" value="登 录" /></td>
					   <td width="76"><input class="button" type="button" style="float:right; width:68px;" onclick="javascript:reset()" value="重 置" />					    </td>
					   </tr>
					   </table>
					</p>				
					
				</form>
			</div> <!-- End #login-content -->
			
		</div> <!-- End #login-wrapper -->
		
  </body>
</html>