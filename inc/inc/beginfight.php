<?
$lt = date("d.m.Y H:i");
if ($zay["vsname"]<>""){
	begin_fight($zay["name"],$zay["vsname"],"Групповой бой на арене",$zay["travm"],$zay["timeout"],$zay["oruj"]);
}else{
$string = ' WHERE ';
$name = explode ("|",$zay["name"]);
$vsname = explode ("|",$zay["vsname"]);
foreach ($name as $n) {
if ($n<>$name[count($name)-1]) $string = $string."`user`='".$n."' or"; else $string = $string."`user`='".$n."' ;";
}

sql ("UPDATE `users` SET `cfight`='' , `curstate`=2 ".$string."");

}
?>