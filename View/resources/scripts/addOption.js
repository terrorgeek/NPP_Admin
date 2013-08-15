
function addOption(objSelectNow,txt,val)
{
	var objOption = document.createElement("OPTION");
	objOption.text= trim(txt);
	objOption.value=val;
	objSelectNow.options.add(objOption);
}

function checkOption(objSelectNow,txt,val) {
	var objOption = document.createElement("OPTION");
	objOption.text= trim(txt);
	objOption.value=val;
	if(trim(txt) == t_name || trim(txt) == s_name) objOption.selected = true;
	objSelectNow.options.add(objOption);
}

function trim(str){
	return str.replace(/(^\s*)|(\s*$)/g, "");
}

function addOptionGroup(optGroupString ,kind)
{
	//这里需要将optGroupString重新处理一下, optGroupString是形如songyu,zhangyuan,zhaopeng,lele,@3,4,5,2,5,6,的字符串
	//先取得@字符的位置
	var pos=optGroupString.indexOf("@");
	//optGroupString_name为含有name的字串
	var optGroupString_name=optGroupString.substring(0,pos-1);
	//optGroupString_id为含有id的字串
	var optGroupString_id=optGroupString.substring(pos+1,optGroupString.length-1);
	
	var objSelect = document.getElementsByTagName("SELECT");
	//optGroup仅为name的数祖
	var optGroup = optGroupString_name.split(",");
	//optGroup_id为id的数祖
    var optGroup_id=optGroupString_id.split(",");
    
	var objtypeNow = objSelect[kind];
	objtypeNow.length = 1;
	for(i = 0; i<optGroup.length-1; i++)
	{
	//	addOption(objtypeNow, optGroup[i], i);
	    addOption(objtypeNow, optGroup[i], optGroup_id[i]);
	}
	
}


