<?php

if (!file_exists("../service/top_gamers/H".date("d-m-y").".txt"))
{
$res = sql("SELECT sign,user,aura,level,state,exp,losses,victories,money,uid,rank_i,sp10
 FROM `users` WHERE 
 priveleged = 0
 and
 block=''
 and
 lasto<>0
  ORDER BY 
  (sp10) DESC LIMIT 0 , 50");
$top = "var list=new Array(\n";
$i=0;
while ($r=mysql_fetch_array($res)) 
{

 $stats = floor($r["sp10"]);

 if ($i<>0) $top .= ",";
 $i++;
 if ($r["sign"]=='') $r["sign"]="none";
 $r["state"]=str_replace("|","",$r["state"]);
 $top .= "'".$r["user"]."|".$r["level"]."|".$r["sign"]."|".$r["state"]."|".abs($stats)."|".$z."|".$r["id"]."|";
 $top .= "'";
}
$top .= ");";
$top .= "show_list ('0+');";
$f = fopen ("service/top_gamers/H".date("d-m-y").".txt","w");
fwrite($f,$top);
fclose($f);
}
?>