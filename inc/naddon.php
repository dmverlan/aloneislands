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

$types = Array ("Нейтральное","Религия","Некромантия","Стихийная магия","Магия порядка","Вызовы существ");
function a_img($img,$href)
{
	return "<a href='".$href."'><img src='images/design/abils/".$img.".gif'></a>";
}
	if ($pers["sign"]<>'none')
	{
		$cl_im = 'right_stream';
		$cl_href = 'main.php?action=addon&gopers=clan';
	}
	else
	{
		$cl_im = 'no_clan';
		$cl_href = '';
	}
	echo '
	<table border="0" width="100%" cellspacing="0" cellpadding="0" height="316">
	<tr>
		<td width="56" background="images/design/abils/left.jpg">&nbsp;</td>
		<td valign="top" bgcolor="#F3F3F3">';

		if(0 and @$_GET["gopers"]<>'clan')
		{
		echo '<table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
			<tr>
				<td valign="top" width="344">
				<img border="0" src="images/design/abils/left_stream.gif" width="184" height="22"></td>
				<td align="right" valign="top">
				'.a_img($cl_im,$cl_href).'
				</td>
			</tr>
			<tr>
				<td height="21%" valign="top" width="344">
				<script>
				show_only_hp('.$pers["chp"].','.$pers["hp"].','.$pers["cma"].','.$pers["ma"].');
ins_HP('.$pers["chp"].','.$pers["hp"].','.$pers["cma"].','.$pers["ma"].','.$sphp.', '.$spma.');
				</script>
				<hr>
				<table border="0" width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td class="timef" width="120">Религия</td>
						<td width="200" align="center">
						<img border="0" src="images/design/abils/sb.gif" width="8" height="6"><img border="0" src="images/design/abils/s.gif" width="'.round($pers["m1"]/8).'" height="6"><img border="0" src="images/design/abils/ns.gif" width="'.round(120-$pers["m1"]/8).'" height="6"><img border="0" src="images/design/abils/se.gif" width="8" height="6"></td>
						<td width="70">['.round($pers["m1"]).'/1000]</td>
					</tr>
					<tr>
						<td class="timef" width="120">Некромантия</td>
						<td width="200" align="center"><img border="0" src="images/design/abils/sb.gif" width="8" height="6"><img border="0" src="images/design/abils/s.gif" width="'.round($pers["m2"]/8).'" height="6"><img border="0" src="images/design/abils/ns.gif" width="'.round(120-$pers["m2"]/8).'" height="6"><img border="0" src="images/design/abils/se.gif" width="8" height="6"></td>
						<td width="70">['.round($pers["m2"]).'/1000]</td>
					</tr>
				</table>
				</td>
				<td valign="bottom" align="right">';
				echo '<img border="0" src="images/design/abils/uup.gif" width="483" height="34"></td>
			</tr>
			<tr>
				<td height="70%" valign="top" width="344">
				<table border="0" width="330" id="table3" cellspacing="0" cellpadding="0" style="border-left-width: 0px; border-right-width: 0px; border-top-width: 0px; border-bottom-style: solid; border-bottom-width: 1px; border-color:#CCCCCC">
					<tr>
						<td colspan="2" width="161">
						<img border="0" src="images/design/abils/u2.gif" width="164" height="36"></td>
						<td colspan="2" width="166" align="right">
						<img border="0" src="images/design/abils/u22.gif" width="164" height="36"></td>
					</tr>
					<tr>
						<td width="50" align="right" valign="top">
						<img border="0" src="images/design/abils/u1.gif" width="46" height="119"></td>
						<td width="113" style="border-left-width: 0px; border-right-style: solid; border-right-width: 1px; border-top-width: 0px; border-bottom-width: 0px; border-color:#CCCCCC">
						'.a_img("godness","main.php?filter_f2=1").'
						'.a_img("necroness","main.php?filter_f2=2").'
						'.a_img("elements","main.php?filter_f2=3").'
						</td>
						<td width="113">	
						'.a_img("auras","main.php?filter_f3=aura").'
						'.a_img("blasts","main.php?filter_f3=blast").'
						</td>
						<td width="50">	
						<img border="0" src="images/design/abils/u12.gif" width="46" height="119"></td>
					</tr>
				</table><div id=aurasc class=aurasc style="float:left;width:100%;"></div>
				</td>
				<td valign=top>';
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

include ("magic/view_blast.php");
include ("magic/view_aura.php");

if ($type=="blast")
{
	if ($stype<>'all') $q = ' and (type='.intval($stype).' or type=0)';
	$blsс = sqlr("SELECT COUNT(*) FROM u_blasts WHERE uidp=".$pers["uid"],0);
	$bls = sql("SELECT * FROM u_blasts WHERE uidp=".$pers["uid"].$q);
	echo "<font class=title>МАГИЧЕСКИЕ УДАРЫ(".$blsс.")</font>";
	echo "<table border=0 width=100% cellspacing=0 cellspadding=0 style='border-left-style: solid; border-width: 1px; border-color:silver'>";
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
	echo "<table border=0 width=100% cellspacing=0 cellspadding=0 style='border-left-style: solid; border-width: 1px; border-color:silver'>";
	while ($bl = mysql_fetch_array($bls))
	{
		vaura($bl,$pers);
	}
	echo "</table>";
}
		echo '</td>
			</tr>
		</table>
		';
		}
		else
		include('inc/inc/clans/info.php');
		
	echo '</td>
		<td width="56" background="images/design/abils/right.jpg">&nbsp;</td>
	</tr>
</table>
	';
	
	

$as = sql("SELECT * FROM p_auras WHERE uid=".$pers["uid"]."");
$txt = '';
while($a = mysql_fetch_array($as))
{
	$txt .= $a["image"].'#<b>'.$a["name"].'</b>@';
	$txt .= 'Осталось <i class=timef>'.tp($a["esttime"]-time()).'</i>';
	$params = explode("@",$a["params"]);
		foreach($params as $par)
		{
			$p = explode("=",$par);
			$perc = '';
			if (substr($p[0],0,2)=='mf') $perc = '%';
			if ($p[1] and $p[0]<>'cma' and $p[0]<>'chp')
			$txt .= '@'.name_of_skill($p[0]).':<b>'.plus_param($p[1]).$perc.'</b>';
		}
	$txt .= '|';
}
echo "<script>view_auras('".$txt."');</script>";

?>