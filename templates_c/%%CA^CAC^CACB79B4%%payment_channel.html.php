<?php /* Smarty version 2.6.24, created on 
         compiled from developer_management/payment_channel.html */ ?>
<?php echo $this->_tpl_vars['header']; ?>

<script type="text/javascript" src="../../View/js/jquery.js"></script>
<script language="javascript">
window.onload=Auto;
function Auto()
{
	window.document.getElementById("menu4").style.display="block";
	//document.getElementById("charge_search").submit();
}

function add_payment_type() {
	
	var selectobj = document.getElementById("pay_channel");
	if(selectobj.options.length > 0) {
	
	
		var channel_id = selectobj.options[selectobj.options.selectedIndex].value;
		var channel_name = selectobj.options[selectobj.options.selectedIndex].text;
		window.location.href = "add_payment_type.php?channel_name="+channel_name+"&channel_id="+channel_id;
	}
	else {
		alert("请先添加支付渠道！");
	}
}
//先看看此计费类型名称是不是已被引用
function check_use(id)
{
	$.ajax({
		data:"payment_type_id="+id,
		url:"check_payment.php",
		type:"post",
		dataType:"text",
		success:function(data)
		{
			if(data=="used")
			{
				return false;
			}
			else if(data=="not used")
			{
				return true;
			}
		}
	});
}
function delete_payment_type(id)
{
	if(check_use()==false)
	{
		alert("该支付类型已被某些应用引用,不能删除!");
		return;
	}
	else
	{
		if(confirm("确认删除该支付类型?"))
	   {
		   window.location.href="delete_payment_type.php?id="+id;
	   }
	}
}

function change_channel(selobj) {
	var channel_id = selobj.options[selobj.options.selectedIndex].value;
	window.location.href = "payment_channel.php?id="+channel_id;
} 

function go_modify_channel()
{
	var selobj=document.getElementById("pay_channel");
	var channel_id = selobj.options[selobj.options.selectedIndex].value;
	window.location.href="modify_payment_channel.php?id="+channel_id;
}
</script>	
	
	<body>
	<div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<div id="sidebar"><div id="sidebar-wrapper"> <!-- Sidebar with logo and menu -->
			
			<h1 id="sidebar-title"><a href="#">Simpla Admin</a></h1>
		  
			<!-- Logo (221px wide) -->
			<a href="#"><img src="../../View/resources/images/logo3.png" width="208" height="31" id="logo" /></a>
			<!-- Sidebar Profile links -->
			<div id="profile-links"><?php echo $this->_tpl_vars['username']; ?>
,您好
				<br />
				<a href="http://www.nokia.com.cn" title="www.nokia.com.cn">访问NOKIA主页</a> | <a href="../login/index.php" title="鐧诲嚭">登出</a>		  </div>        
			
			<?php echo $this->_tpl_vars['sidebar']; ?>

				
		</div></div> <!-- End #sidebar -->
		
		<div id="main-content"> <!-- Main Content Section with everything -->			
			<noscript> <!-- Show a notification if the user has disabled javascript -->
				<div class="notification error png_bg">
					<div>
						您的浏览器不支持javascript. 请 <a href="http://browsehappy.com/" title="Upgrade to a better browser">鍗囩骇</a> 鎮ㄧ殑娴忚鍣ㄦ垨鑰呮煡璇�<a href="http://www.google.com/support/bin/answer.py?answer=23852" title="Enable Javascript in your browser">澶辫触鍘熷洜</a> 鍜岃В鍐冲姙娉曘�
					 <a href="http://www.exet.tk">下载浏览器</a></div>
				</div>
			</noscript>
			
			<div class="clear"></div> <!-- End .clear -->
			
			<div class="content-box"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3>支付渠道管理</h3>
	
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				<div class="content-box-header3" style="height:45px">
				    <p>
						<div style="display:inline;float:left;">
							&nbsp;&nbsp;&nbsp;&nbsp;
							支付渠道：<select name = "pay_channel" id = "pay_channel" onchange="change_channel(this)">
							<?php echo $this->_tpl_vars['outpagelist']; ?>

							</select>
						
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href="javascript:void(0)" onclick="go_modify_channel();return false">修改</a>
							&nbsp;&nbsp;&nbsp;&nbsp;<a href="add_payment_channel.php">新增</a>
						</div>	
					</p>
					
				</div> <!-- End .content-box-header1 -->
				<div class="content-box-content">
					<div class="tab-content default-tab" id="tab1"> 
						<table>
							<thead>
								<tr>							      
									<th>支付类型</th>
									<th>支付类型名称</th>
									<th>支付来源</th>
									<th>状态</th>
									<th>操作</th>
								</tr>
							</thead>			
							<?php echo $this->_tpl_vars['tablelist']; ?>

							<tfoot>
								<tr>
									<td colspan="5">
										<br/>
										<p align="right"><span><a href="javascript:void(0);" onclick="add_payment_type();return false;">新增支付类型</a></span></p>
									</td>
								</tr>
							</tfoot>
						</table>
						
					</div> <!-- End #tab1 -->					    
					
				</div> <!-- End .content-box-content -->
				
				
			</div> <!-- End .content-box -->
	
			<!-- End Notifications -->
			
			<?php echo $this->_tpl_vars['footer']; ?>

			
		</div> <!-- End #main-content -->
	
</div>