<?
// Передача денег
if (isset($_POST["money"]) 
and isset($_POST["fornickname"]) 
and $pers["punishment"]<$time) 
{
	$k = mtrunc(intval($_POST["kolvo"]));
	if ($k>100000) $k=100000;
	if ($k>0 and $k<=$pers["money"]) 
	{
		$persto = sqla ("SELECT location,user,money,uid,lastip FROM users WHERE user='".$_POST["fornickname"]."'");
		if ($persto["user"]<>$pers["user"])
		{
		if ($persto["location"]==$pers["location"]) 
		{
			$_RETURN .= "Вы передали ".$k." LN для ".$persto["user"]."";
			$m = $pers["user"]."|".$k." LN| ||";
			say_to_chat('s',$m,1,$persto["user"],'*',0);
			$pers["money"] -= $k;
			$persto["money"]+=$k;
			set_vars("money=".$pers["money"],$pers["uid"]);
			set_vars("money=".$persto["money"],$persto["uid"]);
			transfer_log(6,$persto["uid"],$pers["user"],$k,0,$_POST["reason"],$persto["lastip"],$pers["lastip"]);
			transfer_log(3,$pers["uid"],$persto["user"],0,$k,$_POST["reason"],$pers["lastip"],$persto["lastip"]);
		}else $_RETURN .= "Нет такого персонажа в данном месте либо у вас нет такой суммы.";
		}
		else
		$_RETURN .= "Нельзя ничего передавать себе.";
	}
}


?>