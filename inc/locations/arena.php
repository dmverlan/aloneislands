<?	
//echo "<a href=main.php?go=back class=bga>ПАРАДНАЯ</a>";
include ("./inc/combats.php");
/*		
		
		
	if ($pers["apps_id"] or $pers["help"]==4) $_FILTER["ar_loc"]=2;
	if ($_FILTER["ar_loc"]<3 and $pers["chp"]<$pers["hp"]*0.75)
	{
		echo "<center class=hp>Вы слишком слабы.[".$pers["chp"]."/".$pers["hp"]."]</center><hr>";
		$_FILTER["ar_loc"]=3;
	}
	if ($_FILTER["ar_loc"]==1 and $pers["level"]>17)
	{
		echo "<center class=hp>Вы уже переросли бои с монстрами.</center><hr>";
		$_FILTER["ar_loc"]=3;
	}
	if ($pers["chp"]<$pers["hp"]*0.75) $_FILTER["ar_loc"]=3;
	
	if ($_FILTER["ar_loc"]<>1 and $_FILTER["ar_loc"]<>2)
	{
	echo
	'
<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr>
<td align="center" class=params>
<table border="0" width="100%" cellspacing="5" cellpadding="5">
<tr>
<td align="center" width="50%">
<a href=main.php?ar_loc=1 class=bga>ТРЕНИРОВОЧНЫЙ ЗАЛ</a>
<p>

<font class=items>Здесь можно провести бои с монстрами.<hr>Уровень доступа: 0-17<br>Запас здоровья: Не менее 75%</font>
</td>
<td align="center" width="50%"><a href=main.php?ar_loc=2 class=bga>БОЕВОЙ ЗАЛ</a><p>';
if ($pers["level"]==0) echo "<img src=\"images/design/warningblue.gif\" />";
echo '

<b class=green>Рекомендуем.</b>
<font class=ym>Здесь проводятся бои с другими игроками.<hr>Уровень доступа: Любой<br>Запас здоровья: Не менее 75%</font>
</td>
</tr>
</table>
</td></tr> </table>  
	';
	}
	elseif ($_FILTER["ar_loc"]==1)
	{
		echo "<a href=main.php?go=back class=bga>ПАРАДНАЯ</a>";
		include ("arena/tr.php");
	}
	else 
	{
		echo "<a href=main.php?go=back class=bga>ПАРАДНАЯ</a>";
		include ("./inc/combats.php");
	}
?>
<?
if (!$pers["apps_id"])
{
?>
<center class=loc><a class=timef href=# <? echo build_go_string('lavka',$lastom_new); ?>><img src="images/battle/down.gif">Подземный ход в лавку</a></center>
<?
}*/




?>
