<?php

if (!file_exists("../service/top_gamers/A".date("d-m-y").".txt"))
{
$res = sql("SELECT *
 FROM `users` WHERE 
 priveleged = 0
 and
 block=''
 and
 lasto<>0
  ORDER BY 
  (level*1000+exp/(victories+losses+1)+(victories-losses) + rank_i*100) DESC LIMIT 0 , 100");

$top = "var list=new Array(\n";
$i=0;
while ($r=mysql_fetch_array($res)) 
{

 $stats = floor(($r["level"]*1000+ $r["exp"]/($r["victories"]+$r["losses"]+1)+($r["victories"]-$r["losses"])+ 100*$r["rank_i"]));

 if ($i<>0) $top .= ",";
 $i++;
 if ($r["sign"]=='') $r["sign"]="none";
 $r["state"]=str_replace("|","",$r["state"]);
 $top .= "'".$r["user"]."|".$r["level"]."|".$r["sign"]."|".$r["state"]."|".abs($stats)."|".$z."|".$r["id"]."|";
 $top .= "'";
}
$top .= ");";
$top .= "show_list ('0+');";
$f = fopen ("service/top_gamers/A".date("d-m-y").".txt","w");
fwrite($f,$top);
fclose($f);
}
?>