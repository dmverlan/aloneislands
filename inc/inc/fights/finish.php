<?
$t = tme();

$waiter = '';
if ($t<$pers["waiter"]) 
{
	echo "show_message_in_f('<div id=waiter class=but align=center></div>');";
	$waiter = "waiter(".($pers["waiter"]-$t).",0,'Ожидание');";
}

###Быстрый выход во время завоевания
if ($pers["gain_time"]>(tme()-1200))
{
	echo "show_message_in_f('<div id=waiter class=but align=center></div>');";	
	$waiter = "waiter(10,0,'Завершение боя');";
}
######

$print_to_exit = "<input value=\'Выйти из поединка\' type=submit class=login style=\"width:90%;height:40px;cursor:pointer;\">";
echo "show_message_in_f('<center><a href=main.php?eff=1 class=gbut>Выйти</a></center>');";

/*
if ($pers["chp"]>0)
{
$sk = sqla("SELECT user,id_skin FROM bots_battle WHERE cfight=".$pers["cfight"]." and fteam<>".$pers["fteam"]." and id_skin>0");
$wp = sqla("SELECT name,dprice,price,max_durability FROM wp WHERE uidp=".$pers["uid"]." and weared=1 and stype='noji' and dprice=0");
if ($wp and $sk) 
{
	if (@$_GET["skinout"])
	{
		$txt = '';
		if ($pers["sp14"]>rand(0,2000))
		{
				$price = $wp["price"]/$wp["max_durability"];
				if($sk["id_skin"]==1) $price += 10;
				if($sk["id_skin"]==2) $price += 2;
				if($sk["id_skin"]==3) $price += 4;
				if($sk["id_skin"]==4) $price += 3;
				$price += floor(sqrt($pers["level"]));
				$txt .= "<div class=green>Удачно срезано «Шкура ".$sk["user"]."»!</div>";
				$txt .= "Мирный опыт <b>+40</b><br>";
				$txt .= "Выделка кожи <b>+".round((10/($pers["sp14"]+1)),2)."</b><br>";
				if ($wp["dprice"]==0)$txt .= "Долговечность <b>".$wp["name"]."</b> -1<br>";
				$txt .= "Стоимость шкуры <b>".$price." LN</b><br>";	
				sql("INSERT INTO `wp` 
				( `id` , `uidp` , `weared` ,`id_in_w`, `price` , `dprice` , `image` 
				, `index` , `type` , `stype` , `name` , `describe` , `weight` , `where_buy` 
				, `max_durability` , `durability` ,`p_type`) 
				VALUES 
				(0, '".$pers["uid"]."', '0','res..skin".$sk["id_skin"]."'
				,'".$price."', 
				'0', 'skin/skin0".$sk["id_skin"]."', '0', 'resources', 'resources', 
				'Шкура ".$sk["user"]."', '', '1', '0', '1', '1','7');");
		}
		else
		{
				$txt .= "<div class=hp>Неудачная попытка.</div>";
				$txt .= "Мирный опыт <b>+40</b><br>";
				$txt .= "Выделка кожи <b>+".round(10/($pers["sp14"]+1),2)."</b><br>";
				if ($wp["dprice"]==0)$txt .= "Долговечность <b>".$wp["name"]."</b> -1<br>";
		}
		sql("UPDATE bots_battle SET id_skin=0 WHERE cfight=".$pers["cfight"]." and fteam<>".$pers["fteam"]." and id_skin>0");
		if ($wp["dprice"]==0)sql("UPDATE wp SET durability=durability-1 WHERE uidp=".$pers["uid"]." and weared=1 and stype='noji' and dprice=0 LIMIT 1;");
		set_vars("sp14=sp14+10/(sp14+1),peace_exp=peace_exp+40",$pers["uid"]);
		echo "show_message_in_f('<center class=inv>".$txt."</center>');";
	}
	else
	{
	$print_to_exit = "<input value=\'Срезать шкуру\' type=submit class=login>";
	echo "show_message_in_f('<center class=inv><form method=post action=\"main.php?skinout=1\">".$print_to_exit."</form></center>');";
	}
}
}*/


$_FT = $fight["travm"];
if ($fight["type"]=='notf' or $fight["type"]=='') {
if ($fight["travm"]<=10)  $fight["travm"] = 4/5;
if ($fight["travm"]==30)  $fight["travm"] = 5/5;
if ($fight["travm"]==50)  $fight["travm"] = 6/5;
if ($fight["travm"]==80)  $fight["travm"] = 7/4;
if ($fight["travm"]>80)  $fight["travm"] = 2;
if ($LIFE1==0) $cc1=0.2;  else  $cc1=1;
if ($LIFE2==0) $cc2=0.2;  else  $cc2=1;
if ($LIFE1==0 and $LIFE2==0) $cc1=$cc2=0.6;

$s='';
if ($fight["turn"]=='timeout' or $fight["turn"]=='n') 
$s=$s."<center><i>Битва закончена по таймауту</i>></center>";
	else 
$s=$s."";

$c1b = sqlr("SELECT COUNT(id) FROM bots_battle WHERE cfight=".$pers["cfight"]." and fteam=1",0);
$c2b = sqlr("SELECT COUNT(id) FROM bots_battle WHERE cfight=".$pers["cfight"]." and fteam=2",0);


$p_t1 = sql("SELECT user,chp,level,sign,hp,uid,xf,yf,cma,ma,aura,losses,victories,exp_in_f,main_present,clan_name,fexp,pol,fteam,kills,punishment,invisible,instructor
FROM users WHERE cfight=".$pers["cfight"]." and fteam=1");
$p_t2 = sql("SELECT user,chp,level,sign,hp,uid,xf,yf,cma,ma,aura,losses,victories,exp_in_f,main_present,clan_name,fexp,pol,fteam,kills,punishment,invisible,instructor
FROM users WHERE cfight=".$pers["cfight"]." and fteam=2");

if($pers["tour"]==1)
{
	$b_t1 = sql("SELECT * FROM bots_battle WHERE cfight=".$pers["cfight"]." and fteam=1");
	$b_t2 = sql("SELECT * FROM bots_battle WHERE cfight=".$pers["cfight"]." and fteam=2");
}
else
{
	$b_t1 = 0;
	$b_t2 = 0;
}
	

if ($c1b==1)
{
	$ghost = sqlr("SELECT dropvalue FROM bots_battle WHERE cfight=".$pers["cfight"]." and droptype=0 and dropvalue>0");
	if($ghost)
		if(sqlr("SELECT COUNT(*) FROM users WHERE uid=".$ghost." and ctip=-1"))
			set_vars("chp=0,silence=0,lb_attack=".(tme()+300),$ghost);
}

$c1=sqlr("SELECT COUNT(uid) FROM users WHERE cfight=".$pers["cfight"]." and fteam=1",0);
$c2=sqlr("SELECT COUNT(uid) FROM users WHERE cfight=".$pers["cfight"]." and fteam=2",0);

if ($fight["turn"]<>"finished" and $fight["type"]=="notf" and $t>$pers["waiter"]) {

if($pers["tour"]==1)
{
	$t1 = sqla("SELECT * FROM quest WHERE id = 2");
	if($t1["type"]==1)
		sql("UPDATE quest SET time=".tme().",type=2 WHERE id=2"); // TOUR1
}
if($pers["tour"]==2)
{
	$t1 = sqla("SELECT * FROM quest WHERE id = 3");
	if($t1["type"]==1)
		sql("UPDATE quest SET time=".tme().",type=2 WHERE id=3"); // TOUR1
}
if($pers["tour"]==3)
{
	$t1 = sqla("SELECT * FROM quest WHERE id = 4");
	if($t1["type"]==1)
		sql("UPDATE quest SET time=".tme().",type=2 WHERE id=4"); // TOUR1
}

$exptext = array();
$expnum = array();

$m='';
$win = ($LIFE1!=0)?1:0;
while ($tmp = mysql_fetch_array($p_t1) or ($tmp = mysql_fetch_array($b_t1)))
{
	
if($tmp["level"]>2) 
{
	$c1b *= 0.7;
	$c2b *= 0.7;
	if($c1b>2) $c1b = 2;
	if($c2b>2) $c2b = 2;
}

$exp = floor(abs($tmp["exp_in_f"]*$cc1*(($c2+$c2b)/($c1+$c1b))*$fight["travm"] + $tmp["kills"]*$tmp["level"]));
if ($exp<15 and $tmp["level"]<2) $exp+=15;
if ($exp>3100*($tmp["level"]+1)) $exp = 4000*($tmp["level"]+1);
if ($tmp["level"]<5) $exp *= 2;
if ($tmp["invisible"]>tme()){$tmp["user"]='невидимка';$tmp["level"]="??";$tmp["sign"]='none';}
$m = $m.$tmp["user"].','; 
if ($tmp["chp"]<1)  {if ($tmp["pol"]) $dead = '<i>убит</i>'; else $dead = '<i>убитa</i>';$exp=floor($exp*0.8);} else $dead = '';
$plus = "";
if ($tmp["main_present"]==1 or $tmp["main_present"]==2) $plus .= "<font class=green>+".($exp*0.15)."</font>";
if ($tmp["instructor"]) $plus .= "<font class=green>+".($exp*0.5)."</font>";
if ($fight["stones"]==1) $plus .= "<font class=green>+".($exp*0.5)."</font>";
if ($tmp["punishment"]>time()) 
{
	$plus .= "<font class=hp>-".($exp*0.5)."</font>";
	$exp *=0.5;
}
if ($tmp["sign"]<>'none') $sign = '<img src="images/signs/'.$tmp["sign"].'.gif" title="'.$tmp["clan_name"].'">'; else $sign='';
$exptext[] = "<tr><td>".$sign."<font class=bnick color=".$colors[$tmp["fteam"]].">".$tmp["user"]."</font>[<font class=lvl>".$tmp["level"]."</font>] ".$dead."</td><td align=center> ".$tmp["kills"]." </td><td align=center> ".$tmp["fexp"]." </td><td title=Опыт class=user>".$exp." ".$plus."</td></tr>";
$expnum[] = $exp;
if ($tmp["main_present"]==1 or $tmp["main_present"]==2) $exp*=1.15;
if ($tmp["instructor"]) $exp *= 1.5;
if ($fight["stones"]==1) $exp*=1.5;
if ($fight["turn"]<>'n' and $_FT>=10) {if ($LIFE1>0) $tmp["victories"]++; else $tmp['losses']++;}
if($_FT<10) $exp = 0;
sql ("UPDATE `users` SET `victories`='".$tmp['victories']."',`losses`='".$tmp['losses']."'
,`exp`=exp + ".$exp.",exp_in_f=0,`exp_chat`=".$exp.",f_turn=".$win." WHERE `uid`='".$tmp['uid']."'");
}
$WIN1 = substr($m,0,strlen($m)-1);

$m='';
$win = ($LIFE2!=0)?1:0;
while ($tmp = mysql_fetch_array($p_t2) or $tmp = mysql_fetch_array($b_t2))
{
if($tmp["level"]>2) 
{
	$c1b *= 0.7;
	$c2b *= 0.7;
	if($c1b>2) $c1b = 2;
	if($c2b>2) $c2b = 2;
}

$exp = floor(abs($tmp["exp_in_f"]*$cc2*(($c1+$c1b)/($c2+$c2b))*$fight["travm"] + $tmp["kills"]*$tmp["level"]));

if ($exp<15 and $tmp["level"]<2) $exp+=15;
if ($exp>3100*($tmp["level"]+1)) $exp = 4000*($tmp["level"]+1);
if ($tmp["level"]<5) $exp *= 2;
if ($tmp["invisible"]>tme()){$tmp["user"]='невидимка';$tmp["level"]="??";$tmp["sign"]='none';}
$m = $m.$tmp["user"].','; 
if ($tmp["chp"]<1) {if ($tmp["pol"]) $dead = '<i>убит</i>'; else $dead = '<i>убитa</i>';$exp=floor($exp*0.8);} else $dead = '';
if ($tmp["main_present"]==1 or $tmp["main_present"]==2) $plus = "<font class=green>+".($exp*0.15)."</font>"; else $plus = "";
if ($tmp["instructor"]) $plus .= "<font class=green>+".($exp*0.5)."</font>";
if ($tmp["punishment"]>time()) 
{
	$plus .= "<font class=hp>-".($exp*0.5)."</font>";
	$exp *=0.5;
}
if ($tmp["sign"]<>'none') $sign = '<img src="images/signs/'.$tmp["sign"].'.gif" title="'.$tmp["clan_name"].'">'; else $sign='';
$exptext[] = "<tr><td>".$sign."<font class=bnick color=".$colors[$tmp["fteam"]].">".$tmp["user"]."</font>[<font class=lvl>".$tmp["level"]."</font>] ".$dead."</td><td align=center> ".$tmp["kills"]." </td><td align=center> ".$tmp["fexp"]." </td><td title=Опыт class=user>".$exp." ".$plus."</td></tr>";
$expnum[] = $exp;
if ($tmp["main_present"]==1 or $tmp["main_present"]==2) $exp*=1.15;
if ($tmp["instructor"]) $exp *= 1.5;
if ($fight["turn"]<>'n' and $_FT>=10) {if ($LIFE2>0) $tmp["victories"]++; else $tmp['losses']++;}
if($_FT<10) $exp = 0;
sql ("UPDATE `users` SET `victories`='".$tmp['victories']."',`losses`='".$tmp['losses']."'
,`exp`=exp + ".$exp.",exp_in_f=0,`exp_chat`=".$exp.",f_turn=".$win." WHERE `uid`='".$tmp['uid']."'");
}
$WIN2 = substr($m,0,strlen($m)-1);

$s=$s."<center><table border=0 class=but width=400 cellspacing=0 id=exp_table>	<tr><td class=mfb>Персонаж</td>	<td class=mfb>Убийства</td>	<td class=mfb>Нанесено урона</td><td class=mfb>Опыт</td>	</tr>";
for ($i=0;$i<count($expnum);$i++)
 for($j=0;$j<$i;$j++)
  if ($expnum[$j+1]>$expnum[$j])
  {
	$tmpn = $expnum[$j+1];
	$tmps = $exptext[$j+1];
	$expnum[$j+1] = $expnum[$j];
	$exptext[$j+1] = $exptext[$j];
	$exptext[$j] = $tmps;
	$expnum[$j] = $tmpn;
  }
for ($i=0;$i<count($expnum);$i++) $s .= $exptext[$i];
$s.="</table></center>";

if ($LIFE2==0 and $LIFE1 and $WIN1) $s=$s."<br><font class=items>Победа за: <b>".$WIN1."</b></font>";
elseif ($LIFE1==0 and $LIFE2 and $WIN2) $s=$s."<br><font class=items>Победа за: <b>".$WIN2."</b></font>";
if ($LIFE1==0 and $LIFE2==0) $s=$s."<br><font class=items><b>Ничья</b></font>";

if ($fight["turn"]<>"finished" and $fight["type"]=="notf") {
sql ("UPDATE `fights` SET `result`='".addslashes($s)."' , `type`='f', `turn`='finish' WHERE `id`='".$pers["cfight"]."' ;");
echo "show_exp('".addslashes($s)."');";
sql("DELETE FROM turns_f WHERE idf='".$pers["cfight"]."'");
}
}
}else echo "show_exp('".addslashes($fight["result"])."');";

$yourwin = 0;
if($fight["type"]=='notf' or $fight["type"]=='')
{
	if($LIFE2==0 and $LIFE1 and $pers["fteam"]==1) $yourwin = 1;
	if($LIFE1==0 and $LIFE2 and $pers["fteam"]==2) $yourwin = 1;
}
else
{
	$yourwin = $pers["f_turn"];
}

if($yourwin==1)
	echo "UP_TOP('Бой завершен. <b class=green>Победа!</b>');";
else
	echo "UP_TOP('Бой завершен. <b class=hp>Проигрыш.</b>');";

echo $waiter;
?>