<script language=JavaScript src='js/ajax.js' type="text/javascript"></script>
<script language=JavaScript src='js/adm_magic.js' type="text/javascript"></script>
<a href=main.php class=bga>Назад</a>
<?
if ($_POST)
{
	$q = 'UPDATE blasts SET ';
	foreach($_POST as $key=>$value)
	{
		$key = str_replace(";","",$key);
		$q .= "`".$key."`='".$value."',";
	}
	$q = substr($q,0,strlen($q)-1)." WHERE id=".intval($_GET["edit"]);
	sql($q);
}
$n = Array ("name"=>"Название","tlevel"=>"Требования Уровень","ts6"=>"Требования сила воли","manacost"=>"Требует маны","tm1"=>"Требование Религия","tm2"=>"Требование Некромантия","tm3"=>"Требование магия стихий","tm4"=>"Требование Порядок","tm5"=>"Требование вызовы существ","type"=>"Тип(1 - религия, 2 - некромантия, 0 - нейтрал)","colldown"=>"Коллдаун в секундах","turn_colldown"=>"Коллдаун в ходах","price"=>"Цена LN","dprice"=>"Цена y.e.","udmin"=>"Мин.Удар.","udmax"=>"Макс.Удар.","targets"=>"Кол-во целей","where_buy"=>"Где выучить (0 - в храме, 2 - нигде)","aura_id"=>"id ауры которая будет наложена после удара(Не обязательно)","describe"=>"Описание");

	$z = sqla("SELECT * FROM blasts WHERE id=".intval($_GET["edit"]));
	echo "<form action=main.php?edit=".$z["id"]." method=post><input type=submit class=login style='width:100%' value=СОХРАНИТЬ><table width=80% border=0>";
	echo "<script>var image='".$z["image"]."';</script>";
	$i = 0;
	$bg = '';
	foreach ($z as $key=>$value)
	{
		if (is_string($key))
		{
		$i++;
		if ($i%2) $bg = '#DDDDDD'; else $bg = '#EEEEEE';
		if (!$n[$key]) $n[$key] = $key;
		if ($key<>"image")
			echo "<tr bgcolor=".$bg."><td class=timef>".$n[$key]."</td><td><input class=login type=text name='".$key."' value='".$value."'></td></tr>";
		else 
			echo "<tr bgcolor=".$bg."><td class=timef>Рисунок</td><td><img src=images/magic/".$z["image"].".gif onclick='change_img()' style='cursor:pointer' id=img><input class=login type=hidden name='".$key."' id='".$key."' value='".$value."'></td></tr>";
		}
	}
	echo "</table></form>";
	
?>