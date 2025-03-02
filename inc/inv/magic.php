<script>
function deletezakl (id) {
if  (confirm ('Вы действительно хотите вычеркнуть это заклинание?')) {location='main.php?deletezakl='+id;}
}
</script>
<?
if (@$_GET["aura_use"])
{
	aura_on(intval($_GET["aura_use"]),$pers,$pers);
}

if (@$_GET["aura_delete"])
{
	sql("DELETE FROM u_auras WHERE uidp=".$pers["uid"]." and id=".intval($_GET["aura_delete"]));
}

if (@$_GET["autocast"])
{
	$cast = intval($_GET["autocast"]);
	$a = sqlr("SELECT autocast FROM u_auras WHERE id=".$cast);
	if($a)$a = 0; else $a = 1;
	sql("UPDATE u_auras SET autocast=".$a." WHERE id=".$cast);
	if($a==0)
		sql("UPDATE p_auras SET autocast=0 WHERE autocast=".$cast);
	else
		aura_on($cast,$pers,$pers);
}

$types = Array ("Нейтральное","Религия","Некромантия","Стихийная магия","Магия порядка","Вызовы существ");
function a_img($img,$href)
{
	return "<a href='".$href."'><img src='images/design/abils/".$img.".gif'></a>";
}
	echo '
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" bgcolor="#F3F3F3">';

		echo '<table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
			<tr>
				<td height="21%" valign="top" align=center>
				<table border="0" width="300" cellspacing="0" cellpadding="0" class=but>
					<tr>
						<td class="timef" width="120">'.name_of_skill("m1").'</td>
						<td width="200" align="center">
						<img border="0" src="images/design/abils/sb.gif" width="8" height="6"><img border="0" src="images/design/abils/s.gif" width="'.round($pers["m1"]/8).'" height="6"><img border="0" src="images/design/abils/ns.gif" width="'.round(120-$pers["m1"]/8).'" height="6"><img border="0" src="images/design/abils/se.gif" width="8" height="6"></td>
						<td width="70">['.round($pers["m1"]).'/1000]</td>
					</tr>
					<tr>
						<td class="timef" width="120">'.name_of_skill("m2").'</td>
						<td width="200" align="center"><img border="0" src="images/design/abils/sb.gif" width="8" height="6"><img border="0" src="images/design/abils/s.gif" width="'.round($pers["m2"]/8).'" height="6"><img border="0" src="images/design/abils/ns.gif" width="'.round(120-$pers["m2"]/8).'" height="6"><img border="0" src="images/design/abils/se.gif" width="8" height="6"></td>
						<td width="70">['.round($pers["m2"]).'/1000]</td>
					</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td height="70%" valign="top" align=center>
				<center class=but>
				<a href=main.php?filter_f3=blast&inv=magic class=Blocked>Магические удары</a>
				<a href=main.php?filter_f3=aura&inv=magic class=Blocked>Ауры</a>
				</center>
			</td></tr><tr>
				<td valign=top align=center style="width: 400px;"><center class=but><img border="0" src="images/design/abils/uup.gif" style="width: 400px;">';
#########################		
switch ($_FILTER["show_z"]){
case 'blast': $type = $_FILTER["show_z"];break;
case 'aura': $type = $_FILTER["show_z"];break;
default : $type = 'blast';break;
}

switch ($_FILTER["h_zn_show"]){
case '1': $stype = $_FILTER["h_zn_show"];break;
case '2': $stype = $_FILTER["h_zn_show"];break;
case '3': $stype = $_FILTER["h_zn_show"];break;
default : $stype = 'all';break;
}

$_INV = 1;

include ("./inc/magic/view_blast.php");
include ("./inc/magic/view_aura.php");

if ($type=="blast")
{
	$blsс = sqlr("SELECT COUNT(*) FROM u_blasts WHERE uidp=".$pers["uid"],0);
	$bls = sql("SELECT * FROM u_blasts WHERE uidp=".$pers["uid"]);
	echo "<font class=title>МАГИЧЕСКИЕ УДАРЫ(".$blsс.")</font>";
	echo "<table border=0 width=100% cellspacing=0 cellspadding=0 class=inv style='border-left-style: solid; border-width: 1px; border-color:silver'>";
	while ($bl = mysql_fetch_array($bls))
	{
		vblast($bl,$pers);
	}
	echo "</table>";
}
if ($type=="aura")
{
	if ($stype<>'all') $q = ' and (type='.intval($stype).' or type=0)';
	$blsс = sqlr("SELECT COUNT(*) FROM u_auras WHERE uidp=".$pers["uid"],0);
	$bls = sql("SELECT * FROM u_auras WHERE uidp=".$pers["uid"].$q);
	echo "<font class=title>МАГИЧЕСКИЕ АУРЫ(".$blsс.")</font>";
	echo "<table border=0 width=100% cellspacing=0 cellspadding=0 style='border-left-style: solid; border-width: 1px; border-color:silver' class=loc>";
	while ($bl = mysql_fetch_array($bls))
	{
		vaura($bl,$pers);
	}
	echo "</table>";
}
		echo '</td></tr></table>';

		
	echo '</td></tr></table>';
?>