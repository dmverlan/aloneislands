<?
if (isset($_GET["fishing"]))
{
$skill_p = 0;
if(WEATHER==5) $skill_p = $pers["sp6"]/(-2);
if(WEATHER==7) $skill_p = $pers["sp6"]/(-1.25);
		if (empty($_POST["primid"]))
		{
			$p = 'Мало';
			if ($cell["fish_population"]==0) $p = 'Нет';
			if ($cell["fish_population"]>20) $p = 'Около 30';
			if ($cell["fish_population"]>50) $p = 'Достаточно';
			if ($cell["fish_population"]>80) $p = 'Много';
			if ($cell["fish_population"]>100) $p = 'Огромное количество';
			if ($cell["fish_population"]>500) $p = 'Туча';
			echo "<center class=inv>Умение: ".round($pers["sp6"]+$skill_p,2)."</center>";			
			echo "<center class=but2><b>".$p."</b> особей.</center>";
		}
		$z=1;
		$snasti = sqla("SELECT id FROM wp WHERE uidp=".$pers["uid"]." and weared=1 and p_type=1 and durability>0");
		$prim = sqla("SELECT id FROM wp WHERE p_type=1 and weared=0 and uidp=".$pers["uid"]." and durability>0 and type<>'orujie' and sp6=0");
		if ($snasti["id"]=='') {$z=0;echo "У вас нет снастей для ловли.";}
		if ($prim["id"]=='') {$z=0;echo "У вас нет приманок для ловли.";}
		if ($pers["tire"]>90) {$z=0;echo "Вы слишком устали.";}
		if ($z==1)
		{
/////////////////////////////////////////////
			if (empty($_POST["primid"]) and $pers["waiter"]<tme())
			{
				echo "<center class=fightlong><form action=main.php?fishing=on method=post><input type=hidden name=check value='".md5($lastom_new."1")."'>
				<table border=0 class=LinedTable width=80%><tr><td align=center colspan=7><input type=submit class=login value='Ловить' style='width:100%'></td></tr>";
				$res = sql("SELECT durability,max_durability,id,image,name FROM wp WHERE uidp=".$pers["uid"]." and weared=0 and p_type=1 and durability>0 and type<>'orujie' and sp6=0");
				while($v=mysql_fetch_array($res))
				echo "<tr><td><img src='images/weapons/".$v["image"].".gif'></td>
				<td class=user>".$v["name"]."</td><td class=timef>[".$v[0]."/".$v[1]."]</td><td>
				<input type=radio name=primid value=".$v[2]."></td></tr>";
				echo "</table></form></center>";
				unset($res);
				set_vars("action=5",UID);
			}
			elseif (@$_POST["primid"] and $pers["action"]==5)
			{
				set_vars("action=0",UID);
				$i=0;
				while($i<3)
				{
					echo "<center class=but2>";
				$i++;
				$v = sqla("SELECT * FROM wp WHERE p_type=1 and weared=0 and id=".intval($_POST["primid"])." and uidp=".$pers["uid"]." and durability>0");
				if (@$v["id"])
				{
				
				sql("UPDATE wp SET durability=durability-1 WHERE uidp=".$pers["uid"]." and weared=1 and p_type=1 and durability>0 LIMIT 1;");
				sql("UPDATE wp SET durability=durability-1 WHERE uidp=".$pers["uid"]." and weared=0 and id=".$v["id"]."");
				if (rand(1,100)<5 and round(10.0/sqrt($pers["sp6"]),2)>0)
				{
				echo "Вы поймали консервную банку.<br><img src=images/weapons/fish/b.jpg><br>Ваше умение \"Рыбак\" выросло на ".round(10.0/(sqrt($pers["sp6"])+1),2)."!<br> Мирный опыт +1";
				set_vars("sp6=sp6+".round(20.0/(sqrt($pers["sp6"])+1),2).",peace_exp=peace_exp+1"
				,$pers["uid"]);
				}elseif (rand(1,100)<3 and round(20.0/sqrt($pers["sp6"]),2)>0)
				{
				echo "Вы поймали дырявый сапог.<br><img src=images/weapons/fish/n.jpg><br>Ваше умение \"Рыбак\" выросло на ".round(20.0/(sqrt($pers["sp6"])+1),2)."!<br> Мирный опыт +1";
				set_vars("sp6=sp6+".round(20.0/(sqrt($pers["sp6"])+1),2).", peace_exp=peace_exp+1"
				,$pers["uid"]);
				}
				elseif (rand(1,500)<3)
				{
					$vesh = insert_wp_new(UID,"price<100 and price>20 and dprice=0 and where_buy=0 ORDER BY RAND()",$pers["user"]);
					echo "Вы поймали вещь!";
					echo "<hr><center class=weapons_box>";
					include("inc/inc/weapon.php");
					echo "</center>";
				}
				else
				{
				$fish = sqla("SELECT * FROM fish WHERE skill<".($pers["sp6"]+$skill_p)." and place=".$cell["fishing"]." and (prim=".intval(str_replace("fishing_prim_","",$v["image"]))." or prim=".(intval(str_replace("fishing_prim_","",$v["image"])+1)%8+1).") ORDER BY RAND()");
				if (rand(sqrt($cell["fish_population"]),150)<($fish["no_kl"]-sqrt($pers["sp6"]+$skill_p)+10) or $cell["fish_population"]==0)
					echo "Нет клёва.";
				elseif ($fish["id"]==0) 
				{
					if (sqlr("SELECT COUNT(*) FROM fish WHERE skill<".($pers["sp6"]+$skill_p)." 
					and place=".$cell["fishing"]))	
						echo "Не хватает умений чтобы ловить здесь рыбу.";
					else 
						echo "Не подходит приманка.";
						
					break;##################	
				}
				elseif(rand(1,100)<($fish["skill"]/10-2*sqrt($pers["sp6"]+$skill_p))) 
					echo "Рыба сорвалась.";
				else
				{
					$durability=1;
					$vesh = insert_wp("fish_1",$pers["uid"],-1,0);
					$vesh = sqla("SELECT * FROM wp WHERE id=".$vesh."");
					$k = rand(-3,3);
					$vesh["weight"] = floor(abs($k+4));
					$vesh["price"] = round($fish["price"]+sqrt(sqrt($fish["price"]/2)*($k+3)),2);
					$vesh["timeout"] = (tme()+345600);
					$vesh["name"] = $fish["name"];
					$vesh["image"] = "fish/".$fish["id"];
					if ($k==-3) $l = "Малёк.";
					if ($k==-2) $l = "Подросший малёк.";
					if ($k==-1) $l = "Малая.";
					if ($k==0) $l = "Средняя.";
					if ($k==1) $l = "Большая.";
					if ($k==2) $l = "Огромная.";
					if ($k==3) $l = "Гигантская.";
					echo "<b class=green>Вы поймали рыбу!</b><br><i class=timef> ".$l."</i><br> Мирный опыт +5";
					sql("UPDATE wp SET price=".$vesh["price"].", weight=".$vesh["weight"].",timeout=".(tme()+345600).",`describe`='".$l."',name='".$vesh["name"]."',image='".$vesh["image"]."'  WHERE id=".$vesh["id"]."");
					$vesh["timeout"] = (tme()+345600);
					set_vars("peace_exp=peace_exp+5",$pers["uid"]);
					echo "<hr><center class=weapons_box>";
					include("inc/inc/weapon.php");
					echo "</center>";
					sql("UPDATE nature SET fish_population=fish_population-1 WHERE x=".$cell["x"]." and y=".$cell["y"]."");
				}
				}
				}
				else
					break;
					echo "</center>";
				}
				set_vars("waiter=".round(tme()+FISHING_TIME*$i).",tire=tire+2*".$i."",$pers["uid"]);
				$pers["waiter"]=round(tme()+FISHING_TIME*$i);
				if($pers["waiter"]>tme())
				echo "<Script>setTimeout('top.Sound(\"misc8\",1,0);',".(($pers["waiter"]-tme())*1000).");</script>";
			}
		}
}
?>