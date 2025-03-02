<center><font class=hp><?
if (@$_POST["id"]) 
{
	$id = sqla("SELECT * FROM presents WHERE id='".intval($_POST["id"])."'");
	if (isset($id["id"]))
	{
		$persto = sqla("SELECT uid,user,location FROM users 
		WHERE user='".addslashes($_POST["towho"])."'");
		if (@$persto["uid"] and $pers["money"]>$id["price"])
		{
			sql("INSERT INTO `presents_gived` ( `uid` , `name` , `image` , `date` , `who` , `anonymous` , `text` ) 
VALUES (
'".$persto["uid"]."', '".$id["name"]."', '".$id["image"]."', '".time()."', '".$pers["user"]."', '".intval($_POST["anonymous"])."', '".$_POST["p"]."');");
			sql("UPDATE users SET money=money-".$id["price"]." WHERE uid='".$pers["uid"]."'");
			echo "Вы подарили подарок для ".$persto["user"];
			say_to_chat('s','Вам подарен подарок.',1,$persto["user"],'*',0);
		}else echo "Нет такого персонажа.";
	}else echo "Hacking attempt, go out!!!";
}
?></font></center>
<center>
<hr>
<form action=main.php method=post>
<table border=0 width=800 cellspacing=0 cellpadding=0 class=but2>
<tr>
<td width="100">Для&nbsp;кого</td>
<td width="100"><input type=text class=laar name=towho></td>
<td width="28">&nbsp;</td>
<td width="46">Подпись</td>
<td width="358"><input type=text class=laar name=p style="width: 100%" size="100"></td>
<td width="11">&nbsp;</td>
<td width="56">Анонимно</td>
<td width="20"><input type="checkbox" name="anonymous" value="1"></td>
<td width="172" align="right"><input type="submit" value="Отправить" class="submit"></td>
</tr>
<tr>
<td width=800 colspan=9 class=fightlong height=17>
<table  border="1" width="100%" cellspacing="0" cellpadding="0" bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF>
<?
	$presents = mysql_query("SELECT * FROM presents WHERE id>86 ORDER BY price ASC");
	$i=0;
	while($p=mysql_fetch_array($presents))
	{
		if ($i%4==0) echo "<tr>";
		echo "<td class=but width=200 align=center><font class=user>".$p["name"]."</font><br><img src='images/presents/".$p["image"].".jpg'><br><font class=items><b>".$p["price"]." LN</b></font><br><input type=radio value=".$p["id"]." name=id></td>";
		if ($i%4==4) echo "</tr>";
		$i++;
	}
?>		
</table>
</td>
</tr>
</table>
</form>
</center>