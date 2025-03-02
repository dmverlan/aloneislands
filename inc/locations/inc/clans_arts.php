<div class=return_win><input type="button" value="Назад" class="inv_but" onclick="location='main.php'" style="width:120">
<input type="button" value="Улучшить" class="inv_but" onclick="location='main.php?c=clap'" style=
"width:120"></div><?
$clan = sqla ("SELECT * FROM `clans` WHERE `sign`='".$pers['sign']."'");
if ($clan["treasury"]>=($clan["maxtreasury"]+30)) die("Казна переполнена...");
if (substr_count($pers["rank"],"<glav>"))
{
	if (empty($_POST) and empty($_GET["id"]))
	{
		echo '<br><form method="POST" action="main.php?c=clan">
<table border="0" width="100%" style="border-style: solid; border-width: 1px; border-color: #777777" cellspacing="1">
	<tr>
		<td align="center" class=user width="100%" colspan="2">Мастер сборки 
		клановых артефактов</td>
	</tr>
	<tr>
		<td bgcolor="#F0F0F0" class=ma align="center" width="100%" colspan="2">
		Пожалуйста, выберите предполагаемый класс артефакта и 
		тип.</td>
	</tr>
	<tr>
		<td bgcolor="#F0F0F0" align="right" width="50%">
<select size="1" name="type" class="return_win" style="width:200">
<option value="shle">Шлем</option>
<option value="kylo">Кулон</option>
<option value="noji">Нож</option>
<option value="book">Книга заклинаний</option>
<option value="mech">Меч</option>
<option value="topo">Топор</option>
<option value="drob">Дробящее</option>
<option value="shit">Щит</option>
<option value="poya">Пояс</option>
<option value="sapo">Сапоги</option>
<option value="naru">Наручи</option>
<option value="perc">Перчатки</option>
<option value="kolc">Кольцо</option>
<option value="bron">Броня</option>
</select>
		</td>
		<td bgcolor="#F0F0F0" width="50%"> 
		<select size="1" name="class" class="inv_but" style="width:80">
		<option selected value="minimal">Minimal</option>
		<option value="standart">Standart</option>
		<option value="business">Business</option>
		<option value="best">Best</option>
		</select> <input type="submit" value=" Ок " class="login">		</td>
	</tr>
</table>
</form>';
	}
	elseif (isset($_POST["class"]))
	{	
		$class = 1;
		if ($_POST["class"]=='minimal')$class=1;
		if ($_POST["class"]=='standart')$class=2;
		if ($_POST["class"]=='business')$class=3;
		if ($_POST["class"]=='best')$class=4;
		$stats = '';
		for ($i=0;$i<=9;$i++)
		{
			$st = $class*2-2+$i;
		 $stats .= '<option value='.$st.'>+'.$st.'</option>';
		}
		$hm = '';
		for ($i=0;$i<=9;$i++)
		{
			$st = ($i*$class)*10;
		 $hm .= '<option value='.$st.'>+'.$st.'</option>';
		}
		$mf = '';
		for ($i=0;$i<=7;$i++)
		{
			$st = ($i*$class)*15;
		 $mf .= '<option value='.$st.'>+'.$st.'</option>';
		}
			
		$slots = $class;
		if ($_POST["type"]=="book") $slots+=10; else $slots=0;
		$slts = '';
		for ($i=$slots-$class;$i<=$slots;$i++)
		{
		 $slts .= '<option value='.$i.'>'.$i.'</option>';
		}
			

		
		if ($_POST["type"]<>"noji" and $_POST["type"]<>"mech" and $_POST["type"]<>"topo" and $_POST["type"]<>"book" and $_POST["type"]<>"drob")
		{
		$uddmin = '';
		for ($i=0;$i<=10;$i++)
		{
		 $uddmin .= '<option value='.$i.'>'.$i.'</option>';
		}
		$uddmax = '';
		for ($i=0;$i<=10;$i++)
		{
		 $uddmax .= '<option value='.($i*2).'>'.($i*2).'</option>';
		}
			$dop = '	<tr>
		<td width="244">Класс брони</td>
		<td width="25%" align="center"><select size="1" name="kb" class="items">'.$hm.'
		</select></td>
		<td width="25%">Удар</td>
		<td width="25%" align="center"><select size="1" name="udmin" class="items">'.$uddmin.'
		</select>-<select size="1" name="udmax" class="items">'.$uddmax.'
		</select></td>
	</tr>';
		}else
		{
		$uddmin = '';
		for ($i=3;$i<=16;$i++)
		{
			if ($_POST["type"]<>"noji" and $_POST["type"]<>"book")
			$st = $i*$class*2;
			else 
			$st = $i*$class+5;
		 $uddmin .= '<option value='.$st.'>'.$st.'</option>';
		}
		$uddmax = '';
		for ($i=4;$i<=17;$i++)
		{
			if ($_POST["type"]<>"noji" and $_POST["type"]<>"book")
			$st = $i*$class*3;
			else 
			$st = $i*$class*2+5;
		 $uddmax .= '<option value='.$st.'>'.($st).'</option>';
		}
		$radius = '';
		for ($i=1;$i<=3;$i++)
		{
		 $radius .= '<option value='.$i.'>'.($i).'</option>';
		}
		$dop = '	<tr>
		<td width="244">Радиус поражения</td>
		<td width="25%" align="center"><select size="1" name="radius" class="items">'.$radius.'
		</select></td>
		<td width="25%">Удар</td>
		<td width="25%" align="center"><select size="1" name="udmin" class="items">'.$uddmin.'
		</select>-<select size="1" name="udmax" class="items">'.$uddmax.'
		</select></td>
		</tr>';
		}
		echo '<br><form method="POST" action="main.php?c=clan">
<table border="0" width="100%" style="border-style: solid; border-width: 1px; border-color: #777777" cellspacing="1" cellspadding=1>
	<tr>
		<td align="center" class=user width="100%">Мастер сборки 
		клановых артефактов</td>
	</tr>
	<tr>
		<td bgcolor="#F0F0F0" class=ma align="center" width="100%">
		Пожалуйста, выберите предполагаемые параметры артефакта.</td>
	</tr>
	<tr>
		<td bgcolor="#F0F0F0" align="right" width="100%" class="ma">
<table border="1" width="100%" cellspacing="0"  bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0">
	<tr>
		<td width="244">Сила</td>
		<td width="25%" align="center"><select size="1" name="s1" class="items">'.$stats.'
		</select></td>
		<td width="25%">Сокрушение</td>
		<td width="25%" align="center"><select size="1" name="mf1" class="items">'.$mf.'
		</select></td>
	</tr>
	<tr>
		<td width="244">Реакция</td>
		<td width="25%" align="center"><select size="1" name="s2" class="items">'.$stats.'
		</select></td>
		<td width="25%">Уловка</td>
		<td width="25%" align="center"><select size="1" name="mf2" class="items">'.$mf.'
		</select></td>
	</tr>
	<tr>
		<td width="244">Удача</td>
		<td width="25%" align="center"><select size="1" name="s3" class="items">'.$stats.'
		</select></td>
		<td width="25%">Точность</td>
		<td width="25%" align="center"><select size="1" name="mf3" class="items">'.$mf.'</select></td>
	</tr>
	<tr>
		<td width="244">Интеллект</td>
		<td width="25%" align="center"><select size="1" name="s5" class="items">'.$stats.'
		</select></td>
		<td width="25%">Стойкость</td>
		<td width="25%" align="center"><select size="1" name="mf4" class="items">'.$mf.'</select></td>
	</tr>
	<tr>
		<td width="244">Сила воли</td>
		<td width="25%" align="center"><select size="1" name="s6" class="items">'.$stats.'
		</select></td>
		<td width="25%">Ярость</td>
		<td width="25%" align="center"><select size="1" name="mf5" class="items">'.$mf.'</select></td>
	</tr>
	<tr>
		<td class="hp" width="244">HP</td>
		<td width="25%" align="center"><select size="1" name="hp" class="items">'.$hm.'
		</select></td>
		<td width="25%">Слотов для заклинаний или рун </td>
		<td width="25%" align="center">
		<select size="1" name="slots" class="items">'.$slts.'</select></td>
	</tr>
	<tr>
		<td class="ma" width="244">MA</td>
		<td width="25%" align="center"><select size="1" name="ma" class="items">'.$hm.'
		</select></td>
		<td width="25%">&nbsp;</td>
		<td width="25%" align="center">&nbsp;</td>
	</tr>
'.$dop.'
	<tr>
		<td class="ma" colspan="4" align="center">Название <input name="name" size="27" class="laar"></td>
	</tr>
	<tr>
		<td class="ma" colspan="4" align="center">
			<input type="hidden" value="'.$_POST["type"].'" name=type>
		<input type="button" value="Назад" class="inv_but" onclick="location=\'main.php?c=individual\'"> |
		<input type="reset" value="Сброс" class="inv_but"> |
		<input type="submit" value="Готово" class="inv_but"></td>
	</tr>
</table>
		</td>
	</tr>
</table>
</form>';
	}elseif ($_POST["name"]=str_replace("'","",str_replace('"',"",$_POST["name"])))
	{
			$lastid = sqla("SELECT MAX(id) FROM wp");
			$lastid = 1+$lastid[0];
		$dprice = 30;
		$p = $_POST;
		
		foreach ($p as $key=>$value)
		if ($key<>'type' and $key<>'name')
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
		$p["name"] = substr($p["name"],0,20);
		$p["name"] = str_replace('"',"",$p["name"]);
		$p["name"] = str_replace("'","",$p["name"]);
		
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
		else $dprice *= 1+($p["slots"])/8;
		$type = "orujie";
		if ($p["type"]=="shle") $type = "shlem";
		if ($p["type"]=="naru") $type = "naruchi";
		if ($p["type"]=="perc") $type = "perchatki";
		if ($p["type"]=="kolc") $type = "kolco";
		if ($p["type"]=="kylo") $type = "ojerelie";
		if ($p["type"]=="sapo") $type = "sapogi";
		if ($p["type"]=="poya") $type = "poyas";
		if ($p["type"]=="bron") $type = "bronya";
		if ($type == "orujie") 
		{
			if ($p["radius"]<1)$p["radius"]=1;
			$dprice *= $p["radius"];
		}
		$img = "ind_art/".$p["type"]."_".rand(1,1);
		sql ("INSERT INTO `wp` (`id`,`name`,`s1`,`s2`,`s3`,`s5`,`s6`,`mf1`,`mf2`,`mf3`,`mf4`,`mf5`,`kb`,`hp`,`ma`,`udmin`,`udmax`,`describe`,`dprice`,`image`,`type`,`where_buy`,`stype`,`weight`,`max_durability`,durability,`index`,`slots`,`radius`) VALUES 
('".$lastid."','".$p["name"]."','".$p["s1"]."','".$p["s2"]."','".$p["s3"]."','".$p["s5"]."','".$p["s6"]."','".$p["mf1"]."','".$p["mf2"]."','".$p["mf3"]."','".$p["mf4"]."','".$p["mf5"]."','".$p["kb"]."','".$p["hp"]."','".$p["ma"]."','".$p["udmin"]."','".$p["udmax"]."','Клан артефакт','".$dprice."','".$img."','".$type."',1,'".$p["type"]."','1','1','1','','".$p["slots"]."','".$p["radius"]."');
");
		$vesh = sqla("SELECT * FROM wp WHERE id='".$lastid."'");
		include("inc/inc/weapon2.php");
		echo "<script>".$text."</script>";
		echo "<center>";
		$clan = sqla ("SELECT * FROM `clans` WHERE `sign`='".$pers['sign']."'");
		if ($clan["dmoney"]>=$dprice*3)echo '<select size="1" id="kkk" class="items" onchange=prch('.$dprice.')>
		<option selected value="3">3</option>
		<option value="4">4</option>
		<option value="5">5</option>
		<option value="6">6</option>
		<option value="7">7</option>
		<option value="8">8</option>
		</select><input type=button value=Купить class=inv_but onclick="sbbmt('.$lastid.')"><div id=price class=hp>'.floor($dprice*3).' y.e.</div>';
		echo "<input type=button value=Отменить class=inv_but onclick=\"location='main.php?c=clan'\">";
		echo "</center>";
	}elseif (@$_GET["id"])
	{
		$vesh = sqla("SELECT * FROM wp WHERE id='".$_GET["id"]."'");
		$k = intval($_GET["k"]);
		$price = $vesh["dprice"]*$k;
		$clan = sqla ("SELECT * FROM `clans` WHERE `sign`='".$pers['sign']."'");
		if ($vesh["dprice"] and $clan["dmoney"]>=$price and $vesh["uidp"]==0 and $k>2 and $k<9)
		{
		sql("UPDATE clans SET dmoney=dmoney-".$price." WHERE sign='".$pers["sign"]."'");
		for ($i=0;$i<$k;$i++) 
		{
			$v = insert_wp_new($pers["uid"],"id='".$_GET["id"]."'");
			sql("UPDATE wp SET uidp=".$pers["uid"].",durability=1,clan_sign='".$pers["sign"]."',clan_name='".$clan["name"]."',present='".$pers["user"]."' WHERE id='".$v["id"]."'");
		}
		echo "<center class=return_win>Вы удачно купили '".$vesh["name"]."' ".$k."шт. за ".$price." y.e.</center>";
		include("inc/inc/weapon2.php");
		echo "<script>".$text."</script>";
		}elseif ($clan["dmoney"]<$price) echo "<center class=return_win>Не хватает у.е. на счету клана</center>";
		else echo "Ошибка мастера. (Возможно неверный параметр)";
	} 
}
?>
<script>
function prch(pr)
{
	var k = parseInt(document.getElementById('kkk').value);
	var p = parseInt(k*pr);
	document.getElementById('price').innerHTML = p+'y.e.';
}
function sbbmt(id)
{
	var k = parseInt(document.getElementById('kkk').value);
	location = 'main.php?c=clan&k='+k+'&id='+id;
}
</script>