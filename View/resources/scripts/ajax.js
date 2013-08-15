/**
* ajax无刷新二级联动下拉菜单
*
* @site www.micsea.com

*
*/
var js_element=document.createElement("script");
js_element.setAttribute("type","text/javascript");
js_element.setAttribute("src","jquery.js");
document.getElementsByTagName("head")[0].appendChild(js_element);



var http_request = false;
var tid = "";

function send_request(url, method) {//初始化、指定处理函数、发送请求的函数
	http_request = false;
	//开始初始化XMLHttpRequest对象
	if(window.XMLHttpRequest) { //Mozilla 浏览器
   		http_request = new XMLHttpRequest();
   		if (http_request.overrideMimeType) {//设置MiME类别
    		http_request.overrideMimeType('text/xml');
   		}
	}
	else if (window.ActiveXObject) { // IE浏览器
   		try {
    		http_request = new ActiveXObject("Msxml2.XMLHTTP");
   		} catch (e) {
    		try {
     			http_request = new ActiveXObject("Microsoft.XMLHTTP");
    		} catch (e) {}
   		}
	}
	if (!http_request) { // 异常，创建对象实例失败
   		window.alert("不能创建XMLHttpRequest对象实例.");
   		return false;
	}
	
	switch(method){
		case 1: http_request.onreadystatechange = processRequest1;break;//选择操作函数
		case 2: http_request.onreadystatechange = processRequest2;break;
		case 3: http_request.onreadystatechange = processRequest3;break;
	}
	// 确定发送请求的方式和URL以及是否同步执行下段代码
	alert(url);
	http_request.open("GET", url, true);
	http_request.send(null);
}

function processRequest1() {//操作函数1,插入支付类型
	if (http_request.readyState == 4) { // 判断对象状态
		if (http_request.status == 200) { // 信息已经成功返回，开始处理信息
			if(trim(http_request.responseText) == "trepeat") {
				document.getElementById("status").value = 0;
				alert("重复的支付类型名称！");
			}else {
				document.getElementById("status").value = 1;
				addOptionGroup(http_request.responseText, "payment_type");
				alert("添加成功！");
				hidDiv("imei");
				hidDiv("source");
			}
     	} else { //页面不正常
			alert("您所请求的页面有异常。");
		}
	}
}

function processRequest2() {//操作函数1,插入支付类型
	if (http_request.readyState == 4) { // 判断对象状态
		if (http_request.status == 200) { // 信息已经成功返回，开始处理信息
			if(trim(http_request.responseText) == "srepeat") {
				document.getElementById("status").value = 0;
				alert("重复的支付来源名称！");
			}
			else {
				document.getElementById("status").value = 1;
				addOptionGroup(http_request.responseText, "payment_source");
				alert("添加成功！");
				hidDiv("imei");
				hidDiv("source");
			}
     	} else { //页面不正常
			alert("您所请求的页面有异常。");
		}
	}
}

function processRequest3() {//操作函数1,插入支付类型
	if (http_request.readyState == 4) { // 判断对象状态
		if (http_request.status == 200) { // 信息已经成功返回，开始处理信息
			//document.getElementById(tid).innerHTML = http_request.responseText;
			$("#"+tid).html(http_request.responseText);
     	} else { //页面不正常
			alert("您所请求的页面有异常。");
		}
	}
}

function addtype(ptype) {
     send_request("select_payment.php?type="+ptype, 1);
}

function addsource(psource) {
     send_request("select_payment.php?source="+psource, 2);
}

function getValue(id ,curpage ,rate_type ) {
	tid = id;
	send_request("page_payment.php?curpage="+curpage+"&rate_type="+rate_type, 3);
}
