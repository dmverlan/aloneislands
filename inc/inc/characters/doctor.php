<?
echo '<form method="POST" action="main.php"><div id=doctor><input type=button value="Лечить" class=submit onclick="lecher();"></div></form>';
if (strpos(" |".$pers["aura"],"doctor_l")>0) $l = '<option value="l">Легкая</option>';
if (strpos(" |".$pers["aura"],"doctor_s")>0) $l = '<option value="l">Легкая</option><option value="s">Средняя</option>';
if (strpos(" |".$pers["aura"],"doctor_t")>0) $l = '<option value="l">Легкая</option><option value="s">Средняя</option><option value="t">Тяжелая</option>';
?>

<script>
function lecher() {
ActionFormUse = 'towho';
document.getElementById('doctor').innerHTML = '<table border="0" width="100%" cellspacing="0" cellpadding="0" class="fightlong"><tr><td width="210">Кого</td><td><p><input type="text" name="towho" size="20" class="ma"></p></form></td></tr><tr><td width="210">Вид травмы</td><td><p><select size="1" name="tr_vid" class="ma" onchange="lech()"><option selected>Не выбрано</option><? echo $l;?></select></p></form></td></tr><tr><td colspan="2" id="lech">&nbsp;</td></tr></table><input type="submit" value="Лечить" class=submit>';
}
function lech() {
if (document.getElementById("tr_vid").value=="l") document.getElementById("lech").innerHTML = "<font class=hp>30 HP</font> , <font class=ma>20 MA</font> , <b> 1 LN </b> (Для 0-1 уровня бесплатно)";
else if (document.getElementById("tr_vid").value=="s") document.getElementById("lech").innerHTML = "<font class=hp>50 HP</font> , <font class=ma>50 MA</font> , <b> 2 LN </b> (Для 0-1 уровня бесплатно)";
else if (document.getElementById("tr_vid").value=="t") document.getElementById("lech").innerHTML = "<font class=hp>50 HP</font> , <font class=ma>80 MA</font> , <b> 3 LN </b> (Для 0-1 уровня бесплатно)"; 
else document.getElementById("lech").innerHTML = "&nbsp;";
}
</script>
