<SCRIPT LANGUAGE="JavaScript" src="js/ch.js?7"></SCRIPT>
<script><?
//error_reporting(0);
include ('inc/functions.php');

$server_state = tme()+microtime();
if (isset($_POST["message"]))
 {
	$_POST["type"] = intval($_POST["type"]);
	$_POST["message"]=trim($_POST["message"]);
	if ($_POST["type"]<>1 and $_POST["type"]<>3) $_POST["type"]=2;
	$_POST["message"] = str_replace ("\\","",$_POST["message"]);
	$_POST["message"] = str_replace (".х","//",$_POST["message"]);
	$_POST["message"] = str_replace ("/[","//",$_POST["message"]);
	$_POST["message"] = str_replace ("•",".",$_POST["message"]);
	$m=$_POST["message"];
	$i=strlen($m)-1;
	while ($m[$i]<>'|' and $i>0) $i--;
	if ($i>0)$_POST["message"] = substr($m,$i+1,strlen($m)-$i);
	$_POST["towho"] = substr($m,0,$i)."|";
	if ($_POST["towho"]=="|") $_POST["towho"]="";
	unset($m);unset($i);
	if ($_POST["ttype"]=="priv") $_POST["priv"] = 1; else $_POST["priv"] = 0;
 }

$info = 0;
$uid = intval($_COOKIE["uid"]);
$opt = explode ("|",$_COOKIE["options"]);

include ("configs/config.php");
$db = $mysqlbase;
$main_conn = mysql_connect ($mysqlhost,$mysqluser,$mysqlpass);
mysql_select_db($mysqlbase, $main_conn);

//sql("SELECT COALESCE(GET_LOCK('".intval($_COOKIE["uid"])."', 60));");


##############################Боты
if(tme()%5==0)
include ("bots/attack.php");
###############################

	$pers=mysql_fetch_array(sql("SELECT * FROM `users`	WHERE `uid`= ".$uid." LIMIT 0,1;"));

										##
										if ($pers["location"]<>"cherch")
											$pers["location"] = '--';
										else
											$_POST["priv"]=0;
										##
	$a_m = $pers["a_m"];
	$flood = $pers["flood"];
	$chcolor = $opt[5];
	if ($pers["block"] or $pers["pass"]<>$_COOKIE["hashcode"] or !$pers["user"]) exit;
	if ($pers["invisible"]< tme())
		$online='`online`=1 ,';
	else
		$online='';
	if ($pers["diler"]) $pers["rank"].="<diler><molch><pv><prison><block><w_pom><b_info><punishment>";


//Добавляем сообщение
if (@$_POST["message"] and $pers["silence"]<=tme())
{
// РВC
$rvs = 0;
$m = $_POST["message"];
if (is_rvs($m." ".$_POST["towho"]))
{
say_to_chat ("a",'Персонаж <b>'.$pers["user"].'</b> замолчал на 2 минуты. Подозрение на РВС.(<b>World Spawn</b>)',0,'','*');
$a["image"] = 'molch';
$a["params"] = '';
$a["esttime"] = 120;
$a["name"] = 'Заклинание молчания';
$a["special"] = 1;
light_aura_on($a,$pers["uid"]);
sql ("UPDATE `users` SET silence=".(tme()+$a["esttime"])." WHERE `uid`=".$pers["uid"]."");
$flood=0;
}elseif (is_mat($m))
{
say_to_chat ("a",'Персонаж <b>'.$pers["user"].'</b> замолчал на 10 минут. Подозрение на мат.(<b>World Spawn</b>)',0,'','*');
$a["image"] = 'molch';
$a["params"] = '';
$a["esttime"] = 600;
$a["name"] = 'Заклинание молчания';
$a["special"] = 1;
light_aura_on($a,$pers["uid"]);
sql ("UPDATE `users` SET silence=".(tme()+$a["esttime"])." WHERE `uid`=".$pers["uid"]."");
$flood=0;
}elseif (is_rkp($m))
{
say_to_chat ("a",'Персонаж <b>'.$pers["user"].'</b> замолчал на 10 минут. Подозрение на РКП.(<b>World Spawn</b>)',0,'','*');
$a["image"] = 'molch';
$a["params"] = '';
$a["esttime"] = 600;
$a["name"] = 'Заклинание молчания';
$a["special"] = 1;
light_aura_on($a,$pers["uid"]);
sql ("UPDATE `users` SET silence=".(tme()+$a["esttime"])." WHERE `uid`=".$pers["uid"]."");
$flood=0;
}
else
{
	////////////////////////////////////
	if ($_POST["type"]<>3)
	{
	if ((tme())<($pers["lasto"]+2)) $flood++; else $flood=0;
		if ($m[0]=='%')
		{
		 if ($m[1]=='u' and $pers["diler"]) $m = "<u>".substr($m,2,strlen($m))."</u>";
		 if (($m[1]=='b'or$m[1]=='и') and $pers["diler"]) $m = "<b>".substr($m,2,strlen($m))."</b>";
		 if ($m[1]=='i' and $pers["diler"]) $m = "<i>".substr($m,2,strlen($m))."</i>";
		 if ($m[1]=='h' and $pers["diler"]) $m = "<h3>".substr($m,2,strlen($m))."</h3>";
		 if (($m[1]=='g' or $m[1]=='п') and $pers["diler"]) $m = "<h2>".substr($m,2,strlen($m))."</h2>";
		}
	$priv=0;
	if (@$_POST["ttype"]=="priv") $priv=1;
	if (empty($_POST["towho"])) $towho=""; else $towho = $_POST["towho"];
	$lt = date("H:i:s");
	if (empty($towho)) $priv=0;
	if ($chcolor<>'') $color=str_replace("#","",$chcolor); else $color="000000";
	if ($priv==0 and $pers["invisible"]>tme()) {$pers["user"]='n='.$pers["user"];$color="000000";}
	if ($_POST['ttype']=="clan")
	{
		$clan = $pers["sign"];
		sql ("INSERT INTO `chat` 		(`id`,`user`,`towho`,`private`,`location`,`message`,`time`,`telepat`,`clan`,`color`,`type`)
		VALUES (0,'".$pers["user"]."','".$towho."' , '".$priv."',
		'".$pers["location"]."' , '".$m."' , '".$lt."','".$telepat."','".$clan."','".$color."',".$_POST["type"].");");
	 }
	else
		sql ("INSERT INTO `chat` (`id`,`user`,`towho`,`private`,`location`,`message`,`time`,`telepat`,`color`,`type`)
		VALUES (0,'".$pers["user"]."','".$towho."','".$priv."',
		'".$pers["location"]."','".$m."','".$lt."','".$telepat."','".$color."',".$_POST["type"].");");
	 }
 else
 {
 		$user = $pers["user"];
 		if($pers["invisible"]>tme()) $user = 'Невидимка';
		sql("INSERT INTO `fight_log` ( `time` , `log` , `cfight` , `turn` )
VALUES (
'".date("H:i")."', '".$user." : ".addslashes($m)."', '".$pers["cfight"]."', '".round((time()+microtime()),2)."'
);");
 }

 echo "top.clearer = 1;";
 }
}
elseif ($pers["silence"]>tme())
 echo "top.clearer = 1;";


//Вывод сообщений...

if ($pers["sign"]<>'s')
$res = sql ("
	SELECT * FROM `chat` WHERE (`id`>".$pers["chat_last_id"].") and
	(location='".$pers["location"]."' or `user`='s' or `telepat`='1' or `clan`='".$pers["sign"]."' or location='*')");
else
$res = sql ("SELECT * FROM `chat` WHERE `id`>".$pers["chat_last_id"]."");

$cfgs = mysql_fetch_array (sql("SELECT a_message,m_frequency FROM configs"));
if ($a_m<time() and date("i")%$cfgs[1]==0)
 {
	$info = 1;
	$a_m=time()+60;
	$tx["time"]=date("H:i:s");
	$tx["user"]='a';
	$tx["message"] = "".$cfgs[0]."";
	$tx["color"]='000000';
 }
 unset($cfgs);


$ignore = '';
$ign  = sql("SELECT nick FROM ignor WHERE uid=".$pers["uid"]."");
while ($ig = mysql_fetch_array($ign,MYSQL_ASSOC))
 $ignore.= '<'.$ig["nick"].'>';

 /*
if ((50-$pers["level"])>rand(1,500))
{
	$tip = mysql_fetch_array (sql("SELECT id,title,text FROM tips WHERE maxlevel>".$pers["level"]." ORDER BY RAND()"));
	$view = mysql_result (sql("SELECT uid FROM no_tips WHERE uid=".$pers["uid"]." and tip_id=".$tip["id"]." "),0);
	if ($tip and !$view)
	echo "show_tip('".$tip["title"]."','".$tip["text"]."',".intval($tip["id"]).");";
}
 */

$s = '';
while (($txt = mysql_fetch_array ($res)) or $info==1)
 {
 if (substr_count($ignore,'<'.$txt["user"].'>')) continue;
 if ($pers["chat_last_id"]<$txt["id"]) $pers["chat_last_id"]=$txt["id"];
	$k=0;
	if (substr_count($pers["rank"],"<pv>") or $pers["sign"]=='watchers') ;
	elseif (substr_count($txt["user"],"n=")) $txt["user"]='n';
	if (empty($txt) and $info==1)
	 {
		$info=0;
		$txt=$tx;
	 }
	if ($txt["time"]=='') $txt["time"] = date ("H:i:s");

	if ($txt["private"]==1 and ($txt["user"]==$pers["user"] or
	substr_count("|".$txt["towho"]."|","|".$pers["user"]."|") or $pers["sign"]=='s')) $k = 1;
	if ($txt["private"]<>1) $k=1;
	if ($txt["clan"]==$pers["sign"] and $txt["clan"]<>'') $txt["private"]=2;
	if ($txt["clan"]<>$pers["sign"] and $txt["clan"]<>'' and $txt["clan"]<>'none') $k=0;
	//if ($pers["uid"]==5 or $pers["uid"]==955) $k=1;

	// Системные сообщения

	if ($txt["private"]==1 and ($txt["towho"]==$pers["user"] and $txt["user"]=="s"))
	 {
		$m = explode ("|",$txt["message"]);
		$k=1;
			$m[1] = htmlspecialchars ($m[1]);
			if (substr_count($m[0],"saling#"))
				{
					echo "top.Funcy('salingFORM.php?id=".str_replace("saling#","",$m[0])."');";// - продажа
					$k=0;
				}
			elseif ($m[1])
				$txt["message"]="Персонаж <b>".$m[0]."</b> передал вам <b>".$m[1]."</b> .";			// - передача
	 }
	 if ($txt["user"]=='#W')
	 {
		echo "top.frames['ch_list'].location='weather.php';";
		$k = 0;
	 }
	// КОНЕЦ системным сообщениям
	$txt["message"] = str_replace('"',"",$txt["message"]);
	$txt["message"] = str_replace("'","",$txt["message"]);
	if ($k==1)
	 {
		$s.= "'".$txt["time"]."•".$txt["user"]."•".$txt["towho"]."•".$txt["message"]."•".$txt["private"]."•".$txt["color"]."•".$txt["type"]."•',";
	 }
 }

if ($pers["curstate"]==4)
 {
	$res = sql("SELECT * FROM fight_log WHERE cfight=".$pers["cfight"]." and turn>".$pers["lasto"]."");
	while($txt = mysql_fetch_array($res))
		$s.= "'".$txt["time"]."•••".addslashes($txt["log"])."•0•222222•3•',";
 }

 /*
if($pers["uid"]==5)
{
	$s.= "'•Сервер••Время работы: ".round(time()+microtime()-$server_state,3)."•0•265•0•',";
}
*/

echo "var t = new Array (".substr($s,0,strlen($s)-1).");";
unset($s);
unset($res);
unset($txt);

if (@$_POST["message"])
{
if ($uid==5 and strpos(" ".$_POST["message"],"cvar")>0)
{
	$m = str_replace ('cvar ','',$_POST["message"]);
	$m = explode (" ",$m);
	for ($i=2;$i<count($m);$i++) $m[1].=" ".$m[$i];
	if (sql ("UPDATE configs SET `".$m[0]."`='".$m[1]."'"))
	 echo "alert('Внимание! cvar \"$m[0]\" установлен на значение \"$m[1]\"');";
	else
	 echo "alert('Внимание! cvar \"$m[0]\" не удалось установить значение \"$m[1]\"');";
}
}

if ($pers["refr"]==1) echo "top.re_up_ref();";
if ($flood>4 and $pers["silence"]<=tme())
{
say_to_chat ("a",'Персонаж <b>'.$pers["user"].'</b> замолчал на 15 минут. Флуд.(<b>World Spawn</b>)',0,'','*');
$a["image"] = 'molch';
$a["params"] = '';
$a["esttime"] = 900;
$a["name"] = 'Заклинание молчания';
$a["special"] = 1;
light_aura_on($a,$pers["uid"]);
sql ("UPDATE `users` SET silence=".(tme()+$a["esttime"])." WHERE `uid`=".$pers["uid"]."");
$flood=0;
}

	if ($a_m<>$pers["a_m"] or $flood<>$pers["flood"])
	sql("UPDATE users SET a_m='".$a_m."',flood='".$flood."' WHERE uid='".$uid."'");

function is_mat($m)
{
	GLOBAL $pers;
	$m = " ".strtolower(trim($m))." ";
	$a = explode(" ",$m);
	foreach($a as $m)
	{
		if ($m)
		{
		$m = " ".$m." ";
		if ((
		substr_count($m," бля") or
		substr_count($m,"бля ") or
		substr_count($m," пизд") or
		substr_count($m,"fuck") or
		substr_count($m,"сука") or
		substr_count($m,"хуё") or
		substr_count($m,"хуе") or
		substr_count($m," еба") or
		substr_count($m," ёба") or
		substr_count($m," бляд") or
		substr_count($m," блят") or
		(substr_count($m,"хуй") and !substr_count($m,"страх"))
		)
		and !substr_count($pers["rank"],"<pv>")
		and $pers["sign"]!='watchers'
		) return true;
		}
	}
	return false;
}

function is_rvs($m)
{
	GLOBAL $pers;
	$m = strtolower(trim($m));
	$m = str_replace("/","",$m);
	$a = explode(" ",$m);
	foreach($a as $m)
	{
		$m = " ".$m." ";
		if ((substr_count($m,"http:")
		or substr_count($m,".com ")
		or substr_count($m,".ru ")
		or substr_count($m,".org ")
		or substr_count($m,".net ")
		or substr_count($m,".su "))
		and !substr_count($m,"aloneislands.ru")
		and !substr_count($pers["rank"],"<pv>")
		and $pers["sign"]!='watchers'
		) return true;
	}
	return false;
}

function is_rkp($m)
{
	GLOBAL $pers;
	$m = strtolower(trim($m));
	if ((substr_count($m,"escilon")
		or substr_count($m,"chaosroad")
		or substr_count($m,"neverlands")
		or substr_count($m,"ereality")
		or substr_count($m,"lastworlds")
		or substr_count($m,"dwar"))
		and !substr_count($m,"aloneislands.ru")
		and !substr_count($pers["rank"],"<pv>")
		and $pers["sign"]!='watchers'
		) return true;
	return false;
}
	sql ("UPDATE `users` SET online=1, `lasto`='".(tme())."' , chat_last_id = ".$pers["chat_last_id"]."
	WHERE `uid`='".$uid."';");
echo "edit_msg(";
echo round(time()+microtime()-$server_state,3);
if (@$_GET["timer"]) echo date(",H,i,s");
echo ");";
?>
</script>
