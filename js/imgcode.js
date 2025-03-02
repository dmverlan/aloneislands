var codename = 'image_protect';
var sn = 0;

function imgcode(hashcode)
{
	document.write('<br><br><hr><img src="captcha/image.php?code='+hashcode+'" width=180 height=80><div class=inv id=code_img><form name="'+codename+'" onsubmit="sbmt_img();return false;"><input type=hidden name=code_name value="'+codename+'"><input class=login type=text name="'+codename+'" id="'+codename+'" value="" id=code><input type=button value="0-9" class=login onclick="show_numbers()" style="cursor:pointer"><br><input class=login type=button onclick="sbmt_img()" value="OK" style="cursor:pointer"><input class=login type=button onclick="location=\'main.php\';" value="[Сменить рисунок]" style="cursor:pointer"></form></div>');
	show_numbers();
}

function sbmt_img()
{
	var code = parseInt(document.image_protect.image_protect.value);
	if (code>999 && code<10000)
	 {
	 document.getElementById('code_img').innerHTML = '<form method=post name="code"><input type=hidden name=code_img value="'+code+'"></form>';
	 document.code.submit();
	 }
}

function show_numbers()
{
	sn = (sn+1)%2;
	var t='<hr>';
	for (var i=0;i<10;i++)
	 t+= '<input style="cursor:pointer" type=button size=2 value=" '+i+' " onclick="plus_codeimg('+i+')" class=but style="width:80px;height:50px;cursor:pointer;">';
	 t+= ' &nbsp; <input style="cursor:pointer" type=button size=2 value=" << " onclick="plus_codeimg(-1)" class=but style="width:100px;height:50px;cursor:pointer;">';
	 t+= '<input style="cursor:pointer" type=button size=2 value=" C " onclick="plus_codeimg(-2)" class=but style="width:100px;height:50px;cursor:pointer;">';
	if (sn==1)
	document.getElementById('code_img').innerHTML = document.getElementById('code_img').innerHTML + t;
	else
	document.getElementById('code_img').innerHTML = '<form name="'+codename+'"><input type=hidden name=code_name value="'+codename+'"><input class=login type=text name="'+codename+'" id="'+codename+'" value="" id=code><input type=button value="0-9" class=login onclick="show_numbers()" style="cursor:pointer"><br><input class=login type=button onclick="sbmt_img()" value="OK" style="cursor:pointer"></form>';
}

function plus_codeimg(i)
{
	var code = document.image_protect.image_protect.value;
if (i>-1)
	code = code + i;
else if (i==-1)
	code = code.substr(0,code.length-1);
else code='';
	document.image_protect.image_protect.value = code;
}