<?
function molch($persto,$perswho,$duration,$reason)
{
	if ($reason=='') $reason = ' отсутствует';
	if ($duration>-1)
	{
	if ($duration==5) $timemolch = '5 минут';
	if ($duration==10) $timemolch = '10 минут';
	if ($duration==15) $timemolch = '15 минут';
	if ($duration==30) $timemolch = '30 минут';
	if ($duration==60) $timemolch = '1 час';
	if ($duration==120) $timemolch = '2 часa';
	if ($duration==180) $timemolch = '3 часa';
	if ($duration==360) $timemolch = '6 часов';
	if ($duration==1440) $timemolch = 'сутки';
	echo 'Персонаж <b>'.$persto["user"].'</b> замолчал на '.$timemolch.' 
	(<b>'.$perswho["user"].'</b>). <b>Причина:</b>'.$reason;
	say_to_chat('a',
	'Персонаж <b>'.$persto["user"].'</b> замолчал на '.$timemolch." (<b>".$perswho["user"]."</b>). <b>Причина:</b>".$reason,
	0,'','*',0); 
	$a["image"] = 'molch';
	$a["params"] = '';
	$a["esttime"] = $duration*60;
	$a["name"] = 'Заклинание молчания';
	$a["special"] = 1;
	light_aura_on($a,$persto["uid"]);
	set_vars ("silence=".(time()+$a["esttime"]),$persto["uid"]);
	sql("INSERT INTO `puns` ( `uid` , `date` , `who` , `type` , `reason` , `duration` ) 
	VALUES (
	".$persto["uid"].", ".time().", '".$perswho["user"]."', '1', '".$reason."', '".($duration*60)."'
	);");
					$persto["kindness"] -= $duration/100*(1+mtrunc(-1*$persto["kindness"]));
					set_vars("kindness=".$persto["kindness"],$persto["uid"]);
	}
	elseif ($persto["silence"]>time())
	{
	echo 'C персонажа <b>'.$persto["user"].'</b> снято заклинание молчания (<b>'.$perswho["user"]
	.'</b>)';
	say_to_chat('a',
	'C персонажа <b>'.$persto["user"].'</b> снято заклинание молчания (<b>'.$perswho["user"]
	.'</b>)',0,'','*',0); 
	sql("UPDATE p_auras SET esttime=0 WHERE uid=".$persto["uid"]." and special=1");
	set_vars ("silence=0",$persto["uid"]);
	sql("INSERT INTO `puns` ( `uid` , `date` , `who` , `type` ) 
	VALUES (".$persto["uid"].", ".tme().", '".$perswho["user"]."', '11');");
	}
}

function punish($persto,$perswho,$duration,$reason)
{
	if ($reason=='') $reason = ' отсутствует';
	if ($duration>-1)
	{
	if ($duration==5) $timemolch = '5 минут';
	if ($duration==10) $timemolch = '10 минут';
	if ($duration==15) $timemolch = '15 минут';
	if ($duration==30) $timemolch = '30 минут';
	if ($duration==60) $timemolch = '1 час';
	if ($duration==360) $timemolch = '6 часов';
	if ($duration==1440) $timemolch = 'сутки';
	if ($duration==2880) $timemolch = 'двое суток';
	echo '<b>'.$perswho["user"].'</b> покарал <b>'.$persto["user"].'</b> на '.$timemolch.' 
	. <b>Причина:</b>'.$reason;
	say_to_chat('a',
	'<b>'.$perswho["user"].'</b> покарал <b>'.$persto["user"].'</b> на '.$timemolch.
	'.<b>Причина:</b>'.$reason,0,'','*',0); 
	set_vars("punishment=".(time()+$duration*60)."",$persto["uid"]);
	sql("INSERT INTO `puns` ( `uid` , `date` , `who` , `type` , `reason` , `duration` ) 
	VALUES (
	".$persto["uid"].", ".time().", '".$perswho["user"]."', '4', '".$reason."', '".($duration*60)."'
	);");
					$persto["kindness"] -= 1/(1+mtrunc(-1*$persto["kindness"]));
					set_vars("kindness=".$persto["kindness"],$persto["uid"]);
	}
	else
	{
	echo 'C персонажа <b>'.$persto["user"].'</b> снято заклинание кары смотрителей (<b>'.$perswho["user"]
	.'</b>)';
	say_to_chat('a',
	'C персонажа <b>'.$persto["user"].'</b> снято заклинание кары смотрителей (<b>'.
	$perswho["user"].'</b>)',0,'','*',0); 
	set_vars ("punishment=0",$persto["uid"]);
	sql("INSERT INTO `puns` ( `uid` , `date` , `who` , `type` , `reason` , `duration` ) 
	VALUES (
	".$persto["uid"].", ".time().", '".$perswho["user"]."', '10', '', ''
	);");
	}
}

function prison($persto,$perswho,$duration,$reason)
{
	if ($duration>0)
	{
	$duration *= 86400;
	set_vars ("curstate=2,location='prison',prison='".($duration+time())."|".$reason."'",$persto["uid"]);
	echo 'Персонаж <b>'.$persto["user"].'</b> попал в тюремное заточение (<b>'.$perswho["user"].'</b>). <b>Причина:</b> '.$reason;
	say_to_chat('a','Персонаж <b>'.$persto["user"].'</b> попал в тюремное заточение (<b>'.$perswho["user"].'</b>). <b>Причина:</b> '.$reason,0,'','*',0); 
	sql("INSERT INTO `puns` ( `uid` , `date` , `who` , `type` , `reason` , `duration` ) 
	VALUES (
	".$persto["uid"].", ".time().", '".$perswho["user"]."', '3', '".$reason."', '".$duration."'
	);");
					$persto["kindness"] -= 1/(1+mtrunc(-1*$persto["kindness"]));
					set_vars("kindness=".$persto["kindness"],$persto["uid"]);
	}
	else
	{
	echo 'Персонаж <b>'.$persto["user"].'</b> выпущен из тюрьмы (<b>'.$perswho["user"].'</b>)';
	say_to_chat('a','Персонаж <b>'.$persto["user"].'</b> выпущен из тюрьмы (<b>'.$perswho["user"].'</b>)',0,'','*',0); 
	set_vars ("prison=''",$persto["uid"]);
	sql("INSERT INTO `puns` ( `uid` , `date` , `who` , `type` , `reason` , `duration` ) 
	VALUES (
	".$persto["uid"].", ".time().", '".$perswho["user"]."', '8', '', ''
	);");
	}
}

function block($persto,$perswho,$duration,$reason)
{
	if ($duration<>2)
	{
	set_vars ("block='".$reason."'",$persto["uid"]);
	echo 'На <b>'.$persto["user"].'</b> наложено заклинание смерти, спи спокойно! (<b>'.$perswho["user"].'</b>). <b>Причина:</b> '.$reason;
	say_to_chat('a','На <b>'.$persto["user"].'</b> наложено заклинание смерти, спи спокойно! (<b>'.$perswho["user"].'</b>). <b>Причина:</b> '.$reason,0,'','*',0); 
	sql("INSERT INTO `puns` ( `uid` , `date` , `who` , `type` , `reason` , `duration` ) 
	VALUES (
	".$persto["uid"].", ".time().", '".$perswho["user"]."', '2', '".$reason."', '0'
	);");
	}
	else
	{
	echo 'Персонаж <b>'.$persto["user"].'</b> оживлён! (<b>'.$perswho["user"].'</b>)';
	say_to_chat('a','Персонаж <b>'.$persto["user"].'</b> оживлён! (<b>'.$perswho["user"].'</b>)',0,'','*',0); 
	set_vars ("block=''",$persto["uid"]);
	sql("UPDATE puns SET duration=".(time()-$persto["lastom"])." WHERE duration=0 and type=2 and uid=".$persto["uid"]."");
	sql("INSERT INTO `puns` ( `uid` , `date` , `who` , `type` , `reason` , `duration` ) 
	VALUES (
	".$persto["uid"].", ".time().", '".$perswho["user"]."', '9', '', ''
	);");
	}
}

function pometka($persto,$perswho,$p)
{

	set_vars ("block='".$reason."'",$persto["uid"]);
	echo 'Оставлена пометка: <b>'.$p.'</b>';
	say_to_chat('a','<b>'.$perswho["user"].'</b> оставил о вас пометку: <b>'.$p.'</b>',1,$persto["user"],'*',0); 
	sql("INSERT INTO `puns` ( `uid` , `date` , `who` , `type` , `reason` , `duration` ) 
	VALUES (
	".$persto["uid"].", ".time().", '".$perswho["user"]."', '6', '".$p."', '0'
	);");
}

function blocki($persto,$perswho,$reason)
{
echo 'На персонажа <b>'.$persto["user"].'</b> наложено заклинание блокирование информации(<b>'.$perswho["user"].'</b>)';
sql ("UPDATE `chars` SET about='Заблокировано. Причина: ".$reason."' WHERE `uid`=".$persto["uid"]);
	sql("INSERT INTO `puns` ( `uid` , `date` , `who` , `type` , `reason` , `duration` ) 
	VALUES (
	".$persto["uid"].", ".time().", '".$perswho["user"]."', '5', '".$reason."', '0'
	);");
}

function diler($persto,$perswho,$count)
{
	GLOBAL	$_NG;
	$count = mtrunc($count);
	if ($count>$perswho["dreserv"]) $count = $perswho["dreserv"];
	if ($count>0)
	{
		$res_count = $count;
		if($_NG)
			$res_count = $res_count/2; 
	set_vars ("dmoney=dmoney+'".$count."'",$persto["uid"]);
	set_vars ("dreserv=dreserv-'".$res_count."'",$perswho["uid"]);
	GLOBAL $you;
	$you["dreserv"]-=$res_count;
	echo 'Продано <b>'.$count.' БР</b> <i class=red>(Потрачено '.$res_count.' резерва)</i>.';
	say_to_chat('a','<b>'.$perswho["user"].'</b> продал вам <b>'.$count.' у.е.</b>',1,$persto["user"],'*',0); 
	sql("INSERT INTO `dtransfer` ( `uid` , `uidwho` , `date` , `summ` ) 
VALUES (
'".$persto["uid"]."', '".$perswho["uid"]."', '".time()."', '".$count."'
);");
	}
}
?>





