<?
$p = $_POST;
$r=0;
if (!($p["ug"].$p["ut"].$p["uj"].$p["un"])) $r=999;
if ($p["aura_id"]) $r = floor(0.9*(round($pers["sb1"])+5));

if($p["ug"] and $p["ug"]<($OD_UDAR+3) and $p["ug"]!='magic') $p["ug"] = $OD_UDAR+3;
if($p["ut"] and $p["ut"]<($OD_UDAR+3) and $p["ut"]!='magic') $p["ut"] = $OD_UDAR+3;
if($p["uj"] and $p["uj"]<($OD_UDAR+3) and $p["uj"]!='magic') $p["uj"] = $OD_UDAR+3;
if($p["un"] and $p["un"]<($OD_UDAR+3) and $p["un"]!='magic') $p["un"] = $OD_UDAR+3;

if($p["ug"]) {$ugs=1;$r+=$p["ug"];}
if($p["ut"]) {$uts=1;$r+=$p["ut"];}
if($p["uj"]) {$ujs=1;$r+=$p["uj"];}
if($p["un"]) {$uns=1;$r+=$p["un"];}
if($p["bg"]) {$bgs=1;$r+=$p["bg"];}
if($p["bt"]) {$bts=1;$r+=$p["bt"];}
if($p["bj"]) {$bjs=1;$r+=$p["bj"];}
if($p["bn"]) {$bns=1;$r+=$p["bn"];}

if($p["ug"]=='magic') {$ugs=1;$r+=3*$p["magic_koef"];}
if($p["ut"]=='magic') {$uts=1;$r+=3*$p["magic_koef"];}
if($p["uj"]=='magic') {$ujs=1;$r+=3*$p["magic_koef"];}
if($p["un"]=='magic') {$uns=1;$r+=3*$p["magic_koef"];}

if($p["bg"]=='magic') {$bgs=1;$r+=2;}
if($p["bt"]=='magic') {$bts=1;$r+=2;}
if($p["bj"]=='magic') {$bjs=1;$r+=2;}
if($p["bn"]=='magic') {$bns=1;$r+=2;}


$g = $ugs + $uts + $ujs + $uns;
if ($g==1) $r+=0;
if ($g==2) $r+=10;
if ($g==3) $r+=35;
if ($g==4) $r+=55;

$g = $bgs + $bts + $bjs + $bns;
if ($g==1) $r+=0;
if ($g==2) $r+=0;
if ($g==3) $r+=35;
if ($g==4) $r+=55;

if($ugs) $zid = $p["ugp"];
if($uts) $zid = $p["utp"];
if($ujs) $zid = $p["ujp"];
if($uns) $zid = $p["unp"];
?>