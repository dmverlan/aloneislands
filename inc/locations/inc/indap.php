<?
		if (empty($_POST) and empty($_GET["buy"]))
		{
			echo '<div class=weapons_box><form action=main.php?c=indap method=post>Выберите вещь: <select size="1" name="id" class="return_win"> ';
			$r = sql("SELECT name,id FROM wp WHERE uidp='".$pers["uid"]."' and where_buy=1 and id_in_w='' and clan_sign='' and weared=0");
			$i=0;
			while ($p = mysql_fetch_array($r))
			{
				$i++;
				echo "<option value=".$p["id"].">".$p["name"]."</option>";
			}
			echo '</select><input type="submit" value=" Ok " class="login"></form></div>';
			if ($i==0) echo "<div class=return_win>Вещь должна быть снята для улучшения.</div>";
		}elseif (empty($_POST["idd"]) and empty($_GET["buy"]))
		{
			$vesh = sqla("SELECT * FROM wp WHERE id='".$_POST["id"]."' and uidp='".$pers["uid"]."' and where_buy=1 and id_in_w='' and clan_sign='' and weared=0");
			include("inc/inc/weapon2.php");
			$type  = $vesh["stype"];
			echo "<script>".$text."</script>";
		$s1 = '';
		for ($i=$vesh["s1"];$i<=$vesh["s1"]+7 and $i<20;$i++)
		 $s1 .= '<option value='.$i.'>+'.$i.'</option>';
		$s2 = '';
		for ($i=$vesh["s2"];$i<=$vesh["s2"]+7 and $i<20;$i++)
		 $s2 .= '<option value='.$i.'>+'.$i.'</option>';
		$s3 = '';
		for ($i=$vesh["s3"];$i<=$vesh["s3"]+7 and $i<20;$i++)
		 $s3 .= '<option value='.$i.'>+'.$i.'</option>';
		$s4 = '';
		for ($i=$vesh["s4"];$i<=$vesh["s4"]+7 and $i<20;$i++)
		 $s4 .= '<option value='.$i.'>+'.$i.'</option>';
		$s5 = '';
		for ($i=$vesh["s5"];$i<=$vesh["s5"]+7 and $i<20;$i++)
		 $s5 .= '<option value='.$i.'>+'.$i.'</option>';
		$s6 = '';
		for ($i=$vesh["s6"];$i<=$vesh["s6"]+7 and $i<20;$i++)
		 $s6 .= '<option value='.$i.'>+'.$i.'</option>';	
	 
		$hp = '';
		for ($i=$vesh["hp"];$i<=$vesh["hp"]+30 and $i<600;$i+=5)
		 $hp .= '<option value='.$i.'>+'.$i.'</option>';
		$kb = '';
		for ($i=$vesh["kb"];$i<=$vesh["kb"]+30 and $i<600;$i+=5)
		 $kb .= '<option value='.$i.'>+'.$i.'</option>';
		$ma = '';
		for ($i=$vesh["ma"];$i<=$vesh["ma"]+30 and $i<600;$i+=5)
		 $ma .= '<option value='.$i.'>+'.$i.'</option>'; 
		$mf1 = '';
		for ($i=$vesh["mf1"];$i<=$vesh["mf1"]+60 and $i<600;$i+=10)
		 $mf1 .= '<option value='.$i.'>+'.$i.'</option>';
		$mf2 = '';
		for ($i=$vesh["mf2"];$i<=$vesh["mf2"]+60 and $i<600;$i+=10)
		$mf2 .= '<option value='.$i.'>+'.$i.'</option>';
		$mf3 = '';
		for ($i=$vesh["mf3"];$i<=$vesh["mf3"]+60 and $i<600;$i+=10)
		 $mf3 .= '<option value='.$i.'>+'.$i.'</option>';
		$mf4 = '';
		for ($i=$vesh["mf4"];$i<=$vesh["mf4"]+60 and $i<600;$i+=10)
		 $mf4 .= '<option value='.$i.'>+'.$i.'</option>';
		$mf5 = '';
		for ($i=$vesh["mf5"];$i<=$vesh["mf5"]+60 and $i<600;$i+=10)
		 $mf5 .= '<option value='.$i.'>+'.$i.'</option>';
			
		$slots = $class;
		$slts = '';
		if ($type<>"book") $slts = '<option value=0>0</option>';
		else
		for ($i=$vesh["slots"];$i<=$vesh["slots"]+3 and $i<20;$i++)
		{
		 $slts .= '<option value='.$i.'>'.$i.'</option>';
		}
			

		
		if ($type<>"noji" and $type<>"mech" and $type<>"topo" and $type<>"book" and $type<>"drob")
		{
		$udmin = '';
		for ($i=$vesh["udmin"];$i<=$vesh["udmin"]+10 and $i<20;$i++)
		{
		 $udmin .= '<option value='.$i.'>'.$i.'</option>';
		}
		$udmax = '';
		for ($i=$vesh["udmax"];$i<=$vesh["udmax"]+10 and $i<30;$i++)
		{
		 $udmax .= '<option value='.$i.'>'.$i.'</option>';
		}
		$dop = '	<tr>
		<td width="244">Класс брони</td>
		<td width="25%" align="center"><select size="1" name="kb" class="items">'.$kb.'
		</select></td>
		<td width="25%">Удар</td>
		<td width="25%" align="center"><select size="1" name="udmin" class="items">'.$udmin.'
		</select>-<select size="1" name="udmax" class="items">'.$udmax.'
		</select></td>
	</tr>';
		}else
		{
		$udmin = '';
		if ($vesh["udmin"]<100)
		for ($i=$vesh["udmin"];$i<=$vesh["udmin"]+20 and $i<100;$i++)
		{
		 $udmin .= '<option value='.$i.'>'.$i.'</option>';
		}
		else $udmin .= '<option value='.$vesh["udmin"].'>'.$vesh["udmin"].'</option>';
		$udmax = '';
		if ($vesh["udmax"]<200)
		for ($i=$vesh["udmax"];$i<=$vesh["udmax"]+20 and $i<200;$i++)
			 $udmax .= '<option value='.$i.'>'.($i).'</option>';
		else $udmax .= '<option value='.$vesh["udmax"].'>'.($vesh["udmax"]).'</option>';
		
		$radius = '';
		for ($i=$vesh["radius"];$i<=$vesh["radius"]+3 and $i<3;$i++)
		{
		 $radius .= '<option value='.$i.'>'.($i).'</option>';
		}
		$dop = '	<tr>
		<td width="244">Радиус поражения</td>
		<td width="25%" align="center"><select size="1" name="radius" class="items">'.$radius.'
		</select></td>
		<td width="25%">Удар</td>
		<td width="25%" align="center"><select size="1" name="udmin" class="items">'.$udmin.'
		</select>-<select size="1" name="udmax" class="items">'.$udmax.'
		</select></td>
		</tr>';
		}
		echo '<br><form method="POST" action="main.php?c=indap">
<table border="0" width="100%" style="border-style: solid; border-width: 1px; border-color: #777777" cellspacing="1">
	<tr>
		<td align="center" class=user width="100%">Мастер сборки 
		индивидуальных артефактов</td>
	</tr>
	<tr>
		<td bgcolor="#F0F0F0" class=ma align="center" width="100%">
		Пожалуйста, выберите предполагаемые параметры артефакта.</td>
	</tr>
	<tr>
		<td bgcolor="#F0F0F0" align="right" width="100%" class="ma">
<table border="1" width="100%" cellspacing="1"  bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0">
	<tr>
		<td width="244">Сила</td>
		<td width="25%" align="center"><select size="1" name="s1" class="items">'.$s1.'
		</select></td>
		<td width="25%">Сокрушение</td>
		<td width="25%" align="center"><select size="1" name="mf1" class="items">'.$mf1.'
		</select></td>
	</tr>
	<tr>
		<td width="244">Реакция</td>
		<td width="25%" align="center"><select size="1" name="s2" class="items">'.$s2.'
		</select></td>
		<td width="25%">Уловка</td>
		<td width="25%" align="center"><select size="1" name="mf2" class="items">'.$mf2.'
		</select></td>
	</tr>
	<tr>
		<td width="244">Удача</td>
		<td width="25%" align="center"><select size="1" name="s3" class="items">'.$s3.'
		</select></td>
		<td width="25%">Точность</td>
		<td width="25%" align="center"><select size="1" name="mf3" class="items">'.$mf3.'</select></td>
	</tr>
	<tr>
		<td width="244">Интеллект</td>
		<td width="25%" align="center"><select size="1" name="s5" class="items">'.$s5.'
		</select></td>
		<td width="25%">Стойкость</td>
		<td width="25%" align="center"><select size="1" name="mf4" class="items">'.$mf4.'</select></td>
	</tr>
	<tr>
		<td width="244">Сила воли</td>
		<td width="25%" align="center"><select size="1" name="s6" class="items">'.$s6.'
		</select></td>
		<td width="25%">Ярость</td>
		<td width="25%" align="center"><select size="1" name="mf5" class="items">'.$mf5.'</select></td>
	</tr>
	<tr>
		<td class="hp" width="244">HP</td>
		<td width="25%" align="center"><select size="1" name="hp" class="items">'.$hp.'
		</select></td>
		<td width="25%">Слотов для заклинаний или рун </td>
		<td width="25%" align="center">
		<select size="1" name="slots" class="items">'.$slts.'</select></td>
	</tr>
	<tr>
		<td class="ma" width="244">MA</td>
		<td width="25%" align="center"><select size="1" name="ma" class="items">'.$ma.'
		</select></td>
		<td width="25%">&nbsp;</td>
		<td width="25%" align="center"></td>
	</tr>
'.$dop.'
	<tr>
		<td class="ma" colspan="4" align="center">Гравировка <input name="gr" size="27" class="laar"></td>
	</tr>
	<tr>
		<td class="ma" colspan="4" align="center">
			<input type="hidden" value="'.$_POST["id"].'" name=idd>
		<input type="button" value="Назад" class="inv_but" onclick="location=\'main.php?c=individual\'"> |
		<input type="reset" value="Сброс" class="inv_but"> |
		<input type="submit" value="Готово" class="inv_but"></td>
	</tr>
</table>
		</td>
	</tr>
</table>
</form>';
		}elseif (empty($_GET["buy"]))
		{
			$lastid = sqla("SELECT MAX(id) FROM wp");
			$lastid = 1+$lastid[0];
			$vesh = sqla("SELECT * FROM wp WHERE id='".$_POST["idd"]."' and uidp='".$pers["uid"]."' and where_buy=1 and id_in_w='' and clan_sign=''");
			$type  = $vesh["stype"];
			$oldp = $vesh["dprice"];
			$oldid = $vesh["id"];
		$dprice = 30;
		$p = $_POST;
				foreach ($p as $key=>$value)
		if ($key<>'type' and $key<>'gr')
		{
			$value = abs($value);
			if ($key[0]=='s' and $value>15) $value=15;
			if ($key[0]=='m' and $value>450) $value=450;
			if ($key[0]=='h' and $value>450) $value=450;
			if ($key[0]=='k' and $value>450) $value=450;
			if ($key[0]=='u' and $value>450) $value=300;
			if ($key[0]=='r' and $value>3) $value=3;
			$p[$key]=$value;
		}
		
		$p["gr"] = substr($p["gr"],0,20);
		$p["gr"] = str_replace('"',"",$p["gr"]);
		$p["gr"] = str_replace("'","",$p["gr"]);
		if ($p["type"]<>"book") $p["slots"] = 0;
		if ($p["udmax"]<$p["udmin"])$p["udmax"]=$p["udmin"];
		$dprice += $p["s1"]*$p["s1"]/2 + $p["s1"];
		$dprice += $p["s2"]*$p["s2"]/2 + $p["s2"];
		$dprice += $p["s3"]*$p["s3"]/2 + $p["s3"];
		$dprice += $p["s5"]*$p["s5"]/2 + $p["s5"];
		$dprice += $p["s6"]*$p["s6"]/2 + $p["s6"];
		$dprice += $p["hp"]*sqrt($p["hp"])/7 + $p["hp"]/10;
		$dprice += $p["ma"]*sqrt($p["ma"])/7 + $p["hp"]/10;
		$dprice += $p["kb"]*sqrt($p["kb"])/6 + $p["kb"]/5;
		$dprice += $p["mf1"]*$p["mf1"]/210 + $p["mf1"]/15;
		$dprice += $p["mf2"]*$p["mf2"]/210 + $p["mf2"]/15;
		$dprice += $p["mf3"]*$p["mf3"]/210 + $p["mf3"]/15;
		$dprice += $p["mf4"]*$p["mf4"]/210 + $p["mf4"]/15;
		$dprice += $p["mf5"]*$p["mf5"]/210 + $p["mf5"]/15;
		$dprice += $p["udmin"]*$p["udmin"]/16 + $p["udmin"];
		$dprice += $p["udmax"]*$p["udmax"]/18 + $p["udmax"];
		if ($p["type"]=="book") $dprice *= 1+($p["slots"]-10)/8;
		else 
		{
			if ($p["slots"]>5)$p["slots"] = 5;
			$dprice *= 1+($p["slots"])/8;
		}
		if ($vesh["type"] == "orujie") 
		{
			if ($p["radius"]<1)$p["radius"]=1;
			$dprice *= $p["radius"];
		}
		
		$dprice *= 2/3;
		
		if ($p["gr"]=str_replace("'","",str_replace('"',"",$p["gr"]))) $vesh["name"].='['.$p["gr"].']';
		sql ("INSERT INTO `wp` (`id`,`name`,`s1`,`s2`,`s3`,`s5`,`s6`,`mf1`,`mf2`,`mf3`,`mf4`,`mf5`,`kb`,`hp`,`ma`,`udmin`,`udmax`,`describe`,`dprice`,`image`,`type`,`where_buy`,`stype`,`weight`,`max_durability`,durability,`index`,`slots`,`radius`,`tlevel`) VALUES 
('".$lastid."','".$vesh["name"]."','".$p["s1"]."','".$p["s2"]."','".$p["s3"]."','".$p["s5"]."','".$p["s6"]."','".$p["mf1"]."','".$p["mf2"]."','".$p["mf3"]."','".$p["mf4"]."','".$p["mf5"]."','".$p["kb"]."','".$p["hp"]."','".$p["ma"]."','".$p["udmin"]."','".$p["udmax"]."','Индивидуальный артефакт','".$dprice."','".$vesh["image"]."','".$vesh['type']."',1,'".$vesh["stype"]."','1','1','1','','".$p["slots"]."','".$p["radius"]."',8);
");
		$vesh = sqla("SELECT * FROM wp WHERE id='".$lastid."'");
		include("inc/inc/weapon2.php");
		echo "<script>".$text."</script>";
		echo "<center>";
		if ($pers["dmoney"]>=floor($dprice-$oldp))echo "<input type=button value='Усилить[".floor($dprice-$oldp)." y.e.]' class=inv_but onclick=\"location='main.php?c=indap&buy=".$lastid."&old=".$oldid."'\">";
		else echo "<input type=button value='Усилить[".abs(floor($dprice-$oldp))." y.e.]' class=inv_but DISABLED>";
		echo "<input type=button value=Отменить class=inv_but onclick=\"location='main.php?c=indap'\">";
		}else
		{
		$vesh = sqla("SELECT * FROM wp WHERE id='".$_GET["buy"]."'");
		$old = sqla("SELECT * FROM wp WHERE id='".$_GET["old"]."'");
		if ($old["image"]==$vesh["image"])
			{
			if ($old["dprice"] and $pers["dmoney"]>=abs($vesh["dprice"]-$old["dprice"]) and $vesh["uidp"]==0)
				{
				sql("UPDATE wp SET uidp=".$pers["uid"].",durability=1 WHERE id='".$_GET["buy"]."'");
				sql("DELETE FROM wp WHERE id='".$old["id"]."' and weared=0 and uidp=".$pers["uid"]."");
				set_vars("dmoney=dmoney-".abs($vesh["dprice"]-$old["dprice"])."",$pers["uid"]);
				echo "<font class=hp>Вы удачно усилили '".$vesh["name"]."' за ".abs($vesh["dprice"]-$old["dprice"])." y.e.</font>";
				include("inc/inc/weapon2.php");
				echo "<script>".$text."</script>";
				}
			}else echo "Hacking attempt!";
				
		}
?>