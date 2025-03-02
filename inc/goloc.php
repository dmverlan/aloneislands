<?
if (@$_GET["gomine"] and $_MINE and !$_UMINE)
{
	set_vars("minex=0,miney=0,waiter=".(tme()+20).",location='mine'",$pers["uid"]);
	$pers["location"]='mine';
	$pers["waiter"]=tme()+20;
}
if ((@($_GET["outmine"] and $pers["minex"]==0 and $pers["miney"]==0) or !$_MINE or $_UMINE) and $pers["location"]=='mine')
{
	set_vars("waiter=".(tme()+20).",location='mine_start'",$pers["uid"]);
	$pers["location"]='mine_start';
	$pers["waiter"]=tme()+20;
}

$prison = explode ('|',$pers["prison"]);

//if (@$_GET["goloc"])
//$str = md5(strtoupper($lastom_old.$_GET["goloc"].count($_GET["goloc"])));

//echo $_GET["time"]." ".$str;

if ($prison[0]>time()) $pers["cfight"]=2;

if (@$_GET["goloc"] and !$_TRVM /*and $_GET["time"]==$str */and tme()>$pers["waiter"]) 
	{
	if ($pers["cfight"]==0)
		{
		if (abs(10+($pers["sm3"]+$pers["s4"])*10) < $pers["weight_of_w"]) 
		 say_to_chat ("s","<b>Вы перегружены!</b>","1",$pers["user"],'*');
		elseif ($pers["tour"]!=0) 
		 say_to_chat ("s","<b>Нельзя перемещаться во время участия в турнире.</b>","1",$pers["user"],'*');  
		else 
		 {
				if ($pers["location"]=='out')
				{
					$cell = sqla("SELECT * FROM nature WHERE x=".$pers["x"]." and y=".$pers["y"]."");
					if ($_GET["goloc"]<>$cell["go_id"]) $Deny=1;
				}
				else
				{
					$Deny=1;
					$loc1 = sqla("SELECT go_id FROM locations WHERE id='".$_GET["goloc"]."'");
					$loc2 = sqla("SELECT go_id FROM locations WHERE id='".$pers["location"]."'");
					if ($pers["location"]==$loc1["go_id"]) $Deny=0;
					if ($_GET["goloc"]==$loc2["go_id"]) $Deny=0;
				}
				if ($_GET["goloc"]=='lavka' and ($pers["location"]=='arena' or ($pers["x"]==0 and $pers["y"]==0))) $Deny=0;
				if ($_GET["goloc"]=='arena' and ($pers["location"]=='lavka' or ($pers["x"]==0 and $pers["y"]==0))) $Deny=0;				
				if (!$Deny)
				{
				$pers["location"] = $_GET["goloc"];
				$t=time();
				sql ("UPDATE `users` SET
				`location`='".$pers["location"]."',`curstate`=2 
				WHERE `uid`=".UID."");
				$pers["curstate"]=2;
				}
		 }
		}
	}
	
/*
if ((@$_GET["goloc"] or isset($_GET["go_nature"]))  and $_TRVM)
 echo "<center class=puns>Вы не можете перемещатся у вас тяжелая травма.</center>";
else
 {

$t=time();
if (@$_GET["go_nature"] and $pers["tire"]>100) echo "<center class=hp>Вы слишком устали!</center>";
if (@$_GET["go_nature"] and $t>=$pers["waiter"] and $pers["cfight"]==0 and $pers["tire"]<101)
{	
 if (abs(10+($pers["sm3"]+$pers["s4"])*10) < $pers["weight_of_w"]) 
  echo "<center class=puns>Вы перегружены!</center>"; 
 else 
  {
	$y = $pers["y"];
	$x = $pers["x"];

	if ($_GET["go_nature"]=='down') $y+=1;
	if ($_GET["go_nature"]=='up') $y-=1;
	if ($_GET["go_nature"]=='left') $x-=1;
	if ($_GET["go_nature"]=='right') $x+=1;
	if ($_GET["go_nature"]=='lup') {$y-=1;$x-=1;}
	if ($_GET["go_nature"]=='ldown') {$y+=1;$x-=1;}
	if ($_GET["go_nature"]=='rup') {$y-=1;$x+=1;}
	if ($_GET["go_nature"]=='rdown') {$y+=1;$x+=1;}
	
	$x = intval($_GET["gox"]);
	$y = intval($_GET["goy"]);
	
	if ((($x-$pers["x"])*($x-$pers["x"])+($y-$pers["y"])*($y-$pers["y"]))>2)
	{
		$x = $pers["x"];
		$y = $pers["y"];
	}
	
	echo "<script>top.wX=".$pers["x"].";top.wY=".$pers["y"].";</script>";
	$pers["x"] = $x;
	$pers["y"] = $y;
	$place = sqla("SELECT type FROM nature WHERE x=".$x." and y=".$y."");
	if (isset($place["type"]))
	{
	$tr = 1.3;
	if ($place["type"]==0) $wait = 0;
	else
	{
	$wait = floor(($place["type"]*10+10)-($pers["sp8"]/8));
	if (WEATHER==2) $wait+=5;
	if (WEATHER==3) {$wait+=12;$tr+=2;}
	if (WEATHER==4) {$wait-=3;$tr+=1;}
	if (WEATHER==6) {$wait*=1.5;$tr+=0;}
	if (WEATHER==7) {$wait+=60;$tr+=0;}
	if (WEATHER==6) {$wait+=5;$tr+=0;}
	if (WEATHER==7)
	{
			$zid = sqlr("SELECT id FROM auras WHERE special=3 ORDER BY RAND()");
			$a = aura_on2($zid,$pers);
			$str =  '«<font class=red><B>'.$a["name"].'.</B> <i>'.$a["describe"].'</i></font>»';
			say_to_chat ("s","На вас обрушились огромные градины и вы получили травму:".$str.".","1",$pers["user"],$pers["location"],date("H:i:s"));
	}
	}
	set_vars("tire=tire + ".$tr.",x=".$x.",y=".$y.",waiter='".($wait+time())."',sp8=sp8+".(1/($pers["sp8"]+1))."",UID);
	$pers["tire"]+=1.3;
	$pers["waiter"]=$wait+time();
	}

	if ($pers["goloc"]<time() and isset($place["type"]))
	{
		set_vars("goloc=".(time()+900)."",$pers["uid"]);
		if(15-sqrt($pers["sp8"])>rand(1,1000)){
		$random = rand(1,3);
		switch ($random)
		{
			case 1:say_to_chat ("s","Неудача! Вы упали в яму и пытаетесь выбраться...","1",$pers["user"],$pers["location"],date("H:i:s"));
			$wait = abs(floor(($place["type"]*3+10)-($pers["sp8"]/7))*2)+60;
			sql ("UPDATE users SET waiter='".($wait+time())."' WHERE uid='".$pers["uid"]."'");break;
			case 2:
			$pers["chp"] = round($pers["chp"]*0.5);
			say_to_chat ("s","Неудача! На вас упало дерево... Здоровье <font class=hp>".$pers["chp"]."/".$pers["hp"]."</font>.","1",$pers["user"],$pers["location"],date("H:i:s"));
			sql ("UPDATE users SET chp='".$pers["chp"]."' WHERE uid='".$pers["uid"]."'");break;
			case 3:
			if (WEATHER==2 or WEATHER==3 or WEATHER==5)
			{
			$pers["cma"] = round($pers["cma"]/WEATHER);
			say_to_chat ("s","Неудача! В вас ударила молния и выжгла жизненную силу <font class=ma>".$pers["cma"]."/".$pers["ma"]."</font>.","1",$pers["user"],$pers["location"],date("H:i:s"));
			sql ("UPDATE users SET cma='".$pers["cma"]."' WHERE uid='".$pers["uid"]."'");break;
			}
		}
		}elseif (10+sqrt($pers["sp8"])>rand(1,1000))
		{
			$random = rand(1,2);
			switch ($random)
			{
			case 1:
			$rand = rand (1,8);
			say_to_chat ("s","Вы обнаружили <b>".$rand." LN</b> , затерянные каким-то торговцем...","1",$pers["user"],$pers["location"],date("H:i:s"));
			sql ("UPDATE users SET money='".($pers["money"]+$rand)."' WHERE uid='".$pers["uid"]."'");
			break;
			case 2:
			$zid = sqlr("SELECT id FROM auras WHERE special=3 ORDER BY RAND()");
			$a = aura_on2($zid,$pers);
			$str =  '«<font class=red><B>'.$a["name"].'.</B> <i>'.$a["describe"].'</i></font>»';
			say_to_chat ("s","Неудача! Вы угодили в капкан и получили травму:".$str.".","1",$pers["user"],$pers["location"],date("H:i:s"));
			break;
			}
		}
	}
}
}
}*/
?>