<?php /* Smarty version 2.6.24, created on 
         compiled from settlement/settlement_total.html */ ?>
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
function formsub_prev()
{
	document.getElementById('currentpage').value=(<?php echo $this->_tpl_vars['currentpage']; ?>
-1);
	document.getElementById('sumpage').value=<?php echo $this->_tpl_vars['sumpage']; ?>
;
	document.getElementById("settlement_search1").submit();
}
function formsub_next()
{
	document.getElementById('currentpage').value=(<?php echo $this->_tpl_vars['currentpage']; ?>
+1);
	document.getElementById('sumpage').value=<?php echo $this->_tpl_vars['sumpage']; ?>
;
//	document.getElementById('time').value=<?php echo $this->_tpl_vars['time']; ?>
;
	document.getElementById("settlement_search1").submit();
}
function formsub_head()
{
	document.getElementById('currentpage').value=1;
	document.getElementById('sumpage').value=<?php echo $this->_tpl_vars['sumpage']; ?>
;
	document.getElementById("settlement_search1").submit();
}
function formsub_last()
{
	document.getElementById('currentpage').value=<?php echo $this->_tpl_vars['sumpage']; ?>
;
	document.getElementById('sumpage').value=<?php echo $this->_tpl_vars['sumpage']; ?>
;
	document.getElementById("settlement_search1").submit();
}
function formsub_jump()
{
	var re=/^[0-9]*$/;
	var pagesjump = document.getElementById('pagejump').value;	
	if(pagesjump!=null && pagesjump.length>0)
	{
		if(re.test(pagesjump))
		{
			if(pagesjump<1||pagesjump><?php echo $this->_tpl_vars['sumpage']; ?>
)
			{
				alert("跳转请求超出页面范围，请重新输入跳转页面");
			}
			else
			{
				document.getElementById('currentpage').value=pagesjump;
				document.getElementById('sumpage').value=<?php echo $this->_tpl_vars['sumpage']; ?>
;
				document.getElementById("settlement_search1").submit();
			}
		}
		else
		{
			alert("您的输入不合法!");
		}
	}
	else
	{
		alert("请输入跳转页!");
	}
}
</script>
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
		var li_3 = document.getElementById("li_3");
		showTab(li_3, li_3.className);
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
function submit_form()
{
	document.getElementById("app").submit();
}

function change_order_type(selobj)
{
	var order_type_id=selobj.options[selobj.options.selectedIndex].value;
	window.location.href = "settlement_total.php?order_type="+order_type_id;
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
							<li id="li_2" class="li_2"><h3><a href="settlement_month_report.php">结算月报查询</a></h3></li>
							<li id="li_3" class="li_3"><h3>结算总明细表查询</h3></li>
						</ul>
						<div class="tab_content" id="tab_content">						
						</div>
					</div>
				<div class="content-box-header3" id = "search_bars">
					<br>
					<form name = "settlement_search1" id = "settlement_search1" action="settlement_total.php" method="post" >					
					<p>
						    &nbsp;&nbsp;&nbsp;&nbsp;
						    <input type="checkbox" name="checkbox_charge_time" id="checkbox_charge_time" checked="checked" disabled="true" />
					 	          <b>时间</b><input type="text" name="charge_time" id="charge_time" value="<?php echo $this->_tpl_vars['charge_time']; ?>
"/>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="checkbox" name="checkbox_cp_name_or_id" id="checkbox_cp_name_or_id" <?php echo $this->_tpl_vars['checkbox_cp_name_or_id']; ?>
 />
							      <b>开发者名称/ID:</b><input type="text" name="cp_name_or_id" id="cp_name_or_id" value="<?php echo $this->_tpl_vars['cp_name_or_id']; ?>
" />
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="checkbox" name="checkbox_order_type" id="checkbox_order_type" checked="checked" disabled="true" />
							      <b>报表类型</b>
							      <select id="order_type" name="order_type" onchange="change_order_type(this)">
							      	<!-- <option value="1">当期应结账单</option>
							      	<option value="2">待退款订单</option>
							      	<option value="3">延迟坏账订单</option> -->
							      	<?php echo $this->_tpl_vars['order_type_pageout']; ?>

							      </select>
							<input type="hidden" name="submit_test1" value="yes" />
							<input type="hidden" name="currentpage" id="currentpage" value="1" />
							<input type="hidden" name="sumpage" id="sumpage" value="" />
							<input type="hidden" name="div1" id="div1" value=0 />
							<span><input type="submit" class="button" value="查询" onclick="submit_form()" /></span>	
							<span><input type="submit" class="button" value="下载" /></span>	
					</p>
				</form>
					<div class="clear"></div>
					
				</div> 
				<!-- End .content-box-header1 -->
				<div class="content-box-content" id = "search_details">
					
					 <div class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->						
					<b>总计:<?php echo $this->_tpl_vars['sum']; ?>
(单位:元)</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<table width="100%" style="text-align: center;">					
							<thead style="font-size: 16px;">
								<tr>							      
									<th width="86"><div align="center">开发者名称</div></th>
									<th width="86"><div align="center">订单号</div></th>
									<th width="89"><div align="center">内容名称</div></th>
									<th width="88"><div align="center">订单金额</div></th>
									 <th width="80"><div align="center">支付渠道</div></th><br/>
									 <th width="80"><div align="center">支付类型</div></th>
									 <th width="111"><div align="center">支付来源</div></th>
									 <th colspan="80" align="center"><div align="center">订单状态(Sina)</div></th>
									 <th colspan="86" align="center"><div align="center">订单状态(NPP)</div></th>
									 <th colspan="80" align="center"><div align="center">时间</div></th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<td colspan="11">
										<div class="pagination" >
											<span>共<?php echo $this->_tpl_vars['out_apply_sum']; ?>
条记录&nbsp;&nbsp;&nbsp;&nbsp;</span>
											<?php if ($this->_tpl_vars['currentpage'] == 1): ?>
											<span>首页</span>
											<?php endif; ?>
											<?php if ($this->_tpl_vars['currentpage'] > 1): ?>
											<span><a href="javascript:formsub_prev();">上一页</a></span>
											<?php endif; ?>
											<a>/</a>
											<?php if ($this->_tpl_vars['currentpage'] == $this->_tpl_vars['sumpage']): ?>
											<span>末页</span>
											<?php endif; ?>
											<?php if ($this->_tpl_vars['currentpage'] >= 1): ?>
												<?php if ($this->_tpl_vars['currentpage'] < $this->_tpl_vars['sumpage']): ?>
												<span><a href="javascript:formsub_next();">下一页</a></span>
												<?php endif; ?>
											<?php endif; ?>
											<span>转到第<input name = "pagejump" id = "pagejump" style="width:40px;">页</span>
											<span><input type="button" value="跳转" class=="button" onClick="javascript:formsub_jump();"></span>
											<?php if ($this->_tpl_vars['sumpage'] == 0): ?>
											<span>0/<?php echo $this->_tpl_vars['sumpage']; ?>
页</span>
											<?php else: ?>
											<span><?php echo $this->_tpl_vars['currentpage']; ?>
/<?php echo $this->_tpl_vars['sumpage']; ?>
页</span>
											<?php endif; ?>
										</div>
									<div class="clear"></div>
									</td>
								</tr>
							</tfoot>
							<tbody style="font-size: 14px;">
                                <?php echo $this->_tpl_vars['outpagelist']; ?>

							</tbody>
						</table>
			
						
					</div> <!-- End #tab1 -->					    
					
				</div> <!-- End .content-box-content -->
				
				
			</div> <!-- End .content-box -->
	
			<!-- End Notifications -->
			
			<?php echo $this->_tpl_vars['footer']; ?>

			
		</div> <!-- End #main-content -->
		
	</div>