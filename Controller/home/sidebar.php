<?php
	$sidebar = "";
	$sidebar .= "<ul id=\"main-nav\">	";
				$sidebar .= "<li>";
					$sidebar .= "<a href=\"#\"  class=\"nav-top-item\"><strong>";
						$sidebar .= "内容管理					</strong></a>";
					$sidebar .= "<ul id=\"menu1\">";
						$sidebar .= "<li><a href=\"../app/app_audit.php\">新内容审核</a></li>";
						$sidebar .= "<li><a href=\"../app/app_select_edit.php\">内容查询/修改</a></li>";
						$sidebar .= "<li><a href=\"../imei/imei_upload.php\">IMEI导入</a></li>";
						$sidebar .= "<li><a href=\"../app/api_upload.php\">API上传</a></li>";
					$sidebar .= "</ul>";
			  $sidebar .= "</li>	";		
/* 				$sidebar .= "<li>";
					$sidebar .= "<a href=\"#\" class=\"nav-top-item\"><strong>";
						$sidebar .= "价格管理					</strong></a>";
					$sidebar .= "<ul id=\"menu2\">";
						$sidebar .= "<li><a href=\"#\">aaaa</a></li>";
						$sidebar .= "<li><a href=\"#\">bbbb</a></li>";
						$sidebar .= "<li><a href=\"#\">cccc</a></li>";
						$sidebar .= "<li><a href=\"#\">dddd</a></li>";
					$sidebar .= "</ul>";
			 $sidebar .= " </li>"; */
			 
 				$sidebar .= "<li>";
					$sidebar .= "<a href=\"#\" class=\"nav-top-item\"><strong>";
						$sidebar .= "结算管理					</strong></a>";
					$sidebar .= "<ul id=\"menu3\">";
					    $sidebar .= "<li><a href=\"../settlement/settlement_ensure.php\">对账确认</a></li>";
						$sidebar .= "<li><a href=\"../settlement/settlement_option.php\">结算设置</a></li>";
						$sidebar .= "<li><a href=\"../settlement/settlement_search.php\">结算查询</a></li>";					
					$sidebar .= "</ul>";
			 $sidebar .= " </li>"; 
			$sidebar .= "<li>";
					$sidebar .= "<a href=\"#\" class=\"nav-top-item\"><strong>";
						$sidebar .= "合作方管理					</strong></a>";
					$sidebar .= "<ul id=\"menu4\">";
						$sidebar .= "<li><a href=\"../developer_management/payment_channel.php\">支付渠道管理</a></li>";
						$sidebar .= "<li><a href=\"../developer_management/developer_check.php\">开发者管理</a></li>";			
					$sidebar .= "</ul>";
			 $sidebar .= " </li>"; 
			 
			 
			 
			 
				$sidebar .= "<li>";
					$sidebar .= "<a href=\"#\" class=\"nav-top-item\"><strong>";
						$sidebar .= "统计分析					</strong></a>";
					$sidebar .= "<ul id=\"menu5\">";
						$sidebar .= "<li><a href=\"../charge/charge_select.php\">订单查询</a></li>";
					$sidebar .= "</ul>";
			 $sidebar .= " </li>";
				$sidebar .= "<li>";
					$sidebar .= "<a href=\"#\" class=\"nav-top-item\"><strong>";
						$sidebar .= "用户管理</strong></a>";
					$sidebar .= "<ul id=\"menu6\">";
						$sidebar .= "<li><a href=\"../blacklist/blacklist_upload.php\">黑名单管理</a></li>";
					$sidebar .= "</ul>";
			 $sidebar .= " </li>";	
				$sidebar .= "<li>";
					$sidebar .= "<a href=\"#\" class=\"nav-top-item\"><strong>";
						$sidebar .= "系统管理</strong></a>";
					$sidebar .= "<ul id=\"menu7\">";
						$sidebar .= "<li><a href=\"../system_management/role_management.php\">管理员操作</a></li>";
						$sidebar .= "<li><a href=\"../system_management/authority_management.php\">权限管理</a></li>";
						$sidebar .= "<li><a href=\"../log/admin_log.php\">操作日志查询</a></li>";
					$sidebar .= "</ul>";
			 $sidebar .= " </li>";
				$sidebar .= "<li>";
					$sidebar .= "<a href=\"../login/index.php\" class=\"nav-top-item no-submenu\">";
					$sidebar .= "<strong>退出登录</strong> </a></li>";	      	
			$sidebar .= "</ul>	";	
?>	
	