<script><?
	if(empty($_GET["gopers"]))
		echo "var _SHOW_EXP = 1;"; 
	else
		echo "var _SHOW_EXP = 0;";
?></script>
<div id=inf_from_php style='display:none;'>
<?
		$level = sqla("SELECT * FROM `exp` WHERE `level`=".($pers["level"]+1));
		$level1 = sqla("SELECT * FROM `exp` WHERE `level`=".($pers["level"]));

if ($_RETURN)echo "<br><center class=fightlong style='width:60%'><b class=user>".$_RETURN."</b></center>";



if(@$_GET["gopers"]=='battle' and $pers["free_stats"]<5 and $pers["level"]<3 and $pers["chp"]/$pers["hp"]>0.75)
{
	$pers["waiter"] = tme() + 10;
	echo "<div class=lightblock style='width:70%;'>Сейчас мы подыщем для вас подходящего соперника...</div>";
	
	$bts = sqlr("SELECT uid FROM users WHERE level=".($pers["level"])." and block<>'' and rank_i>5 and silence = 0 and lb_attack<".tme()." and s6=1 and s5=1 LIMIT 0,1");
	include("combat_apps/_begin_ghost_fight.php");
	begin_ghost_fight($bts,$pers["user"],"Битва на арене",50,120,1,0);
}

	if ($pers["priveleged"]) echo '<a class=bg href=main.php?go=administration>Возможности министра['.$priv["status"].']</a>';
	
	if ($t<$pers["waiter"]) {echo "<center class=items><b>".$pers["user"]."</b></center><hr><div id=waiter class=items align=center></div>";}
	
$ye_to_ln = 50;


if (@$_POST["newchatcolor"] or @$_POST["selectob"]<>"") {
if (@$_POST["selectob"]<>0 and $pers["obr"]==0 and $_POST["selectob"]<12) 
 {
	sql ("UPDATE `users` SET `obr`='".$_POST["selectob"]."' WHERE `uid` = ".UID);
	$pers["obr"] = $_POST["selectob"];
 }
if (@$_POST["newchatcolor"])
 {
	$pers["options"] = $_POST["inv"]."|".$_POST["zak"]."|".$_POST["sort"]."|".$_POST["chat"]."|".$_POST["dur"]."|".$_POST["newchatcolor"]."|".intval($_POST["design"])."|".$_POST["fchat"];
	$options = explode("|",$pers["options"]);
	sql ("UPDATE `users` SET `options`='".$pers["options"]."' WHERE `uid` = ".UID);
	echo "<script> top.location = 'game.php?rand=".microtime()."';</script>";
 }
}

if (@$_POST["pass"] and md5($_POST["pass"])==$pers["pass"] and $_POST["newpass"]==$_POST["newpass2"] and $pers["noaction"]<time()) 
{
	set_vars("`pass` = '".(md5($_POST["newpass"]))."',noaction=UNIX_TIMESTAMP()+86400");
	$pers["noaction"] = time()+86400;
	echo "<font class=red> Ваш Пароль изменён! <br></font>"; 
	sql("INSERT INTO `puns` ( `uid` , `date` , `who` , `type` , `reason` , `duration` ) 
	VALUES (
	".$pers["uid"].", ".time().", '".show_ip()."', '7', 'главный пароль', '0'
	);");
	$_GET["gopers"]="parol";	
}elseif (@$_POST["pass"] and md5($_POST["pass"])<>$_pers["pass"]and $pers["noaction"]<time()) echo  "Ваш пароль<font class=red> НЕ </font> изменён. Неверный старый пароль.<br>";
elseif (@$_POST["pass"] and $_POST["newpass"]<>$_POST["newpass2"]and $pers["noaction"]<time()) echo  "Ваш пароль<font class=red> НЕ </font> изменён. Пароли не совпадают.<br>";

if (@$_POST["snewpass"] and @$_POST["snewpass"]==$_POST["snewpass2"]and $pers["noaction"]<time()) 
{
	set_vars("`second_pass` = '".(md5($_POST["snewpass"]))."',noaction=UNIX_TIMESTAMP()+86400");
	$pers["noaction"] = time()+86400;
	echo "<font class=red> Ваш ВТОРОЙ Пароль изменён! <br></font>"; 
	$_GET["gopers"]="parol";	
	sql("INSERT INTO `puns` ( `uid` , `date` , `who` , `type` , `reason` , `duration` ) 
	VALUES (
	".$pers["uid"].", ".time().", '".show_ip()."', '7', 'второй пароль', '0'
	);");
}elseif (@$_POST["snewpass"]and $pers["noaction"]<tme()) echo  "Ваш пароль<font class=red> НЕ </font> изменён. Пароли не совпадают.<br>";

if (@$_POST["set_flash"]==1 and $pers["noaction"]<tme())
{
	$pers["flash_pass"] = rand(10000,99999);
	$_GET["gopers"]="parol";	
	echo "<div class=return_win>ВНИМАНИЕ!!!<br>Запишите ваш цифровой пароль, и запомните его. При следущем заходе, игра попросит вас ввести его, и если вы его не сможете ввести - вы не сможете управлять персонажем.</div><font class=bnick color=#990000>ТЕКУЩИЙ ПАРОЛЬ: <b>".$pers["flash_pass"]."</b></font><br>";
}elseif (@$_POST["set_flash"]==2 and $pers["noaction"]<time())
{
	$pers["flash_pass"] = 0;
	$_GET["gopers"]="parol";	
}
if (@$_POST["set_flash"]==2 or @$_POST["set_flash"]==1 and $pers["noaction"]<time())
{
	sql("INSERT INTO `puns` ( `uid` , `date` , `who` , `type` , `reason` , `duration` ) 
	VALUES (
	".$pers["uid"].", ".time().", '".show_ip()."', '7', 'цифровой пароль', '0'
	);");
	set_vars("flash_pass=".$pers["flash_pass"].",noaction=UNIX_TIMESTAMP()+86400");
	$pers["noaction"] = time()+86400;
}

if (!empty($_POST["name"]) or !empty($_POST["city"]) or !empty($_POST["country"]) or !empty($_POST["ovas"])){
$chars = sqla("SELECT about FROM chars WHERE uid= ".$pers["uid"]."");
$pers["name"]   = addslashes($_POST["name"]);
$pers["city"]   = addslashes($_POST["city"]);
$pers["country"]= addslashes($_POST["country"]);
$chars["about"] = addslashes($_POST["ovas"]);
$chars["about"] = str_replace("


","
",$chars["about"]);
if (!$pers["diler"]) $chars["about"] = substr($chars["about"],0,900);
$chars["about"] = str_replace("\\","",$chars["about"]);
sql ("UPDATE `users` SET `name` = '".$pers["name"]."' ,`city` = '".$pers["city"]."' ,`country` = '".$pers["country"]."'  WHERE `uid` = ".$pers["uid"]." ;");
sql ("UPDATE `chars` SET `about` = '".$chars["about"]."'  WHERE `uid` = ".$pers["uid"]." ;");
unset($chars);
}

if (@$_GET["gopers"]=="ref")
echo "<p class=weapons_box>
<b class=ma>Приводи друзей в игру и зарабатывай LN!</b> <br>Если человек регистрируется по вашей реферальной ссылке (т.е. заходит по ней, видит главную страницу, а потом регистрируется), то вы сразу получаете <b>10 LN и 1 Пергамент</b>, а при получение каждого 5ого уровня этим перснажем вы получаете по 50 LN, при покупке этим персонажем у.е.(ВАЛЮТЫ) - вам даётся 3% от купленных им у.е.
Так же при достижении этим персонажем 15 уровня вам на счёт насчитывается 200 LN.!
<font class=items>Ваша уникальная ссылка: <br> <font class=ma>
http://aloneislands.ru/into.php?id=".$pers["uid"]." </font> <br>С уважением, Администрация Alone Islands.</font><br>
</p>
";

include("hero/_referal.php");

if (empty($_GET["gopers"])) {
	####Новый год
	if($pers["new_year"]>1)
	{
		echo "<center class=fightlong><img src=images/persons/male_moroz.gif height=20> До нападения Деда осталось <b>".mtrunc($pers["new_year"]-tme())." сек.</b>!</center>";
	}
	
	if($pers["free_stats"]<5 and $pers["level"]<3  and $pers["chp"]/$pers["hp"]>0.75)
	{
		$bts = sqlr("SELECT uid FROM users WHERE level=".($pers["level"])." and block<>'' and rank_i>5 and silence = 0 and lb_attack<".tme()." and s6=1 and s5=1 LIMIT 0,1");
		if($bts)
	 		echo "<a href=main.php?gopers=battle class=wbut>В бой!</a>";
	}
	if(mtrunc($pers["free_f_skills"]+$pers["free_m_skills"]) and !$pers["free_stats"])
	{
		echo "<center class=but><b class=red><a class=timef href=main.php?gopers=um>Распределите</a> очки умений, доступные для повышения!</b></center>";
	}
	if($pers["tour"])
	{
		echo "<center class=but><b class=red>Вы в турнире!</b></center>";
	}
	
	if($_NG)
	{
		echo "<center class=but><b class=green><img src='images/gameplay/1_1.png'>Новогодние скидки(-50%)</b><hr> Новогодние скидки на БР действуют до 15ого января, спешите!</center>";
	}
	
	//echo "<center class=but><b class=green>Новости</b><hr> Игра не работала 24-25 января, потому что была атака на сервер. В данный момент атаки нет, и игра работает в предыдущем режиме. Всем недолговечным вещам была добавлена долговечность, поэтому ваша рыба и ресуры и не пропадут. Так же продлён реферальный конкурс ещё на 2 дня. <b>С уважением, Администрация.</b></center>";
	$kindness_class = "dark";
	if($pers["kindness"]>0)
		$kindness_class = "green";
	else
		$kindness_class = "hp";
	echo "<center class=dark><b>Мораль:</b> <b class=".$kindness_class.">".kind_stat($pers["kindness"])."</b></center>";
	if (($duration=($pers["punishment"]-tme()))>0) echo "<b>На вас наложена кара смотрителя!</b><font class=timef>ещё ".tp($duration)."</font>(Опыт -50%)<br>";
	if ($GOOD_DAY&GD_HUMANHEAL)
	 echo "<center><b class=green>Хороший день.</b> Сегодня, после боев с людьми, ваш персонаж будет мгновенно излечиваться от травм.</center>";

		$all_weight = sqla("SELECT SUM(weight) as w,COUNT(*) as c FROM `wp` WHERE uidp=".$pers["uid"]." AND in_bank=0");
		$all_wp = $all_weight["c"];
		$all_weight = $all_weight["w"];
		if (intval($all_weight)<>$pers["weight_of_w"])
			set_vars("weight_of_w=".intval($all_weight),UID);
		$pers["weight_of_w"] = intval($all_weight);
		if (abs(10+($pers["sm3"]+$pers["s4"])*10) < ($all_weight))
			echo " <center class=but><font class=hp>Вы перегружены!</font></center>";
			
//if ($pers["coins"])
/*echo "<center><table style='width: 90%' class=but> <tr> <td style='height: 58px; width: 40px; text-align: center; background-image: url(\"images/pgs.gif\")'><b>".$pers["coins"]."</b></td> <td class=items>Количество ваших пергаментов , полученных за проведение отличных боёв.<br><i> Они могут вам понадобиться в университете.</i></td> </tr> </table></center>";*/
if ($pers["exp"]>1050)
echo "
<br>
<hr>
<div style='text-align: left; width:70%'>
<b class=ma>Приводи друзей в игру и зарабатывай по <b color=#00CC00 class=bnick>10LN и 1 Пергамент</b> за каждого!</b>[<a href=main.php?gopers=ref class=timef>Подробнее...</a>] <br>
</div>
";
if (!$pers["second_pass"] and $pers["level"]>1) echo "<br><div class=weapons_box align=center><i>Безопасность:</i><br>У вас не установлен второй пароль! Чтобы прочитать зачем он нужен, и установить его зайдите в раздел \"Пароль\"</div>";
//if (substr_count($pers["aura"],"doctor")>0) include('inc/inc/characters/doctor.php');
if(@$_GET["deny"])
{
	$pupil = sqla("SELECT * FROM users WHERE uid = ".$pers["instructor"]);
	sql("UPDATE users SET instructor=0 WHERE uid = ".$pers["uid"]);
	say_to_chat ('n',"Персонаж <b>".$pers["user"]."</b>[".$pers["level"]."] отказался от обучения.",1,$pupil["user"],'*',0);
	$pers["instructor"] = 0;
}
if($pers["level"]<5 and $pers["instructor"])
{
	$pupil = sqla("SELECT * FROM users WHERE uid = ".$pers["instructor"]);
	if($pupil)
{
	echo "<br><center>";
	echo "<center style='width:90%' class=combofight>";
	echo "<i class=gray>";
	echo "Вы обучаетесь у персонажа <b class=hp>[".$pupil["level"]."] уровня</b>";
	echo "</i>";
	echo "<div class=but><b class=user>".$pupil["user"]."</b> <b class=lvl>[".$pupil["level"]."]</b> <img src=images/i.gif onclick=\"javascript:window.open('info.php?p=".$pupil["user"]."','_blank')\" style='cursor:pointer' height=16> <input type=button class=login value='Отказаться от обучения' onclick=\"location = 'main.php?deny=1'\"></div>";
	echo "</center>";
	echo "</center>";
}
}
}

if (@$_GET["gopers"]=="student" and $pers["level"]>9) 
{
	include ("hero/student.php");
}
if (@$_GET["gopers"]=="law") 
{
	include ("hero/justice.htm");
	if ($pers["help"]<2)
	{
	set_vars("help=2",UID);
	$pers["help"] = 2;
	}
}
if (@$_GET["gopers"]=="info") 
{
	include("hero/help.html");
	if ($pers["help"]<1)
	{
	set_vars("help=1",UID);
	$pers["help"] = 1;
	}
}
/* {
echo "
<font class=ma>Помощь<image src=images/q.gif onclick='javascript:top.helpwin(\"index.html\")' style='cursor:hand'><br></font>
Характеристики даются персонажу при получении каждого уровня. Для того чтобы распределить характеристики необходимо нажать на знак \"+\" расположенном напротив необходимого параметра.
<hr>
Информация об основных характеристиках персонажа:<br>
<b>1.</b> Сила - Повышает силу физического удара.<br>
<b>2.</b> Реакция - (Второе название ловкость) Позволяет уворачиваться от ударов в бою и не даёт уворачиваться вашим противникам.<br>
<b>3.</b> Удача - Позволяет наносить сокрушительные(критические) удары во время боя (Обычно двоекратное увеличение урона).Так же не даёт противнику нанести сокрушительный удар по вам.<br>
<b>4.</b> Здоровье - Увеличивает максимальные запас здоровья вашего персонажа(1 здоровья = 5 HP) , а так же прибавляет класс брони. (1 здоровья = 1 класс брони) <br>
<b>5.</b> Интеллект - Позволяет собирать различные предметы из ингредиентов. В бою даёт возможность нанести ответный удар сопернику не используя хода.(Большая редкость)<br>
<b>6.</b> Сила воли - Повышает максимальный запас маны вашего персонажа(1 здоровья = 9 MA). Позволяет учить сложные заклинания.
<hr>
Информация о модификаторах персонажа:<br>
<b>1.</b> Сокрушение - Повышает вероятность сокрушительного удара и его силу. <i>Формула расчёта критического удара = УДАР*(1.5 + (СОКРУШЕНИЕ(ваше)-СТОИКОСТЬ(противника) )/1000 ) </i><br>
<b>2.</b> Уловка - Повышает вероятность увернуться от удара противника. <i>Формула расчёта скрыта</i><br>
<b>3.</b> Точность - Повышает вероятность попасть по противнику. <i>Формула расчёта скрыта</i><br>
<b>4.</b> Стоикость - Снижает вероятность того, что противник нанесёт вам сокрушительный удар. <br>
<b>5.</b> Ярость - Повышает вероятность снизить класс брони противника на время вашего удара. <i>Формула расчёта (3+ЯРОСТЬ(ваша)/100-СТОИКОСТЬ(противника)/100)%</i><br>
<hr>
Главные характеристики:<br>
<b>1.</b> Класс Брони - снижает урон по вам. <i>Фромула расчёта УРОН(результ) = УРОН(промежут.)*20/(КЛАСС БРОНИ(ваш)+1) Иными словами можно сказать что каждые 20 очков класса брони снижают урон на раз. (Пример 60КБ снижают урон в 3 раза.)</i><br>
<b>2.</b> Удар - Двойная характеристика, включает в себя минимальный удар и максимальный.
В бою результирующий удар выбирается случайным образом между минимальным и максимальным ударом и прибавляется к силе.<hr>
";} */
elseif (@$_GET["gopers"]=="um") {
	if ($pers["skill_zeroing"])
	{
		echo "<center class=but>Обнуления мирного умения <b>".$pers["skill_zeroing"]."</b> , <i class=timef>Для использования этого обнуления пройдите в университет и выберите для обучения нужную для обнуления профессию.</i></center>";
	}
	echo "<center class=but>Понижение физического урона: <b>".DecreaseDamage($pers)."%</b></center>";
	include ('inc/inc/characters/ym.php');
} elseif (@$_GET["gopers"]=="abilities") {
include ('inc/inc/characters/abilities.php');
} elseif (@$_GET["gopers"]=="referals") {
include ('inc/hero/referals.php');
} elseif (@$_GET["gopers"]=="ovas") {
$chars = sqla("SELECT about FROM chars WHERE uid='".$pers["uid"]."'");
echo "
<form method=POST action='main.php?gopers=ovas' name=ovass>
<table border=0 width=459 id=table1 cellspacing=0 cellpadding=0>
<tr>
<td height=20><font class=ym> Ваше имя: </font></td>
<td width=300 height=20> <input type=text name=name value='".$pers["name"]."' size=20 class=laar></td>
</tr>
<tr>
<td height=20><font class=ym>Город:</font> </td>
<td width=300 height=20> <input type=text name=city value='".$pers["city"]."' size=20 class=laar></td>
</tr>
<tr>
<td height=20><font class=ym>Страна:</font> </td>
<td width=300 height=20> <input type=text name=country value='".$pers["country"]."' size=20 class=laar></td>
</tr>
<tr>
<td height=20><font class=ym>Дата Рождения</font></td>
<td width=300 height=20> <input type=text name=dr value='".$pers["dr"]."' size=20 disabled class=laar></td>
</tr>
</table>
<font class=ym>О вас:</font><br>
<textarea name=ovas rows=9 cols=65 class=fightlong>".$chars["about"]."</textarea>
<input type=hidden name=gopers value=ovas>
<hr>";
echo "
<hr>
<input type=submit class=laar value=Сохранить style='width:100%'>
</form>
";
} elseif (@$_GET["gopers"]=="parol" and $pers["noaction"]<time()) {
echo "
<div class=return_win>Ваш основной пароль:</div>
<form action=main.php method=post>
<table border='0' width='100%' cellspacing='0' cellpadding='0'>
<tr>
<td width='217'><font class=ym>Старый пароль</font></td>
<td><input type=password name=pass size='15' class=login></td>
</tr>
<tr>
<td width='217'><font class=ym>Новый пароль</font></td>
<td><input type=password name=newpass size='15' class=login></td>
</tr>
<tr>
<td width='217'><font class=ym>Повторите новый пароль</font></td>
<td><input type=password name=newpass2 size='15' class=login></td>
</tr>
</table>
<input type=submit class=login value=Сохранить  style='width:100%'>
</form>

<hr>
<div class=return_win>Ваш второй пароль:</div>
<form action=main.php method=post>
<table border='0' width='100%' cellspacing='0' cellpadding='0'>
<tr>
<td width='217'><font class=ym>Новый пароль</font></td>
<td><input type=password name=snewpass size='15' class=login></td>
</tr>
<tr>
<td width='217'><font class=ym>Повторите новый пароль</font></td>
<td><input type=password name=snewpass2 size='15' class=login></td>
</tr>
</table>
<input type=submit class=login value=Сохранить  style='width:100%'>
<div class=return_win><i>Безопасноcть: Второй пароль нужен для восстановления персонажа в случае взлома, либо в сервисе напоминания вашего пароля. (Второй пароль обязательно должен отличаться от основного.)</i></div>
</form>
<hr>

<div class=return_win>Цифровая защита (Защита с помощью цифрового пароля):<br></div>
<font class=bnick color=#990000>ТЕКУЩИЙ ПАРОЛЬ: <b>".$pers["flash_pass"]."</b></font><br>
<form action=main.php method=post>
Установить: <input type=radio name=set_flash value=1><br>
Удалить: <input type=radio name=set_flash value=2><br>
Ничего не делать: <input type=radio name=set_flash value=0 CHECKED><br>
<input type=submit class=login value=Готово>
<div class=return_win><i>Безопасноcть: Цифровой пароль нужен для защиты от троянских программ(Считывание нажатых клавиш клавиатуры), в случае установления такого пароля вы будете обязаны ввести сначала основной пароль с клавиатуры, а потом цифровой с помощью мыши.</i></div>
</form>

<hr>
<div class=return_win><i>Безопасноcть: После любых действий с паролем, вы не сможете изменить второй пароль или цифровой в течении суток.</i></div>
</form>
<br>
";
}elseif (@$_GET["gopers"]=="parol" and $pers["noaction"]>=time()) {
echo "
<div class=return_win>Вы сможете изменить любой пароль через ".tp($pers["noaction"]-time())."<br><font class=bnick color=#990000>ТЕКУЩИЙ ЦИФРОВОЙ ПАРОЛЬ: <b>".$pers["flash_pass"]."</b></font></div>";
}elseif (@$_GET["gopers"]=="options") {
if ($pers["level"]<3)
echo "<a href=\"javascript:{if(confirm('Вы действительно хотите обнулиться полностью?')) location='main.php?fz=1';}\" class=bga>Полное обнуление</a><b><center>(0 уровень, все не артовые вещи  пропадают.)</center></b><br>";
echo "<form method=post action='main.php?gopers=options'>";
echo "Цвет сообщений в чате:";
echo "<div><SELECT class=real name=newchatcolor><option>&nbsp;</option><OPTION style='BACKGROUND: #000000' value=000000></OPTION>";
$opt = explode ("|",$pers["options"]);
for ($aa=0;$aa<8;$aa+=2)
for ($bb=0;$bb<8;$bb+=2)
for ($cc=0;$cc<8;$cc+=2) if ($opt[5]==dechex($aa).dechex($aa).dechex($bb).dechex($bb).dechex($cc).dechex($cc))echo "<OPTION style='BACKGROUND: #".dechex($aa).dechex($aa).dechex($bb).dechex($bb).dechex($cc).dechex($cc)."' value=".dechex($aa).dechex($aa).dechex($bb).dechex($bb).dechex($cc).dechex($cc)." SELECTED></OPTION>"; else echo "<OPTION style='BACKGROUND: #".dechex($aa).dechex($aa).dechex($bb).dechex($bb).dechex($cc).dechex($cc)."' value=".dechex($aa).dechex($aa).dechex($bb).dechex($bb).dechex($cc).dechex($cc)."></OPTION>";
echo "</select></div>";

$v1='';$v2='';$v3='';
if ($opt[0]=="full")$v1='selected';
if ($opt[0]=="small")$v2='selected';
if ($opt[0]=="min")$v3='selected';
echo '<table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px">
<tr>
<td>Информация о вещах</td>
<td width="120"><select size="1" name="inv" style="width: 120"  class=laar>
<option '.$v1.' value="full">Полная</option>
<option '.$v2.' value="small">Сокращённая</option>
<option '.$v3.' value="min">Минимальная</option>
</select></td>
</tr>';
$v1='';$v2='';$v3='';
if ($opt[1]=="full")$v1='selected';
if ($opt[1]=="small")$v2='selected';
if ($opt[1]=="min")$v3='selected';	
echo '<tr>
<td>Информация о заклинаниях</td>
<td width="120"><select size="1" name="zak" style="width: 120" class=laar>
<option '.$v1.' value="full">Полная</option>
<option '.$v2.' value="small">Сокращённая</option>
<option '.$v3.' value="min">Минимальная</option>
</select></td>
</tr>';
$v1='';$v2='';$v3='';
if ($opt[2]=="az")$v1='selected';
if ($opt[2]=="0+")$v2='selected';
if ($opt[2]=="+0")$v3='selected';	
echo'<tr>
<td>Сортировка персонажей</td>
<td width="120"><select size="1" name="sort" style="width: 120" class=laar>
<option '.$v1.' value="3">От a-z</option>
<option '.$v2.' value="1">От 0 уровня</option>
<option '.$v3.' value="2">К 0 уровню</option>
</select></td>
</tr>';
$v1='';$v2='';$v3='';
if ($opt[3]=="yes")$v1='selected';
if ($opt[3]=="no")$v2='selected';
echo'<tr>
<td>Показывать доп. информацию в чате</td>
<td width="120"><select size="1" name="chat" style="width: 120" class=laar>
<option '.$v1.' value="yes">Да</option>
<option '.$v2.' value="no">Нет</option>
</select></td>
</tr>';
if ($opt[7]=="no")$v2='selected';
else$v1='selected';
echo'<tr>
<td>Автоматически переключаться в боевой чат</td>
<td width="120"><select size="1" name="fchat" style="width: 120" class=laar>
<option '.$v1.' value="yes">Да</option>
<option '.$v2.' value="no">Нет</option>
</select></td>
</tr>';
if ($options[6]) $design = 'SELECTED';
$v1='';$v2='';$v3='';$v4='';$v5='';$v6='';
if ($opt[4]=="0.1")$v1='selected';
if ($opt[4]=="0.3")$v2='selected';
if ($opt[4]=="0.6")$v3='selected';		
if ($opt[4]=="1")$v4='selected';		
if ($opt[4]=="2")$v5='selected';		
if ($opt[4]=="0")$v6='selected';		
echo'<tr>
<td>Плавный переход(Только IExplorer)</td>
<td width="120"><select size="1" name="dur" style="width: 120" class=laar>
<option  '.$v1.' value="0.1">Оч. Быстро</option>
<option  '.$v2.'  value="0.3">Быстро</option>
<option  '.$v3.' value="0.6">Средне</option>
<option  '.$v4.' value="1">Медленно</option>
<option  '.$v5.' value="2">Оч. медленно</option>
<option  '.$v6.' value="0">Нет</option>
</select></td></tr>
</table> ';
if ($pers["obr"]==0) include ("hero/_avatars_show.php");


echo "<input type=submit class=laar value=Сохранить  style='width:100%'></form>";
}elseif (@$_GET["gopers"]=="service") include("hero/service.php");

if (@$_GET["gopers"]=='sms')
{
		include("hero/sms.php");
}
?></div>
<script><?
$zv=sqlr ("SELECT name FROM `zvanya` WHERE `id` = '".$pers["zvan"]."'");

echo "var DecreaseDamage = ".DecreaseDamage($pers).";";
echo "build_pers('".$sh["image"]."','".$sh["id"]."','".$oj["image"]."','".$oj["id"]."','".$or1["image"]."','".$or1["id"]."','".$po["image"]."','".$po["id"]."','".$z1["image"]."','".$z1["id"]."','".$z2["image"]."','".$z2["id"]."','".$z3["image"]."','".$z3["id"]."','".$sa["image"]."','".$sa["id"]."','".$na["image"]."','".$na["id"]."','".$pe["image"]."','".$pe["id"]."','".$or2["image"]."','".$or2["id"]."','".$ko1["image"]."','".$ko1["id"]."','".$ko2["image"]."','".$ko2["id"]."','".$br["image"]."','".$br["id"]."','".$pers["pol"]."_".$pers["obr"]."',0,'".$pers["sign"]."','".$pers["user"]."','".$pers["level"]."','".$pers["chp"]."','".$pers["hp"]."','".$pers["cma"]."','".$pers["ma"]."',".$pers["tire"].",'".$kam1["image"]."','".$kam2["image"]."','".$kam3["image"]."','".$kam4["image"]."','".$kam1["id"]."','".$kam2["id"]."','".$kam3["id"]."','".$kam4["id"]."',".intval($hp).",".$pers["hp"].",".intval($ma).",".$pers["ma"].",".intval($sphp).",".intval($spma).",".$pers["s1"].",".$pers["s2"].",".$pers["s3"].",".$pers["s4"].",".$pers["s5"].",".$pers["s6"].",".$pers["free_stats"].",".round($pers["money"],2).",".$pers["dmoney"].",".$pers["kb"].",".$pers["mf1"].",".$pers["mf2"].",".$pers["mf3"].",".$pers["mf4"].",".$pers["mf5"].",".$pers["udmin"].",".$pers["udmax"].",".$pers["rank_i"].",'".$zv."',".$pers["victories"].",".$pers["losses"].",".$pers["exp"].",".$pers["peace_exp"].",".($level["exp"] - $pers["exp"]-$pers["peace_exp"]).",".$pers["zeroing"].",0,'".$pers["diler"]."',".round(($level["exp"]-$pers["exp"])*100/($level["exp"]-($level1["exp"]+1))).",'".$ws1."','".$ws2."','".$ws3."','".$ws4."','".$ws5."','".$ws6."',".intval($pers["free_f_skills"] + $pers["free_p_skills"] + $pers["free_m_skills"]).",".intval($pers["help"]).",".intval(($pers["refc"]+$pers["referal_counter"])?1:0).",".$pers["coins"].");";

if(mtrunc($pers["waiter"]-$t))
	echo "waiter(".($pers["waiter"]-$t).");";

//mod_st_fin();
?>
</script>
<?
$as = sql("SELECT * FROM p_auras WHERE uid=".$pers["uid"]."");
$txt = '';
while($a = mysql_fetch_array($as))
{
	$txt .= $a["image"].'#<b>'.$a["name"].'</b>@';
	$txt .= 'Осталось <i class=timef>'.tp($a["esttime"]-time()).'</i>';
	$params = explode("@",$a["params"]);
		foreach($params as $par)
		{
			$p = explode("=",$par);
			$perc = '';
			if (substr($p[0],0,2)=='mf') $perc = '%';
			if ($p[1] and $p[0]<>'cma' and $p[0]<>'chp')
			$txt .= '@'.name_of_skill($p[0]).':<b>'.plus_param($p[1]).$perc.'</b>';
		}
	$txt .= '|';
}
echo "<script>view_auras('".$txt."');</script>";
?>