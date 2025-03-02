function code(user,pass)
{
	document.write('<br><img src="images/logo.gif" width=180><div class=inv id=code_img><form name="code" onsubmit="sbmt_img();" method=post><input type=hidden name=user value="'+user+'"><input type=hidden name=passnmd value="'+pass+'"><input class=login type=text name="spass" id="spass" value="" DISABLED><br><input class=login type=button onclick="sbmt_img()" value="OK" style="cursor:pointer"></form></div>');
	show_numbers();
}

function sbmt_img()
{
	var code = parseInt(document.code.spass.value);
	if (code>9999 && code<100000)
	 {
	 document.code.spass.disabled = false;
	 document.code.submit();
	 }
}

function show_numbers()
{
	var t='<hr>';
	for (var i=0;i<10;i++)
	 t+= '<input style="cursor:hand" type=button size=2 value=" '+i+' " onclick="plus_codeimg('+i+')" class=laar ondblclick="plus_codeimg('+i+')"> ';
	 t+= '<input style="cursor:hand" type=button size=2 value=" << " onclick="plus_codeimg(-1)" class=laar ondblclick="plus_codeimg(-1)"> ';
	 t+= '<input style="cursor:hand" type=button size=2 value=" C " onclick="plus_codeimg(-2)" class=laar ondblclick="plus_codeimg(-2)">';
	document.getElementById('code_img').innerHTML = document.getElementById('code_img').innerHTML + t;
}

function plus_codeimg(i)
{
	var code = document.code.spass.value;
if (i>-1)
	code = code + i;
else if (i==-1)
	code = code.substr(0,code.length-1);
else code='';
	document.code.spass.value = code;
}