<script>
<?
	error_reporting(0);
	include ("../configs/config.php");
	include ("../inc/functions.php");
	$main_conn = mysql_connect ($mysqlhost,$mysqluser,$mysqlpass,$mysqlbase);
	mysql_select_db($mysqlbase, $main_conn);
	$t = '';
	$t2 = '';
	$pers = catch_user(intval($_COOKIE["uid"]));
	if ($pers["pass"]<>$_COOKIE["hashcode"] or !$pers)exit;
	if (@$_GET["ctip"]==-1)
	{
		set_vars ("ctip=0",$pers["uid"]);
	}
	elseif(@$_GET["ctip"])
	{
		$ctip = intval($_GET["ctip"]);
		if (@$_GET["gtt"]==1) $gt = 0; else $gt = 1;
		if (@$_GET["ltt"]==1) $lt = 0; else $lt = 1;
		$tip = sqla("SELECT * FROM tips WHERE id>=".$ctip." and (type<>".$gt." and type<>".$lt.")");
		if (!$tip)
		{
		$t2 .= "top.ctip=1;";
		$ctip = 1;
		$tip = sqla("SELECT * FROM tips WHERE id>=".$ctip." and (type<>".$gt." and type<>".$lt.")");
		}
		set_vars ("ctip=".$ctip,$pers["uid"]);
		
		if ($tip)
		{
			$t .= "<b>".$tip["title"]."</b><br>";
			$tip["text"] = str_replace("\n","<br>",$tip["text"]);
			$t .= $tip["text"]."<br>";
			if ($tip["type"]==1) 
				$t .= "<i class=timef>[Игровая подсказка]</i>";
			else
				$t .= "<i class=timef>[Подсказка от смотрителей]</i>";
			$t.= "<hr>";
		}
	}
	$t = str_replace("'","\\'",$t);
	$t = str_replace("\r","<br>",$t);
	if ($t) echo "top.frames['main_top'].init_tip('".$t."');".$t2;
?>
</script>