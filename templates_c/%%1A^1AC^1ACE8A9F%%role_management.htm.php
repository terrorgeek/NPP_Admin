<?php /* Smarty version 2.6.24, created on 
         compiled from system_management/role_management.htm */ ?>
<?php echo $this->_tpl_vars['header']; ?>

<script language="javascript">
window.onload=Auto;
function Auto()
{
	window.document.getElementById("menu7").style.display="block";
}

</script>
<script language="javascript">
window.onload=Auto;
function Auto()
{
	window.document.getElementById("menu7").style.display="block";
}
function del_confirm(ids)
{
	var r=confirm("确认删除？")
	if (r==true)
	{
		window.location.href="admin_dele.php?user_id="+ids;
	}
}
function pass_modify(idss)
{
	window.location.href="pass_modify.php?user_id="+idss;
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

			
			<div id="messages" style="display: none"> <!-- Messages are shown when a link with these attributes are clicked: href="#messages" rel="modal"  -->
				
				<h3>3 Messages</h3>
			 
				<p>
					<strong>17th May 2009</strong> by Admin<br />
					2011-05-26 00:00:00 sit amet, consectetur adipiscing elit. Vivamus magna. Cras in mi at felis aliquet congue.
					<small><a href="#" class="remove-link" title="Remove message">Remove</a></small>
				</p>
			 
				<p>
					<strong>2nd May 2009</strong> by Jane Doe<br />
					Ut a est eget ligula molestie gravida. Curabitur massa. Donec eleifend, libero at sagittis mollis, tellus est malesuada tellus, at luctus turpis elit sit amet quam. Vivamus pretium ornare est.
					<small><a href="#" class="remove-link" title="Remove message">Remove</a></small>
				</p>
			 
				<p>
					<strong>25th April 2009</strong> by Admin<br />
					2011-05-26 00:00:00 sit amet, consectetur adipiscing elit. Vivamus magna. Cras in mi at felis aliquet congue.
					<small><a href="#" class="remove-link" title="Remove message">Remove</a></small>
				</p>
				
				<form action="#" method="post">
					
					<h4>New Message</h4>
					
					<fieldset>
						<textarea class="textarea" name="textfield" cols="79" rows="5"></textarea>
					</fieldset>
					
					<fieldset>
					
						<select name="dropdown" class="small-input">
							<option value="option1">Send to...</option>
							<option value="option2">Everyone</option>
							<option value="option3">Admin</option>
							<option value="option4">Jane Doe</option>
						</select>
						
						<input class="button" type="submit" value="Send" />
						
					</fieldset>
					
				</form>
				
			</div> <!-- End #messages -->
			
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
					
					<h3>系统管理员列表</h3>
					
					
					
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
					
					<div class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->
						
						
						<table>
							
							<thead>
								<tr>							      
								   <th width="25%">级别</th>
								   <th width="25%">真实姓名</th>
								   <th width="25%">用户名</th>
								   <th width="25%" >操作内容</th>
								</tr>
								
							</thead>
						 						 
							<tbody>
							<?php echo $this->_tpl_vars['table_list']; ?>

							</tbody>
							
						</table>
						<br>
						<p>
							<input class="button" type="button" value="添加管理员" onclick="javascript:newadmin()" />
						</p>
					</div> <!-- End #tab1 -->					    
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->
	
			<!-- End Notifications -->
			
		<?php echo $this->_tpl_vars['footer']; ?>

			
		</div> <!-- End #main-content -->
		
	</div></body>
<script language="javascript">
function newadmin()
{
	window.location.href="add_admin.php";
}
</script>

<!-- Download From www.exet.tk-->
</html>