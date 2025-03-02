<?
	$cell_type = sqlr("SELECT type FROM nature WHERE x=".$pers["x"]." and y=".$pers["y"]."");
	
	if ($_GET["d"])
	{
		sql("UPDATE wp SET p_type=201 WHERE weared=0 and uidp=".$pers["uid"]." and id=".intval($_GET["d"])." and p_type=200");
		sql("UPDATE users SET alchemy_m=alchemy_m-1 WHERE uid=".$pers["uid"]."");
		$pers["alchemy_m"]--;
	}
	
	if ($_GET["g"])
	{
		sql("UPDATE wp SET p_type=200 WHERE weared=0 and uidp=".$pers["uid"]." and id=".intval($_GET["g"])." and p_type=201");
	}
	
	if ($_GET["alchemy_go"]==1 and $cell_type==6)
	{
		if ($pers["sp11"]>1200) $pers["sp11"] = 1200;
		$rcount = sqlr("SELECT COUNT(image) FROM `wp` WHERE `uidp`=".$pers["uid"]." and weared=0 and p_type=201");
		$all = sql ("SELECT image,name FROM `wp` WHERE `uidp`=".$pers["uid"]." and weared=0 
		and p_type=201 ORDER BY image ASC");
		$id1 = mysql_fetch_array($all);
		$id2 = mysql_fetch_array($all);
		$id3 = mysql_fetch_array($all);
		$id4 = mysql_fetch_array($all);
		$material = '';
		$material_show = '';
		if($id1[0])
		{
			$material .= $id1[0]."|";
			$material_show .= $id1[1].", ";
		}
		if($id2[0])
		{
			$material .= $id2[0]."|";
			$material_show .= $id2[1].", ";
		}
		if($id3[0])
		{
			$material .= $id3[0]."|";
			$material_show .= $id3[1].", ";
		}
		if($id4[0])
		{
			$material .= $id4[0]."|";
			$material_show .= $id4[1].", ";
		}

		$id1 = str_replace("herbals/","",$id1[0]);
		$id2 = str_replace("herbals/","",$id2[0]);
		$id3 = str_replace("herbals/","",$id3[0]);
		$id4 = str_replace("herbals/","",$id4[0]);
		$rsumm = $id1 + $id2 + $id3 + $id4;
		while($tmp = mysql_fetch_array($all,MYSQL_ASSOC))
		{
			$rsumm += intval(str_replace("herbals/","",$tmp["image"]));
			$material .= $id4["image"]."|";
			$material_show .= $id4["name"].", ";
		}
		$material = substr($material,0,strlen($material)-1);
		$material_show = substr($material_show,0,strlen($material_show)-2);
		$z = 100;
		if ($id1==8 and $id2==19 and $id3==20 and $id4==33) $z=14;
		if ($id1==1 and $id2==28 and $id3==33 and $id4==89) $z=15;
		if ($id1==7 and $id2==34 and $id3==35 and $id4==36) $z=16;
		if ($id1==58 and $id2==63 and $id3==68 and $id4==82) $z=17;
		if ($id1==30 and $id2==39 and $id3==71 and $id4==99) $z=18;
		if ($id1==12 and $id2==72 and $id3==75 and $id4==83) $z=19;
		if ($id1==86 and $id2==24) $z=20;
		if ($id1==63 and $id2==93 and $id3==96) $z=21;
		if($rcount>1)
		{
			if ($z<>100)
				$z = sqla("SELECT image,name,param FROM potions WHERE image=".$z."");
			else
				$z = sqla("SELECT image,name,param FROM potions WHERE image%30=".($rsumm%30));
		}
		$koef = 0.4*sqrt($rcount/2);
		if ($id1%30==0)
		{
		if (($id1+$id4)%50==0)$koef+=0.5;
		if (($id1+$id3)%50==1)$koef+=0.5;
		if (($id1+$id2)%50==2)$koef+=0.5;
		}
		if ($id2<>0) $pers["sp11"]+=10;
		if ($id3<>0) $pers["sp11"]+=20;
		if ($id4<>0) $pers["sp11"]+=30;
		if ($z["image"])
		{
		$a = 0;
		if (substr($z["param"],0,1)=='s')
		$a = $pers["sp11"]/80+1;
		if (substr($z["param"],0,1)=='m' and $z["param"]<>'mf5')
		$a = $pers["sp11"]/6.5+30;
		if ($z["param"]=='mf5' or $z["param"]=='udmax' or $z["param"]=='kb')
		$a = $pers["sp11"]/65+5;
		if ($z["param"]=='hp' or $z["param"]=='ma')
		$a = intval($pers["sp11"]/6+50);
		$a = $a*$koef;
		if ($id2==0) $a = $a*(1+floor($id1/60));
		$time = $pers["sp11"]*4+300;
		if ($id1==1 or $z["image"]==20) $time+=1800;
		$price = rand(1,10)+$pers["sp11"]/100+13;
		$a=round($a);
		$time = round($time);
		$price = round($price);
		$param[1] = $z["param"];
		if ($param[1]=="s1")$sk = "Сила";
		if ($param[1]=="s2")$sk = "Реакция";
		if ($param[1]=="s3")$sk = "Удача";
		if ($param[1]=="s4")$sk = "Здоровье";
		if ($param[1]=="s6")$sk = "Сила воли";
		if ($param[1]=="kb")$sk = "Класс брони";
		if ($param[1]=="hp")$sk = "HP";
		if ($param[1]=="ma")$sk = "МАНА";
		if ($param[1]=="udmax")$sk = "Удар";
		if ($param[1]=="mf1")$sk = "Сокрушение";
		if ($param[1]=="mf2")$sk = "Уловка";
		if ($param[1]=="mf3")$sk = "Точность";
		if ($param[1]=="mf4")$sk = "Стоикость";
		if ($param[1]=="mf5")$sk = "Ярость";
		$lastid = sqla("SELECT MAX(id) FROM wp");
		$lastid = 1+$lastid[0];
		if ($a>=0) $b="+".$a;
		if ($a<0) $b="-".abs($a);
		$sk = "".$sk." <b>".$b."</b>";
		if ($z["image"]>13)
		{
			$a = intval($pers["sp11"]/35)*($koef+0.6);
		}
		$a = floor($a);
		$time = floor($time/(15*60))*15*60;
		if ($z["image"]==21) {$a*=2;$time=1;}
		if ($z["image"]==14) $sk = "Удар +<b>".$a."%</b>";
		if ($z["image"]==15) $sk = "Класс брони +<b>".$a."%</b>";
		if ($z["image"]==16) $sk = "Сила воли +<b>".$a."%</b><br>Мана +<b>".($a-5)."%</b>";
		if ($z["image"]==17) $sk = "Сила +<b>".$a."%</b><br>HP +<b>".($a-5)."%</b>";
		if ($z["image"]==18) $sk = "Реакция +<b>".$a."%</b><br>Удача +<b>".$a."%</b><br>Уловка +<b>".$a."%</b><br>Сокрушение +<b>".$a."%</b>";
		if ($z["image"]==19) $sk = "Невидимость";
		if ($z["image"]==21) $sk = "Усталость -<b>".$a."</b>";
		sql("INSERT INTO `wp` ( `id` , `uidp` , `weared` , `price` ,  `image` , `index` , `type` , `stype` , `name` , `weight` , `where_buy` , `max_durability` , `durability` ,`p_type`, `describe`, `material` , `material_show`) 
VALUES (
'".$lastid."', '".$pers["uid"]."', '0','".$price."', 'potions/".$z["image"]."', '".$time."|".$z["param"]."|".$a."', 'potion', 'potion', '".$z["name"]."', '1', '0', '".(1+$pers["sp11"]/200)."', '".(1+$pers["sp11"]/200)."','0','".$sk."<br>Время действия ".tp($time)."','".$material."','".$material_show."');");
echo "<center class=but><font class=green>Удачно сварено «<img src=images/weapons/potions/".$z["image"].".gif height=20>".$z["name"]."»!</font><br><b>Мирный опыт +5</b>";
$vesh = sqla("SELECT * FROM wp WHERE id=".$lastid);
include ("inc/inc/weapon.php");
echo "</center>";
set_vars("peace_exp=peace_exp+5",$pers["uid"]);
		}
		else 
			echo "<center class=but><b class=red>Неудачно подобранные компоненты</b></center>";
		sql("UPDATE users SET sp11=sp11+1/(sp11+1), alchemy_b=alchemy_b-1, alchemy_d=alchemy_d-1,alchemy_m=alchemy_m-1 WHERE uid=".$pers["uid"]."");
		sql("DELETE FROM wp WHERE p_type=201 and weared=0 and uidp=".$pers["uid"]."");
	}
?>


<table border="0" width="100%" cellspacing="0" cellpadding="0"  class=but2>
<tr>
		<td align="center" width="50%" class=mfb>	Умение алхимик</td>
		<td class="mdb">[<? echo round($pers["sp11"],2);?>]</td>
</tr><tr>
		<td align="center" width="50%" class=user>	Дистиллятор</td>
		<td class="td">[<? echo $pers["alchemy_d"];?>]</td>
</tr><tr>		
		<td align="center" width="50%" class=user>	Ступка</td>
		<td class="td">[<? echo $pers["alchemy_m"];?>]</td>
</tr><tr>
		<td align="center" width="50%" class=user>Пустые ёмкости</td>
		<td class="td">[<? echo $pers["alchemy_b"];?>]</td>
</tr><tr>
		<td align="center" width="50%" class=Luser>Вода поблизости</td>
		<td class="td">[<?
		if($cell_type==6) 
			echo "Да";
		else
			echo "Нет";
		?>]</td>		
</tr>
</table>

<?
	$count_r = 0;
	$res =sql ("SELECT name,id,image FROM `wp` WHERE `uidp`=".$pers["uid"]." and weared=0 and p_type=201");
	
	echo "<center class=but><table width=70% border=0 class=LinedTable>";
	while ($vesh=mysql_fetch_array($res)) {
	$tmp_id = str_replace("herbals/","",$vesh["image"]);
	if ($tmp_id%2==0)
		$get_out = "<a href=main.php?g=".$vesh["id"]."&inv=cat5 class=bg>Вынуть</a>";
	else
		$get_out = "<i>нельзя вынуть</i>";
	echo "<tr>";
	echo "<td class=user><img src=images/weapons/".$vesh["image"].".gif height=20>".$vesh["name"]."</td><td align=center>".$get_out."</td>";
	echo "</tr>";
	$count_r++;
	}
	echo "</table>";
	if ($count_r==8) echo "Дистиллятор полный.<br>";
	if ($count_r>0 and $cell_type==6) echo "<a href='main.php?alchemy_go=1&inv=cat5' class=but>Варить</a>";
	echo "</center>";
	
	$res =sql ("SELECT * FROM `wp` WHERE `uidp`=".$pers["uid"]." and weared=0 and p_type=200");
echo "<center class=but><table width=90%>";
$unique_herbal = '';
$counter=0;
while ($vesh=mysql_fetch_array($res)) {
if (!substr_count($unique_herbal,substr($vesh["image"],8,8)))
{
$unique_herbal .= substr($vesh["image"],8,8).'#';
$sht = 1;
$item_lib = $vesh["id"];
echo "<tr><td class=weapons_box>";
include ("weapon.php");
echo "</td></tr>";
$counter++;
$buttons = '';
if ($count_r<8) $buttons .= "<input type=button class=inv_but value='Размельчить и поместить в дистиллятор' onclick=\"location='main.php?d=".$vesh["id"]."&inv=cat5'\"> | <input type=button class=inv_but value='Информация' onclick=\"window.open('rec.php?id=".substr($vesh["image"],8,8)."','','resize=auto,width=520,height=400,left=300,top=300')\">";
echo "<tr><td>".$buttons."</td></tr>";
}
}
echo "</table>";
if ($counter==0) echo "У вас нет ингредиентов.";
echo "</center>";
?>