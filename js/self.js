var c_showed = 0;
document.write('<div style="position:absolute; left:-2px; top:-2px; z-index: 2; width:0px; height:0px; visibility:visible;" id="center"></div><div style="position:absolute; left:0px; top:0px; z-index: 1; width:100%; height:100%; visibility:hidden;" id="center2" class=blackbg>&nbsp;</div>');
$("#center").hide(1);

function ch_photo()
{
$("#center").css({left:'40%',top:'100',width:'350px',height:'200px'});
	if (!c_showed)
	{
	 $("#center2").css("visibility","visible");
	 $("#center").show(500);
	 c_showed++;
	 $("#center").html(sbox('<table style="width: 90%"> <tr> <td class=title>Загрузить фотографию</td> <td style="height: 30px; width: 30px"> <img src="images/closebox.png" width="30" height="30" onclick="ch_photo()" title=Close></td> </tr> <tr> <td colspan=3 class=items>Пожалуйста, выберите фотографию на вашем компьютере.<hr><form enctype="multipart/form-data" method="post"> <input type="hidden" name="MAX_FILE_SIZE" value="5000000" /><input name="photofile" type="file" class=login>  <input type="submit" value="Загрузить" class=login style="width:90%"> </form><hr><i>Могут возникнуть проблемы при загрузке. Советуем вам загружать JPEG файлы размером не более 500 килобайт.</i> </td></tr> </table>')); 	
	 }
	else
	{
	 $("#center2").css("visibility","hidden");
	 $("#center").hide(500);
	 c_showed--;
	}
}

function add_friend()
{
$("#center").css({left:'40%',top:'100',width:'250px',height:'300px'});
	if (!c_showed)
	{
	 $("#center2").css("visibility","visible");
	 $("#center").show(500);
	 c_showed++;
	 $("#center").html(sbox('<table style="width: 100%"> <tr> <td class=title>Добавить друга</td> <td style="height: 30px; width: 30px"> <img src="images/closebox.png" width="30" height="30" onclick="ch_photo()" title=Close></td> </tr> <tr> <td colspan=3 class=items>Пожалуйста, введи имя персонажа с которым вы хотите подружить.<hr><form method="post"> <input name="friend_nick" type="text" class=login style="width:100%" id=friend_nick>  <input type="submit" value="Добавить" class=login style="width:100%"> </form><hr></td></tr> </table> ')); 	
	 ActionFormUse = 'friend_nick';
	 }
	else
	{
	 $("#center2").css("visibility","hidden");
	 $("body").fadeIn(300);
	 $("#center").hide(500);
	 c_showed--;
	}
}