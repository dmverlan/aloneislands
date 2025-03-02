<?
if($_POST["pupil"])
{
	$pname = $_POST["pupil"];
	$p = sqla("SELECT uid,user FROM users WHERE user = '".$pname."' 
	and level<5 and instructor=0");
	if($p)
	{
		say_to_chat ("^","Персонаж <b>".$pers["user"]."</b>[".$pers["level"]."] предлагает вам стать его учеником. За это вы получите 10 LN и +50% опыта за бои.","1",$p["user"],"*");
		
		sql ("INSERT INTO `salings` (`id`,`idw`,`uidp`,`price`, `uidwho`) VALUES (0,0,'".$pers["uid"]."',0,".$p["uid"].") ");
		$idf =  mysql_insert_id($main_conn);
		$m = "saling#".$idf;
		say_to_chat ('s',$m,1,$p["user"],'*',0);
		
		say_to_chat ("^","Заявка удачно подана.","1",$pers["user"],"*");
	}
}

$pupil = sqla("SELECT * FROM users WHERE instructor = ".$pers["uid"]);


if(@$_GET["deny"])
{
	sql("UPDATE users SET instructor=0 WHERE instructor = ".$pers["uid"]);
	say_to_chat ('^',"Персонаж <b>".$pers["user"]."</b>[".$pers["level"]."] отказался от обучения.",1,$pupil["user"],'*',0);
	$pupil = sqla("SELECT * FROM users WHERE instructor = ".$pers["uid"]);
}

if($pupil)
{
	echo "<center>";
	echo "<center style='width:90%' class=combofight>";
	echo "<i class=gray>";
	echo "У вас есть ученик <b>[".$pupil["level"]."] уровня</b>";
	echo "</i>";
	echo "<div class=but><b class=user>".$pupil["user"]."</b> <b class=lvl>[".$pupil["level"]."]</b> <img src=images/i.gif onclick=\"javascript:window.open('info.php?p=".$pupil["user"]."','_blank')\" style='cursor:pointer' height=16> <input type=button class=login value='Отказаться от обучения' onclick=\"location = 'main.php?gopers=student&deny=1'\"></div>";
	echo "</center>";
	echo "</center>";
}
else
{
	echo "<center>";
	echo "<center style='width:90%' class=combofight>";
	echo "<i class=gray>";
	echo "Вы никого не обучаете...";
	echo "</i><br>";
	echo "<form method=post action=main.php?gopers=student>";
	echo "<div class=but><input class=login type=text name=pupil id=pupil value=''><input type=submit class=login value='Предложить стать наставником'></div>";
	echo "</form>";
	echo "</center>";
	echo "</center>";
	echo "<p><i class=gray style='text-align:left;'><b class=ma>Справка:</b> Предложить стать наставником можно любому персонажу ниже 5ого уровня. Предложение бесплатно, однако, если персонаж примет его, то с вашего счёта спишется <b>20 LN</b>, а ученик получит <b>10 LN</b> и <b>+50% опыта за бои</b> в награду. Обучать можно лишь одного персонажа. Если ваш ученик достигнет 5ого уровня вы получите в награду <b>200 LN</b> и <b>10 пергаментов</b>!</i></p>";
	echo "<script>ActionFormUse = 'pupil';</script>";
}

if($pers["good_pupils_count"]) 
	echo "<center class=combofight>Вы уже обучили <b>".$pers["good_pupils_count"]."</b> персонажей.<br><i class=ma>За каждого 5ого вы будете дополнительно получать по 100 LN</i></center>";
?>