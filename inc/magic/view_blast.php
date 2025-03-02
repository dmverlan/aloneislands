<?
	function vblast($bl,$pers)
	{
		GLOBAL $types;
		$_REQ ='';
		//ATTRUBUTES
		$_ATTR = '<font class=timef>Удар:</font> <b class=green>'.$bl["udmin"].'</b>-<b class=red>'.$bl["udmax"].'</b>';
		$_ATTR .= '<br>';
		if ($bl["targets"]<1) $bl["targets"]=1;
		$_ATTR .= '<font class=timef>Целей:</font> не более <b>'.$bl["targets"].'</b>';
		$_ATTR .= '<br>';
		if ($bl["turn_colldown"]) 
		$_ATTR .= '<font class=timef>Перезарядка:</font> <b class=red>'.$bl["turn_colldown"].' ход.</b>';
		elseif ($bl["colldown"])
		$_ATTR .= '<font class=timef>Перезарядка:</font> <b class=green>'.$bl["colldown"].' сек.</b>';
		else 
		$_ATTR .= '<font class=timef>Перезарядка отсутствует</font>';
		$_ATTR .= '<br>';
		if ($bl["aura_id"])
		{
			$a = sqla("SELECT name,image FROM auras WHERE id=".intval($bl["aura_id"])."");
			$_ATTR .= "<br><center class=submit style='width:80%'>При ударе накладывает<br>«<img src=images/magic/".$a["image"].".gif height=12><b>".$a["name"]."</b>»</center>";
		}
		$_ATTR .= '<br><i>'.$bl["describe"].'</i>';
		if ($bl["cur_colldown"]>time()) $_ATTR .= '<br><img src=images/spinner.gif>Идёт перезарядка ещё '.tp($bl["cur_colldown"]-time());
		//REQUIRES
		foreach ($bl as $key=>$value)
		{
			if ($key[0]=='t' and $key<>'targets' and $key<>'turn_colldown' and $key<>'type' and$value)
			{
				if ($pers[substr($key,1,strlen($key)-1)]>=$value) 
					$value = "<font color=#008800>".$value."</font>";
				else
					$value = "<font color=#880000>".$value."</font>";
				$_REQ .= name_of_skill(substr($key,1,strlen($key)-1)).': <b>'.$value.'</b><br>';
			}
		}
		$_REQ .= '<b class=user>Маны на удар:</b> <b class=ma>'.$bl["manacost"].' MP</b>';
		echo "<tr>";
		echo '<td width="150" class=user align=center  style="border-bottom-style: solid; border-bottom-width: 4px; border-color:silver;">'.$bl["name"].' <table border="0" width="90" cellspacing="0" cellpadding="0"> <tr> <td colspan="3" width=90> <img border="0" src="images/design/abils/zup.gif" width="90" height="16"></td> </tr> <tr> <td rowspan="2" width="23"> <img border="0" src="images/design/abils/zleft.gif" width="23" height="76"></td> <td width=47><img src="images/magic/'.$bl["image"].'.gif"></td> <td width="20"> <img border="0" src="images/design/abils/zright.gif" width="20" height="61"></td> </tr> <tr> <td colspan="2" width=68> <img border="0" src="images/design/abils/zbottom.gif" width="68" height="15"></td> </tr> </table> <i class=blue>'.$types[$bl["type"]].'</i></td>'; 
		echo "<td valign=top style='border-bottom-style: solid; border-bottom-width: 4px; border-color:silver'>";
		echo '<table style="width: 100%;" cellspacing="0" cellpadding="0"> <tr> <td class="mfb">СВОЙСТВА</td> <td class="mfb" style="border-left-style: solid; border-left-width: 1px; border-color:silver">ТРЕБОВАНИЯ</td> </tr> <tr> <td class=ym width=50%>'.$_ATTR.'</td> <td style="border-left-style: solid; border-left-width: 1px; border-color:silver" class=ym  width=50%> '.$_REQ.'</td> </tr> </table> '; 		
		echo "</td>";
		echo "</tr>";
	}
?>