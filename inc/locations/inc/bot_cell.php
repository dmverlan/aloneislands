<?
	$TXT = '';
	if ($cell["last_bots_change"]<(time()-7200))
	{
		if ($cell["blvlmax"]>rand(0,100))
		{
			$w = sqla("SELECT COUNT(id) FROM `bots_cell` WHERE xy='".$cell["x"]."_".$cell["y"]."'");
			if ($w[0]>3)
			sql("DELETE FROM bots_cell WHERE xy='".$cell["x"]."_".$cell["y"]."' LIMIT 5;");
			$q = '';
			if($cell["type"]==6) $q = 'where_ap = 1';
			elseif($cell["type"]==3) $q = 'where_ap = 2';
			else $q = 'where_ap = 3';
			$bot_id = sqla("SELECT id,user,id_skin FROM bots WHERE ".$q." and id_skin>0 ORDER BY RAND()");
			if($bot_id)
			{
				$count = rand(1,1);
				sql("INSERT INTO `bots_cell` ( `id` , `name` , `time` , `xy` , `count` , `id_skin`) 
		VALUES ('".$bot_id["id"]."', '".$bot_id["user"]."', '".time()."', '".$cell["x"]."_".$cell["y"]."',".$count.",".$bot_id["id_skin"].");");
				sql("UPDATE nature SET last_bots_change=".time()." WHERE x='".$cell["x"]."' and y='".$cell["y"]."'");
			}
		}elseif(rand(0,100)<50)
		{
			$user = sqla("SELECT user FROM bots WHERE id='".$cell["bot"]."'");
			$bot_id = sqla ("SELECT id,user,level FROM bots 
			WHERE user='".$user["user"]."' and level='".rand($cell["blvlmin"],$cell["blvlmax"])."'");
			if($bot_id)
			{
				$count = rand(1,1);
				sql("INSERT INTO `bots_cell` ( `id` , `name` , `time` , `xy` , `count`,`id_skin`) 
		VALUES ('".$bot_id["id"]."', '<font class=user>".$bot_id["user"]."</font>[<font class=lvl>".$bot_id["level"]."</font>]<img src=images/info.gif onclick=\"javascript:window.open(\'binfo.php?".$bot_id["id"]."\',\'_blank\')\" style=\"cursor:pointer\">', '".time()."', '".$cell["x"]."_".$cell["y"]."',".$count.",0);");
			}
			sql("UPDATE nature SET last_bots_change=".time()." WHERE x='".$cell["x"]."' and y='".$cell["y"]."'");
		}
	}
	$TXT .= "Живность на локации: <br>";
	$TXT .= '<table border="0" width="100%" cellspacing="0" cellpadding="0" class=LinedTable>';
	$bots = sql("SELECT * FROM bots_cell WHERE xy='".$cell["x"]."_".$cell["y"]."'");
	$BCNT = 0;
	while($b = mysql_fetch_array($bots))
	{
		$BCNT++;
		$TXT .= "<tr>";
		if ($b["id_skin"])$b["name"] = "<font class=user>".$b["name"]."</font>[<font class=lvl>".($pers["level"])."</font>]";
		if ($b["time"]<=time()) 
		{
		$TXT .= "<td><input type=button class=login onclick=\"{if(confirm('Вы действительно хотите напасть?')) location='main.php?out_action=battle&id=".$b["id"]."'}\" value=X></td>";
		$TXT .= "<td class=user nowrap>".$b["name"]."</td>";
		if (@$_GET["out_action"]=="battle" and $_GET["id"]==$b["id"])
		{
			$f_type = 0;
			if ($cell["type"]==0) $f_type = 1;
			if ($cell["type"]==1) $f_type = 1;
			if ($cell["type"]==2) $f_type = 4;
			if ($cell["type"]==6) $f_type = 5;
			if ($cell["type"]==5) $f_type = 0;
			if ($cell["type"]==8) $f_type = 2;
			if ($cell["type"]==3) $f_type = 3;
			//$f_type = 0;
			$bb = '';
			if ($b["id_skin"])
			{
			for ($i=1;$i<=$b["count"];$i++)$bb.="bot=".(floor($b["id"]/100)*100+$pers["level"]-1)."|";
			$bb = substr($bb,0,strlen($bb)-1);
			begin_fight ($pers["user"],$bb,"Охота на существо",50,300,1,$f_type);
			sql("DELETE FROM bots_cell WHERE xy='".$cell["x"]."_".$cell["y"]."' and id='".$b["id"]."' and time<".time()." LIMIT 1;");
			echo "<script>location='main.php';</script>";
			}
			else
			{
			for ($i=1;$i<=$b["count"];$i++)$bb.="bot=".$b["id"]."|";
			$bb = substr($bb,0,strlen($bb)-1);
			begin_fight ($pers["user"],$bb,"Охота на существо",50,300,1,$f_type);
			sql("DELETE FROM bots_cell WHERE xy='".$cell["x"]."_".$cell["y"]."' and id='".$b["id"]."' and time<".time()." LIMIT 1;");
			echo "<script>location='main.php';</script>";
			}
		}
		}else $TXT .= "<td>&nbsp;</td>";
		$TXT .= "</tr>";
	}
	$TXT .= "</table>";
	if($BCNT==0)
		$TXT .= '<i class=gray>Не обнаружено...</i>';
?>