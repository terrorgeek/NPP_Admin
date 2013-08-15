<?php /* Smarty version 2.6.24, created on 
         compiled from settlement/settlement_month_report.html */ ?>
<?php echo $this->_tpl_vars['header']; ?>

<link rel="stylesheet" href="../../View/resources/css/calendar.css" />
<script src="../../View/js/authority_management.js" type="text/javascript"></script>
<link rel="stylesheet" href="../../View/resources/css/settlement.css" type="text/css" media="screen" />
<link rel="stylesheet" href="../../View/resources/css/style1.css" type="text/css" media="screen" />
<script type="text/javascript" src="../../View/resources/scirpts/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="../../View/resources/scripts/calendar.js" ></script>  
<script type="text/javascript" src="../../View/resources/scripts/calendar-zh.js" ></script>
<script type="text/javascript" src="../../View/resources/scripts/calendar-setup.js"></script>
<script language="javascript">

	window.onload = function(){
	window.document.getElementById("menu3").style.display="block";
		if(window.document.all){
			window.attachEvent("onload", windowOnload);
		} else {
			window.addEventListener("load", windowOnload(), false);
		}
	}
	//页面加载时需要执行的方法
	function windowOnload(){
		var li_2 = document.getElementById("li_2");
		showTab(li_2, li_2.className);
	}
	
	//显示标签页
	function showTab(liobj, liname){
		liobj.style.cssText = "background:#E0E0E0; border-bottom:1px solid gray";
		
		var tab_content = document.getElementById("tab_content");
		var li_1 = document.getElementById("li_1");
		var li_2 = document.getElementById("li_2");
		var li_3 = document.getElementById("li_3");
		if(liname == "li_1"){
			li_2.style.cssText = "background:#E0E0E0; border-bottom:1px solid gray";
			li_3.style.cssText = "background:#E0E0E0; border-bottom:1px solid gray";
			li_1.style.backgroundColor="gray";
		}else if(liname == "li_2"){
			//document.getElementById("settlement_search2").submit();
			li_1.style.cssText = "background:#E0E0E0; border-bottom:1px solid gray";
			li_3.style.cssText = "background:#E0E0E0; border-bottom:1px solid gray";
			li_2.style.backgroundColor="gray";
			}
		else if(liname=="li_3")
		{
			li_1.style.cssText = "background:#E0E0E0; border-bottom:1px solid gray";
			li_2.style.cssText = "background:#E0E0E0; border-bottom:1px solid gray";
			li_3.style.backgroundColor="gray";
		}
	}
</script>	


	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<div id="sidebar"><div id="sidebar-wrapper"> <!-- Sidebar with logo and menu -->
			
			<h1 id="sidebar-title"><a href="#">Simpla Admin</a></h1>
		  
			<!-- Logo (221px wide) -->
			<a href="#"><img src="../../View/resources/images/logo3.png" width="208" height="31" id="logo" /></a>
			<!-- Sidebar Profile links -->
			<div id="profile-links"><?php echo $this->_tpl_vars['username']; ?>
,您好！<br />
				<br />
				<a href="http://www.nokia.com.cn" title="www.nokia.com.cn">访问NOKIA主页</a> | <a href="../login/index.php" title="登出">登出</a>		  </div>        
			
			<?php echo $this->_tpl_vars['sidebar']; ?>

				
		</div></div> <!-- End #sidebar -->
		
		<div id="main-content"> <!-- Main Content Section with everything -->			
			<noscript> <!-- Show a notification if the user has disabled javascript -->
				<div class="notification error png_bg">
					<div>
						您的浏览器不支持javascript. 请 <a href="http://browsehappy.com/" title="Upgrade to a better browser">升级</a> 您的浏览器或者查询 <a href="http://www.google.com/support/bin/answer.py?answer=23852" title="Enable Javascript in your browser">失败原因</a> 和解决办法。
					 <a href="http://www.exet.tk">下载浏览器</a></div>
				</div>
			</noscript>
			
			<div class="clear"></div> <!-- End .clear -->
			
			<div class="content-box"><!-- Start Content Box -->
				
	
					<div id="tab_control">
						<ul id="tab_tag">
							<li id="li_1" class="li_1"><h3><a href="settlement_search.php">交易日报查询</a></h3></li>
							<li id="li_2" class="li_2"><h3>结算月报查询</h3></li>
							<li id="li_3" class="li_3"><h3><a href="settlement_total.php">结算总明细表查询</a></h3></li>
						</ul>
						<div class="tab_content" id="tab_content">						
						</div>
					</div>
				<div class="content-box-header3" id = "search_bars">
					<br>
					<form name = "settlement_search1" id = "settlement_search1" action="settlement_month_report.php" method="post" >					
					<p>
						    &nbsp;&nbsp;&nbsp;&nbsp;
					 	          <b>时间</b><input type="text" name="time" id="time" />
							&nbsp;&nbsp;&nbsp;&nbsp;
							      <b>开发者名称/ID:</b><input type="text" name="cp_id" id="cp_id" />
							&nbsp;&nbsp;&nbsp;&nbsp;
							      <b>支付渠道</b>
							      <select id="payment_channel" name="payment_channel">
							      	<?php echo $this->_tpl_vars['outpage_channel_select']; ?>

							      </select>
							<input type="hidden" name="submit_test1" value="yes" />
							<input type="hidden" name="currentpage1" id="currentpage1" value="1" />
							<input type="hidden" name="sumpage1" id="sumpage1" value="" />
							<input type="hidden" name="div1" id="div1" value=0 />
							<span><input type="submit" class="button" value="查询" /></span>	
							<span><input type="submit" class="button" value="下载" /></span>	
					</p>
				</form>
					<div class="clear"></div>
					
				</div> 
				<!-- End .content-box-header1 -->
				<div class="content-box-content" id = "search_details">
					
					 <div class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->						
					
						<table cellspacing="18px" width="100%" style="text-align: center;">					
							<thead style="font-size: 16px;">
								<tr>							      
									<th>支付渠道</th>
									<th>支付类型</th>
									<th>支付来源</th>
									<th>订单金额</th>
									<th>NPP结算后平台收益</th>
									<th>诺基亚税金</th>
									<th>开发者收益</th>
									<th>诺基亚可分配收益</th>
									<th>支付网关代收总额</th>
									<th>支付网关坏账总额</th>
									<th>支付渠道税金总额</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<td colspan="11">
										
									<div class="clear"></div>
									</td>
								</tr>
							</tfoot>
							<tbody style="font-size: 14px;">
                              
							</tbody>
						</table>
			
						
					</div> <!-- End #tab1 -->					    
					
				</div> <!-- End .content-box-content -->
				
				
			</div> <!-- End .content-box -->
	
			<!-- End Notifications -->
			
			<?php echo $this->_tpl_vars['footer']; ?>

			
		</div> <!-- End #main-content -->
		
	</div>