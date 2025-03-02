<?
	function vaura($bl,$pers,$use=1)
	{
		GLOBAL $types,$_INV;
		$_REQ ='';
		$_ATTR = '';
		//ATTRUBUTES
		$_ATTR .= '<table class=LinedTable border=0 cellspacing=0 cellspadding=0 width=100%>';
		$params = explode("@",$bl["params"]);
		foreach($params as $par)
		{
			$p = explode("=",$par);
			if (substr($p[0],0,2)=='mf') $perc = '%'; else $perc = '';
			if ($p[1][strlen($p[1])-1]=='%') $perc .= '<i>[%]</i>';
			if ($p[1])
			$_ATTR .= "<tr><td width=60% class=items>".name_of_skill($p[0])."</td><td class=items><b>".plus_param(intval($p[1])).$perc."</b></td></tr>";
		}
		$_ATTR .= '</table>';
		$_ATTR .= '<br>';
		if ($bl["targets"]<1) $bl["targets"]=1;
		$_ATTR .= 'Целей: не более <b>'.$bl["targets"].'</b>';
		$_ATTR .= '<br>';
		if ($bl["turn_colldown"]) 
		$_ATTR .= 'Перезарядка: <b>'.$bl["turn_colldown"].' ход.</b>';
		else
		$_ATTR .= 'Перезарядка: <b class=timef>'.$bl["colldown"].' сек.</b>';
		$_ATTR .= '<br>';
		if ($bl["turn_esttime"]) 
		$_ATTR .= 'Действие: <b>'.$bl["turn_esttime"].' ход.</b>';
		else
		$_ATTR .= 'Действие: <b class=timef>'.tp($bl["esttime"]).'</b>';
		$_ATTR .= '<br>';
		if ($bl["forenemy"]==0)
			$_ATTR .= "<i class=green>На свою команду</i>";
		elseif ($bl["forenemy"]==1)
			$_ATTR .= "<i class=red><b>На чужую команду</b></i>";
		else
			$_ATTR .= "<i class=blue>На любую команду</i>";
		$_ATTR .= '<br><i>'.$bl["describe"].'</i>';
		if ($bl["cur_colldown"]>time()) $_ATTR .= '<br><center class=but><center class=submit style="width:90%"><img src=images/spinner.gif>Идёт перезарядка ещё <i class=timef>'.tp($bl["cur_colldown"]-time())."</i></center></center>";
		//REQUIRES
		foreach ($bl as $key=>$value)
		{
			if ($key[0]=='t' and $key<>'targets' and $key<>'turn_colldown' and $key<>'type' and $value>0 and $key<>'turn_esttime')
			{
				if ($pers[substr($key,1,strlen($key)-1)]>=$value) 
					$value = "<font color=#008800>".$value."</font>";
				else
					$value = "<font color=#880000>".$value."</font>";
				$_REQ .= name_of_skill(substr($key,1,strlen($key)-1)).': <b>'.$value.'</b><br>';
			}
		}
		$_REQ .= 'Стоимость маны: <b class=ma>'.$bl["manacost"].'</b>';
		if($_INV)
		{
		if ($bl["forenemy"]==0 and $bl["manacost"]<=$pers["cma"] and $bl["tlevel"]<=$pers["level"]
		and $bl["ts6"]<=$pers["s6"] and $bl["tm1"]<=$pers["m1"] and $bl["tm2"]<=$pers["m2"] and $bl["cur_colldown"]<=time() and $bl["turn_colldown"]==0 and $bl["turn_esttime"]==0 and $use)
		{
		$_REQ .= "<br><center class=but><input type=button class=inv_but value='Использовать на себя' onclick=\"location='main.php?aura_use=".$bl["id"]."&inv=magic'\"></center>";
		/*if(!$bl["autocast"]) 
			$_REQ .= "<br><center class=but><input type=button class=but value='Автокаст ВКЛ' onclick=\"location='main.php?autocast=".$bl["id"]."&inv=magic'\"></center>";
		else
			$_REQ .= "<br><center class=but><input type=button class=but2 value='Автокаст ВЫКЛ' onclick=\"location='main.php?autocast=".$bl["id"]."&inv=magic'\"></center>";
		*/}	
		$_REQ .= "<br><center class=but><input type=button class=but2 value='Удалить' onclick=\"location='main.php?aura_delete=".$bl["id"]."&inv=magic'\"></center>";
		}
		echo "<tr>";
		echo '<td width="150" class=user align=center  style="border-bottom-style: solid; border-bottom-width: 4px; border-color:silver">'.$bl["name"].' <table border="0" width="90" cellspacing="0" cellpadding="0"> <tr> <td colspan="3" width=90> <img border="0" src="images/design/abils/zup.gif" width="90" height="16"></td> </tr> <tr> <td rowspan="2" width="23"> <img border="0" src="images/design/abils/zleft.gif" width="23" height="76"></td> <td width=47><img src="images/magic/'.$bl["image"].'.gif"></td> <td width="20"> <img border="0" src="images/design/abils/zright.gif" width="20" height="61"></td> </tr> <tr> <td colspan="2" width=68> <img border="0" src="images/design/abils/zbottom.gif" width="68" height="15"></td> </tr> </table> <i class=blue>'.$types[$bl["type"]].'</i></td>'; 
		echo "<td valign=top style='border-bottom-style: solid; border-bottom-width: 4px; border-color:silver'>";
		echo '<table style="width: 100%" cellspacing="0" cellpadding="0"> <tr> <td class="mfb">СВОЙСТВА</td> <td class="mfb" style="border-left-style: solid; border-left-width: 1px; border-color:silver">ТРЕБОВАНИЯ</td> </tr> <tr> <td class=ym width=50%>'.$_ATTR.'</td> <td style="border-left-style: solid; border-left-width: 1px; border-color:silver" class=ym  width=50%> '.$_REQ.'</td> </tr> </table> '; 		
		echo "</td>";
		echo "</tr>";
	}
?>