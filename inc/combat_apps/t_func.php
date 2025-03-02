<?
function start_t1($a=20,$STEP=1,$tour=1,$quest_id=1)
{
		GLOBAL $t1,$pers;
		$users = sql("SELECT * FROM users WHERE tour=".$tour.";");
		if($tour==1)
			$bots = sql("SELECT * FROM bots WHERE level=10 ORDER BY RAND() LIMIT 0,20;");
		if($tour==2)
			$bots = sql("SELECT * FROM bots WHERE level=15 ORDER BY RAND() LIMIT 0,20;");
		if($tour==3)
			$bots = sql("SELECT * FROM bots WHERE level=22 ORDER BY RAND() LIMIT 0,20;");
		$us1 = Array();
		$r = Array();
		$u_counter = 0;
		for($i=0;$i<$a;$i++)
		{
			$u1 = $u;
			$u = mysql_fetch_array($users,MYSQL_ASSOC);
			if(!$u)
			{
				$u = mysql_fetch_array($bots,MYSQL_ASSOC);
				if(!$u)
					$u = $u1;
				$us1[] = "bot=".$u["id"]."";
				$r[] = $u["rank_i"];
			}
			else
			{
				$us1[] = $u["user"];
				$r[] = $u["rank_i"];
				$u_counter++;
			}
		}
		$c1 = count($us1);
		for ($i=0;$i<$c1;$i++)
		 for($j=0;$j<$i;$j++)
		 if ($r[$j]<$r[$j+1])
		 {
			$tmp = $r[$j];
			$r[$j] = $r[$j+1];
			$r[$j+1] = $tmp;
			$tmp = $us1[$j];
			$us1[$j] = $us1[$j+1];
			$us1[$j+1] = $tmp;
		 }
		$p1 = '';
		$p2 = '';
		for ($i=0;$i<$c1;$i++)
			if ($i%2==0)  		$p1.=$us1[$i]."|";
			else				$p2.=$us1[$i]."|";
		if(!$u_counter)
			sql("UPDATE quest SET finished=1,time=".tme()." WHERE id=".$quest_id);
		return begin_fight ($p1,$p2,"Турнир №1. Этап №".$STEP.".","80","900","1",0,TOUR1,1);
}

?>