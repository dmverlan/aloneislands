function atype_ch()
{
	var a = parseInt(jQuery("#atype").get(0).value);
	
	if(a==0) 
	{
		jQuery("#_atype").html("");
	}
	if(a==1) 
	{
		jQuery("#_atype").html(speech);
	}
	if(a==2) 
	{
		jQuery("#_atype").html("");
	}
	if(a==3) 
	{
		jQuery("#_atype").html(quests);
	}
	if(a==4) 
	{
		jQuery("#_atype").html("<input type=text class=login name=value value=Фраза>");
	}
	if(a==5) 
	{
		jQuery("#_atype").html("");
	}
	if(a==6) 
	{
		jQuery("#_atype").html("<input type=text class=login name=value value=0>");
	}
	if(a==7) 
	{
		jQuery("#_atype").html("<input type=text class=login name=value value=0>");
	}
	if(a==8) 
	{
		jQuery("#_atype").html("<input type=text class=login name=value value=0>");
	}
	if(a==9) 
	{
		jQuery("#_atype").html("<input type=text class=login name=value value=0>");
	}
	if(a==10) 
	{
		jQuery("#_atype").html("");
	}
	if(a==11) 
	{
		jQuery("#_atype").html("<input type=text class=login name=location value=out> [<input type=text class=login name=x value=0 style='width:15px;'>:<input type=text class=login name=y value=0 style='width:15px;'>]");
	}
}

/*
		$atype = '<select name=atype id=atype onchange="atype_ch()">';
		$atype .= '<option value=0 SELECTED>Ничего</option>';
		$atype .= '<option value=1>Перейти на речёвку</option>';
		$atype .= '<option value=2>Закрыть окно общения</option>';
		$atype .= '<option value=3>Выдать квест</option>';
		$atype .= '<option value=4>Написать фразу в чат</option>';
		$atype .= '<option value=5>Начать бой с говорящим</option>';
		$atype .= '<option value=6>Выдать опыта</option>';
		$atype .= '<option value=7>Выдать денег</option>';
		$atype .= '<option value=8>Выдать бриллиантов</option>';
		$atype .= '<option value=9>Выдать пергаментов</option>';
		$atype .= '<option value=10>Вылечить травму</option>';
		$atype .= '<option value=11>Телепортировать</option>';
		$atype .= '</select>';
		
		*/