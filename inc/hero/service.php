<?
if (@$_POST["dk"])
{
$dk = abs($_POST["dk"]);
if ($dk<=$pers["dmoney"]){
sql("UPDATE users SET money=money+".($dk*$ye_to_ln).", dmoney=dmoney-".$dk." WHERE uid='".$pers["uid"]."'");
$pers["money"]+=$dk*$ye_to_ln;
$pers["dmoney"]-=$dk;
}
echo "<script>location='main.php';</script>";
}
if (@$_POST["dmoney"])
{
$dk = abs($_POST["dmoney"]);
if ($dk<=$pers["dmoney"]){
sql("UPDATE users SET dmoney=dmoney-".$dk." WHERE uid='".$pers["uid"]."'");
sql("UPDATE clans SET dmoney=dmoney+".$dk." WHERE sign='".$pers["sign"]."'");
$pers["dmoney"]-=$dk;
echo "<script>location='main.php';</script>";
}
}
if (@$_GET["gopers"]=="service")
{
	if ($_GET["do"]=="healthy" and $pers["dmoney"]>=1)
	{
		if($pers["level"]<=15)
			$pers["dmoney"]-=0.25;
		else
			$pers["dmoney"]--;
		$pers["chp"]=$pers["hp"];
		$pers["cma"]=$pers["ma"];
		sql("UPDATE users SET chp=hp,cma=ma,dmoney=".$pers["dmoney"]." WHERE uid=".$pers["uid"]);
		echo "<script>location='main.php';</script>";
	}
	if ($_GET["do"]=="notravm" and $pers["dmoney"]>=1)
	{
		$pers["dmoney"]--;
			sql("UPDATE p_auras SET esttime=0 
			WHERE uid=".$pers["uid"]." and special>2 and special<6 and esttime>".tme().";");
			sql("UPDATE users SET dmoney=dmoney-1 WHERE uid=".$pers["uid"]);
			echo "<script>location='main.php';</script>";
	}
	if ($_GET["do"]=="zeroing" and $pers["dmoney"]>=5)
	{
		$pers["dmoney"]-=5;
		$pers["zeroing"]++;
		sql("UPDATE users SET zeroing=zeroing+1,dmoney=dmoney-5 WHERE uid=".$pers["uid"]);
		echo "<script>location='main.php';</script>";
	}	
	if ($_GET["do"]=="szeroing" and $pers["dmoney"]>=20)
	{
		$pers["dmoney"]-=20;
		$pers["zeroing"]++;
		sql("UPDATE users SET skill_zeroing=skill_zeroing+1,dmoney=dmoney-20 WHERE uid=".$pers["uid"]);
		echo "<script>location='main.php';</script>";
	}
	if ($_GET["do"]=="fz" and $pers["dmoney"]>=10)
	{
		$pers["dmoney"]-=10;
		sql("UPDATE users SET dmoney=dmoney-10,action=-11 WHERE uid=".$pers["uid"]);
		echo "<script>location='main.php';</script>";
	}
	if ($_GET["do"]=="obr" and $pers["dmoney"]>=1)
	{
		$pers["dmoney"]--;
		$pers["obr"]=0;
		sql("UPDATE users SET obr=0,dmoney=dmoney-1 WHERE uid=".$pers["uid"]);
		echo "<script>location='main.php?gopers=options';</script>";
	}
	if ($_GET["do"]=="tire" and $pers["dmoney"]>=1)
	{
		$pers["dmoney"]--;
		$pers["tire"]=0;
		sql("UPDATE users SET tire=0,dmoney=dmoney-1 WHERE uid=".$pers["uid"]);
		echo "<script>location='main.php?gopers=options';</script>";
	}
	if ($_GET["do"]=="prg" and $pers["dmoney"]>=5)
	{
		$pers["dmoney"]-=5;
		sql("UPDATE users SET coins=coins+5,dmoney=dmoney-5 WHERE uid=".$pers["uid"]);
		echo "<script>location='main.php';</script>";
	}
	if ($_GET["do"]=="bot" and $pers["dmoney"]>=0.05)
	{
		$pers["dmoney"]-=0.05;
		sql("UPDATE users SET lb_attack=0,dmoney=dmoney-0.05 WHERE uid=".$pers["uid"]);
		echo "<script>location='main.php';</script>";
	}	
	if ($_GET["do"]=="bot3" and $pers["dmoney"]>=0.1)
	{
		$pers["dmoney"]-=0.1;
		sql("UPDATE users SET dmoney=dmoney-0.1 WHERE uid=".$pers["uid"]);
		$SPECIAL_pers = $pers;
		$SPECIAL_count = 3;
		include("bots/attack.php");
	}
	if ($_GET["do"]=="bot6" and $pers["dmoney"]>=0.2)
	{
		$pers["dmoney"]-=0.2;
		sql("UPDATE users SET dmoney=dmoney-0.2 WHERE uid=".$pers["uid"]);
		$SPECIAL_pers = $pers;
		$SPECIAL_count = 6;
		include("bots/attack.php");
	}
}
if (@$_POST["user"] and $pers["dmoney"]>=100)
{
$err=0;
if (strlen($_POST["user"])<3 or strlen($_POST["user"])>21) {print "Некорректный Логин.(Величина)<hr>"; $err=1;}
if (strpos(" ".$_POST["user"],"~")>0 or
	strpos(" ".$_POST["user"],"!")>0 or
	strpos(" ".$_POST["user"],"@")>0 or
	strpos(" ".$_POST["user"],"#")>0 or
	strpos(" ".$_POST["user"],"$")>0 or
	strpos(" ".$_POST["user"],"%")>0 or
	strpos(" ".$_POST["user"],"^")>0 or
	strpos(" ".$_POST["user"],"*")>0 or
	strpos(" ".$_POST["user"],"(")>0 or
	strpos(" ".$_POST["user"],")")>0 or
	strpos(" ".$_POST["user"],"№")>0 or
	strpos(" ".$_POST["user"],";")>0 or
	strpos(" ".$_POST["user"],"?")>0 or
	strpos(" ".$_POST["user"],":")>0 or
	strpos(" ".$_POST["user"],"`")>0 or
	strpos(" ".$_POST["user"],"'")>0 or
	strpos(" ".$_POST["user"],"\"")>0
	) {print "Некорректный Логин.(Нельзя использовать специальные символы в нике)<hr>"; $err=1;}
if ($err==0)	
{
	sql("UPDATE users SET smuser=LOWER('".$_POST["user"]."'),user='".$_POST["user"]."',dmoney=dmoney-100 WHERE uid=".$pers["uid"]);
	$_SESSION["user"]=$_POST["user"];
}
	echo "<script>location='main.php';</script>";
}

echo "<center><a href='main.php?gopers=sms' class=Button>SMS-Сервис</a> для пополнения счёта.</center>";

	$req = sqlr("SELECT uid FROM avatar_request WHERE uid=".$pers["uid"]);
	if (@$_FILES and !$req and $pers["dmoney"]>=30)
	{
		if ($_FILES['obr']['type']=='image/gif')
		{
			$im = @imagecreatefromgif ($_FILES['obr']['tmp_name']);
			if ($im) 
			{
				$filename = $_FILES['obr']['tmp_name'];
				list($width, $height) = getimagesize($filename);
				$newwidth = 115;
				$newheight = 255;
				$thumb = imagecreatetruecolor($newwidth, $newheight);
				imagecopyresized($thumb, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
				imagegif($thumb,"images/tmp/ava_".$pers["uid"].".gif");
				sql("INSERT INTO `avatar_request` (`uid`)VALUES ('".$pers["uid"]."');");
				set_vars("dmoney=dmoney-30",$pers["uid"]);
				$req = 1;
			}
		}
		if (eregi('image/?jpeg',$_FILES['obr']['type']))
		{
			$im = @imagecreatefromjpeg ($_FILES['obr']['tmp_name']);
			if ($im) 
			{
				$filename = $_FILES['obr']['tmp_name'];
				list($width, $height) = getimagesize($filename);
				$newwidth = 115;
				$newheight = 255;
				$thumb = imagecreatetruecolor($newwidth, $newheight);
				imagecopyresized($thumb, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
				imagegif($thumb,"images/tmp/ava_".$pers["uid"].".gif");
				sql("INSERT INTO `avatar_request` (`uid`)VALUES ('".$pers["uid"]."');");
				set_vars("dmoney=dmoney-30",$pers["uid"]);
				$req = 1;
			}
		}
	}
	echo "<table border=0 class=but2 width=100%>";
	if(!$req)
	{
	echo '<tr>
		<td class=but><b class=ma>Загрузить образ<br>[30 БР.]</b><br><i class=gray>Если образ не будет одобрен - деньги вернутся назад.</i></td>
		<td class=but>
		<form enctype="multipart/form-data" method=post><input type="hidden" name="MAX_FILE_SIZE" value="5000000" /><input class="login" name="obr" type=file>
		';
	if($pers["dmoney"]>=30)
		echo '<input type=submit class=login value="Загрузить...">';
	else	
		echo '<input type=submit class=login value="Загрузить..." DISABLED>';
		echo '</form></td>
	</tr>';
	}
	else
	{
		echo '<tr>
		<td class=but colspan=3 align=center valign=center>Ожидает одобрения:<br> <img src="images/tmp/ava_'.$pers["uid"].'.gif" height=100></td>
		</tr>';
	}
	echo "</table>";
	
echo '<form method="POST" action=main.php?gopers=service>
<table border="0" width="100%" cellspacing="5" class="but">
	<tr>
		<td class=timef>Обмен валюты: (1 Бр. = '.$ye_to_ln.' LN)</td>
		<td><input type="text" name="dk" size="10" class=login> Бр.
		<input type="submit" value="Обменять" class="login"></td>
	</tr>
	<tr>
		<td class=timef>Смена ника (100 Бр.)</td>
		<td><input type="text" name="user" size="26" class=login><input type="submit" value="Сменить" class="login"></td>
	</tr>';
	
	if($pers["level"]<=15)
	echo '<tr>
		<td colspan=3><a href="javascript:{if(confirm(\'Вы уверены?\')) location=\'main.php?gopers=service&do=healthy\';}" class=bg>Полное 
		восстановление HP и MA (0.25 Бр.)</a></td>
	</tr>';
	else
	echo '<tr>
		<td colspan=3><a href="javascript:{if(confirm(\'Вы уверены?\')) location=\'main.php?gopers=service&do=healthy\';}" class=bg>Полное 
		восстановление HP и MA (1 Бр.)</a></td>
	</tr>';
	echo '<tr>
		<td colspan=3><a href="javascript:{if(confirm(\'Вы уверены?\')) location=\'main.php?gopers=service&do=notravm\';}" class=bg>Полное 
		излечение всех травм (1 Бр.)</a></td>
	</tr>
	<tr>
		<td colspan=3><a href="javascript:{if(confirm(\'Вы уверены?\')) location=\'main.php?gopers=service&do=zeroing\'};" class=bg>Обнуление 
		(5 Бр.)</a></td>
	</tr>
	<tr>
		<td colspan=3><a href="javascript:{if(confirm(\'Вы уверены?\')) location=\'main.php?gopers=service&do=fz\';}" class=bg>Полное обнуление 
		(10 Бр.)</a></td>
	</tr>
	<tr>
		<td colspan=3><a href="javascript:{if(confirm(\'Вы уверены?\')) location=\'main.php?gopers=service&do=szeroing\'};" class=bg>Обнуление мирного умения
		(20 Бр.)</a></td>
	</tr>
	<tr>
		<td colspan=3><a href="javascript:{if(confirm(\'Вы уверены?\')) location=\'main.php?gopers=service&do=obr\';}" class=bg>Обнулить образ 
		(1 Бр.)</a></td>
	</tr>
	<tr>
		<td colspan=3><a href="javascript:{if(confirm(\'Вы уверены?\')) location=\'main.php?gopers=service&do=tire\';}" class=bg>Снять всю усталость 
		(1 Бр.)</a></td>
	</tr>
	<tr>
		<td colspan=3><a href="javascript:{if(confirm(\'Вы уверены?\')) location=\'main.php?gopers=service&do=prg\';}" class=bg>Купить 5 пергаментов 
		(5 Бр.)</a></td>
	</tr>
	<tr>
		<td colspan=3><a href="main.php?gopers=service&do=bot" class=bg>Приманить существо (0.05 Бр.)</a></td>
	</tr>
	<tr>
		<td colspan=3><a href="main.php?gopers=service&do=bot3" class=bg>Приманить 3 существа (0.1 Бр.)</a></td>
	</tr>
	<tr>
		<td colspan=3><a href="main.php?gopers=service&do=bot6" class=bg>Приманить 6 существ (0.2 Бр.)</a></td>
	</tr>
';
	

	if (@$pers["sign"]<>'none') 
	echo "<tr><td class=timef>Вложить валюту в клан</td><td><input type='text' name='dmoney' size='6' class=login><input type='submit' value='Вложить' class='login'></td></tr>";
echo'</table></form>';

echo "<i>Бриллианты можно получить, пожертвовав немного реальных денег персонажам со значком<img src=images/signs/diler.gif> после ника.</i>";
if($pers["uid"]==5)echo "<br><i>Или вы можете воспользоваться <a href='main.php?gopers=sms' class=timef>SMS-Сервисом</a></i>";

echo "<hr><center class=inv>Ещё раз предупреждаем, что администрация ни в коем случае не требует с вас каких-либо выплат. AloneIslands.Ru была, есть и будет бесплатной онлайн игрой. Любые пожертвования на счёт своего персонажа, ваше личное решение.</center>";
?>