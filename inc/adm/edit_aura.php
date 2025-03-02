<?
	$params = explode("@",$_POST["params"]);
	foreach ($params as $z)
	{
		$z = explode("=",$z);
		$p[$z[0]] = $z[1];
	}
	
	if (@$_POST and $priv["emagic"]==2)
	{	
		$q = '';
		foreach($p as $key=>$val)
		{
			$key = str_replace(" ","",$key);
			if ($key and $key<>'params' and $key<>'vparams')
			$q .= "`".$key."`='".$val."',";
		}
		$q .= "`params`='".$_POST["vparams"]."'";
		if (sql("UPDATE auras SET ".$q." WHERE id='".intval($_GET["edit"])."'")) echo "<center class=return_win><b>".$p["name"]." удачно изменен!</b></center>";
	}
?>

<script language=JavaScript src='js/ajax.js' type="text/javascript"></script>
<script language=JavaScript src='js/adm_auras.js' type="text/javascript"></script>
<script>
<?
	$v = mysql_fetch_array(sql("SELECT * FROM auras WHERE id='".$_GET["edit"]."'"),MYSQL_ASSOC);
	$params = '';
	$names = '';
	foreach ($v as $key=>$value)
	if (is_string($key) and $key<>"params")
	{
	if ($key=='describe') $value = str_replace("
"," ",$value);
	$params .= $key."=".$value."@";
	}
	
	$r = all_params();
	$r[] = 'cma';
	$r[] = 'chp';
	foreach ($r as $key=>$a)
	if ($a)
	{
		$names .= "'".name_of_skill($a)."',";
		$r[$key] = "'".$a."'";
	}
	$names = substr($names,0,strlen($names)-1);
	echo "var params='".$params."';";
	echo "var vparams='".$v["params"]."';";
	echo "var all_params = [".implode(",",$r)."];";
	echo "var par_names = [".$names."];";
	echo "editw();";
?>
</script>