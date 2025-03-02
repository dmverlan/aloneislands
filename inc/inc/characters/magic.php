<table border="0" width="435" cellspacing="1" class=inv>
	<tr>
		<td width="155" class=um>Религия</td>
		<td><img src="images/ma.png" width=<?= round(200*$pers["light_necr"]/1000);?> height=8><img src="images/no.png" width=<?= round(200-200*$pers["light_necr"]/1000);?> height=8></td>
		<td width="80" align="right">[<?= round($pers["light_necr"]);?>/1000]</td>
	</tr>
	<tr>
		<td width="150" class=um>Некромантия</td>
		<td><img src="images/ma.png" width=<?= round(200*$pers["dark_necr"]/1000);?> height=8><img src="images/no.png" width=<?= round(200-200*$pers["dark_necr"]/1000);?> height=8></td>
		<td width="80" align="right">[<?= round($pers["dark_necr"]);?>/1000]</td>
	</tr>
	<tr>
		<td width="150" class=um>Стихийная магия</td>
		<td><img src="images/ma.png" width=<?= round(200*$pers["elements"]/1000);?> height=8><img src="images/no.png" width=<?= round(200-200*$pers["elements"]/1000);?> height=8></td>
		<td width="80" align="right">[<?= round($pers["elements"]);?>/1000]</td>
	</tr>
	<tr>
		<td width="150" class=um>Магия порядка</td>
		<td><img src="images/ma.png" width=<?= round(200*$pers["order"]/1000);?> height=8><img src="images/no.png" width=<?= round(200-200*$pers["order"]/1000);?> height=8></td>
		<td width="80" align="right">[<?= round($pers["order"]);?>/1000]</td>
	</tr>
	<tr>
		<td width="150" class=um>Вызовы существ</td>
		<td><img src="images/ma.png" width=<?= round(200*$pers["call"]/1000);?> height=8><img src="images/no.png" width=<?= round(200-200*$pers["call"]/1000);?> height=8></td>
		<td width="80" align="right">[<?= round($pers["call"]);?>/1000]</td>
	</tr>
</table>