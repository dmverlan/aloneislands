<?
	if($pers["f_turn"]==1)
	{
		$bid = sqlr("SELECT id FROM bots WHERE level=".($pers["level"])." and special=1;");
		$v = sqla("SELECT id,name FROM wp WHERE uidp=".(-1*$bid)." ORDER BY RAND()");
		$id = insert_wp_new($pers["uid"],"id=".$v["id"],$pers["user"]);
		sql("UPDATE wp SET where_buy=2,timeout=".(tme()+14*84600)." WHERE id=".$id["id"]."");
		say_to_chat('s',"<center class=return_win>Дедушка не ожидал встретить на своем пути столь сильного воина, и в знак почтения он дарит вам <b>«".$v["name"]."»</b></center>",1,$pers["user"],'*',0);
	}else
	{
		$bid = sqlr("SELECT id FROM bots WHERE level=".($pers["level"])." and special=1;");
		$v = sqla("SELECT id,name FROM weapons WHERE id='1095'");
		$id = insert_wp($v["id"],$pers["uid"]);
		sql("UPDATE wp SET where_buy=2,timeout=".(tme()+7*84600)." WHERE id=".$id."");
		say_to_chat('s',"<center class=return_win>Дедушка улыбнулся и сказал чтобы вы немного подросли, пожелал вам всего хорошего в этом году и подарил <b>«".$v["name"]."»</b></center>",1,$pers["user"],'*',0);
	} 
?>