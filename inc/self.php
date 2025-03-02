<?
	#Photo upload
	if (@$_FILES)
	{
		if ($_FILES['photofile']['type']=='image/gif')
		{
			$im = @imagecreatefromgif ($_FILES['photofile']['tmp_name']);
			if ($im) 
			{
				$filename = $_FILES['photofile']['tmp_name'];
				list($width, $height) = getimagesize($filename);
				$newwidth = 400;
				if($width < $newwidth) $newwidth = $width;
				$percent = $newwidth/$width;
				$newheight = $height * $percent;
				$thumb = imagecreatetruecolor($newwidth, $newheight);
				imagecopyresized($thumb, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
				imagejpeg($thumb,"images/photos/".$pers["uid"]."_".(++$pers["photo"]).".jpg",100);
				set_vars("photo=photo+1",UID);
			}
		}
		if (eregi('image/?jpeg',$_FILES['photofile']['type']))
		{
			$im = @imagecreatefromjpeg ($_FILES['photofile']['tmp_name']);
			if ($im) 
			{
				$filename = $_FILES['photofile']['tmp_name'];
				list($width, $height) = getimagesize($filename);
				$newwidth = 400;
				if($width < $newwidth) $newwidth = $width;
				$percent = $newwidth/$width;
				$newheight = $height * $percent;
				$thumb = imagecreatetruecolor($newwidth, $newheight);
				imagecopyresized($thumb, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
				imagejpeg($thumb,"images/photos/".$pers["uid"]."_".(++$pers["photo"]).".jpg",100);
				set_vars("photo=photo+1",UID);
			}
		}
	}
	
	if (@$_POST["email"])
	{
		$pers["email"] = $_POST["email"];
		$pers["name"] = $_POST["name"];
		$pers["city"] = $_POST["city"];
		$pers["country"] = $_POST["country"];
		$pers["icq"] = intval($_POST["icq"]);
		$pers["vkid"] = intval($_POST["vkid"]);
		if($pers["level"]<2)
		{
			$from = explode(".",$_POST["dr"]);
			$from = mktime(0,0,0,$from[1],$from[0],$from[2]);
			$pers["DR_congratulate"] = mktime(0, 0, 0, $from[1], $from[0],date("Y"))+86400*7;
			if(date("d.m.Y",$from)!==false)
			{
				set_vars("dr='".date("d.m.Y",$from)."',DR_congratulate=".$pers["DR_congratulate"],UID);
				$pers["dr"] = date("d.m.Y",$from);
				
			}
		}
		set_vars("
		email='".$pers["email"]."',
		name='".$pers["name"]."',
		city='".$pers["city"]."',
		country='".$pers["country"]."',
		vkid='".$pers["vkid"]."',
		icq='".$pers["icq"]."'",UID);
	}
	
	if (@$_POST["about"])
	{
		$chars["about"] = $_POST["about"];
		$chars["about"] = str_replace("


","
",$chars["about"]);
		if (!$pers["diler"]) $chars["about"] = substr($chars["about"],0,900);
		$chars["about"] = str_replace("\\","",$chars["about"]);
		sql("UPDATE chars SET about='".$chars["about"]."' WHERE uid=".UID);
	}
?>
<SCRIPT src="js/self.js?1"></SCRIPT>
<center class=but>
<table style="width: 100%" class=but>
	<tr>
		<td valign="top" style="width: 200px">
		<table style="width: 100%">
			<tr>
				<td class="title">ФОТОГРАФИЯ</td>
			</tr>
			<tr>
				<td align=center class=loc>
<?
	if ($pers["photo"])
	 echo "<img src='images/photos/".$pers["uid"]."_".$pers["photo"].".jpg'>";
	else 
	 echo "<img src=images/icons/image.png><br/><i class=timef>Нет фотографии</i><br/>";
?>
				</td>
			</tr>
			<tr>
				<td>
<?
	if ($pers["photo"])
	 echo "<a class=bg href='javascript:ch_photo()'>Сменить фотографию</a>";
	else 
	 echo "<a class=bg href='javascript:ch_photo()'>Загрузить фотографию</a>";
?>
				</td>
			</tr>
		</table>
		</td>
		<td valign="top">
		<table style="width: 100%;height:100%">
			<tr>
				<td class="title">О ВАС<img src=images/icons/eyeChat.png height=16></td>
			</tr>
			<tr>
				<td align=center>
				<form method=post>
				<table border="1" width="400" cellspacing="0" cellpadding="0" bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF>
<?
	echo "<tr>";
	echo "<td class=user width=150>E-Mail</td>";
	echo "<td><input class=login name=email value='".$pers["email"]."' style='width:100%'></td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td class=user width=150>Имя</td>";
	echo "<td><input class=login name=name value='".$pers["name"]."' style='width:100%'></td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td class=user width=150>Город</td>";
	echo "<td><input class=login name=city value='".$pers["city"]."' style='width:100%'></td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td class=user width=150>Страна</td>";
	echo "<td><input class=login name=country value='".$pers["country"]."' style='width:100%'></td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td class=user width=150>ICQ</td>";
	echo "<td><input class=login name=icq value='".$pers["icq"]."' style='width:100%'></td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td class=user width=150>ID vkontakte.ru</td>";
	echo "<td><input class=login name=vkid value='".$pers["vkid"]."' style='width:100%'></td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td class=user width=150>Дата рождения</td>";
	if($pers["level"]<2)
		echo "<td><input class=but name=dr value='".$pers["dr"]."' size=10></td>";
	else
		echo "<td><input class=but name=dr value='".$pers["dr"]."' size=10 DISABLED></td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td class=user width=150>Дата регистрации</td>";
	echo "<td class=timef>".$pers["ds"]."</td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td class=user width=150>Время онлайн</td>";
	echo "<td class=timef>".tp($curtimeonline=(time()-$pers["lastvisits"]))."</td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td class=user width=150>Проведено в игре</td>";
	echo "<td class=timef>".tp($pers["timeonline"]+$curtimeonline)."</td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td colspan=2 align=center><input type=submit value='Применить' class=login style='width:100%'></td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td colspan=2 align=center><a href=main.php?go=friends class=bg>Списки друзей</a></td>";
	echo "</tr>";
?>				
				</form>
				</table>
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" valign="top">
			<table style="width: 100%">
			<tr>
				<td class="title">Краткая информация о вас <img src=images/icons/eyeNotes.png height=16></td>
			</tr>
			<tr>
				<td align=center>
				<form method=post>
<?
	if (!sqlr("SELECT COUNT(*) FROM chars WHERE uid='".$pers["uid"]."'"))
	{
		sql("INSERT INTO `chars` (`uid`) VALUES ('".$pers["uid"]."');");
	}
	$chars = sqla("SELECT about FROM chars WHERE uid=".$pers["uid"]);
	echo "<textarea style='width:100%' rows=8 class=inv name=about>";
	echo $chars["about"];
	echo "</textarea>";
	echo "<br>";
	echo "<input type=submit value='Применить' class=login style='width:100%'>";
?>
				</form>
				</td>
			</tr>
			</table>
		</td>
	</tr>
</table></center>
