<center>
<table border="0" width="600" cellspacing="0" cellpadding="0" class=but>
<tr>
<td align="center" height="55">
<table border="0" width="500" cellspacing="0" cellpadding="0">
<tr>
<td width="500" colspan="8" align=center>
<div style="border-color:#2B587A;border-bottom-style: solid; border-bottom-width: 1px; padding-bottom: 1px">
<h2>Рейтинги</h2>
<i class=gray>Тестируется и дорабатывается...</i>
</div>
</td>
</tr>
<tr>
<td width=16% class=but>
<b><a class=bg href=main.php?cat=1>Рейтинг войнов</a></b>
</td>
<td width=16% class=but>
<b><a class=bg href=main.php?cat=2>Рейтинг рыболовов</a></b>
</td>
<td width=16% class=but>
<b><a class=bg href=main.php?cat=3>Рейтинг алхимиков</a></b>
</td>
<td width=16% class=but>
<b><a class=bg href=main.php?cat=4>Рейтинг шахтёров</a></b>
</td>
<td width=16% class=but>
<b><a class=bg href=main.php?cat=5>Рейтинг охотников</a></b>
</td>
<td width=16% class=but>
<b><a class=bg href=main.php?cat=6>Реферальный рейтинг</a></b>
</td>
</tr>
</table></td>
</tr>
<tr>
<td align="center" style="border-left-width: 1px; border-right-width: 1px; border-top-style: solid; border-top-width: 1px; border-bottom-width: 1px"><script>
<?
if (empty($_GET["cat"]) or $_GET["cat"]==1)
include("service/top_gamers/A".date("d-m-y").".txt");
if (@$_GET["cat"]==2)
include("service/top_gamers/F".date("d-m-y").".txt");
if (@$_GET["cat"]==3)
include("service/top_gamers/L".date("d-m-y").".txt");
if (@$_GET["cat"]==4)
include("service/top_gamers/M".date("d-m-y").".txt");
if (@$_GET["cat"]==5)
include("service/top_gamers/H".date("d-m-y").".txt");
if (@$_GET["cat"]==6)
include("service/top_gamers/R".date("d-m-y").".txt");

?>
function show_list()
{
	document.write (sbox2b(1)+'<table width=500 border="0" cellspacing="0" cellpadding="0">');
	for (var i=0;i<list.length;i++) document.write(hero_string(list[i],i+1));
	document.write ('</table>'+sbox2e());
}
function hero_string (element,a)
{
 var arr = element.split("|");
 var s;
 var info;
 var bg = '#EEEEEE';
 if(a%2) bg = '#F5F5F5';
 if (arr[5]==0)
  info = '<a href=\'info.php?p='+arr[0]+'\' target=_blank> <img src=images/_i.gif border=0> </a>';
 else
  info = '<img src=images/i.gif onclick="javascript:window.open(\'binfo.php?'+arr[6]+'\',\'_blank\')" style="cursor:pointer">';
 s = '<tr style="background-color:'+bg+'"><td class=items>'+a+'.</td><td><img src=images/_p.gif onclick="javascript:top.say_private(\''+arr[0]+'\')" style="cursor:pointer"> </td><td> <img src=images/signs/'+arr[2]+'.gif title=\''+arr[3]+'\'><font class=user onclick="javascript:top.say_private(\''+arr[0]+'\')"> '+arr[0]+'</font></td><td>[<font class=lvl>'+arr[1]+'</font>]</td><td>'+info+'</font>';
 s+='</td><td class=ma style="border-left-style: solid; border-left-width: 1px; border-right-width: 1px; border-top-width: 1px; border-bottom-width: 1px"> &nbsp;Очки: '+arr[4];
 s+='</td></tr>';
 return s;
}
</script>
</td>
	</tr>
</table>
</center>
<?
if ($_GET["cat"]==2)
 {
 $wins = sqla(("SELECT MAX(victories) FROM `users` WHERE sign<>'sl'"));
 $wins_u = sqla(("SELECT user,level,sign FROM `users` WHERE `victories`='".$wins[0]."'"));
 $lozes = sqla(("SELECT MAX(losses) FROM `users` WHERE sign<>'sl'"));
 $lozes_u = sqla(("SELECT user,level,sign FROM `users` WHERE `losses`='".$lozes[0]."'"));
 $hunt = sqla(("SELECT MAX(sp10) FROM `users` WHERE sign<>'sl'"));
 $hunt_u = sqla(("SELECT user,level,sign FROM `users` WHERE ROUND(sp10*10)='".round($hunt[0]*10)."'"));
 $money = sqla(("SELECT MAX(money) FROM `users` WHERE sign<>'sl'"));
 $money_u = sqla(("SELECT user,level,sign FROM `users` WHERE `money`='".$money[0]."'"));
 $f = sqla(("SELECT MAX(losses+victories) FROM `users` WHERE sign<>'sl'"));
 $f_u = sqla(("SELECT user,level,sign FROM `users` WHERE losses+victories='".$f[0]."'"));
 $exp = sqla(("SELECT MAX(exp) FROM `users` WHERE sign<>'sl'"));
 $exp_u = sqla(("SELECT user,level,sign FROM `users` WHERE exp='".$exp[0]."'"));
 $hp = sqla(("SELECT MAX(hp) FROM `users` WHERE sign<>'sl'"));
 $hp_u = sqla(("SELECT user,level,sign FROM `users` WHERE hp='".$hp[0]."'"));
 $ma = sqla(("SELECT MAX(ma) FROM `users` WHERE sign<>'sl'"));
 $ma_u = sqla(("SELECT user,level,sign FROM `users` WHERE ma='".$ma[0]."'"));
 if (!file_exists("service/records/".date("d-m-y").".txt"))
{
	$rekords = '
	<table border="0" width="600" id="table1" cellspacing="0" cellpadding="0">
	<tr>
		<td width="294" class="items"><span lang="ru">Самое большое количество
		побед</span></td>
		<td align="center" class="items">'.$wins[0].'</td>
		<td class="items"><img src=images/pr.gif onclick="javascript:top.say_private(\''.$wins_u[0].'\')" style=cursor:hand> </td><td> <img src=images/signs/'.$wins_u[2].'.gif><font class=user onclick="javascript:top.say_private(\''.$wins_u[0].'\')"> '.$wins_u[0].'</font></td><td>[<font class=lvl>'.$wins_u[1].'</font>]</td><td><img src=images/info.gif onclick="javascript:window.open(\'info.php?p='.$wins_u[0].'\',\'_blank\')" style="cursor:hand"></font></td>
	</tr>
	<tr>
		<td width="294" class="items"><span lang="ru">Самое большое количество
		поражений</span></td>
		<td align="center" class="items">'.$lozes[0].'</td>
		<td class="items"><img src=images/pr.gif onclick="javascript:top.say_private(\''.$lozes_u[0].'\')" style=cursor:hand> </td><td> <img src=images/signs/'.$lozes_u[2].'.gif><font class=user onclick="javascript:top.say_private(\''.$lozes_u[0].'\')"> '.$lozes_u[0].'</font></td><td>[<font class=lvl>'.$lozes_u[1].'</font>]</td><td><img src=images/info.gif onclick="javascript:window.open(\'info.php?p='.$lozes_u[0].'\',\'_blank\')" style="cursor:hand"></font></td>
	</tr>
	<tr>
		<td width="294" class="items"><span lang="ru">Самое большое количество
		умений &quot;Охота&quot;</span></td>
		<td align="center" class="items">'.round($hunt[0]*10).'</td>
		<td class="items"><img src=images/pr.gif onclick="javascript:top.say_private(\''.$hunt_u[0].'\')" style=cursor:hand> </td><td> <img src=images/signs/'.$hunt_u[2].'.gif><font class=user onclick="javascript:top.say_private(\''.$hunt_u[0].'\')"> '.$hunt_u[0].'</font></td><td>[<font class=lvl>'.$hunt_u[1].'</font>]</td><td><img src=images/info.gif onclick="javascript:window.open(\'info.php?p='.$hunt_u[0].'\',\'_blank\')" style="cursor:hand"></font></td>
	</tr>
	<tr>
		<td width="294" class="items"><span lang="ru">Самое большое количество
		Игровой Валюты</span></td>
		<td align="center" class="items">'.round($money[0],2).'</td>
		<td class="items"><img src=images/pr.gif onclick="javascript:top.say_private(\''.$money_u[0].'\')" style=cursor:hand> </td><td> <img src=images/signs/'.$money_u[2].'.gif><font class=user onclick="javascript:top.say_private(\''.$money_u[0].'\')"> '.$money_u[0].'</font></td><td>[<font class=lvl>'.$money_u[1].'</font>]</td><td><img src=images/info.gif onclick="javascript:window.open(\'info.php?p='.$money_u[0].'\',\'_blank\')" style="cursor:hand"></font></td>
	</tr>
	<tr>
		<td width="294" class="items"><span lang="ru">Самое большое количество
		боёв</span></td>
		<td align="center" class="items">'.$f[0].'</td>
		<td class="items"><img src=images/pr.gif onclick="javascript:top.say_private(\''.$f_u[0].'\')" style=cursor:hand> </td><td> <img src=images/signs/'.$f_u[2].'.gif><font class=user onclick="javascript:top.say_private(\''.$f_u[0].'\')"> '.$f_u[0].'</font></td><td>[<font class=lvl>'.$f_u[1].'</font>]</td><td><img src=images/info.gif onclick="javascript:window.open(\'info.php?p='.$f_u[0].'\',\'_blank\')" style="cursor:hand"></font></td>
	</tr>
	<tr>
		<td width="294" class="items"><span lang="ru">Самое большое количество
		опыта</span></td>
		<td align="center" class="items">'.$exp[0].'</td>
		<td class="items"><img src=images/pr.gif onclick="javascript:top.say_private(\''.$exp_u[0].'\')" style=cursor:hand> </td><td> <img src=images/signs/'.$exp_u[2].'.gif><font class=user onclick="javascript:top.say_private(\''.$exp_u[0].'\')"> '.$exp_u[0].'</font></td><td>[<font class=lvl>'.$exp_u[1].'</font>]</td><td><img src=images/info.gif onclick="javascript:window.open(\'info.php?p='.$exp_u[0].'\',\'_blank\')" style="cursor:hand"></font></td>
	</tr>
	<tr>
		<td width="294" class="items"><span lang="ru">Самое большое количество
		</span><font class=hp>HP</font></td>
		<td align="center" class="items">'.$hp[0].'</td>
		<td class="items"><img src=images/pr.gif onclick="javascript:top.say_private(\''.$hp_u[0].'\')" style=cursor:hand> </td><td> <img src=images/signs/'.$hp_u[2].'.gif><font class=user onclick="javascript:top.say_private(\''.$hp_u[0].'\')"> '.$hp_u[0].'</font></td><td>[<font class=lvl>'.$hp_u[1].'</font>]</td><td><img src=images/info.gif onclick="javascript:window.open(\'info.php?p='.$hp_u[0].'\',\'_blank\')" style="cursor:hand"></font></td>
	</tr>
	<tr>
		<td width="294" class="items"><span lang="ru">Самое большое количество
		</span><font class=ma>MA</font></td>
		<td align="center" class="items">'.$ma[0].'</td>
		<td class="items"><img src=images/pr.gif onclick="javascript:top.say_private(\''.$ma_u[0].'\')" style=cursor:hand> </td><td> <img src=images/signs/'.$ma_u[2].'.gif><font class=user onclick="javascript:top.say_private(\''.$ma_u[0].'\')"> '.$ma_u[0].'</font></td><td>[<font class=lvl>'.$ma_u[1].'</font>]</td><td><img src=images/info.gif onclick="javascript:window.open(\'info.php?p='.$ma_u[0].'\',\'_blank\')" style="cursor:hand"></font></td>
	</tr>
</table>
	';
$f = fopen ("service/records/".date("d-m-y").".txt","w");
fwrite($f,$rekords);
fclose($f);
}else include("service/records/".date("d-m-y").".txt");
 }
?>