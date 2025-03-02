<?
// ЦВЕТА
	$colors[1] = "#087C20";
	$colors[2] = "#0052A6";
	$colors[3] = "#444400";
	$colors[4] = "#002222";
	$colors[5] = "#FF0088";
	$colors[6] = "#800080";
	$colors[7] = "#077777";
	$colors[8] = "#900900";
///////////////////////////
	// Лог боя. Половой признак
	if ($pers["pol"]=='female') $male='а'; else $male='';
		if ($male=='а') 
		$pitalsa = 'пыталась';
		else
		$pitalsa = 'пытался';
		
		if ($persvs["pol"]=='female')
		 {
			$pogib = 'погибла';
			$malevs='а';
			$yvvs = 'увернулась';
		 }
		else
		 {
			$pogib = 'погиб';
			$malevs='';
			$yvvs = 'увернулся';
		 }
/////////////////////////////
$magic=0;
 if (@$_POST["p"])
 {
	if ($_POST["ug"]){$point="ug";$_POST[$point] = 'magic';$_POST[$point."p"] = $_POST["p"];$_POST["magic"]=1;}
	if ($_POST["ut"]){$point="ut";$_POST[$point] = 'magic';$_POST[$point."p"] = $_POST["p"];$_POST["magic"]=1;}
	if ($_POST["uj"]){$point="uj";$_POST[$point] = 'magic';$_POST[$point."p"] = $_POST["p"];$_POST["magic"]=1;}
	if ($_POST["un"]){$point="un";$_POST[$point] = 'magic';$_POST[$point."p"] = $_POST["p"];$_POST["magic"]=1;}
	$magic=1;
 }
 if (@$_POST["ap"]) $magic = 2;
 // ^ Преобразование магического удара
?>