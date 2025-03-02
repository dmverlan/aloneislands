document.write('<META Content="text/html; Charset=windows-1251" Http-Equiv=Content-type>');
top.statusMSG = 1;
function edit_msg(server_state,h,m,s)
{
if (server_state) top.SERVER_STATE = server_state;
if (h+m+s) 
{
	top.HOURS = h;
	top.MINUTES = m;
	top.SECONDS = s;
}
var nick = top.frames['chmain'].nick;
var txt=new Array();
var s = '';
var type = 'time';
var towho = new Array();
var i,j,prv=0;
var smile,q=0;
var msg='';
 var inviz='';
 var uninviz='';
 var att=0;
var PRIV_COUNTER = 0;

	for (i=0;i<t.length;i++)
	{
		s='';
		inviz='';
		uninviz='';
		type='time';
		txt = t[i].split('•');
		if (txt[6]!=3)
		{
		msg = ' '+txt[3];
		msg = str_replace2 (msg,'=)','//001');
		msg = str_replace2 (msg,':)','//001');
		msg = str_replace2 (msg,':-)','//001');
		msg = str_replace2 (msg,'=(','//002');
		msg = str_replace2 (msg,':(','//002');
		msg = str_replace2 (msg,':-(','//002');
		msg = str_replace2 (msg,';-)','//003');
		msg = str_replace2 (msg,';)','//003');
		msg = str_replace2 (msg,':D','//004');
		msg = str_replace2 (msg,':-D','//004');
		msg = str_replace2 (msg,':d','//004');
		msg = str_replace2 (msg,'=d','//004');
		msg = str_replace2 (msg,'=в','//004');
		msg = str_replace2 (msg,'=0','//005');
		msg = str_replace2 (msg,':-0','//005');
		msg = str_replace2 (msg,':0','//005');
		msg = str_replace2 (msg,'=[','//010');
		msg = str_replace2 (msg,':[','//010');
		msg = str_replace2 (msg,':-[','//010');
		msg = str_replace2 (msg,'=P','//008');
		msg = str_replace2 (msg,'=p','//008');
		msg = str_replace2 (msg,'=р','//008');
		msg = str_replace2 (msg,'=Р','//008');
		msg = str_replace2 (msg,'+)','//118');
		msg = str_replace2 (' '+msg+' ',' хай ','//046');
		 if (txt[1].indexOf('n=')>-1)
		{txt[1]=txt[1].replace('n=','');inviz='<i>';uninviz='</i>';}
		while (msg.indexOf ('//')>0 && q<3) {
		if (msg.indexOf ('//')>0) smile = msg.substr(msg.indexOf('//')+2,3);
		smile++;smile--;
		if (smile < 268) {
		if (smile/100<1) if (smile/10<1) smile='00'+smile;
		else if (smile/100<1) if (smile/10>=1) smile='0'+smile;
		msg = str_replace2 (msg,'//'+smile,'<img src=images/smiles/smile_'+smile+'.gif onclick="top.sm_ins(\''+smile+'\')">');
		}
		q++;
		}
		txt[3] = msg;
	att = 0;
	if(txt[1]=='#sound')
	{
	 top.Sound(str_replace(" ","",txt[3]),1,0);
	 continue;
	}else
	if(txt[1]=='w')
	{
	 s+='<font class=user style="color:#990000">Смотрители сообщают.</font> '+txt[3]+'<br>';
	 att=1;
	 top.Sound('msg_in',0.5,0);
	}else
	if(txt[1]=='a')
	{
	 s+='<font class=al>AloneIslands.Ru</font> '+txt[3]+'<br>'; 
	 att=1;
	}else
	if(txt[1]=='^')
	{
	 s+='<span class=red>Гильдия наставников</span> '+txt[3]+'<br>'; 
	 att=1;
	}else
	if(txt[1]=='s')
	{
	 s+='<font class=user style="color:#990000">Системная информация.</font> '+txt[3]+'<br>'; 
	 att=1;
	 top.Sound('msg_in',0.5,0);
	}
	if (att==0)
	{
	if(txt[1]=='n' && top.ChatFyo==0)
	{
	s+='<font class=time>'+txt[0]+'</font> &nbsp;<i>невидимка</i>'; 
	if (txt[2]!='')
		 {
			s+=' для ';
			towho = txt[2].split('|');
			for (j=0;j<towho.length-1;j++) 
			 {
				s+= '<font class=user onclick="top.say_private(\''+towho[j]+'\','+prv+')">'+
				towho[j]+'</font>';
				if (j<(towho.length-2)) s+=',';
			 }
		 }
		 s=s+':'+txt[3]+'<br>';
	}
	else {
		prv = 0;
		if (txt[1]==nick || (' |'+txt[2]+'|').indexOf('|'+nick+'|')>-1) type='toyou';
		txt[2]=' '+txt[2];
		txt[2] = txt[2].substr(1,txt[2].length-1);
		if (txt[4]==2 && type=='toyou') {type = 'clan_you';prv=2;}
		else if (txt[4]==2) {type = 'clan';prv=2;}
		if (txt[4]==1) {
			type = 'priv';prv=1;
			if (txt[1]!=nick)PRIV_COUNTER++;
		}
		if ((top.ChatFyo==1 && type!='time') || top.ChatFyo==0)
		{
		s+='<font class='+type+'>'+txt[0]+'</font> &nbsp;'+inviz+'<font class=user onclick="top.say_private(\''+txt[1]+'\','+prv+')">'+
		txt[1]+'</font>'+uninviz+'';
		if (txt[2]!='')
		 {
			s+=' для ';
			towho = txt[2].split('|');
			for (j=0;j<towho.length-1;j++) 
			 {
				s+= '<font class=user onclick="top.say_private(\''+towho[j]+'\','+prv+')">'+
				towho[j]+'</font>';
				if (j<(towho.length-2)) s+=',';
			 }
		 }
		if (txt[1]!='') s+=':';
		s += ' <font color="#'+txt[5]+'">'+txt[3]+'</font><br>';
		}
		}
	}
	if (s!='')	
		s = "<div>"+s+"</div>";
	if (i==t.length-1)
	top.add_msg(s,txt[6],1);
		else
	top.add_msg(s,txt[6],0);
	}
	else
	{
		//txt[3] = str_replace("#009900","#66FF66",txt[3]);
		//txt[3] = str_replace("#000099","#6666FF",txt[3]);
		top.add_msg('<font class=timef>'+txt[0]+'</font> '+str_replace("%",'<br><font class=timef>'+txt[0]+'</font> ',txt[3])+'<hr>',txt[6],1);
	}

	}
	for (var h=0;h<PRIV_COUNTER;h++) top.Sound('msg_in',1,0);
}

function str_replace(replacement,substr,str)
{
	var w = str.split(replacement);
	return w.join(substr);
}
function str_replace2(str,replacement,substr)
{
	var w = str.split(replacement);
	return w.join(substr);
}
