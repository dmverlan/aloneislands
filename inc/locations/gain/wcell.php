<?
	if ($_GET["wcell"]=='start')
	{
		$have_cells = sqlr("SELECT COUNT(*) FROM nature WHERE belong=".$pers["uid"]."");
		if ($have_cells)
		$near_cells = sqlr("SELECT COUNT(*) FROM nature WHERE x>=".($x-1)." and x<=".($x+1)." and 
y>=".($y-1)." and y<=".($y+1)." and belong=".$pers["uid"]."");
		if ($have_cells and !$near_cells) echo "Вы не можете завоевать земли вдали от уже завоеванных.";
		else	
		if ($pers["money"]<GAIN_COST) echo "Не хватает денег для завоевания.";
		else
		{
			echo "<center class=but>Вы уверены что хотите начать атаку[50LN]?<br><A class=Button href=main.php?wcell=go>Да</A> <A class=Button href=main.php>Нет</A></center>";
		}
	}
	if ($_GET["wcell"]=='go')
	{
		$have_cells = sqlr("SELECT COUNT(*) FROM nature WHERE belong=".$pers["uid"]."");
		if ($have_cells)
		$near_cells = sqlr("SELECT COUNT(*) FROM nature WHERE x>=".($x-1)." and x<=".($x+1)." and 
y>=".($y-1)." and y<=".($y+1)." and belong=".$pers["uid"]."");
		if ($have_cells and !$near_cells) echo "Вы не можете завоевать земли вдали от уже завоеванных.";
		else	
		if ($pers["money"]<GAIN_COST) echo "Не хватает денег для завоевания.";
		else
		{
			if ($cell["belong"])
			{
				say_to_chat ("s","<i>".date("H:i:s")."</i> Персонаж <b class=user>".$pers["user"]."</b><b class=lvl>[".$pers["level"]."]</b> начал атаку на ваши владения[".$x.";".$y."]!","1",_UserByUid($cell["belong"]),'*');
			}
			
			set_vars("money=money-".GAIN_COST.",gain_time=".tme(),$pers["uid"]);
			
			$perstowho = sqla("SELECT * FROM users WHERE x=".$x." and y=".$y." and online=1 and gain_time<>0 and uid<>".$pers["uid"]." LIMIT 0,1");
			if ($perstowho)
			{
			if ($perstowho["cfight"]>10)
			{
				$fight = sqla("SELECT * FROM `fights` WHERE `id`='".$perstowho["cfight"]."'");

				if ($pers["invisible"]<=tme())
				$nyou = "<font class=bnick color=".$colors[$pers["fteam"]].">".$pers["user"]."</font>[".$pers["level"]."]";
					else 
				$nyou = "<font class=bnick color=".$colors[$pers["fteam"]]."><i>невидимка</i></font>[??]";

				if ($fight["type"]<>'f' and $fight["id"]) 
				{
					$pers["curstate"] = 4;
					$pers["cfight"] = $fight["id"];
					sql ("UPDATE `fights` SET players=players+1 WHERE id=".$fight["id"]."");
					sql ("UPDATE `users` SET `curstate`=4 , `cfight`='".$fight["id"]."', fteam=".(($perstowho["fteam"]+1)%2).",refr=1 WHERE `uid`='".$pers["uid"]."'");
					add_flog($nyou." вмешивается в бой!",$perstowho["cfight"]);
				}
			}
			else
			begin_fight ($pers["user"],$perstowho["user"],'Споры за местность',50,100,1,rand(0,5));
			}
		}
	}
	
	if ($_GET["wcell"]=='abort' and $pers["gain_time"]>(tme()-1200))
	{
		set_vars("gain_time=0",$pers["uid"]);
	}
	
	if ($pers["gain_time"]>0 and $pers["gain_time"]<=(tme()-1200))
	{
		echo "<b class=green>Поздравляем! Вы завоевали эту местность!</b>";
		sql("UPDATE nature SET belong=".$pers["uid"]." WHERE x=".$x." and y=".$y."");
		set_vars("gain_time=0",$pers["uid"]);
	}
?>