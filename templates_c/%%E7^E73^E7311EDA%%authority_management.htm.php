<?php /* Smarty version 2.6.24, created on 
         compiled from system_management/authority_management.htm */ ?>
<?php echo $this->_tpl_vars['header']; ?>

<script language="javascript">
window.onload=Auto;
function Auto()
{
	window.document.getElementById("menu7").style.display="block";
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
				
				<div class="content-box-header">
					
					<h3>权限管理</h3>
	
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
					
					<div class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->
			
						<table>
							
							<thead>
								<tr>							      
								   <th width="25%">级别</th>
								   <th width="25%">姓名</th>
								   <th width="25%">登录名</th>
								   <th width="25%" >操作</th>
								</tr>
								
							</thead>
						 
							<tfoot>
								<tr>
									<td colspan="5">	<!-- End .pagination -->
										<div class="clear"></div>
								  </td>
								</tr>
							</tfoot>
						 
							<tbody>
							<?php echo $this->_tpl_vars['page_put']; ?>

							</tbody>
							
						</table>
						
					</div> <!-- End #tab1 -->					    
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->
	
			<!-- End Notifications -->
			
			<?php echo $this->_tpl_vars['footer']; ?>

			
		</div> <!-- End #main-content -->
		
	</div>

<script src="../../View/js/authority_management.js" type="text/javascript"></script>
</body>
</html>