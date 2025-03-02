<?
	$lb = sqla("SELECT b_frequency FROM configs");
	if (empty($_GET["id"]))
	{
		$bts = sql("SELECT id,user,level FROM bots WHERE level>".($pers["level"]-2)." and level<".($pers["level"]+2)." ORDER BY RAND() LIMIT 0,3");
		if (($pers["lb_attack"]+$lb["b_frequency"])>=tme()) 
		echo "<center><i class=timef>Вы сможете начать бой через ".tp(($pers["lb_attack"]+$lb["b_frequency"])-tme()).".</i></center>";
		$TXT .= '<center class=but2><table border="0" width="40%" cellspacing="0" cellpadding="0" class=but>
	<tr><td>Сущ-во</td><td width=20>Атака</td></tr>';
		while ($b = mysql_fetch_array($bts))
		{
			$TXT .= "<tr>";
			$TXT .= "<td class=user>".$b["user"]."[<b class=lvl>".$b["level"]."</b>]<img src=images/info.gif onclick=\"javascript:window.open('binfo.php?".$b["id"]."','_blank');\" style=\"cursor:point\"></td>";
			if (($pers["lb_attack"]+$lb["b_frequency"])<tme()) 
			$TXT .= "<td align=right><input type=button class=login onclick=\"{if(confirm('Вы действительно хотите напасть?')) location='main.php?id=".$b["id"]."'}\" value=[X]></td>";
			$TXT .= "</tr>";
		}
		$TXT .= "</table></center>";
		echo $TXT;
	}
	else
	{
		$b = sqla("SELECT id,level,user FROM bots WHERE id=".intval($_GET["id"])."");
		if ($b["level"]>$pers["level"]-2 and $b["level"]<$pers["level"]+2)
		{
			$lb_attack = $pers["level"]*30;
			if ($pers["level"]<5) $lb_attack/= 2;
				else
			$lb_attack += 100;
			$lb_attack += tme();
			$rnd = rand(1,$pers["level"]/3+1);
			for ($i=1;$i<=$rnd;$i++)$bb.="bot=".$b["id"]."|";
			$bb = substr($bb,0,strlen($bb)-1);
			begin_fight ($pers["user"],$bb,"Охота на существо",50,300,1,1);
			echo "<center class=hp>Бой начался!<script>location='main.php';</script></center><hr>";
			sql("UPDATE users SET lb_attack=".$lb_attack." WHERE uid=".$pers["uid"]);
		}
	/*
if (intval($_POST["type"])==0)$_POST["type"]=1;
$koef = intval(25+25*intval($_POST["type"]))/100;
$lt = date("d.m.Y H:i");

$idf = 0;
while($idf<11)
{
sql ("INSERT INTO `fights` (`oruj`,`travm`,`timeout`,`ltime`,players,bplace) 
VALUES (1,50,120,".time().",2,0) ");
$idf = mysql_insert_id($main_conn);
}
$bot_id_max = $idf*100;

$all = '<font class=time>'.$lt.'</font> Бой между ';
unset ($turns);
$turns[0] = '';
unset ($exps);
$exps[0] = 0;
$n = -1;$i=0;
$xf=mtrunc(6-count($tmp1));
$yf=floor(mtrunc(3-count($tmp1)/4));
$tmp = $pers["user"];
$yf++;
if ($yf%5==0){$yf=0;$xf++;}
if ($yf<6)
{
 	$p = sqla("SELECT user,level,sign,rank_i,chp,hp,cma,ma,sm6,sm7,lastom,uid,aura FROM `users` WHERE `user`='".$tmp."'");
	sql ("UPDATE `users` SET `xf`=".$xf.",`yf`=".$yf.",".hp_ma_up($p["chp"],$p["hp"],$p["cma"],$p["ma"],$p["sm6"],$p["sm7"],$p["lastom"]).",damage_get=chp,damage_give=0 WHERE `uid`='".$p["uid"]."'");
	$p["lib"] = $p["user"];
	if (substr_count($p["aura"],"invisible")) {$p["user"]='невидимка';$p["sign"]='none';$p["level"]='??';}
 
 
$all .= "<img src=images/signs/".$p['sign'].".gif><font class=green>".$p["user"]."</font>[<font class=lvl>".$p["level"]."</font>] ,";
$i++;
}

$all = substr ($all,0,strlen ($all)-1);
$all .= 'и ';
$xf=mtrunc(6-count($tmp1));$yf=floor(mtrunc(3-count($tmp1)/4));
$yf++;
if ($yf%5==0){$yf=0;$xf++;}
if ($yf<6)
{
	$bot_id_max++;
	sql ("INSERT INTO `bots_battle` ( `user` , `level` , `sign` , `s1` , `s2` , `s3` , `s4` , `s5` , `s6` , `kb` , `mf1` , `mf2` , `mf3` , `mf4` , `mf5` , `udmin` , `udmax` , `hp` , `ma` , `chp` , `cma` , `id` , `pol` , `obr` , `wears` , `rank_i` , `cfight` , `fteam` , `xf` , `yf` , `bid`) 
VALUES (
'Тень ".$pers["user"]."', '".$pers["level"]."', 'none', '".($pers["s1"]+$pers["s6"]-1)."', '".$pers["s2"]."', '".$pers["s3"]."', '".$pers["s4"]."', '".$pers["s5"]."', '1', '".$pers["kb"]."', '".$pers["mf1"]."', '".$pers["mf2"]."', '".$pers["mf3"]."', '".$pers["mf4"]."', '".$pers["mf5"]."', '".$pers["udmin"]."', '".$pers["udmax"]."', '".(($pers["hp"]+$pers["ma"])*$koef)."', 1, '".(($pers["hp"]+$pers["ma"])*$koef)."', 1, '".(-1*$bot_id_max)."' , '".$pers["pol"]."', '".$pers["obr"]."', '', '".$pers["rank_i"]."', '".$idf."', '2', '".(15-$xf)."', '".$yf."', ".(-1*$pers["uid"])."
);");

 
$all .= "<font class=blue>Тень ".$pers["user"]."</font>[<font class=lvl>".$pers["level"]."</font>] ,";
$i++;
}

$all = addslashes ( substr ($all,0,strlen ($all)-1).".(Тренировочный бой)" );
add_flog($all,$idf);

set_vars ("`cfight`='".$idf."' ,`curstate`=4 , `refr`=1 , damage_get=hp , damage_give=0 , fteam = 1",UID);
echo "<center class=hp>Бой начался!<script>location='main.php';</script></center><hr>";*/
	}
?>