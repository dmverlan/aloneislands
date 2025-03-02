<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td width="96"><img border="0" src="images/design/borders/b1/lt.gif" width="96" height="114"></td>
		<td background="images/design/borders/b1/tbg.gif" align="center" width=100%>
		<img border="0" src="images/design/borders/b1/t.gif" width="380" height="114"></td>
		<td width="99">
		<img border="0" src="images/design/borders/b1/rt.gif" width="99" height="116"></td>
	</tr>
	<tr>
		<td width="96" background="images/design/borders/b1/l.gif">&nbsp;</td>
		<td width=100% align=center><div id=message class=hp>
<?
$_SESSION["a_type"]=$_POST["a_type"];
if ($pers["chp"]<$pers["hp"]/2 and $pers["cfight"]=10) echo "Вы слишком слабы для поединков.";
if ($_SESSION["a_type"]=="") $_SESSION["a_type"]="duel";
if (@$_GET["goarena"]) $_SESSION["a_type"]=$_GET["goarena"];
if (($wears = sqla("SELECT id FROM wp WHERE weared=1 and uidp='".$pers["uid"]."'"))
and $wears[0]<>0) $pers["wears"]='123'; else $pers["wears"]='';
if (isset($_POST["travm"]) and isset($_POST["timeout"]) and $_SESSION["a_type"]=='duel') 
	 {
		$_POST["travm"] = intval($_POST["travm"]);
		$_POST["timeout"] = intval($_POST["timeout"]);
		if ($_POST["travm"]<>'10' and $_POST["travm"]<>'30' and $_POST["travm"]<>'50' 
		and $_POST["travm"]<>'80')	$_POST["travm"]=10;
		if (str_replace ("none|","",$pers["wears"])<>"") $_POST["oruj"]=1;
		if ($_POST["oruj"]<>1 and $_POST["oruj"]<>0) $_POST["oruj"]=1;
		if ($_POST["timeout"]<>120 and $_POST["timeout"]<>180 and $_POST["timeout"]<>300) $_POST["timeout"]=120;
		if (sql ("
		INSERT INTO `zayavki` ( `name` , `travm` , `oruj` , `type` ,`timeout`,`time`) 
VALUES ('".$pers["user"]."','".$_POST["travm"]."','".$_POST["oruj"]."','duel','".$_POST["timeout"]."','".time()."');
 ") and sql ("UPDATE `users` SET cfight=1 WHERE uid='".$pers["uid"]."';")) 
		{echo"Заявка удачно подана";$pers["cfight"]=1;}
		else
		echo"Заявка не подана";
	 }
	 
if (count($_POST)==11 and $_SESSION["a_type"]=='group' and $pers["cfight"]==0) 
	 {
		$_POST["travm"]=intval($_POST["travm"]);
		$_POST["t1_k"]=intval($_POST["t1_k"]);
		$_POST["t2_k"]= intval($_POST["t2_k"]);
		$_POST["t1_l2"]=intval($_POST["t1_l2"]);
		$_POST["t1_l1"]=intval($_POST["t1_l1"]);
		$_POST["t2_l2"]=intval($_POST["t2_l2"]);
		$_POST["t2_l1"]=intval($_POST["t2_l1"]);
		if ($_POST["travm"]<>'10' and $_POST["travm"]<>'30' and $_POST["travm"]<>'50' 
		and $_POST["travm"]<>'80')	$_POST["travm"]=10;
		if (str_replace ("none|","",$pers["wears"])<>"") $_POST["oruj"]=1;
		if ($_POST["oruj"]<>1 and $_POST["oruj"]<>0) $_POST["oruj"]=1;
		if ($_POST["timeout"]<>120 and $_POST["timeout"]<>180 and $_POST["timeout"]<>300) $_POST["timeout"]=120;
		if ($_POST["wait"]<120) $_POST["wait"]=120;
		if ($_POST["t1_k"]<1) $_POST["t1_k"]=1;
		if ($_POST["t2_k"]<1) $_POST["t2_k"]=1;
		if ($_POST["t1_l2"]<$pers["level"]) $_POST["t1_l2"]=$pers["level"];
		if ($_POST["t1_l1"]>$pers["level"]) $_POST["t1_l1"]=$pers["level"];
		if (sql ("
		INSERT INTO `zayavki` (`name`,`travm`,`oruj`,`type`,`timeout`,`time`,`mpl1`,`minlvl1`,`maxlvl1`,`mpl2`,`minlvl2`,`maxlvl2`,`wait`) 
VALUES ('".$pers["user"]."|','".$_POST["travm"]."','".$_POST["oruj"]."','group','".$_POST["timeout"]."','".(time()+microtime())."','".$_POST["t1_k"]."','".$_POST["t1_l1"]."','".$_POST["t1_l2"]."','".$_POST["t2_k"]."','".$_POST["t2_l1"]."','".$_POST["t2_l2"]."','".$_POST["wait"]."');
 ") and sql ("UPDATE `users` SET cfight=3 WHERE uid='".$pers["uid"]."';")) 
		{echo"Заявка удачно подана";$pers["cfight"]=3;}
		else
		echo"Заявка не подана";
	 }

if (@$_GET["z"]=="otkaza" and $pers["cfight"]==2) {
if (sql("UPDATE zayavki SET `vsname`='' WHERE `vsname`='".$pers["user"]."'"))
sql ("UPDATE `users` SET cfight=0 WHERE uid='".$pers["uid"]."';");
$pers["cfight"]=0;
}

if (@$_GET["z"]=="otkaz" and $pers["cfight"]==1) {
$z = mysql_fetch_array (sql ("SELECT vsname FROM zayavki WHERE `name`='".$pers["user"]."'"));
if (sql("UPDATE zayavki SET `vsname`='' WHERE `name`='".$pers["user"]."'"))
sql ("UPDATE `users` SET cfight=0 WHERE user='".$z["vsname"]."';");
}

if (@$_GET["z"]=="begin" and $pers["cfight"]==1) {
$z = sqla ("SELECT * FROM zayavki WHERE `name`='".$pers["user"]."'");
if ($z["vsname"])
{
begin_fight ($z["name"],$z["vsname"],"Дуэль на арене",$z["travm"],$z["timeout"],$z["oruj"]);
sql ("DELETE FROM zayavki WHERE `name`='".$pers["user"]."'");
echo "Начинаем бой. Ждите.";
}
}

if (@$_POST["tobot"] and $pers["cfight"]==0 and $pers["last_bot_time"]+$pers["level"]*200<time()) {
$bot = sqla ("SELECT * FROM bots WHERE obr='bear' and level>".($pers["level"]-2)." and level<".($pers["level"]+2)." and id=".intval($_POST["tobot"]));
if (@$bot["user"])
{
$travm=0;
if ($bot["level"]==$pers["level"]-1) $travm=30;
if ($bot["level"]==$pers["level"]) $travm=50;
if ($bot["level"]==$pers["level"]+1) $travm=80;
if ($travm>0)
{
begin_fight ($pers["user"],"bot=".$bot["id"],"Дуэль на арене",$travm,300,1);
set_vars ("refr=1,last_bot_time=".time(),UID);
echo "Начинаем бой. Ждите.";
}
}
}

if (@$_POST["towhozay"] and $pers["cfight"]==0 and $_SESSION["a_type"]=='duel') {
$z = mysql_fetch_array (sql ("SELECT * FROM zayavki WHERE `name`='".$_POST["towhozay"]."'"));
if ($z["oruj"]==1 or ($z["oruj"]==0 and str_replace("none|","",$pers["wears"])=='')){
if (sql("UPDATE zayavki SET `vsname`='".$pers["user"]."' WHERE `name`='".$_POST["towhozay"]."'"))
sql ("UPDATE `users` SET cfight=2 WHERE uid='".$pers["uid"]."';");
sql ("UPDATE `users` SET refr=1 WHERE user='".$_POST["towhozay"]."'");
$pers["cfight"]=2;
}}

if (@$_POST["towhozay_g"] and $pers["cfight"]==0 and $_SESSION["a_type"]=='group') {
$twz = explode ("_",$_POST["towhozay_g"]);
$z = mysql_fetch_array (sql ("SELECT * FROM zayavki WHERE time='".$twz[0]."'"));
$l=0;
if (substr_count($z["name"],"|")<$z["mpl1"] and $twz[1]=='1') $l=1;
if (substr_count($z["vsname"],"|")<$z["mpl2"] and $twz[1]=='2') $l=1;

if ($pers["level"]>=$z["minlvl".$twz[1]] and $pers["level"]<=$z["maxlvl".$twz[1]] and $l==1)
if ($z["oruj"]==1 or ($z["oruj"]==0 and str_replace("none|","",$pers["wears"])=='')){
if ($twz[1]=='1')
if (sql("UPDATE zayavki SET `name`=CONCAT(name,'".$pers["user"]."|') WHERE `time`='".$twz[0]."'"));
if ($twz[1]=='2')
if (sql("UPDATE zayavki SET `vsname`=CONCAT(vsname,'".$pers["user"]."|') WHERE `time`='".$twz[0]."'"));
sql ("UPDATE `users` SET cfight=4 WHERE uid='".$pers["uid"]."';");
$pers["cfight"]=4;
}
}

if($pers["cfight"]==2) echo "Ожидаем подтверждения";
if($pers["cfight"]==4) echo "Ожидаем начала боя";

function show_create_z_duel ()
 {
	GLOBAL $pers;
	if ($pers["cfight"]==0) $p = '<input class=laar type=submit value=Подать>';
	else
	$p = '<input class=laar type=submit value=Подать DISABLED>';
	if (str_replace("none|","",$pers["wears"])=='')$r='<option value="0">Рукопашная</option>';
	echo '		<form method=post action=main.php>
	<input type=hidden name=a_type value=duel>
				<td class="time" width="159" align="center">Подать заявку: </td>
				<td width="520" align="center">
				<select size="1" name="oruj" class=laar>
				<option selected>Тип боя</option>
				<option value="1">C оружием</option>
				'.$r.'
				</select><select size="1" name="travm" class=laar>
				<option selected>Травматичность</option>
				<option value=10>Минимум</option>
				<option value=30>Средняя</option>
				<option value=50>Высокая</option>
				<option value=80>Очень высокая</option>
				</select><select size="1" name="timeout" class=laar>
				<option selected>Timeout</option>
				<option value="120">2 минуты</option>
				<option value="180">3 минуты</option>
				<option value="300">5 минут</option>
				</select>'.$p.'<input class=laar onclick="location=&quot;main.php?goarena=duel&quot;" type="button" value="Обновить"></td>
				</form>';
 }
function show_create_z_group ()
 {
 GLOBAL $pers;
 if ($pers["cfight"]<>0) $p = 'DISABLED';
 if (str_replace("none|","",$pers["wears"])=='')$r='<option value="0">Рукопашная</option>';
	echo '		<form method=post action=main.php>
				<input type=hidden name=a_type value=group>
				<td class="time" width="159" align="center">Подать заявку: </td>
				<td width="520" align="center">
				<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td><font class=time><select size="1" name="oruj" class=laar>
				<option selected>Тип боя</option>
				<option value="1">C оружием</option>
				'.$r.'
				</select></font></td>
						<td align="center"><font class=time><select size="1" name="travm" class=laar>
				<option selected>Травматичность</option>
				<option value=10>Минимум</option>
				<option value=30>Средняя</option>
				<option value=50>Высокая</option>
				<option value=80>Очень высокая</option>
				</select></font></td>
						<td align="right"><font class=time><select size="1" name="timeout" class=laar>
				<option selected>Timeout</option>
				<option value="120">2 минуты</option>
				<option value="180">3 минуты</option>
				<option value="300">5 минут</option>
				</select><select size="1" name="wait" class=laar>
				<option selected>Ожидание</option>
				<option value="120">2 минуты</option>
				<option value="180">3 минуты</option>
				<option value="300">5 минут</option>
				<option value="600">10 минут</option>
				<option value="1800">30 минут</option>
				</select></font></td>
					</tr>
					<tr>
						<td style="border-left-width: 1px; border-right-style: solid; border-right-width: 1px; border-top-width: 1px; border-bottom-width: 1px">
						<font class=time>Кол-во<input type="text" name="t1_k" size="4" class="fightlong" value="0">,
				<input type="text" name="t1_l1" size="4" class="fightlong" value="0">-<input type="text" name="t1_l2" size="4" class="fightlong" value="1"></font></td>
						<td>&nbsp;</td>
						<td align="right" style="border-left-style: solid; border-left-width: 1px; border-right-width: 1px; border-top-width: 1px; border-bottom-width: 1px">
						<font class=time>Кол-во<input type="text" name="t2_k" size="4" class="fightlong" value="0">,
				<input type=text name="t2_l1" size="4" class="fightlong" value="0">-<input type="text" name="t2_l2" size="4" class="fightlong" value="1"></font></td>
					</tr>
					<tr>
						<td class=ma>Ваша Команда</td>
						<td align="center"><input class=laar type=submit value=Подать '.$p.'><input class=laar onclick="location=\'main.php?goarena=group\'" type=button value=Обновить></td>
						<td align="right" class="ma">Команда 
						соперников</td>
						</tr>
				</table>
				<font class=time>&nbsp;</font></td>
			</form>
			';
 }

function show_z_duel ($n,$nvs,$orj,$tr,$timeout)
{
GLOBAL $pers;
$n = str_replace ("|","",$n);
$n = sqla("SELECT user,level,sign,state,aura FROM users WHERE user='".$n."'");
$nvs = str_replace ("|","",$nvs);
$nvs = sqla("SELECT user,level,sign,state,aura FROM users WHERE user='".$nvs."'");

if ($orj==1 or ($orj==0 and str_replace("none|","",$pers["wears"])==''))
	$dis = '';
else
	$dis = 'DISABLED';
	
if (substr_count($n["aura"],"invisible")) $ntext = ' <i>невидимка</i>[<font class=lvl>??</font>]';
else $ntext = '<img src=images/signs/'.$n["sign"].'.gif title="'.$n["state"].'"> <b>'.$n["user"].'</b>[<font class=lvl>'.$n["level"].'</font>]<img style="CURSOR: hand" onclick="javascript:window.open(\'info.php?p='.$n["user"].'\',\'_blank\')" src=images/info.gif>';

if ($nvs["user"])
{
if (substr_count($n["aura"],"invisible"))
$nvstext = ' <i>невидимка</i>[<font class=lvl>??</font>]';else
$nvstext = '<img src=images/signs/'.$nvs["sign"].'.gif title="'.$nvs["state"].'"> <b>'.$nvs["user"].'</b>[<font class=lvl>'.$nvs["level"].'</font>]<img style="CURSOR: hand" onclick="javascript:window.open(\'info.php?p='.$nvs["user"].'\',\'_blank\')" src=images/info.gif>';
}
else
{
if ($pers["cfight"]==0) 
$nvstext = "<input type=radio value='".$n["user"]."' name='towhozay' ".$dis.">нет соперника.";
else
$nvstext = "<input type=radio value='".$n["user"]."' name='towhozay' DISABLED>нет соперника.";
}



if ($orj==1) $o_t = 'С оружием'; else $o_t = 'Без оружия';
$t_t = 'Травматичность '.$tr.'%';
$timeout = tp($timeout);
echo '
<tr>
						<td width="17">
						<img title="'.$o_t.'" src="images/arena/zayor_'.$orj.'.gif" style="cursor:hand"></td>
						<td width="17">
						<img title="'.$t_t.'" src="images/arena/blood_'.$tr.'.gif" style="cursor:hand"></td>
						<td width="7" class="time" style="border-style: solid; border-width: 1px" title=timeout style="cursor:hand">'.$timeout.'</td>
						<td width="35">&nbsp;</td>
						<td width="459" class="items">'.$ntext.'
						против '.$nvstext.'
						</td>
					</tr>
	';
}

function show_z_group ($n,$nvs,$orj,$tr,$timeout,$time,$k1,$l1m,$l1n,$k2,$l2m,$l2n,$wait)
{
$nvs_s = $nvs;
$n_s = $n;
GLOBAL $pers;
$n = explode ("|",$n);
$s1 = '';
foreach ($n as $e) {
$e = sqla("SELECT user,level,sign,state,aura FROM users WHERE user='".$e."'");
if ($e[0]<>'')
{
if(substr_count($e["aura"],"invisible"))
$s1 .= '<i>невидимка</i>[<font class=lvl>??</font>] ,';
else
$s1 .= '<img src=images/signs/'.$e["sign"].'.gif title="'.$e["state"].'"><b>'.$e["user"].'</b>[<font class=lvl>'.$e["level"].'</font>]<img style="CURSOR: hand" onclick="javascript:window.open(\'info.php?p='.$e["user"].'\',\'_blank\')" src=images/info.gif> ,';
}
}
$s1 = substr($s1,0,strlen($s1)-2);

$s2 = '';
$nvs = explode ("|",$nvs);
foreach ($nvs as $e){
$e = sqla("SELECT user,level,sign,state FROM users WHERE user='".$e."'");
if ($e[0]<>'')
{
if(substr_count($e["aura"],"invisible"))
$s2 .= '<i>невидимка</i>[<font class=lvl>??</font>] ,';
else
$s2 .= '<img src=images/signs/'.$e["sign"].'.gif title="'.$e["state"].'"><b>'.$e["user"].'</b>[<font class=lvl>'.$e["level"].'</font>]<img style="CURSOR: hand" onclick="javascript:window.open(\'info.php?p='.$e["user"].'\',\'_blank\')" src=images/info.gif> ,';
}
}
$s2 = substr($s2,0,strlen($s2)-2);

if ($orj==1 or ($orj==0 and str_replace("none|","",$pers["wears"])==''))
	$dis = '';
else
	$dis = 'DISABLED';
	
if ($pers["level"]>=$l1m and $pers["level"]<=$l1n and substr_count($n_s,"|")<$k1) $dis_1 =''; else $dis_1 ='DISABLED';
if ($pers["level"]>=$l2m and $pers["level"]<=$l2n and substr_count($nvs_s,"|")<$k2) $dis_2 =''; else $dis_2 ='DISABLED';

if ($s2=='')
if ($pers["cfight"]==0) 
$s2 = "<input type=radio value='".$time."_2' name='towhozay_g' ".$dis." ".$dis_2.">нет соперников.";
else
$s2 = "<input type=radio DISABLED>нет соперников.";
else
if ($pers["cfight"]==0 and substr_count($nvs_s,"|")<$k2) 
$s2 = "<input type=radio value='".$time."_2' name='towhozay_g' ".$dis." ".$dis_2.">".$s2;
else
$s2 = "<input type=radio DISABLED>".$s2;

if ($pers["cfight"]==0 and substr_count($n_s,"|")<$k1) 
$s1 = "<input type=radio value='".$time."_1' name='towhozay_g' ".$dis." ".$dis_1.">".$s1;
else
$s1 = "<input type=radio DISABLED>".$s1;


if ($orj==1) $o_t = 'С оружием'; else $o_t = 'Без оружия';
$t_t = 'Травматичность '.$tr.'%';
$timeout = tp($timeout);
echo '
<tr>
						<td width="17">
						<img title="'.$o_t.'" src="images/arena/zayor_'.$orj.'.gif" style="cursor:hand"></td>
						<td width="17">
						<img title="'.$t_t.'" src="images/arena/blood_'.$tr.'.gif" style="cursor:hand"></td>
						<td width="7" class="time" style="border-style: solid; border-width: 1px" title=timeout style="cursor:hand">'.$timeout.'</td>
						<td width="459" class="items"><font class=time>'.$k1.'('.$l1m.'-'.$l1n.') vs '.$k2.'('.$l2m.'-'.$l2n.')</font> '.$s1.'
						против '.$s2.'  <u>('.tp(abs(round($wait-(time()-$time)))).')
						</u></td>
					</tr>
	';
}

function show_z () 
{
	if ($_SESSION["a_type"]=='duel') 
	 {
		$resault = sql ("SELECT * FROM zayavki WHERE type='duel'");
		$i=0;
		echo "<form method=post action=main.php>";
		while ($z = mysql_fetch_array ($resault))
		 {
		 if ($z["time"]<time()-600) 
		 {
			sql ("DELETE FROM zayavki WHERE name='".$z["name"]."'");
			sql ("UPDATE users SET cfight=0 WHERE user='".$z["name"]."' or user='".$z["vsname"]."'");
		 }
		 $i++;
		 show_z_duel ($z["name"],$z["vsname"],$z["oruj"],$z["travm"],$z["timeout"]);
		 }
		if ($i==0)  echo "В этой категории нет заявок.";
	 }
	 if ($_SESSION["a_type"]=='group') 
	 {
		$resault = sql ("SELECT * FROM zayavki WHERE type='group'");
		$i=0;
		echo "<form method=post action=main.php?goarena=group>";
		while ($z = mysql_fetch_array ($resault))
		 {
		 if ($z["wait"]-(time()-$z["time"])<0 or (substr_count($z["name"],"|")==$z["mpl1"] and 
		 substr_count($z["vsname"],"|")==$z["mpl2"])) 
		 {
			if ($z["vsname"]<>'')
			{
			begin_fight ($z["name"],$z["vsname"],"Групповой бой на арене",$z["travm"],$z["timeout"],$z["oruj"]);
			echo "Начинаем бой. Ждите.";
			}
			else
			{
			$a = explode ("|",$z["name"]);
			$s = "UPDATE users SET cfight=0 WHERE user='".$a[0]."'";
			unset ($a[0]);
			foreach ($a as $e) 
			$s = $s." or user='".$e."'";
			sql ($s);
			}
			sql ("DELETE FROM zayavki WHERE time='".$z["time"]."'");
		 }else{
		 $i++;
		 show_z_group ($z["name"],$z["vsname"],$z["oruj"],$z["travm"],$z["timeout"],$z["time"],$z["mpl1"],$z["minlvl1"],$z["maxlvl1"],$z["mpl2"],$z["minlvl2"],$z["maxlvl2"],$z["wait"]);
		 }
		 }
		if ($i==0)  echo "В этой категории нет заявок.";
	 }
	 if ($_SESSION["a_type"]=='bot') 
	 {
		$pers = catch_user (UID);
		if ($pers["last_bot_time"]+$pers["level"]*300>time() and $pers["cfight"]==0)
		echo "<font class=hp>Вы можете провести бой со зверем через 
		".tp($pers["last_bot_time"]+$pers["level"]*300-time())."</font>";
		else
		{
			echo "<form method=post action=main.php>";
			$bot1 = sqla("SELECT * FROM bots WHERE obr='bear' and level=".($pers["level"]-1));
			$bot2 = sqla("SELECT * FROM bots WHERE obr='bear' and level=".($pers["level"]+0));
			$bot3 = sqla("SELECT * FROM bots WHERE obr='bear' and level=".($pers["level"]+1));
			if (@$bot1["user"]) 
			echo '<tr>
						<td width="17">
						<img title="C оружием" src="images/arena/zayor_1.gif" style="cursor:hand"></td>
						<td width="17">
						<img title="30" src="images/arena/blood_30.gif" style="cursor:hand"></td>
						<td width="7" class="time" style="border-style: solid; border-width: 1px" title=timeout style="cursor:hand">5м</td>
						<td width="35">&nbsp;</td>
						<td width="459" class="items"><input type=radio value="'.$bot1["id"].'" name="tobot"><b>'.$bot1["user"].'</b>[<font class=lvl>'.$bot1["level"].'</font>]<img style="CURSOR: hand" onclick="javascript:window.open(\'binfo.php?'.$bot1["id"].'\',\'_blank\')" src=images/info.gif></td></tr>';
			if (@$bot2["user"]) 
			echo '<tr>
						<td width="17">
						<img title="C оружием" src="images/arena/zayor_1.gif" style="cursor:hand"></td>
						<td width="17">
						<img title="30" src="images/arena/blood_50.gif" style="cursor:hand"></td>
						<td width="7" class="time" style="border-style: solid; border-width: 1px" title=timeout style="cursor:hand">5м</td>
						<td width="35">&nbsp;</td>
						<td width="459" class="items"><input type=radio value="'.$bot2["id"].'" name="tobot"><b>'.$bot2["user"].'</b>[<font class=lvl>'.$bot2["level"].'</font>]<img style="CURSOR: hand" onclick="javascript:window.open(\'binfo.php?'.$bot2["id"].'\',\'_blank\')" src=images/info.gif></td></tr>';
			if (@$bot3["user"]) 
			echo '<tr>
						<td width="17">
						<img title="C оружием" src="images/arena/zayor_1.gif" style="cursor:hand"></td>
						<td width="17">
						<img title="30" src="images/arena/blood_80.gif" style="cursor:hand"></td>
						<td width="7" class="time" style="border-style: solid; border-width: 1px" title=timeout style="cursor:hand">5м</td>
						<td width="35">&nbsp;</td>
						<td width="459" class="items"><input type=radio value="'.$bot3["id"].'" name="tobot"><b>'.$bot3["user"].'</b>[<font class=lvl>'.$bot3["level"].'</font>]<img style="CURSOR: hand" onclick="javascript:window.open(\'binfo.php?'.$bot3["id"].'\',\'_blank\')" src=images/info.gif></td></tr>';
			if (empty($bot1["user"]) and empty($bot2["user"]) and empty($bot3["user"]))
			echo "Извините, для вас подходящего зверя нет.";
		}
		echo "</form>";
	 }
}

function take_z(){
	GLOBAL $pers;
	if ($_SESSION["a_type"]=="duel")
{
	if ($pers["cfight"]==0)
	echo '<input class="loc" type="submit" value="Принять вызов на бой"></form>';
	if ($pers["cfight"]==1)
	{
	$z = sqla ("SELECT name,vsname FROM zayavki WHERE `name`='".$pers["user"]."'");
	if ($z["vsname"]<>'') echo '<input class="loc" onclick="location=\'main.php?z=begin\'" type="button" value="Начать поединок"><input class="loc" onclick="location=\'main.php?z=otkaz\'" type="button" value="Отказаться">';
	echo '<input type=hidden name=delete value=1><input class="loc" type="submit" value="Удалить заявку"></form>';
	}
	if ($pers["cfight"]==2)
	{
	echo '<input class="loc" onclick="location=\'main.php?z=otkaza\'" type="button" value="Отказаться">';
	}
}
	if ($_SESSION["a_type"]=="group")
{
	if ($pers["cfight"]==0)
	echo '<input class="loc" type="submit" value="Принять вызов на бой"></form>';
	if ($pers["cfight"]==3)
	{
	$z = mysql_fetch_array (sql ("SELECT name FROM zayavki WHERE `name`='".$pers["user"]."|' and vsname=''"));
	if ($z["name"]<>'') 
	echo '<input type=hidden name=delete value=2><input class="loc" type="submit" value="Удалить заявку"></form>';
	}
}
	if ($_SESSION["a_type"]=="bot")
{
	if ($pers["last_bot_time"]+$pers["level"]*300<time())
	echo '<input class="loc" type="submit" value="Начать бой со зверем"></form>';
}

}

if ($pers["cfight"]==1 and $_POST["delete"]==1)
{	
	$z = sqla ("SELECT name,vsname FROM zayavki WHERE `name`='".$pers["user"]."'");
	if (sql ("DELETE FROM zayavki WHERE name='".$pers["user"]."';	
 ") and sql ("UPDATE `users` SET cfight=0 WHERE uid='".$pers["uid"]."' or user='".$z["vsname"]."';")) 
		{echo"Заявка удачно удалена";$pers["cfight"]=0;}
		else
		echo"Заявка не удалена";
}

if ($pers["cfight"]==3 and $_POST["delete"]==2)
{	
	$z = mysql_fetch_array (sql ("SELECT name,vsname FROM zayavki WHERE `name`='".$pers["user"]."|' and vsname=''"));
	if ($z["name"]<>'') 
	{
	if (sql ("DELETE FROM zayavki WHERE name='".$pers["user"]."|';	
 ") and sql ("UPDATE `users` SET cfight=0 WHERE uid='".$pers["uid"]."';")) 
	{echo "Заявка удачно удалена";$pers["cfight"]=0;}
	else
	echo "Заявка не удалена";
	}
}

?>
</div></center>
<hr>
<table width="100%" cellspacing="0" cellpadding="0" style="border-left-width: 1px; border-right-width: 1px; border-bottom-width: 1px">
	<tr>
		<td class="inv" align="center">
		<a href="main.php?goarena=duel"><u>Дуэли</u></a></td>
		<td class="inv" align="center">
		<a href="main.php?goarena=group"><u>Групповые</u></a></td>
		<td class="inv" align="center">
		<a href="main.php?goarena=haot"><u></u></a>Хаоты</td>
		<td class="inv" align="center">
		<a href="main.php?goarena=statistic"><u></u></a>Статистика</td>
	</tr>
</table>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center">
		<hr style="color: #800000" size=1>
		<table border="0" width="679" cellpadding="2">
			<tr>
			<? 
				if ($_SESSION["a_type"]=="duel")show_create_z_duel();
				if ($_SESSION["a_type"]=="group")show_create_z_group();
				if ($_SESSION["a_type"]=="haot")show_create_z_haot();
				if ($_SESSION["a_type"]=="statistic")show_create_z_st();
			?>
			<tr>
				<td width="679" colspan="2" style="border-left-width: 1px; border-right-width: 1px; border-top-style: solid; border-top-width: 1px; border-bottom-width: 1px" class="fightlong" align="center">
				<table border="0" width="535" cellspacing="0" cellpadding="0">
					<?
					show_z();
					?>
				</table>
				</td>
			</tr>
			<tr>
				<td width="679" colspan="2" style="border-left-width: 1px; border-right-width: 1px; border-top-style: solid; border-top-width: 1px; border-bottom-width: 1px" class="fightlong" align="center">&nbsp;<? take_z(); ?></td>
			</tr>
		</table>
		</td>
	</tr>
</table>





</td>
		<td width="99" background="images/design/borders/b1/r.gif">&nbsp;</td>
	</tr>
	<tr>
		<td width="96">
		<img border="0" src="images/design/borders/b1/lb.gif" width="96" height="109"></td>
		<td background="images/design/borders/b1/bbg.gif" align="center" width=100%>
		<img border="0" src="images/design/borders/b1/b.gif" width="380" height="106"></td>
		<td width="99">
		<img border="0" src="images/design/borders/b1/rb.gif" width="99" height="107"></td>
	</tr>
</table>
