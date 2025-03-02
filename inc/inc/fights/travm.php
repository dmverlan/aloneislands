<?
$str="";
 if (!$_persvs) $_persvs = $persvs;
 if ($_persvs["invisible"]>tme()) $_persvs["user"]='<i>невидимка</i>';
 
$rand = rand (1,100);
if (($rand<$fight["travm"]/3 and $_persvs["uid"]  or $fight["travm"]==100) and $_persvs["level"]>4) {

$rand = rand (1,round($fight["travm"]/8));
if ($fight['travm']==100) $rand=rand(8,10);
if ($rand<4) 
 $rand=3;
elseif ($rand<8)
 $rand=4;
else
 $rand=5;
 
$perstravm = catch_user($_persvs["uid"]);
$zid = sqlr("SELECT id FROM auras WHERE special=".$rand." ORDER BY RAND()");
$a = aura_on2($zid,$perstravm);
$str .= '<b>'.$_persvs["user"].'</b> получает «<font class=red><B>'.$a["name"].'.</B> <i>'.$a["describe"].'</i></font>»%';
unset($perstravm);
}

if ($_persvs["uid"] and $_pers["uid"] and $_persvs["level"]>4)
{
if ($_pers["pol"]=="male") $ob = "порыскал в карманах трупа"; else $ob = "порыскала в карманах трупа";

$r = rand(1,1+mtrunc($_persvs["level"]-8));

if (rand(0,100)<4 and $_persvs["level"]>=($_pers["level"]-2))
{
if (($_persvs["level"]/12 + $_pers["sp10"]/20)>rand(1,200))
{
	$v = sqla("SELECT * FROM wp WHERE uidp=".($_persvs["uid"])." and price<100 and dprice=0
	and weared=0 ORDER BY RAND()");
	if ($v["id"]<>'') {
	$res = "Обнаружен и украден «".$v["name"]."» !";
	sql("UPDATE wp SET uidp=".$_pers["uid"].",user=".$_pers["user"]." WHERE id=".$v."");
	}else{
	$res = "Ничего не найдено.";
	}
}else 
{
	$res = "Обнаружено и украдено ".$r." LN!";
	sql ("UPDATE `users` SET `money`=money+".$r." WHERE `uid`='".$_pers["uid"]."'");
	sql ("UPDATE `users` SET `money`=money-".$r." WHERE `uid`='".$_persvs["uid"]."'");
}
}
else $res = "Ничего не найдено.";

$str .= " <font class=bnick color=".$colors[$_pers["fteam"]].">".$_pers["user"]."</font> ".$ob." <b>".$_persvs["user"]."</b>. Результаты: <b>".$res."</b>%";
}
?>