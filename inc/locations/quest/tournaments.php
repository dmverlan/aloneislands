<?
	$t1 = sqla("SELECT * FROM quest WHERE id = ".TOUR1."");
	$t2 = sqla("SELECT * FROM quest WHERE id = ".TOUR2."");
	$t3 = sqla("SELECT * FROM quest WHERE id = ".TOUR3."");
	if($t1["finished"] and ($t1["time"]+T_DURATION)<tme()) //Начинаем турнир
	{
		sql("UPDATE quest SET finished=0,time=".tme().",type=0 WHERE id = ".TOUR1);
		say_to_chat ("a","На арене начался Турнир №1. Приглашаются все персонажи 5-10 уровня.",0,'','*',0);
	}
		if($t2["finished"] and ($t2["time"]+T_DURATION)<tme()) //Начинаем турнир
	{
		sql("UPDATE quest SET finished=0,time=".tme().",type=0 WHERE id = ".TOUR2);
		say_to_chat ("a","На арене начался Турнир №2. Приглашаются все персонажи 10-15 уровня.",0,'','*',0);
	}
		if($t3["finished"] and ($t3["time"]+T_DURATION)<tme()) //Начинаем турнир
	{
		sql("UPDATE quest SET finished=0,time=".tme().",type=0 WHERE id = ".TOUR3);
		say_to_chat ("a","На арене начался Турнир №3. Приглашаются все персонажи 15-50 уровня.",0,'','*',0);
	}
?>