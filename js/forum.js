function dw(a)
{
	return document.write(a);
}

function Top(a) {
return dw("<table width=100% cellspacing=0 cellpadding=0>");
}

function Center(a) {
return dw("<tr><td>");
}

function Bottom(a) {
return dw("</td></tr><tr><td class='BottomBorder' height='32'></td></tr></table>");
}


function razd(arr, time){

a = '<';
b = 'razdel>';
x = a + time + b;

  razd = arr.split(x);

//Loop by division
  for (i = 0; i < razd.length - 1; i++){

	Top();
	
	c = '<';
    d = 'subrazdel>';
	y = c + time + d;
    subrazd = razd[i].split(y);
    dw("<br><div class=but><br><Center class=header>" + subrazd[0] + "</Center><br></div>");

	Center();

	//Print subdivision

	//Table header
	dw("<table width=100% cellspacing=4 cellpadding=0 class=fightlong><tr align=Center><td width=50% class=tableHeader>Название раздела:</td><td class=tableHeader>Тем:</td><td class=tableHeader>Сообщений:</td><td class=tableHeader>Последнее сообщение:</td></tr>");



	// Table body
	for (j = 0; j < subrazd.length / 9 - 1; j++){
    dw("<tr><td valign=Center class=inv><b><a href='/forum/?act=show_cat&id=");
	dw(subrazd[j * 9 + 1]); //Print ID
	dw("' class=timef>");
	dw(subrazd[j * 9 + 2]); //Print title
	dw("</a></b><br><div class=timef>");
	dw(subrazd[j * 9 + 3]); // Print about
	dw("</div></td><td align=Center valign=Center width=50 class=but valign=Center><span class=counts>");
	dw(subrazd[j * 9 + 4]); //Print count of topics
    dw("</span></td><td align=Center valign=Center width=60 class=but valign=Center><span class=counts>");
    dw(subrazd[j * 9 + 5]);  //Print count of messages

	if (subrazd[j * 9 + 6] != -666){
		dw("</td><td align=left valign=Center class=but2><div class=timef>");
	    dw(subrazd[j * 9 + 8]); //Print date
		dw(" Автор:");
	    dw(subrazd[j * 9 + 9]); // Print author
    	}
    else{
     	dw("</span></td><td align=left valign=Center class=but2><div class=timef>Нет сообщений");
    	}
			dw("</div></td></tr>");
	}

      //Table footer
    dw("</table>");

	//End print subdivision
	Bottom();
  }
}

  function info(uid){
    link = '/info.php?id='+uid;
    window.open(link);
  }
  function molch(uid){
    link = 'index.php?act=molch_form&uid='+uid;
    window.open(link);
  }
  function redir(href){
    setTimeout(href,100);
  }
  function add_code(code){
    if (document.selection){
      str = document.selection.createRange();
      str.text = '['+code+']'+str.text+'[/'+code+']';
    }else{
      txt=document.getElementById('text');
      txt.value=txt.value.substring(0, txt.selectionStart)+'['+code+']'+txt.value.substring(txt.selectionStart, txt.selectionEnd)+'[/'+code+']'+txt.value.substring(txt.selectionEnd, txt.value.length);
    }
  }

  function add_smile(code){
    smiles = Array('O:-)','=)',':(',';)',':-P','8-)',':-D',':-/','=-O',':-*',':\'(',':-X','>:o',':-|','*JOKINGLY*',']:->','[:-}','*KISSED*',':-!','*TIRED*','*STop*','*KISSING*','@}->--','*THUMBS UP*','*DRINK*','*IN LOVE*','@=','*HELP*','%)','*OK*','*WASSUP*','*SORRY*','*BRAVO*','*ROFL*','*PARDON*','*NO*','*CRAZY*','*DONT_KNOW*','*DANCE*','*YAHOO*','*HI*','*BYE*',';D','*SCRATCH*');
    if (document.selection){
      str = document.selection.createRange();
      str.text = smiles[code];
    }else{
      txt=document.getElementById('text');
      txt.value=txt.value.substring(0, txt.selectionStart)+' '+smiles[code]+' '+txt.value.substring(txt.selectionEnd, txt.value.length);
    }
  }

  function del_msg(id){
    if(confirm("Вы точно хотите удалить это сообщение?")){
      location.href='index.php?act=adm_delete_msg&id='+id;
    }
  }


function subrazd(arr, time){
a = '<';
b = 'razdel>';
x = a + time + b;

  razd = arr.split(x);

//Table header
dw("<table width=100% cellspacing=2 cellpadding=2 bgcolor=FFFFFF><tr align=Center><td width=50% class=tableHeader>Название раздела:</td><td class=tableHeader>Сообщений:</td><td class=tableHeader>Просмотров:</td><td class=tableHeader>Последнее сообщение:</td></tr>");

//Loop by division
  for (i = 0; i < razd.length - 1; i++){
	//dw(razd[i]);
	c = '<';
    d = 'subrazdel>';
	y = c + time + d;
    subrazd = razd[i].split(y);
   // dw("<div align=left><strong>" + subrazd[0] + "<br />" + "</strong><br></div>");
	//Print subdivision
	// Table body
	for (j = 0; j < subrazd.length / 10; j++){
    dw("<tr><td valign=Top class=inv>");
	 
	if(subrazd[j * 7 ] == 1) {dw("<font class=green>[Прикреплена]</font>");}
	if(subrazd[j * 7 + 1] == 1) {dw("<font class=hp>[Закрыта]</font>");}
	
	 dw("<a href='/forum/?act=show_topic&id=");
	dw(subrazd[j * 7 + 2]); //Print ID
	dw("' class=timef>");

	dw(subrazd[j * 7 + 3]); //Print title
	dw("</a>");
	//dw(subrazd[j * 9 + 2]); // Print about
	dw("</td>");
	//dw(subrazd[j * 9 + 3]); //Print count of topics
    dw("<td align=Center valign=Top width=60 class=but valign=Center>");
    dw("<font class=counts>"+subrazd[j * 7 + 4]+"</font>");  //Print count of messages
	 dw("<td align=Center valign=Top width=60 class=but valign=Center>");
    dw("<font class=counts>"+subrazd[j * 7 + 9]+"</font>");  //Print count of messages
    dw("</td><td align=left valign=Top class=but2><div class=timef>");
    dw(subrazd[j * 7 + 7]); //Print date
	 dw(" Автор:");
    dw(subrazd[j * 7 + 8]); // Print author
	
	if (j < 20) {dw("</div></td></tr><tr><td colspan=3 align=Center></td></tr>");}
		else
			{dw("</div></td></tr>");}
	}

      //Table footer
    //dw("</div></td></tr>");
	//End print subdivision

  }
  dw("</table>");
  Bottom();
}

function topic(arr, time){
var _Date = '';
//dw(arr);
a = '<';
b = 'next_msg>';
x = a + time + b;

  razd = arr.split(x);

//Loop by division
  for (i = 0; i < razd.length - 1; i++){
  //dw(razd[i]);
//  dw("<br /> <br />");

	c = '<';
    d = 'msg>';
	y = c + time + d;
    subrazd = razd[i].split(y);
    //dw("<div align=left><strong>" + subrazd[0] + "<br />" + "</strong><br></div>");

	//Print subdivision
//	dw("<br />");
	//Table header
	dw("<table width=100% cellspacing=0 cellpadding=0><tr align=left><td width=20% class=tableHeader></td><td></td><td></td></tr>");
	// Table body
	for (j = 0; j < subrazd.length / 12 ; j++){
		if(subrazd[j * 11 + 11])	
			_Date = subrazd[j * 11 + 11]+'<br>'; 
		else
			_Date = '';
	if(i==0)
    dw("<tr><td valign=Top class=but2><i class=gray>"+_Date+"</i><strong class=user>");
	else
	 dw("<tr><td valign=Top class=but><i class=gray>"+_Date+"</i><strong class=user>");
	dw(subrazd[j * 11].substr(0,17)); //Print Nmae
	dw(" [<b class=lvl>"+subrazd[j * 11 + 1]+"</b>]</strong> "); //Print ID
    dw("<img src='/images/i.gif' onClick=\"info("+subrazd[j * 11 + 4]+")\" style='cursor:pointer;'>");
	dw("<br><div class=timef>");
	dw(subrazd[j * 11 + 2]); // Print group
	if(i==0)
		dw("</div></td><td align=left valign=Center class=but style='overflow:scroll;'>");
	else
		dw("</div></td><td align=left valign=Top class=fightlong style='overflow:scroll;'>");
	dw(subrazd[j * 11 + 3]); //Print text
    dw("</td>");
	   dw("<td style='width:100px;'>");
      //Table footer

    //Print buttons ------------------------------------------------------------
    dw("<table cellspacing=0 cellpadding=0 bgcolor=FFFFFF  style='width:100px;'><tr><td width=100% align=left>");
    //Print Nakazanie
    if (subrazd[j * 11 + 7] == 1){
    	/*dw(" <input type=button value='Наказание' class=login onclick=molch(");
    	dw(subrazd[j * 11 + 4]);
    	dw(")  style='width:100px;'>");*/
		dw("<a class=Button href='javascript:molch("+subrazd[j * 11 + 4]+");'>Наказание</a>");
    	dw("<div align=right>");
    	}
 	//Print quote
    if (subrazd[j * 11 + 8] == 1){
    	//dw("<input type=button value='Цитировать' class=login onclick='location=\"index.php?act=add_quote&id=");
		dw("<a class=Button href='index.php?act=add_quote&id="+subrazd[j * 11 + 5]+"'>Цитировать</a>");
    	//dw(subrazd[j * 11 + 5]);
    	//dw("\";'  style='width:100px;'>");
    	}
    //Print Redact
    if (subrazd[j * 11 + 9] == 1){
    	/*dw("<input type=button value='Редактировать' class=login onclick='location=\"index.php?act=adm_edit_msg_form&id=");
    	dw(subrazd[j * 11 + 5]);
    	dw("\";'  style='width:100px;'>");*/
		dw("<a class=Button href='index.php?act=adm_edit_msg_form&id="+subrazd[j * 11 + 5]+"'>Редактировать</a>");
    	}
    //Print delete
    if (subrazd[j * 11 + 10] == 1){
		dw("<a class=Button href='javascript:del_msg("+subrazd[j * 11 + 5]+")'>Удалить</a>");
    	/*dw("<input type=button value='Удалить' class=login onClick='del_msg(");
    	dw(subrazd[j * 11 + 5]);
    	dw(")'  style='width:100px;'>");*/
    	}
  	dw("</div></td></tr></table>");
	   
		dw("</td></tr></table>");
		 
    //End print buttons---------------------------------------------------------
    }
	//End print subdivision

  }
Bottom();
}






 function info(uid){
    link = '/info.php?id='+uid;
    window.open(link);
  }
  function molch(uid){
    link = 'index.php?act=molch_form&uid='+uid;
    window.open(link);
  }

  function redir(href){
    setTimeout(href,10000);
  }

  function add_code(code){
    if (document.selection){
      str = document.selection.createRange();
      str.text = '['+code+']'+str.text+'[/'+code+']';
    }else{
      txt=document.getElementById('text');
      txt.value=txt.value.substring(0, txt.selectionStart)+'['+code+']'+txt.value.substring(txt.selectionStart, txt.selectionEnd)+'[/'+code+']'+txt.value.substring(txt.selectionEnd, txt.value.length);
    }
  }

  function add_smile(code){
    smiles = Array('O:-)','=)',':(',';)',':-P','8-)',':-D',':-/','=-O',':-*',':\'(',':-X','>:o',':-|','*JOKINGLY*',']:->','[:-}','*KISSED*',':-!','*TIRED*','*STop*','*KISSING*','@}->--','*THUMBS UP*','*DRINK*','*IN LOVE*','@=','*HELP*','%)','*OK*','*WASSUP*','*SORRY*','*BRAVO*','*ROFL*','*PARDON*','*NO*','*CRAZY*','*DONT_KNOW*','*DANCE*','*YAHOO*','*HI*','*BYE*',';D','*SCRATCH*');
    if (document.selection){
      str = document.selection.createRange();
      str.text = smiles[code];
    }else{
      txt=document.getElementById('text');
      txt.value=txt.value.substring(0, txt.selectionStart)+' '+smiles[code]+' '+txt.value.substring(txt.selectionEnd, txt.value.length);
    }
  }

  function del_msg(id){
    if(confirm("Вы точно хотите удалить это сообщение?")){
      location.href='index.php?act=adm_delete_msg&id='+id;
    }
  }

