<?php /* Smarty version 2.6.24, created on 
         compiled from log/admin_log.htm */ ?>
<?php echo $this->_tpl_vars['header']; ?>

<?php echo $this->_tpl_vars['son_select_info']; ?>

<link rel="stylesheet" href="../../View/resources/css/calendar.css" />
<!-- 
<script type="text/javascript" src="../../View/resources/scirpts/jquery-1.3.2.min.js"></script> -->
<script type="text/javascript" src="../../View/resources/scripts/calendar.js" ></script>  
<script type="text/javascript" src="../../View/resources/scripts/calendar-zh.js" ></script>
<script type="text/javascript" src="../../View/resources/scripts/calendar-setup.js"></script>
<script language="javascript">
function confirm()
{
	document.getElementById("charge_search").submit();
}	
</script>
<script language="javascript">
window.onload=Auto;
function Auto()
{
	window.document.getElementById("menu7").style.display="block";
}
</script>
<script language="javascript">
		//var groupName = new Array();
		function SelectGroup(obj)
		{
			var index = obj.options[obj.selectedIndex].value;
			if(index == '全部')
			{
				document.getElementById("mode_detail").options.length = 0;
				document.getElementById("mode_detail").options.add(new Option("不限", "不限"));
			}

			else
			{
				document.getElementById("mode_detail").options.length = 0;
				document.getElementById("mode_detail").options.add(new Option("不限", "不限"));
				for(i = 0; i < groupName[index].length; i++)
				{
					document.getElementById("mode_detail").options.add(new Option(groupName[index][i], groupName[index][i]));
				}
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
			
			<div class="content-box" ><!-- Start Content Box -->
				
				<div class="content-box-header"  >
					
					<h3>操作日志查询</h3>
	
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-header3" style="height:95px">
				<br />
				<form name = "charge_search" id = "charge_search" action="admin_log.php" method="post" >
					<p>
						<input type="checkbox" name="date_check" <?php echo $this->_tpl_vars['date_check_pageout']; ?>
 >
						<b>时间</b>
						<input name="stime" readonly="true" type="text" id="stime" size="10" value="<?php echo $this->_tpl_vars['stime_pageout']; ?>
" maxlength="10" onclick="return showCalendar('stime', 'y-mm-dd');"/>	
						<b>到</b>
						<input name="etime" readonly="true" type="text" id="etime"  size="10" value="<?php echo $this->_tpl_vars['etime_pageout']; ?>
" maxlength="10" onclick="return showCalendar('etime', 'y-mm-dd');"/>						
						<?php if ($this->_tpl_vars['admin_level'] == 1): ?>
						<input type="checkbox" name="name_check" <?php echo $this->_tpl_vars['name_check_pageout']; ?>
>
						<?php else: ?>
						<input type="checkbox" name="name_check" checked hidden="true" >
						<input type="checkbox" name="name_check_view" checked disabled >
						<?php endif; ?>
						<b>登录名</b>
						<select name = "user_name" id = "user_name">
						<?php echo $this->_tpl_vars['user_name_pageout']; ?>

						</select>
					
					</p>
					<p>
						<input type="checkbox" name="mode_status_check" <?php echo $this->_tpl_vars['mode_status_check_pageout']; ?>
>
							<b>操作模块</b>
							<select name = "mode_status" id = "mode_status" onchange=SelectGroup(this) >
								<option value="全部">全部</option>
								<?php echo $this->_tpl_vars['mode_status_pageout']; ?>

							</select>
							<select name = "mode_detail" id = "mode_detail">	
								<option value="不限">不限</option>
								<?php echo $this->_tpl_vars['mode_detail_outpage']; ?>
	
							</select>
							<input type="hidden" name="submit_test" value="yes" />
							<input type="hidden" name="currentpage" id="currentpage" value="1" />
							<input type="hidden" name="sumpage" id="sumpage" value="1" />
							<span><input type="button" class="button" onClick="javascript:confirm();" value="查询" /></span>	
					</p>
					
				</form>
				
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header1 -->
				<div class="content-box-content">
					
					<div class="tab-content default-tab" id="tab1" > <!-- This is the target div. id must match the href of this div's tab -->						
						<?php if ($this->_tpl_vars['outpagelist'] != null): ?>
						<table>					
							<thead>
								<tr>							      
									<th width="25%"><div align="center">时间</div></th>
									<th width="17%"><div align="center">管理员级别</div></th>
									<th width="11%"><div align="center">登录名</div></th>
									<th width="20%"><div align="center">操作模块</div></th>
									<th width="27%"><div align="center">操作记录</div></th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<td colspan="11">
										<div class="pagination" >
											<span>共<?php echo $this->_tpl_vars['out_search_sum']; ?>
条记录</span>
											<?php if ($this->_tpl_vars['currentpage'] > 1): ?>
											<span><a href="javascript:formsub_head();">首页</a></span>
											<?php endif; ?>
											<?php if ($this->_tpl_vars['currentpage'] > 1): ?>
											<span><a href="javascript:formsub_prev();">上一页</a></span>
											<?php endif; ?>
											<a>/</a>
											
											<?php if ($this->_tpl_vars['currentpage'] >= 1): ?>
												<?php if ($this->_tpl_vars['currentpage'] < $this->_tpl_vars['sumpage']): ?>
												<span><a href="javascript:formsub_next();">下一页</a></span>
												<?php endif; ?>
											<?php endif; ?>
											<?php if ($this->_tpl_vars['currentpage'] < $this->_tpl_vars['sumpage']): ?>
											<span><a href="javascript:formsub_last();">末页</a></span>
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
							<tbody>
						<?php endif; ?>	
						<?php echo $this->_tpl_vars['outpagelist']; ?>
		
						<?php if ($this->_tpl_vars['outpagelist'] != null): ?>
							</tbody>
						</table>
						<?php endif; ?>
						
					</div> <!-- End #tab1 -->	
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->
	
			<!-- End Notifications -->
			
			<?php echo $this->_tpl_vars['footer']; ?>

			
		</div> <!-- End #main-content -->
<script language="javascript">
function formsub_prev()
{
	document.getElementById('currentpage').value=(<?php echo $this->_tpl_vars['currentpage']; ?>
-1);
	document.getElementById('sumpage').value=<?php echo $this->_tpl_vars['sumpage']; ?>
;
	document.getElementById("charge_search").submit();
}
function formsub_next()
{
	document.getElementById('currentpage').value=(<?php echo $this->_tpl_vars['currentpage']; ?>
+1);
	document.getElementById('sumpage').value=<?php echo $this->_tpl_vars['sumpage']; ?>
;
	document.getElementById("charge_search").submit();
}
function formsub_head()
{
	document.getElementById('currentpage').value=1;
	document.getElementById('sumpage').value=<?php echo $this->_tpl_vars['sumpage']; ?>
;
	document.getElementById("charge_search").submit();
}
function formsub_last()
{
	document.getElementById('currentpage').value=<?php echo $this->_tpl_vars['sumpage']; ?>
;
	document.getElementById('sumpage').value=<?php echo $this->_tpl_vars['sumpage']; ?>
;
	document.getElementById("charge_search").submit();
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
				document.getElementById("charge_search").submit();
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

</div>
</body>
</html>