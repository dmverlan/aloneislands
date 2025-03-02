<script type="text/javascript" src="js/clan.js?8"></script>


<div class=inv>
<center>
<table border=0 cellspacing=0 width=500 class=but>
<tr>
<td width=160><a href=main.php?action=addon&gopers=clan&<? echo tme();?> class=blocked>Обновить</a></td>
<td width=160><a href=main.php?clan=w&action=addon&gopers=clan class=blocked>Казна</a></td>
<td width=160><a href=main.php?clan=wall&action=addon&gopers=clan class=blocked>Стена</a></td>
</tr>
</table>
</center>
<?
//if ($pers['sign']=='watchers') echo ' | <input type="button" value="Подсказки игрокам" class="laar" onclick="location=\'main.php?clan=tips&action=addon&gopers=clan\'">';
?>

<?
$clan = sqla ("SELECT * FROM `clans` WHERE `sign`='".$pers['sign']."'");


$status = $pers["clan_state"];


function report($txt)
{
	echo "<br><center class=but2><center class=puns>".$txt."</center></center><br>";
}

if ($status=='g' and @$_GET["tzero"])
{
	$tr = sqlr("SELECT COUNT(*) FROM wp WHERE clan_sign='".$pers["sign"]."'");
	sql("UPDATE clans SET treasury=".$tr." WHERE sign='".$pers["sign"]."'");
}
if (@$_GET["well"])
{
	$clan = sqla ("SELECT * FROM `clans` WHERE `sign`='".$pers['sign']."'");
	if($clan["time_well"]<=time())
	{
		$pers["chp"] = $pers["chp"]+$clan["well"];
		if ($pers["chp"]>$pers["hp"]) $pers["chp"] = $pers["hp"];
		$pers["cma"] = $pers["cma"]+$clan["well"];
		if ($pers["cma"]>$pers["ma"]) $pers["cma"] = $pers["ma"];
		set_vars("chp=".$pers["chp"].",cma=".$pers["cma"],UID);
		sql("UPDATE clans SET time_well=".(time()+600)." WHERE `sign`='".$pers['sign']."'");
	}
}
if (@$_GET["up_um"])
{
	$clan = sqla ("SELECT * FROM `clans` WHERE `sign`='".$pers['sign']."'");
	if ($clan["freestats"]>0)
	{
		if ($_GET["up_um"]==1)
			sql("UPDATE clans SET maxtreasury=maxtreasury+15,freestats=freestats-1 WHERE `sign`='".$pers['sign']."'");
		if ($_GET["up_um"]==2)
			sql("UPDATE clans SET maxpl=maxpl+3,freestats=freestats-1 WHERE `sign`='".$pers['sign']."'");
		if ($_GET["up_um"]==3)
			sql("UPDATE clans SET well=well+10,freestats=freestats-1 WHERE `sign`='".$pers['sign']."'");
	}
}
if (@$_GET["zero"])
{
	$clan = sqla ("SELECT * FROM `clans` WHERE `sign`='".$pers['sign']."'");
	if ($clan["money"]>=500)
	sql("UPDATE clans SET maxtreasury=0,maxpl=0,well=0,freestats=level,money=money-500 WHERE `sign`='".$pers['sign']."'");
}
if (@$_GET["set_params"] and ($status=='g' or $status=='z'))
{
		$bp=sqla("SELECT uid,clan_state FROM `users` 
	WHERE `smuser`=LOWER('".$_GET["set_params"]."') and `sign`='".$pers['sign']."'");
		if (($bp["clan_state"]=='g' and $status=='g') or $bp["clan_state"]!='g')
		{
		if (@$bp['uid'])
		{
			if ($bp["clan_state"]=='g' or !$_POST["clan_state"])
				sql ("UPDATE `users` SET `state`='".$_POST["state"]."', clan_tr='".$_POST["clan_tr"]."' WHERE `uid`='".$bp['uid']."'");
			else
				sql ("UPDATE `users` SET `state`='".$_POST["state"]."' ,
			`clan_state`='".$_POST["clan_state"]."', clan_tr='".$_POST["clan_tr"]."' WHERE `uid`='".$bp['uid']."'");
		}
		}
}
if (@$_POST["money"])
{
	report("Деньги удачно выданы.");
	$m = mtrunc(intval($_POST["money"]));
	if ($m>$pers["money"]) $m = $pers["money"];
	set_vars("money=money-".$m,UID);
	sql("UPDATE clans SET money=money+".$m." WHERE sign='".$pers["sign"]."'");
}
if (@$_POST["takemoney"] and ($status=='g' or $status=='c'))
{
	$clan = sqla ("SELECT * FROM `clans` WHERE `sign`='".$pers['sign']."'");
	report("Деньги удачно взяты.");
	$m = mtrunc(intval($_POST["takemoney"]));
	if ($m>$clan["money"]) $m = $clan["money"];
	set_vars("money=money+".$m,UID);
	sql("UPDATE clans SET money=money-".$m." WHERE sign='".$pers["sign"]."'");
}
if (@$_POST["site"])
{
	report("Сайт удачно изменён.");
	$site = str_replace("http://","",$_POST["site"]);
	$site = str_replace("'","",$site);
	$site = str_replace("/","",$site);
	$site = str_replace("\\","",$site);
	sql("UPDATE clans SET sait='".$site."' WHERE sign='".$pers["sign"]."'");
	$clan["sait"] = $site;
}


$cdmoney = "<div class=but align=center> <img src=images/signs/diler.gif> <b>".$clan["dmoney"]."</b> Бр.</div>";
$cmoney  = "<a href='javascript:give_money()' class=blocked> <img src=images/money.gif> <b>".$clan["money"]." LN</b></a>";
if ($status=='g' or $status=='c') 
$cmoney  .= "<a href='javascript:take_clan()' class=blocked>Снять деньги</a>";



echo "
<center>
<table border=0 cellspacing=0 width=500 class=but>
<tr>
<td width=250>".$cmoney."</td>
<td width=250>".$cdmoney."</td>
</tr>
<tr><td class=but id=money colspan=3 align=center></td></tr>
</table>
</center>
";

if (isset($clan)) $c=1;
if ($pers["user"]==$clan["glav"] and $status!='g') 
{
	$pers["clan_status"] = "g";
	sql("UPDATE users SET clan_state='g' WHERE uid=".$pers["uid"]."");
	$status='g';
}


if($c==1 and ($status=='g' or $status=='z') and isset($_GET["go_out"]))
{
	$pers["money"]-=200;
	$go_out=sqla("SELECT uid,user FROM `users` 
	WHERE `smuser`=LOWER('".$_GET["go_out"]."')");
		if (@$go_out['uid'])
		{
			sql ("UPDATE `users` SET `sign`='none' ,
			`state`='' , `rank`='' WHERE `uid`='".$go_out['uid']."'");
			sql ("UPDATE `users` SET `money`='".$pers["money"]."' WHERE `uid`='".$pers['uid']."'");
			report("Персонаж <b>".$go_out['user']."</b> выгнан из клана! 
			С вашего счёта списано 200 LN");
		}
		else
			report("Нет такого персонажа.");
}
if ($_GET["sn_all"] and ($status=='g' or $status=='c' or $status=='z'))
{
	$pers_tmp = $pers;
	$pers = catch_user(intval($_GET["sn_all"]));
	if ($pers["sign"]==$pers_tmp["sign"] and $pers["sign"]<>"none")	
	remove_all_weapons();
	$pers = $pers_tmp;
	unset($pers_tmp);
}

if ($c==1 and ($status=='g' or $status=='z' or $status=='k') and isset($_POST["go_in"]) and $allpers<($clan["maxpl"]+10))
{
	$go_in = sqla ("SELECT sign,uid,user FROM `users` 
	WHERE `user`='".$_POST["go_in"]."'");
	if (@$go_in['uid'] and $go_in["sign"]=="none") 
		{
		$pers["money"]-=200;
		sql ("UPDATE `users` SET `sign`='".$clan["sign"]."' , `state`='' , 
		`rank`='' WHERE `uid`='".$go_in['uid']."'");
		sql ("UPDATE `users` SET `money`='".$pers["money"]."' WHERE `uid`='".$pers['uid']."'");
		report("Персонаж <b>".$go_in['user']."</b> принят в клан! С вашего счёта списано 200 LN");
		}
		else
		report("Нет такого персонажа или персонаж уже находится в клане.");
		unset($go_in);
}

if ($c==1 and ($status=='g' or $status=='z') and isset($_POST["editclan"]))
{
$who = sqla ("SELECT rank,uid FROM `users` WHERE `user`='".$_POST["editclan"]."'");
if ($who["uid"])
{
$who["rank"]='';
if ($_POST['edit']==1) $who["rank"] .= "<edit>";
if (@$_POST["inv"]) $who["rank"].="<inv>";
if ($clan["sign"]=='watchers') 
{
	if (@$_POST["m"]) $who["rank"].="<molch>";
	if (@$_POST["p"]) $who["rank"].="<prison>";
	if (@$_POST["b"]) $who["rank"].="<block>";
	if (@$_POST["w"]) $who["rank"].="<w_pom>";
	if (@$_POST["i"]) $who["rank"].="<b_info>";
	if (@$_POST["u"]) $who["rank"].="<punishment>";
}
sql ("UPDATE `users` SET `state`='".$_POST["state"]."',`rank`='".$who["rank"]."' WHERE `uid`='".$who['uid']."'");
}
}
unset($who);

if ($status=='g' and isset($_POST["do_glav"])) 
{
$p = sqla("SELECT uid,sign,user FROM users WHERE smuser=LOWER('".$_POST["do_glav"]."')");
if (@$p["uid"] and $p["sign"]==$pers["sign"] and $p["user"]<>$pers["user"])
sql("UPDATE clans SET glav='".$p["user"]."' WHERE sign='".$pers["sign"]."'");
sql("UPDATE users SET state='',clan_state='b' WHERE uid='".$pers["uid"]."'");
sql("UPDATE users SET state='',clan_state='g' WHERE uid='".$p["uid"]."'");
$status='b';
}


################################
##################################
####################################
if ($c==1) {
if (@$clan['name'] and empty($_GET['clan'])) 
{
	
	$uplvltxt = '';
if (($clan["level"]%6)==0) 
	$uplvltxt = "<i>Для получения <b>".($clan["level"]+1)."</b> уровня клан должен иметь не менее ".floor(($clan["level"]/2+1)*4)." членов.</i>";
if (($clan["level"]%6)==1) 
	$uplvltxt = "<i>Для получения <b>".($clan["level"]+1)."</b> уровня клан должен иметь не менее ".floor(($clan["level"]/2+1)*3)." членов онлайн.</i>";
if (($clan["level"]%6)==2) 
	$uplvltxt = "<i>Для получения <b>".($clan["level"]+1)."</b> уровня клан должен иметь наиболее сильного персонажа с ранком не менее ".floor(($clan["level"]/3+1)*200).".</i>";	
if (($clan["level"]%6)==3) 
	$uplvltxt = "<i>Для получения <b>".($clan["level"]+1)."</b> уровня клан должен иметь не менее ".floor(($clan["level"]+1)*5000)." ЛН в казне</i>";	
if (($clan["level"]%6)==4) 
	$uplvltxt = "<i>Для получения <b>".($clan["level"]+1)."</b> уровня клан должен иметь не менее ".floor(($clan["level"]+1)*50)." у.е. в казне</i>";		
if (($clan["level"]%6)==5) 
	$uplvltxt = "<i>Для получения <b>".($clan["level"]+1)."</b> уровня средний уровень клана должен быть не менее ".floor(($clan["level"]+8)).".</i>";	
	
	
	
	if ($status=='g')
	{
		$ch_site = ' | <a href="javascript:ch_site(\''.$clan['sait'].'\')" class=timef>Сменить</a>';
	}
echo "<center><table class=combofight width=400 cellspacing=0 cellspadding=0><tr><td align=center>Вы состоите в клане <img src='images/signs/".$clan['sign'].".gif'> <b class=user>".$clan['name']."[".$clan['level']."]</b></div></td></tr>
<tr><td class=but>Глава Клана <font class=user>".$clan['glav']."</font><img src='images/info.gif' onclick=\"javascript:window.open('info.php?p=".$clan['glav']."','_blank')\" style='cursor:pointer'> | <a href='http://".$clan['sait']."' target=_blank class=bold>".$clan['sait']."</a>".$ch_site."</td></tr>

</table></center>
";
	
echo "<center><i class=user>Состав</i><table class=but width=800>";
$sostav = sql ("SELECT user,rank,online,location,state,level,aura,uid,rank_i,clan_state,lastom,silence,clan_tr FROM `users` WHERE `sign`='".$clan['sign']."' ORDER BY `clan_state` ASC");

$online = 0;
$maxrank = 0;
$dye = $clan["dmoney"];
$money = $clan["money"];
$avglvl = 0;
$allpers = 0;
while ($perssost = mysql_fetch_array($sostav)) 
{
	
if ($status=='g' or $status=='z') 
	$onclick = "onclick=\"set_status('".$perssost["user"]."','".$perssost["clan_state"]."','".$perssost["state"]."',".$perssost["clan_tr"].",".(($perssost["uid"]==$pers["uid"])?1:0).",'".$status."',".(($pers["sign"]=="watchers")?1:0).",".$perssost["uid"].")\" style='cursor:pointer'";
	else $onclick = '';
	
$online += $perssost["online"];
if ($perssost["rank_i"]>$maxrank) $maxrank=$perssost["rank_i"];
$avglvl += $perssost["level"];
$allpers ++;
echo"<tr><td>";
echo"<img src='images/pr.gif' onclick=\"javascript:top.say_private('".$perssost["user"]."')\" style='cursor:pointer' height=16> 
<font class=user  ".$onclick.">";
echo " ".$perssost["user"]."</font><font class=lvl>[".$perssost["level"]."]</font>";
echo "<img src='images/i.gif' onclick=\"javascript:window.open('info.php?p=".$perssost["user"]."','_blank')\" style='cursor:pointer'>";
if ($perssost["silence"]>tme()) echo "<img src='images/signs/molch.gif' title='Заклинание Молчания'>";
$color = '#333333';
if ($perssost["clan_state"]=='g') $color = '#990000';
if ($perssost["clan_state"]=='z') $color = '#DD0000';
if ($perssost["clan_state"]=='c') $color = '#009900';
if ($perssost["clan_state"]=='k') $color = '#000099';
if ($perssost["clan_state"]=='b') $color = '#009999';
if ($perssost["clan_state"]=='p') $color = '#00DDDD';
echo "</font></td><td><b style='color:".$color."'>"._StateByIndex($perssost["clan_state"])."</b>[".$perssost['state']."]</td>";
if ($perssost["online"]==1) 
	{
		$loc = sqla ("SELECT name FROM `locations` WHERE `id`='".$perssost['location']."'");
		$loc = $loc["name"];
		echo "<td class=green>".$loc."</td>";
	}
	else 
		echo "<td class=hp>".time_echo(time()-$perssost["lastom"])."</td>";
if ($perssost["clan_tr"]) 
	echo "<td class=timef>Казна <i class=green>вкл</i></td>";
else
	echo "<td class=timef>Казна <i class=hp>выкл</i></td>";
	
echo "</tr>";
}		
echo "</table></center>";


###########
echo "<center><div class=but style='width:300px;'>";
echo "<i>Свободных очков клана:</i> <b>".$clan["freestats"]."</b><br>";
$tr = $clan["treasury"];
if ($clan["treasury"]>($clan["maxtreasury"]+30)) 
	$tr = "<font class=hp>".$tr."</font>"; 
else 
	$tr = "<font class=green>".$tr."</font>";
if ($clan["freestats"]>0)
{
	$plus_1 = '<a href=main.php?action=addon&gopers=clan&up_um=1>+</a>';
	$plus_2 = '<a href=main.php?action=addon&gopers=clan&up_um=2>+</a>';
	$plus_3 = '<a href=main.php?action=addon&gopers=clan&up_um=3>+</a>';
}

if ($clan["time_well"]>time()) 
$well = "Пусто<font class=hp>0</font>";
	else 
$well = "<a href=main.php?action=addon&gopers=clan&well=on class=timef>Пить</a><font class=green>".($clan["well"]+10)."</font>";
echo "".$plus_1."Вместимость казны: [".$tr."/".($clan["maxtreasury"]+30)."]<br>";
echo "".$plus_2."Максимальное число членов клана: <b>".($clan["maxpl"]+10)."</b><br>";
echo "".$plus_3."Колодец клана: [".$well."/<b>".($clan["well"]+10)."</b>]<br>";
echo "<marquee scrollamount=2 scrolldelay=14>".$uplvltxt."</marquee></div></center>";
###########

echo "<center><div class=but style='width:300px;'>";
$avglvl = floor($avglvl/$allpers);
echo "<i>Персонажей в клане</i> : <b>".$allpers."</b><br>";
echo "<i>Персонажей онлайн</i> : <b>".$online."</b><br>";
echo "<i>Ранк у сильнейшего персонажа</i> : <b>".$maxrank."</b><br>";
echo "<i>Средний уровень</i> : <b>".$avglvl."</b><br>";
echo "</div></center>";

if ((
 (($clan["level"]%6)==0 and $allpers>=floor(($clan["level"]/2+1)*4)) or
 (($clan["level"]%6)==1 and $online>=floor(($clan["level"]/2+1)*3)) or
 (($clan["level"]%6)==2 and $maxrank>=floor(($clan["level"]/3+1)*200))or 
 (($clan["level"]%6)==3 and $money>=floor(($clan["level"]+1)*5000)) or
 (($clan["level"]%6)==4 and $dye>=floor(($clan["level"]+1)*50)) or 
 (($clan["level"]%6)==5 and $avglvl>=floor(($clan["level"]+8)))  )and @$_GET["lvlup"])
 {
	sql("UPDATE clans SET level=level+1,freestats=freestats+1 WHERE sign='".$pers["sign"]."'");
	$clan["level"]++;
	set_vars("refr=1",UID);
 }


if (
 (($clan["level"]%6)==0 and $allpers>=floor(($clan["level"]/2+1)*4)) or
 (($clan["level"]%6)==1 and $online>=floor(($clan["level"]/2+1)*3)) or
 (($clan["level"]%6)==2 and $maxrank>=floor(($clan["level"]/3+1)*200))or 
 (($clan["level"]%6)==3 and $money>=floor(($clan["level"]+1)*5000)) or
 (($clan["level"]%6)==4 and $dye>=floor(($clan["level"]+1)*50)) or 
 (($clan["level"]%6)==5 and $avglvl>=floor(($clan["level"]+8))) )
	echo '<center class=return_win><hr><input type="button" value="Повысить уровень клана" class="laar" onclick="location=\'main.php?action=addon&gopers=clan&lvlup=1\'"><hr></center>';


if ($status=='g' or $status=='z' or $status=='k') 
{
echo'<table border="0" width="100%" style="border-style: solid; border-width: 1px; border-color: #777777" cellspacing="1">
	<tr>
		<td align="center" class="user" bgcolor="#F9F9F9">Регулирование клана</td>
	</tr>
';
if ($allpers<($clan["maxpl"]+10))
echo '<tr>
		<td bgcolor="#F0F0F0" class="td"><form method="POST" action=main.php?gopers=clan&action=addon>
<p align="right">
<input name=go_in size=100 class=laar style="float: left"> 
<input type="submit" value="Принять [200 LN]" class=inv_but></p>
		</form></td>
	</tr>';
if ($status=='g') 
echo '	<tr>
		<td bgcolor="#F0F0F0" class="td"><form method="POST" action=main.php?gopers=clan&action=addon>
<p align="right">
<input name=do_glav size=100 class=laar style="float: left"> 
<input type="submit" value="Сделать главой клана" class=inv_but></p>
		</form></td>
	</tr>';
echo '</table>';
}
}
}
unset($perssost);

if ($c==1 and $_GET['clan']=='edit' and ($status=='g' or $status=='z'))
 {
$who = sqla ("SELECT sign,state,rank,user FROM `users` WHERE `smuser`=LOWER('".$_GET["who"]."')");
if ($who['sign']==$pers['sign']) {
echo '<div class=return_win>
<form method="POST" action=main.php?gopers=clan&action=addon>';
if ($clan["sign"]=='watchers') 
{
$m = (strpos(" ".$who["rank"],"<molch>")) ? 1 : 0;
$p = (strpos(" ".$who["rank"],"<prison>")) ? 1 : 0;
$b = (strpos(" ".$who["rank"],"<block>")) ? 1 : 0;
$w = (strpos(" ".$who["rank"],"<w_pom>")) ? 1 : 0;
$i = (strpos(" ".$who["rank"],"<b_info>")) ? 1 : 0;
$u = (strpos(" ".$who["rank"],"<punishment>")) ? 1 : 0;
echo "<center><table width=500 class=but>";
echo "<tr><td>Молчания</td>";if ($m==1) echo '<td><input type="checkbox" name=m value=1 CHECKED></td></tr>'; else echo '<input type="checkbox" name=m value=1></td></tr>';
echo "<tr><td>Тюрьма</td>";if ($p==1) echo '<td><input type="checkbox" name=p value=1 CHECKED></td></tr>'; else echo '<td><input type="checkbox" name=p value=1></td></tr>';
echo "<tr><td>Блокирование</td>";if ($b==1) echo '<td><input type="checkbox" name=b value=1 CHECKED></td></tr>'; else echo '<td><input type="checkbox" name=b value=1></td></tr>';
echo "<tr><td>Оставлять заметки</td>";if ($w==1) echo '<td><input type="checkbox" name=w value=1 CHECKED></td></tr>'; else echo '<td><input type="checkbox" name=w value=1></td></tr>';
echo "<tr><td>Блокирование информации</td>";if ($i==1) echo '<td><input type="checkbox" name=i value=1 CHECKED></td></tr>'; else echo '<td><input type="checkbox" name=i value=1></td>';
echo "<tr><td>Кара</td>";if ($u==1) echo '<td><input type="checkbox" name=u value=1 CHECKED></td></tr>'; else echo '<td><input type="checkbox" name=u value=1></td></tr>';
echo "</table></center>";
}
echo '<center><input type=hidden name=editclan value="'.$who['user'].'"><input type="submit" value="Сохранить" class=login></center></form></div>';
}}
unset($who);

if (@$_GET["clan"]=="w") include("inv.php");
elseif (@$_GET["clan"]=="wall") include("wall.php");
elseif (@$_GET["clan"]=="tips") include("new_tip.php");
elseif ($clan["sign"]=='watchers')
{
	echo '<table border="0" width="100%" style="border-style: solid; border-width: 1px; border-color: #777777" cellspacing="1">
	<tr>
		<td align="center" class="user" bgcolor="#F9F9F9">Массовое сообщение игрокам</td>
	</tr>
	<tr>
		<td bgcolor="#F0F0F0" class="td">
		<form method="POST" action=main.php?gopers=clan&action=addon>
			<p align="right">
			<input name="mass" size="100" class="laar" style="float: left; border-style: solid; border-width: 1px">
			<input type="submit" value="Отправить" class="inv_but">
			<input type="reset" value="Сброс" class="inv_but"></p>
		</form>
		</td>
	</tr>
</table>';
if (@$_POST["mass"])
{
	say_to_chat('w',$_POST["mass"],0,'','*',0); 
}
}
if($status=='g') 
	echo "<a href=main.php?action=addon&gopers=clan&zero=1 class=bga>Обнулить клан[-500 LN со счета клана]</a>";
	
if($status=='g') 
	echo "<a href=main.php?action=addon&gopers=clan&tzero=1 class=bga>Пересчитать счётчик казны</a>";
?>
<script>
function go_out(user){
if (confirm('Вы действительно хотите выгнать '+user+' за 200LN?')) document.location='main.php?go_out='+user+'&gopers=clan&action=addon';
}
function give_money()
{
	document.getElementById('money').innerHTML = '<form action=main.php?action=addon&gopers=clan method=post><input type=text class=login size=20 name=money><input class=login type=submit value="Пожертвовать"></form>';
}
function take_clan()
{
	document.getElementById('money').innerHTML = '<form action=main.php?action=addon&gopers=clan method=post><input type=text class=login size=20 name=takemoney><input class=login type=submit value="Забрать"></form>';
}
</script></div>


