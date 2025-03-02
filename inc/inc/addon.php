<?
	$book = sqla("SELECT slots,`index`,id FROM wp WHERE uidp=".$pers["uid"]." and weared=1 and stype='book'");
	if ($_GET["into_book"] and $book["slots"])
	{
		$book["index"].="|".addslashes($_GET["into_book"])."|";
		$book["slots"]--;
		sql("UPDATE wp SET slots=slots-1,`index`='".$book["index"]."' WHERE id=".$book["id"]."");
	}
	if ($_GET["out_book"])
	{
		$book["index"]=str_replace("|".addslashes($_GET["out_book"])."|","",$book["index"]);
		$book["slots"]++;
		sql("UPDATE wp SET slots=slots+1,`index`='".$book["index"]."' WHERE id=".$book["id"]."");
	}
?>
<SCRIPT LANGUAGE='JavaScript' SRC='js/only_hp.js'></SCRIPT>
<script>
function deletezakl (id) {
if  (confirm ('Вы действительно хотите вычеркнуть это заклинание?')) {location='main.php?deletezakl='+id;}
}
</script>
<center>
<?
if ($pers["sign"]<>'none') echo "<input type=button class=laar value=Клан onclick=\"location='main.php?action=addon&gopers=clan'\">"; ?>
</center>
<hr>
<?
if ($_GET["gopers"]=="clan") {include('inc/inc/clans/info.php');exit;}
?>
<script>
show_only_hp(<?=$pers["chp"];?>,<?=$pers["hp"];?>,<?=$pers["cma"]?>,<?=$pers["ma"]?>);
ins_HP(<?=$pers["chp"]?>,<?=$pers["hp"]?>,<?=$pers["cma"]?>,<?=$pers["ma"]?>, <?=$sphp?>, <?=$spma?>);
</script>
<table border="0" width="100%" cellspacing="0" cellpadding="0" class=inv>
	<tr>
		<td><? include ("inc/inc/characters/magic.php"); ?></td>
		<td align=center><table border="0" width="100%" cellpadding="0">
	<tr>
		<td width="130" valign="top"><form name=sort method=get><select size="1" name="filter_f1" class="fightlong" onchange="document.sort.submit();">
		<option <? if ($_FILTER["sort"]=='') echo "selected";?>>Сортировка</option>
		<option value="tlevel" <? if ($_FILTER["sort"]=='tlevel') echo "selected";?>>По уровню</option>
		<option value="cena" <? if ($_FILTER["sort"]=='cena') echo "selected";?>>По цене</option>
		<option value="mana" <? if ($_FILTER["sort"]=='mana') echo "selected";?>>Исп. маны</option>
		<option value="udmax" <? if ($_FILTER["sort"]=='udmax') echo "selected";?>>По удару</option>
		<option value="time" <? if ($_FILTER["sort"]=='time') echo "selected";?>>Время действия</option>
		</select></form></td>
		<td width="152">
		<input type="button" value="Обновить" class="loc" onclick="location='main.php?zinfo=full'"></td>
	</tr>
</table></td>
	</tr>
	<tr>
		<td valign="top"><table width=437 border=0><tr>
<td width=20% align='center' class=inv><a class=ma href=main.php?filter_f2=light_necr>Религия</a></td>
<td width=20% align='center' class=inv><a class=ma href=main.php?filter_f2=dark_necr>Некромантия</a></td>
<td width=20% align='center' class=inv><a class=ma href=main.php?filter_f2=elements>Стихийная магия</a></td>

</tr><tr>
<td width=20% align='center' class=inv><a class=ma href=main.php?filter_f2=order>Магия порядка</a></td>
<td width=20% align='center' class=inv><a class=ma href=main.php?filter_f2=call>Вызовы существ</a></td>
<td width=20% align='center' class=inv>&nbsp;</td>

</tr></table>
<table border="0" width=437 cellspacing="0" cellpadding="0" class="laar" style="border-width: 0">
	<tr>
		<td align="center">
		<input type=button class=loc onclick="location='main.php?filter_f3=blast'" value='Магические удары' style="width: 100"></td>
		<td align="center"><input type=button class=loc onclick="location='main.php?filter_f3=aura'" value='Ауры' style="width: 100"></td>
	</tr>
	<tr>
		<td align="center"><input type=button class=loc onclick="location='main.php?filter_f3=visov'" value='Вызовы существ' style="width: 100"></td>
		<td align="center"><input type=button class=loc onclick="location='main.php?filter_f3=other'" value='Прочее' style="width: 100"></td>
	</tr>
</table><center><input type=button class=loc onclick="location='main.php?filter=clear'" value='Сбросить фильтр' style="width: 100"></center></td>
		<td>
<?
if ($pers["cfight"]==0) {

switch ($_FILTER["show_z"]){
case 'blast': $type = $_FILTER["show_z"];break;
case 'aura': $type = $_FILTER["show_z"];break;
case 'other': $type = $_FILTER["show_z"];break;
default : $type = 'all';break;
}

switch ($_FILTER["h_zn_show"]){
case 'elements': $stype = $_FILTER["h_zn_show"];break;
case 'light_necr': $stype = $_FILTER["h_zn_show"];break;
case 'dark_necr': $stype = $_FILTER["h_zn_show"];break;
case 'order': $stype = $_FILTER["h_zn_show"];break;
case 'call': $stype = $_FILTER["h_zn_show"];break;
default : $stype = 'all';break;
}

if ($_GET["filter"]=='clear')
{
	$type = 'all';
	$stype= 'all';
}

if ($book["id"])
echo "В вашей книге заклинаний ".$book["slots"]." своб. слот.<br>";
else
echo "У вас нет книги заклинаний , либо она не надета.";
$idall=explode ("|",$pers["zakl"]."|19");
if ($pers["zakl"]=="" or $pers["zakl"]=="|") echo "<br><b>Ваша книга заклинаний пуста.</b>";
$zakl_count=0;
foreach ($idall as $id)
if ($id<>"" and $id<>"kamen") {
include('inc/inc/zakl.php');
if ($zak["name"]<>""){
	if (substr_count($book["index"],"|".$id."|")) echo "<input type=button class=submit onclick=\"location='main.php?out_book=".$id."'\" value='Вычеркнуть из книги заклинаний'>";
	elseif ($book["slots"]) echo "<input type=button class=submit onclick=\"location='main.php?into_book=".$id."'\" value='Вписать в книгу заклинаний'>";
echo "<input type=button class=submit onclick=\"deletezakl ('".$id."')\" value='Удалить' title ='Вычеркнуть из книги заклинаний'>";	
if ($zak["type"]=="aura" or $zak["type"]=="health" or $zak["type"]=="aura2")
{
	if ($pers["cma"]>=$zak["mana"] and $zak["t_in_c"]==0)
	echo "<input type=button class=submit onclick=\"location='main.php?usezakl=".$id."'\" value='Использовать'>";
	elseif ($pers["cma"]<$zak["mana"])
	echo "<font class=hp>Не хватает маны</font>";
	elseif ($zak["t_in_c"]>0)
	echo "<font class=hp>Использование только в бою</font>";
}else echo "<font class=hp>Использование только в бою</font>";
echo"<br><br>";$zakl_count++;
}
}
if ($zakl_count==0 and $pers["zakl"]<>"" and $pers["zakl"]<>"|") echo "<font class=items>У вас нет заклинаний в этой категории</font>";
} else {echo "<font class=hp>Нельзя использовать магию, находясь в бою или в заявке.</font>";}

?>
</td>
	</tr>
</table>
