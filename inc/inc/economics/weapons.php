<?
//Сдаём вещь в лавку
###############
if (isset($_GET["lavkasdat"]) and $pers["punishment"]<$time and strpos(" ".$pers["location"],"lavka")) 
{
	$v = sqla("SELECT * FROM wp 
	WHERE weared=0 and id=".intval($_GET["lavkasdat"])." and uidp=".UID." and where_buy<>1");
	
	if (@$v["id"] and ($v["clan_name"]=="" or $pers["clan_state"]=='g'))
	{
	$koef_cur = 1;
	if ($v["present"]) $koef_cur = 0.5;
	$koef=1;
	if ($pers["level"]>4) $koef =0.8;
	if ($koef<1) $koef += $pers["sp9"]/1000;
	if ($koef>0.99) $koef=0.99;
	if ($_ECONOMIST) $koef=0.99;
	
	$pers["money"]+= $koef*$koef_cur*$v["price"]*($v["durability"]+1)/($v["max_durability"]+1);
	sql ("UPDATE `weapons` SET `q_s`=q_s+1 WHERE `id`='".$v["id_in_w"]."'");
	sql ("DELETE FROM wp WHERE id=".$v["id"]."");
	set_vars("`money`='".$pers["money"]."', sp9=sp9+1/(sp9+1),`weight_of_w`=weight_of_w-".($v["weight"]),$pers["uid"]);
	$pers["weight_of_w"]-=$v["weight"];
	$_RETURN .= "Вещь удачно сдана в лавку. (Комиссия <b>".round($v["price"]-$koef*$koef_cur*$v["price"]*($v["durability"]+1)/($v["max_durability"]+1),2)."</b> LN)";
	}
	unset($v);
}
###############
//Сдаём вещь в банк
###############
if (isset($_GET["bank"]) and $pers["punishment"]<$time and strpos(" ".$pers["location"],"bank") and $pers["money"]>=round(($v["price"]*0.1),2)) 
{
	$v = sqla("SELECT id_in_w,price,durability,max_durability,weight,id FROM wp 
	WHERE weared=0 and id=".intval($_GET["bank"])." and uidp=".UID." and where_buy<>1 and in_bank=0");
	
	if (@$v["id"])
	{
	$pers["money"]-= round($v["price"]*0.1,2);
	sql ("UPDATE wp SET in_bank=1 WHERE id=".$v["id"]."");
	set_vars("`money`='".$pers["money"]."',`weight_of_w`=weight_of_w-".($v["weight"]),$pers["uid"]);
	$pers["weight_of_w"]-=$v["weight"];
	$_RETURN .= "Вещь удачно сдана в банк на хранение. (Комиссия <b>".round($v["price"]*0.1,2)."</b> LN)";
	}
	unset($v);
}


#######################
if (isset($_GET["getbank"]) and $pers["punishment"]<$time and strpos(" ".$pers["location"],"bank")) 
{
	$v = sqla("SELECT id_in_w,price,durability,max_durability,weight,id FROM wp 
	WHERE weared=0 and id=".intval($_GET["getbank"])." and uidp=".UID." and where_buy<>1 and in_bank=1");
	
	if (@$v["id"])
	{
	sql ("UPDATE wp SET in_bank=0 WHERE id=".$v["id"]."");
	set_vars("`weight_of_w`=weight_of_w+".($v["weight"]),$pers["uid"]);
	$pers["weight_of_w"]+=$v["weight"];
	}
	unset($v);
}
###############
//Сдаём  всю рыбу
###############
if (@$_GET["give"]=='allfish' and $pers["punishment"]<$time and strpos(" ".$pers["location"],"lavka"))
{
	$koef=1;
	if ($pers["level"]>4) $koef =0.8;
	if ($koef<1) $koef+=$pers["sp9"]/1000;
	if ($koef>0.99) $koef=0.99;
	if ($_ECONOMIST) $koef=0.99;
	$price = sqlr("SELECT SUM(price) FROM wp 
	WHERE uidp=".$pers["uid"]." and type='fish' and weared=0",0);
	if($price)
	{
		$pers["money"]+= $koef*$price;
		sql("DELETE FROM wp WHERE uidp=".$pers["uid"]." and type='fish' and weared=0");
		set_vars("sp9=sp9+1/(sp9+1),money=money+".($koef*$price),$pers["uid"]);
		$_RETURN .= "Вся рыба удачно сдана в лавку. (Выручка <b>".round($koef*$price,2)."</b> LN)";
	}
}
###############
###############
//Сдаём  все деревья
###############
if (@$_GET["give"]=='alltrees' and $pers["punishment"]<$time and strpos(" ".$pers["location"],"lavka"))
{
	$koef=1;
	if ($pers["level"]>4) $koef =0.8;
	if ($koef<1) $koef+=$pers["sp9"]/1000;
	if ($koef>0.99) $koef=0.99;
	if ($_ECONOMIST) $koef=0.99;
	$price = sqlr("SELECT SUM(price) FROM wp 
	WHERE uidp=".$pers["uid"]." and id_in_w='res..tree' and weared=0",0);
	$pers["money"]+= $koef*$price;
	sql("DELETE FROM wp WHERE uidp=".$pers["uid"]." and type='fish' and weared=0");
	set_vars("sp9=sp9+1/(sp9+1),money=".$pers["money"],$pers["uid"]);
	$_RETURN .= "Все деревья удачно сданы в лавку. (Выручка <b>".round($koef*$price,2)."</b> LN)";
}
###############

// Сдаём в ДД
######################
if (@$_GET["dhousesdat"]) {
$v = sqla("SELECT durability,max_durability,weight,dprice,id FROM wp 
WHERE uidp=".$pers["uid"]." and id=".intval($_GET["dhousesdat"])." and weared=0 and timeout=0");
if ($v["dprice"]>5)
{
sql ("DELETE FROM wp WHERE id=".$v["id"]."");
$pers["dmoney"] += ($v["dprice"]*DD_STND_KOEF)*(($v["durability"]+1)/($v["max_durability"]+1));
$pers["weight_of_w"]-=$v["weight"];
set_vars ("`dmoney`='".$pers["dmoney"]."',weight_of_w=weight_of_w-".($v["weight"]),$pers["uid"]);
$_RETURN .= "Вещь удачно сдана в Дом Дилеров. (Комиссия <b>".round($v["dprice"]-($v["dprice"]*DD_STND_KOEF)*(($v["durability"]+1)/($v["max_durability"]+1)),2)."</b> y.e.)";
}
else
$_RETURN .= "Нельзя сдать эту вещь.";
unset($v);
}
######################

//Сдаём вещь в ДД (Клановая)
if (@$_GET["dchousesdat"] and $status=='g') {
$v = sqla("SELECT durability,max_durability,weight,dprice,id FROM wp 
WHERE uidp=".$pers["uid"]." and id=".intval($_GET["dchousesdat"])." and weared=0 and timeout=0 and clan_sign='".$pers["sign"]."'");
if (@$v["dprice"])
{
sql ("DELETE FROM wp WHERE id=".$v["id"]."");
sql ("UPDATE `clans` SET `dmoney`=dmoney+".floor(($v["dprice"]*DD_CLAN_KOEF)*(($v["durability"]+1)/($v["max_durability"]+1)))." WHERE `sign`='".$pers["sign"]."'");
set_vars ("weight_of_w=weight_of_w-".($v["weight"]),$pers["uid"]);
$pers["weight_of_w"]-=$v["weight"];
$_RETURN .= "Вещь удачно сдана в Дом Дилеров. (Комиссия <b>".round($v["dprice"]-($v["dprice"]*DD_CLAN_KOEF)*(($v["durability"]+1)/($v["max_durability"]+1)),2)."</b> y.e.)";
}
unset($v);
}
######################


//Сдаём вещь в клан-казну
#########################
if (@$_GET["to_clan"] and $pers["clan_tr"] and $pers["punishment"]<$time)
{
	$v = sqla("SELECT id,name,price,dprice FROM wp WHERE id=".intval($_GET["to_clan"])." and uidp=".$pers["uid"]." and weared=0 and where_buy=0 and clan_name=''");
	if(@$v["id"])
	{
		$clan = sqla ("SELECT * FROM `clans` WHERE `sign`='".$pers['sign']."'");
		if ($clan["treasury"]<($clan["maxtreasury"]+30))
		{
		sql("UPDATE wp SET clan_sign='".$pers["sign"]."' , clan_name='".$clan["name"]."', present='".$pers["user"]."' WHERE id=".$v["id"]." and uidp=".$pers["uid"]);
		sql("UPDATE clans SET treasury=treasury+1 WHERE sign='".$pers["sign"]."'");
		}
	
	transfer_log(2,$pers["uid"],'',0,0,$v["name"]."[".$v["price"]."LN,".$v["dprice"]."y.e.] в клан казну",show_ip(),'');
	$_RETURN .= "Вещь удачно сдана в клан казну.";
	}
unset($v);
}
#########################



// Продажа вещи
########################
if (@$_GET["sell"]=='yes' and $pers["punishment"]<$time) {
$t=time ()-1;
$sale = sqla ("SELECT * FROM `salings` WHERE `id`=".intval($_GET["hash"])."");
$persto = sqla("SELECT uid,money,user,lastip FROM `users` WHERE `uid`=".intval($sale["uidp"])."");
$v = sqla("SELECT price,name,weight,`id` FROM `wp` WHERE `id`='".$sale["idw"]."' and uidp=".$sale["uidp"]." and weared=0");
if ($v["id"])
{
	if ($pers["money"]>$sale["price"])
	{
	$pers["money"]-=$sale["price"];
	$persto["money"]+=$sale["price"];
	set_vars("sp9=sp9+1/(sp9+3),money=".$pers["money"].",weight_of_w=weight_of_w+".($v["weight"]),$pers["uid"]);
	set_vars("sp9=sp9+1/(sp9+3),money=".$persto["money"].",`refr`=1,weight_of_w=weight_of_w-".($v["weight"]),$persto["uid"]);
	sql("UPDATE wp SET uidp=".$pers["uid"]." WHERE id=".$sale["idw"]."");
	say_to_chat ('s',"Сделка удачно завершена",1,$persto["user"],'*',0);
	transfer_log(1,$persto["uid"],$pers["user"],$sale["price"],$v["price"],$v["name"],$persto["lastip"],$pers["lastip"]);
	transfer_log(4,$pers["uid"],$persto["user"],$v["price"],$sale["price"],$v["name"],$pers["lastip"],$persto["lastip"]);
	$_RETURN .= "Вещь удачно куплена.(Ушло <b>".$sale["price"]."</b> LN)";
	}
else 
	{
	say_to_chat ('s',"У персонажа нет таких денег",1,$persto["user"],'*',0);
	$_RETURN .= "Вещь не куплена. Недостаточно денег. Требуется <b>".$sale["price"]."</b> LN.";
	}
}
unset($v);
}
############################


// Подача заявки на продажу
#####################
if (isset($_POST["fornickname"]) and intval(@$_POST["forprice"])>0 and $_POST["fornickname"]<>$pers["user"] and $pers["punishment"]<$time) 
{

$v = sqla("SELECT name,id,where_buy FROM wp 
WHERE uidp=".$pers["uid"]." and weared=0 and id=".intval($_POST["id"])." and where_buy=0");

if (@$v["id"])
{
$persto = sqla ("SELECT uid,location,x,y FROM `users` WHERE `smuser` = LOWER('".$_POST["fornickname"]."')");
if ($pers["location"]==$persto["location"]) 
{
sql ("INSERT INTO `salings` (`id`,`idw`,`uidp`,`price`, `uidwho`) VALUES (0,'".$v["id"]."','".$pers["uid"]."','".$_POST["forprice"]."',".$persto["uid"].") ");
$idf =  mysql_insert_id($main_conn);
$m = "saling#".$idf;
say_to_chat ('s',$m,1,$_POST["fornickname"],'*',0);
$_RETURN .= "Ожидаем подтверждения.";
}
	else $_RETURN .= "Нет такого персонажа в данном месте";
}
unset($v);
}elseif ($_POST["fornickname"]==$pers["user"] and intval(@$_POST["forprice"])>0)$_RETURN .= "Нельзя ничего продавать себе.";
#######################


// Передача вещи
#######################
if (intval(@$_POST["forprice"])==0 and isset($_POST["fornickname"]) and empty($_POST["money"]) and $_POST["fornickname"]<>$pers['user'] and isset($_GET["ids"]) and $pers["punishment"]<$time) 
{
	$ids = explode("!",$_GET["ids"]);
	$persto = sqla ("SELECT uid,money,user,lastip,location,x,y FROM `users` WHERE `smuser` = LOWER('".$_POST["fornickname"]."')");
	foreach($ids as $id)
	{
	if(!$id) continue;
	$v = sqla("SELECT weight,price,name,id FROM `wp` WHERE uidp=".$pers["uid"]." and weared=0 and id=".intval($id)." and where_buy=0");

if ($pers["location"]==$persto["location"] and $v) 
{
	sql("UPDATE wp SET uidp=".$persto["uid"]." WHERE id=".$id."");
	set_vars ("weight_of_w=weight_of_w-".($v["weight"]),$pers["uid"]);
	$pers["weight_of_w"]-=$v["weight"];
	$m = $pers["user"]."|".$v["name"]."|"." "."|".$v["id"]."|";
	say_to_chat('s',$m,1,$_POST["fornickname"],'*',0);
	transfer_log(5,$persto["uid"],$pers["user"],$v["price"],0,$v["name"],$persto["lastip"],$pers["lastip"]);
	transfer_log(2,$pers["uid"],$persto["user"],0,$v["price"],$v["name"],$pers["lastip"],$persto["lastip"]);
	$_RETURN .= "Вещь передана[".$v["name"]."]<br>";
}
	elseif($v)
	{ 
		$_RETURN .= "Нет такого персонажа в данном месте";
		break;
	}
	else
		break;
	
	unset($v);
	}
}elseif ($_POST["fornickname"]==$pers["user"] and isset($_POST["id"]))$_RETURN .= "Нельзя ничего передавать себе.";
#########################

// Передача всех трав
#######################
if (@$_GET["giveallH"] and isset($_POST["fornickname"]) and $_POST["fornickname"]<>$pers['user'] and $pers["punishment"]<$time) 
{
	$persto = sqla ("SELECT uid,money,user,lastip,location,x,y FROM `users` WHERE `smuser` = LOWER('".$_POST["fornickname"]."')");
	$herbals = sql("SELECT weight,price,name,id FROM `wp` WHERE uidp=".$pers["uid"]." and weared=0 and type='herbal'");
	if ($pers["location"]==$persto["location"]) 
	{
	while($v = mysql_fetch_array($herbals,MYSQL_ASSOC))
	{
		sql("UPDATE wp SET uidp=".$persto["uid"]." WHERE id=".$v["id"]."");
		set_vars ("weight_of_w=weight_of_w-".($v["weight"]),$pers["uid"]);
		$pers["weight_of_w"]-=$v["weight"];
		$m = $pers["user"]."|".$v["name"]."|"." "."|".$v["id"]."|";
		say_to_chat('s',$m,1,$_POST["fornickname"],'*',0);
		transfer_log(5,$persto["uid"],$pers["user"],$v["price"],0,$v["name"],$persto["lastip"],$pers["lastip"]);
		transfer_log(2,$pers["uid"],$persto["user"],0,$v["price"],$v["name"],$pers["lastip"],$persto["lastip"]);
		$_RETURN .= "Вещь передана[".$v["name"]."]<br>";
	}
	}
	else
		$_RETURN .= "Нет такого персонажа в данном месте";
}	
elseif ($_POST["fornickname"]==$pers["user"] and isset($_POST["id"]))$_RETURN .= "Нельзя ничего передавать себе.";
#########################

//Выкидывание предмета
##########################
if (isset($_GET["drop"])) {
$v = sqla("SELECT weight,clan_sign,dprice,price FROM wp WHERE id=".intval($_GET["drop"])." and dprice=0 and (where_buy<>1 and where_buy<>1 or p_type=13) and uidp=".$pers["uid"]." and weared=0");
if ($v["clan_sign"]=="" or ($v["clan_sign"]<>"" and $v["price"]<1400 and $v["dprice"]<1 and $status=='g'))
{
if ($v["clan_sign"]) sql("UPDATE clans SET treasury=treasury-1 WHERE sign='".$pers["sign"]."'");
set_vars("weight_of_w=weight_of_w-".intval($v["weight"]),$pers["uid"]);
$pers["weight_of_w"]-=$v["weight"];
sql("DELETE FROM wp WHERE id=".intval($_GET["drop"]));
$_RETURN .= "Вы выкинули предмет.";
}
}
##########################

?>