<?

	$qWitch = sqla("SELECT * FROM quest WHERE id = ".Q_WITCH."");
	if (!$qWitch)
	{
		sql("INSERT INTO quest (id,finshed)VALUES(".Q_WITCH.",1)");
	}
	if ($qWitch["finished"] && $qWitch["time"]<tme())
	{
		$randWp   = sqlr("SELECT name FROM wp WHERE price<300 and dprice=0 and (type='herbal' or type='potion') ORDER BY RAND() LIMIT 0,1;");
		$randCell = sqla("SELECT x,y FROM nature WHERE (x*x+y*y)<1024 ORDER BY RAND() LIMIT 0,1");
		if(signum($randCell["x"])==0 && signum($randCell["y"])==-1)$go_n ='север';
		if(signum($randCell["x"])==0 && signum($randCell["y"])==1)$go_n ='юг';
		if(signum($randCell["x"])==-1 && signum($randCell["y"])==0)$go_n ='запад';
		if(signum($randCell["x"])==1 && signum($randCell["y"])==0)$go_n ='восток';
		if(signum($randCell["x"])==-1 && signum($randCell["y"])==-1)$go_n ='северо-запад';
		if(signum($randCell["x"])==-1 && signum($randCell["y"])==1)$go_n ='юго-запад';
		if(signum($randCell["x"])==1 && signum($randCell["y"])==-1)$go_n ='северо-восток';
		if(signum($randCell["x"])==1 && signum($randCell["y"])==1)$go_n ='юго-восток';
		say_to_chat ("a","Ведьма Алиса снова просит обитателей Метрополиса помочь ей в готовке нового термоядерного зелья. На этот раз ей нужно <b>«".$randWp."»</b>. И как обычно она благодарит за помощь щедрыми подарками!",0,'','*',0);
		say_to_chat ("a","Хитрая старушка забрела не так далеко от города, но её нужно поискать. Она считает, что находится на ".$go_n."е(Неподалеку от локации [".rand($randCell["x"]-3,$randCell["x"]+3).":".rand($randCell["y"]-3,$randCell["y"]+3)."]) от входа в метрополис,и ожидать вас она будет ровно час.",0,'','*',0);
		$qWitch["sParam"] = $randWp;
		$qWitch["lParam"] = $randCell["x"];
		$qWitch["zParam"] = $randCell["y"];
		sql(
		"UPDATE quest SET 
		sParam = '".$qWitch["sParam"]."',
		lParam = '".$qWitch["lParam"]."',
		zParam = '".$qWitch["zParam"]."',
		finished = 0,
		time = ".(tme()+3600)."
		WHERE id = ".Q_WITCH.""
		);
	}
	if (@$_GET["gW"] && !$qWitch["finished"] && $qWitch["time"]>tme())
	{
		if($pers["x"] == $qWitch["lParam"] &&
			$pers["y"] == $qWitch["zParam"])
			{
			$yourWp = sqla("SELECT * FROM wp WHERE uidp=".UID." and weared=0 and name='".$qWitch["sParam"]."'");
				if ($yourWp)
				{
					if ($pers["pol"]=='female') 
					{
						$male='а'; 
						$la = "ла";
					}
					else 
					{
						$male='';
						$la = "";
					}
					sql("UPDATE wp SET durability=0 WHERE id=".$yourWp["id"]."");
					$r = rand(2,4);
					$exp = 1000 + $pers["level"]*1000;
					$ln = $yourWp["price"]*2;
					say_to_chat ("a","Ведьма Алиса в восторге от великодушия <b>".$pers["user"]."</b>, ведь он".$male." помог".$la." ей в осуществлении её нового плана! Она щедро дарит <b>".$pers["user"]."</b> ".$exp." опыта и сундук с сокровищами.",0,'','*',0);
					say_to_chat ("a","Ведьма Алиса дарит вам ".$exp." опыта, ".$r." пергамента, <b>".$ln." LN</b> , 1 обнуление и накладывает на вас «Благословение Небес»",1,$pers["user"],'*',0);
					sql(
					"UPDATE users SET
					exp = exp + ".$exp.",
					coins = coins + ".$r.",
					zeroing = zeroing + 1,
					questWitch = questWitch + 1
					WHERE uid=".UID.""
					);
					$a["image"] = 35;
					$a["params"] = '';
					$a["esttime"] = 3600;
					$a["name"] = 'Благословение Небес';
					$a["special"] = 16;
					light_aura_on($a,$pers["uid"]);
					sql(
					"UPDATE quest SET 
					finished = 1,
					time = time+82800
					WHERE id = ".Q_WITCH.""
					);
				}
			}
	}
	else
	if (!$qWitch["finished"] && $qWitch["time"]>tme())
	{
		if($pers["x"] == $qWitch["lParam"] &&
			$pers["y"] == $qWitch["zParam"])
			{
				$_RETURN .= '<center class=but>Вы нашли Ведьму Алису!</center><i class=user>Она всё ещё нуждается в <b>«'.$qWitch["sParam"].'»</b></i>';
				$yourWp = sqla("SELECT * FROM wp WHERE uidp=".UID." and weared=0 and name='".$qWitch["sParam"]."'");
				if ($yourWp)
				{
					$vesh = $yourWp;
					include("./inc/inc/weapon2.php");
					$_RETURN .= "<div class=but2><script>".$text."</script></div><input type=button class=login value='Отдать' onclick=\"location = 'main.php?gW=".md5(tme())."';\">";
				}
			}
	}
	if (!$qWitch["finished"] && $qWitch["time"]<=tme())
	{
		say_to_chat ("a","Никто не смог помочь Ведьме Алисе... Огрызнувшись на нерадивых жителей, она ушла спать...",0,'','*',0);
		sql(
		"UPDATE quest SET 
		finished = 1,
		time = ".(tme()+82800)."
		WHERE id = ".Q_WITCH.""
		);
	}
	
?>