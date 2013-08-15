function formsub_prev()
{
	document.getElementById('currentpage').value=({#$currentpage#}-1);
	document.getElementById('sumpage').value={#$sumpage#};
	document.getElementById("charge_search").submit();
}
function formsub_next()
{
	document.getElementById('currentpage').value=({#$currentpage#}+1);
	document.getElementById('sumpage').value={#$sumpage#};
	document.getElementById("charge_search").submit();
}
function formsub_head()
{
	document.getElementById('currentpage').value=1;
	document.getElementById('sumpage').value={#$sumpage#};
	document.getElementById("charge_search").submit();
}
function formsub_last()
{
	document.getElementById('currentpage').value={#$sumpage#};
	document.getElementById('sumpage').value={#$sumpage#};
	document.getElementById("charge_search").submit();
}
function formsub_jump()
{
	var pagesjump = document.getElementById('pagejump').value;
	if(pagesjump<1||pagesjump>{#$sumpage#})
	{
		alert("跳转请求超出页面范围，请重新输入跳转页面");
	}
	else
	{
		document.getElementById('currentpage').value=pagesjump;
		document.getElementById('sumpage').value={#$sumpage#};
		document.getElementById("charge_search").submit();
	}
}