<?
	if (@$_POST and $priv["ewp"]==2)
	{	
		$params = explode("@",$_POST["params"]);
		foreach ($params as $z)
		{
			$z = explode("=",$z);
			$p[$z[0]] = $z[1];
		}
		$q = '';
		foreach($p as $key=>$val)
		{
			$key = str_replace(" ","",$key);
			if ($key and $key<>'params')
			$q .= ", `".$key."`='".$val."'";
		}
		$q = substr($q,1,strlen($q)-1);
		if (sql("UPDATE weapons SET ".$q." WHERE id='".$_GET["edit"]."'")) echo "<center class=return_win><b>".$p["name"]." удачно изменен!</b></center>";
	}
?>

<script language=JavaScript src='js/ajax.js' type="text/javascript"></script>
<script language=JavaScript src='js/adm_new.js' type="text/javascript"></script>
<script>
<?
	$v = mysql_fetch_array(sql("SELECT * FROM weapons WHERE id='".$_GET["edit"]."'"),MYSQL_ASSOC);
	$params = '';
	$names = '';
	foreach ($v as $key=>$value)
	{
	if ($key=='describe') $value = str_replace("
"," ",$value);
	$params .= $key."=".$value."@";
	}
	$r = all_params();
	foreach ($r as $key=>$a)
	if ($a)
	{
		$names .= "'".name_of_skill($a)."',";
		$r[$key] = "'".$a."'";
	}
	$names = substr($names,0,strlen($names)-1);
	echo "var params='".$params."';";
	echo "var all_params = [".implode(",",$r)."];";
	echo "var par_names = [".$names."];";
	if ($v["type"]=='zakl')
	{
		$as = sql("SELECT id,name,esttime FROM auras");
		$r = '';
		while($a = mysql_fetch_array($as,MYSQL_ASSOC))
		{
			$a["name"].= '['.tp($a["esttime"]).']';
			$r .= ',['.$a["id"].',\''.$a["name"].'\']';
		}
		$r = "[0".$r."]";
		echo "var auras=".$r.";";
	}
	echo "editw();";
	
?>
</script>