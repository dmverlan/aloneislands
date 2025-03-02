<?
$m = (substr_count($you["rank"],"<molch>")) ? 1 : 0;
$p = (substr_count($you["rank"],"<prison>")) ? 1 : 0;
$b = (substr_count($you["rank"],"<block>")) ? 1 : 0;
$w = (substr_count($you["rank"],"<w_pom>")) ? 1 : 0;
$i = (substr_count($you["rank"],"<b_info>")) ? 1 : 0;
$u = (substr_count($you["rank"],"<punishment>")) ? 1 : 0;
$d = (substr_count($you["rank"],"<diler>")) ? 1 : 0;

$_NG = 0;
if((date("m")==12 and date("d")>=13) or (date("m")==1 and date("d")<=15))
	$_NG = 1;
if($_NG and $you["dreserv"])
	echo "<div class=but><i class=green>Время новогодних скидок на БР(-50%)</i></div>";
if (@$_GET["ch_silence"])$_POST["molch"] = $_GET["ch_silence"];

if (@$_POST["molch"] and $m) 
molch($pers,$you,intval($_POST["molch"]),$_POST["reason1"]);
if (@$_POST["punishment"] and $u) 
punish($pers,$you,intval($_POST["punishment"]),$_POST["reason2"]);
if (@$_POST["prisontime"] and $p) 
prison($pers,$you,intval($_POST["prisontime"]),$_POST["prison"]);
if ((@$_POST["block"] or @$_POST["blockt"]) and $b) 
block($pers,$you,intval($_POST["blockt"]),$_POST["block"]);
if ((@$_POST["pometka"]) and $w) 
pometka($pers,$you,$_POST["pometka"]);
if ((@$_POST["blocki"]) and $i) 
blocki($pers,$you,$_POST["blocki"]);
if ((@$_POST["d_num"]) and $d) 
diler($pers,$you,$_POST["d_num"]);

if (empty($_GET["ch_silence"]))
echo "<script>r=".$you["dreserv"].";var cc='".$pers["sign"]."';show_mpb($m,$p,$b,$w,$i,$d,$u,".$you["dreserv"].");</script>";
?>