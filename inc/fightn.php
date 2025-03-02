<?
mod_st_start("Вывод боя",0);
$cans = sql("SELECT uid2 FROM turns_f WHERE uid1=".$pers["uid"]."");
$uids = '';
while($c = mysql_fetch_array($cans)) $uids.='<'.$c["uid2"].'>';
if ($_GET["fstate"]==1 and $pers["fstate"]<>1 and $pers["fstate"]=1) set_vars("fstate=1",$pers["uid"]);
if ($_GET["fstate"]==2 and $pers["fstate"]<>2 and $pers["fstate"]=2) set_vars("fstate=2",$pers["uid"]);
if ($_GET["fstate"]==3 and $pers["fstate"]<>3 and $pers["fstate"]=3) set_vars("fstate=3",$pers["uid"]);
if ($_GET["fstate"]==4 and $pers["fstate"]<>4 and $pers["fstate"]=4) set_vars("fstate=4",$pers["uid"]);
?><table border=0 width=100% cellspacing=0 cellpadding=0><tr><td width=250 valign=top align=center><?=show_pers_in_f($pers,3);?></td><td id=fight valign=top bgcolor=#CDCBCC></td><td width=250 valign=top id="pers_vs" align=center>&nbsp;</td></tr></table><script><?
$temp1=explode ("|",$fight["name"]);
$temp2=explode ("|",$fight["namevs"]);
$life1 = 0; $life2 = 0;
if ($team==1) echo "var whatteam=1;"; else echo "var whatteam=2;";
if ($fight["bplace"])$bplace = sqla("SELECT * FROM battle_places WHERE id=".$fight["bplace"]);
if ($_GET["no"]) 
sql("UPDATE battle_places SET xy='".$bplace["xy"].$pers["xf"]."_".$pers["yf"]."|' 
WHERE id=".$bplace["id"]."");

echo "var logid = '".$pers["cfight"]."';\nvar arrow_name='".ARROW_NAME."';\n";
echo "var x=".$pers["xf"].";var y=".$pers["yf"].";\nvar mid='".$fight["bplace"]."';\n";
echo "var damage_get=".intval($pers["damage_get"]-$pers["chp"]).";var damage_give=".intval($pers["damage_give"]).";\n";
if ($persvs["uid"]<>'bot')echo "var persvs_id='".$persvs["uid"]."';\n"; else
echo "var persvs_id='".$persvs["id"]."';\n"; 
$go_no_p = '|';
echo "var team1 = '";
$zzz = 1;
foreach ($temp1 as $tmp)
 { 
	if ($tmp)
	{
	if (strpos(" |".$tmp,'bot=')>0)
	 {
		$p = explode ("=",$tmp);
		$tmp = sqla("SELECT hp,ma FROM `bots` WHERE `id`='".$p[1]."'");
		$tmp["chp"]=$p[2];
		$xy = explode ("_",$p[4]);
		$tmp["xf"]=$xy[0];
		$tmp["yf"]=$xy[1];
		$tmp["user"]=$p[5];
		$tmp["sign"]="none";
		$tmp["level"]=$p[6];
		$tmp["uid"] = $p[7];
		$tmp["bot"] = $p[1];
		$tmp["cma"]=$tmp["ma"];
	}
	  else
		$tmp = sqla ("SELECT user,chp,level,sign,hp,uid,xf,yf,cma,ma,aura,uid FROM `users` 
		WHERE `user`='".$tmp."'");
		if($team==2)
		{
			if (substr_count($uids,'<'.$tmp["uid"].'>')==0) $zzz=0;
		}
	if (floor($tmp["chp"])>0)
	{
		$life1++;
		 if (substr_count($tmp["aura"],"invisible") and $tmp["uid"]<>$pers["uid"])
		 {
			$tmp["user"]='невидимка';
			$tmp["sign"]='none';
			$tmp["level"]='??';
			$tmp["chp"]=1;$tmp["hp"]=1;
			$tmp["cma"]=1;$tmp["ma"]=1;
		 }
		echo $tmp["sign"]."|".$tmp["user"]."|".$tmp["level"]."|".$tmp["chp"]."|".$tmp["hp"]."|".$tmp["cma"]."|".$tmp["ma"]."|".$tmp["xf"]."|".$tmp["yf"]."|".$tmp["uid"]."|".$tmp["bot"]."@";
		$go_no_p .= $tmp["xf"]."_".$tmp["yf"]."|";
	}
	}
 }
echo "';";
 
echo "var team2 = '";
foreach ($temp2 as $tmp)
 { 
 if ($tmp)
 {
	if (strpos(" ".$tmp,'bot=')>0)
	 {
		$p = explode ("=",$tmp);
		$tmp = sqla("SELECT hp,ma FROM `bots` WHERE `id`='".$p[1]."'");
		$tmp["chp"]=$p[2];
		$xy = explode ("_",$p[4]);
		$tmp["xf"]=$xy[0];
		$tmp["yf"]=$xy[1];
		$tmp["user"]=$p[5];
		$tmp["sign"]="none";
		$tmp["level"]=$p[6];
		$tmp["uid"] = $p[7];
		$tmp["bot"] = $p[1];
		$tmp["cma"]=$tmp["ma"];
	 }
	  else
		$tmp=sqla ("SELECT user,chp,level,sign,hp,uid,xf,yf,cma,ma,aura,uid FROM `users` WHERE `user`='".$tmp."'");
		if($team==1)
		{
			if (substr_count($uids,'<'.$tmp["uid"].'>')==0) $zzz=0;
		}
	if (floor($tmp["chp"])>0)
	{
		$life2++;
		if (substr_count($tmp["aura"],"invisible") and $tmp["uid"]<>$pers["uid"])
		 {
			$tmp["user"]='невидимка';
			$tmp["sign"]='none';
			$tmp["level"]='??';
			$tmp["chp"]=1;$tmp["hp"]=1;
			$tmp["cma"]=1;$tmp["ma"]=1;
		 }
		echo $tmp["sign"]."|".$tmp["user"]."|".$tmp["level"]."|".$tmp["chp"]."|".$tmp["hp"]."|".$tmp["cma"]."|".$tmp["ma"]."|".$tmp["xf"]."|".$tmp["yf"]."|".$tmp["uid"]."|".$tmp["bot"]."@";
		$go_no_p .= $tmp["xf"]."_".$tmp["yf"]."|";
	}
	}
 }
echo "';";
echo "var go_no='".$bplace["xy"].$go_no_p."';";

if ($fight["turn"]=='' or $fight["turn"]==' ') $fight["turn"]='finish';

$turns = explode("|",$fight["turn"]);
$str='';
foreach($turns as $t)
	if (substr_count($t,"=")>0 and $p=explode("=",$t)) $str.=$p[5]."=".$p[7]."|";
	elseif($p=sqla("SELECT uid,user,aura FROM users WHERE user='".$t."'"))	
	if (substr_count($p["aura"],"invisible"))
	$str.="невидимка=".$p["uid"]."|";
	else
	$str.=$p["user"]."=".$p["uid"]."|";
	
echo "var turn='';";

if (($fight["ltime"]<time()-$fight["timeout"]) and !empty($_GET["boytime"])) 
 {
	if ($_GET["boytime"]=='zavboy') 
	 {
		$tmp = $turns[0];
		$uid = $persvs['uid'];
		$travm = $fight['travm'];
		$tmp=sqla ("SELECT chp,uid,user,pol FROM `users` WHERE `user`='".$tmp."'");
		if ($tmp["chp"]>0)
		 { 
			if ($tmp["pol"]=="female") $m='а'; else $m='';
			$s = "<font class=time>".$lts."</font> <b>".$tmp["user"]."</b> проиграл".$m." бой по таймауту.".$s;
			$fight["all"]=$s.";".$fight["all"];
			$persvs['uid'] = $tmp['uid'];
			$fight['travm'] = 100;
			$persvs['chp']=0;
			$fight["turn"] = str_replace ($tmp["user"]."|","",$fight["turn"]);
			include ('inc/inc/fights/travm.php');
			sql ("UPDATE `users` SET `chp`=0,`losses`=losses+1 WHERE `uid`='".$tmp['uid']."'");
		 }
		sql ("UPDATE `fights` SET `all`='".addslashes($fight["all"])."',`turn`='".$fight["turn"]."' WHERE `id`='".$fight["id"]."'");
		$persvs['uid'] = $uid;
		$fight['travm'] = $travm;
		$turns = explode ("|",$fight["turn"]);
	}
	
	if ($_GET["boytime"]=='nicya') 
	 {
		sql ("UPDATE `fights` SET `turn`='nicya'  WHERE `id`='".$pers["cfight"]."'");
		$fight["turn"]='nicya';
	 }
 }
 
$lt = date("H:i:s ");

 if ($life1==0 or $life2==0 or $fight["turn"]=="finish" or $fight["turn"]=="finished" or $fight["turn"]=="timeout" or $fight["turn"]=="nicya" or count($turns)<2) include ('inc/inc/finish.php');
 else 
 {
 
$kblast=0;$kaura=0;$kkid=0;
$idall=explode ("|",BOOK_INDEX."|19|");
$img[0]='';$name[0]='';$idc[0]='';
$arimg[0]='';$arname[0]='';$arid[0]='';
$kidimg[0]='';$kidname[0]='';$kidid[0]='';

foreach ($idall as $id)
 if ($id<>"") 
  {
	$zak=sqla ("SELECT image,name,`id`,`type`,`describe`,`mana`,`time`,`t_in_c` FROM `zakl` WHERE `id`='".$id."' and ts6<=".$pers["s6"].";");
	if ($zak["t_in_c"]>0) $l = $zak["t_in_c"]." ход."; else $l = tp($zak["time"]);
	if ($zak["type"]<>'blast' and $zak["type"]<>'blast2' and $zak["type"]<>'push') $l = " || Время действия: ".$l; else $l='';
	$zak["name"] .= "[".$zak["mana"]."] :: ".$zak["describe"].$l;
	if ($zak["type"]=='blast' or $zak["type"]=='blast2' or $zak["type"]=='push')
	 {
		$kblast++;
		$img[$kblast]=$zak["image"];
		$name[$kblast]=$zak["name"];
		$idc[$kblast]=$zak["id"];
	 }
	elseif (@$zak["id"])
	 {
		$kaura++;
		$arimg[$kaura]=$zak["image"];
		$arname[$kaura]=$zak["name"];
		$arid[$kaura]=$zak["id"];
	 }
  }

if ($fight["stones"]>0) 
 {
	$kkid++;
	$kidimg[1]='kamen.gif';
	$kidname[1]='Острый камень['.$fight["stones"].']';
	$kidid[1]='kamen';
 }

 $timeout = $fight["ltime"]+$fight["timeout"]-time();
 if ($timeout<0) $timeout=0;
echo "var od=".(round($pers["sm5"]/3) + 3 + $pers["od_b"]).";show_fight_head(".$fight["oruj"].",".$fight["travm"].",".$timeout.");";
$bliz = '';
$bliz_od = '';
if ($pers["fstate"]==1 or $pers["fstate"]==0)
{
	$bliz = 'Простой|Прицельный|Оглушающий';
	$bliz_od = '1|2|5';
}
if ($pers["fstate"]==3)
{
	$bliz = 'Магия';
	$bliz_od = 'magic';
}
if ($pers["fstate"]==4)
{
	$bliz = 'Кинуть предмет';
	$bliz_od = 'kid';
}
if ($pers["posfight"]<20 and $pers["posfight"]>-10) $otoity = 0; else $otoity=1;$otoity=1;
$block = 'Простой|Усиленный|Крепчайший';
$block_od = '1|2|5';

if ($zzz==0 and $pers["chp"]>0) 
{
echo "var bliz='".$bliz."';";
echo "var bliz_od='".$bliz_od."';";
echo "var block='".$block."';";
echo "var block_od='".$block_od."';";
echo "var speed=".(1.5+$pers["sm4"]/30).";";
echo "show_boxes_and_form('".$addon."','".$addon_od."',".$pers["fstate"].");";
}
elseif ($fight["turn"]<>"finish" and $fight["turn"]<>"finished" and $fight["turn"]<>"nicya" and $fight["turn"]<>"timeout" and $life1<>0 and $life2<>0 and $pers["chp"]>0) {
 $str = '';
 if ($timeout>0) 
 echo "show_message_in_f('<b><center>Ожидаем хода соперника.</center></b><hr style=\"border-style: dotted; border-width: 1px\">');";
 else
 echo "show_message_in_f('<center>Бой закончен по таймауту.<br><input type=button class=submit value=Ничья onclick=\"location=\'main.php?boytime=nicya\'\"><input type=button class=submit value=\"Завершить бой\" onclick=\"location=\'main.php?boytime=zavboy\'\"></center><hr style=\"border-style: dotted; border-width: 1px\">');";

 }else echo "show_message_in_f('<b><center>Ожидаем конца боя.</center></b><hr style=\"border-style: dotted; border-width: 1px\">');";
echo '
var n = '.$kblast.';
img = new Array();
id = new Array();
nam = new Array();

var na = '.$kaura.';
kidimg = new Array();
kidid = new Array();
kidnam = new Array();

var nk = '.$kkid.';
arimg = new Array();
arid = new Array();
arnam = new Array();';

for ($i=1;$i<=$kblast;$i++) echo'
img['.$i.']="'.$img[$i].'";
nam['.$i.']="'.$name[$i].'";
id['.$i.']="'.$idc[$i].'";';

for ($i=1;$i<=$kaura;$i++) echo'
arimg['.$i.']="'.$arimg[$i].'";
arnam['.$i.']="'.$arname[$i].'";
arid['.$i.']="'.$arid[$i].'";';

for ($i=1;$i<=$kkid;$i++) echo'
kidimg['.$i.']="'.$kidimg[$i].'";
kidnam['.$i.']="'.$kidname[$i].'";
kidid['.$i.']="'.$kidid[$i].'";';
}
?></script><div id=log><?
$all = explode (";",$fight["all"]);
$i = 0;
while ($i<12) {echo $all[$i].";";$i++;}
?></div><script><?
 if ($life1==0 or $life2==0 or $fight["turn"]=="finish" or $fight["turn"]=="finished" or $fight["turn"]=="timeout" or $fight["turn"]=="nicya" or count($turns)<2) echo "log_replace();"; 
 else 
	echo "show_finish(".$pers["fexp"].",".$pers["exp_in_f"].",".$pers["f_turn"].");";
mod_st_fin();
if (!$persvs["bot"]) $pvs = '|||||||||'.$persvs["uid"]; else $pvs='|||'.$persvs["chp"].'|||||||'.$persvs["id"];
echo "info_p('".$pvs."');";
?></script>
