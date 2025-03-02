<center>
<div style="width:500px" class=but>
<div id=report align=center><a href="javascript:report();" class=bg>Написать отзыв</a></div>
<div id=mainpers></div>
<script>
var d=document;
var $ = function(id){
	return d.getElementById(id);
};
var rep_text='';

function report()
{
	$('report').innerHTML = '<form method=post><textarea name=report class=return_win rows=5></textarea><hr><input type=submit class=login value="Отправить"></form>';
}

function pr_r(WHO,LVL,SIGN,DATE,text)
{
if (SIGN!= 'none') SIGN = '<img src=images/signs/'+SIGN+'.gif>'; else SIGN='';
	rep_text += '<tr><td class=login>'+SIGN+' <b>'+WHO+'</b>[<font class=lvl>'+LVL+'</font>] <img src="images/i.gif" onclick="window.open(\'info.php?p='+WHO+'\',\'\',\'width=800,height=600,left=10,top=10,toolbar=no,scrollbars=yes,resizable=yes,status=no\');" style="cursor:pointer"> <font class=time>'+DATE+'</font></td></tr><tr><td>'+text+'</td></tr>';
	return true;
}
</script>
<?
#Добавить отзыв:
if (@$_POST["report"])
{
	sql("INSERT INTO 
	`reports_for_clans` ( `csign` , `lvl` , `sign` , `date` , `who` , `text` ) 
	VALUES ('".$pers["sign"]."', '".$pers["level"]."', '".$pers["sign"]."', '".time()."'
	, '".$pers["user"]."', '".str_replace("'","",$_POST["report"])."');");
}
	# Отзывы
echo "<script>";
if (empty($_GET["all_reports"]))
$rep = sql("SELECT * FROM reports_for_clans WHERE csign='".$pers["sign"]."' ORDER BY date DESC LIMIT 7;");
else
$rep = sql("SELECT * FROM reports_for_clans WHERE csign='".$pers["sign"]."' ORDER BY date DESC");

echo "rep_text +='<table border=1 width=100% cellspacing=3 cellpadding=2 bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF><tr><td class=brdr>ОТЗЫВЫ:<a href=\"main.php?clan=wall&action=addon&gopers=clan&all_reports=1\">(ВСЕ)</a></td></tr>';";
$k = 0;
while($r = mysql_fetch_array ($rep))
{
	$k++;
	echo "pr_r('".$r["who"]."',".$r["lvl"].",'".$r["sign"]."','".date("d.m.Y H:i",$r["date"])."','".str_replace('
','<br>',$r["text"])."');";
}
if ($k==0) echo "rep_text +='<tr><td class=time>Здесь пока никто не написал</td></tr>';";
echo "rep_text +='</table>';";
echo "$('mainpers').innerHTML += rep_text;";
echo "</script>";
?>
</div>
</center>