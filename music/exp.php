<?
	$q = trim($_POST["q"]);
	$page = $_GET["page"]*100;
	$postdata = array("c[q]"=>$q,"c[section]"=>"audio","offset"=>$page);
	$headers = array('USER_AGENT' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3', 'ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8', 'ACCEPT_LANGUAGE' => 'ru,en-us;q=0.7,en;q=0.3','ACCEPT_CHARSET' => 'windows-1251,utf-8;q=0.7,*;q=0.7','CONTENT_TYPE' => 'application/x-www-form-urlencoded; charset=UTF-8', 'X_REQUESTED_WITH' => 'XMLHttpRequest');
	$cookies = array(
	"remixlang"=>'0', "remixchk"=>'5', "remixmid"=>'38561612', "remixemail"=>'slaiderm%40gmail.com', "remixpass"=>'26eabcb0a75a1b7fc837ce6ef8beb011', "remixsid"=>'2dbae85ffc046cf56dbff770bbb5c064e4171213291cedea026394e0');
	//$r = new HttpRequest('http://alon/vkmusic/gsearch.php?section=audio', 	HttpRequest::METH_POST);
	$r = new HttpRequest('http://vkontakte.ru/gsearch.php?section=audio', 	HttpRequest::METH_POST);
	$r->setPostFields($postdata);
	$r->setHeaders($headers);
	$r->setCookies($cookies);
		
try {
	$text = $r->send()->getBody();
} catch (HttpException $ex) 
{
	echo $ex;
	exit;
}

$x = strpos($text,'<div id="searchSummary" class="summary">')+strlen('<div id="searchSummary" class="summary">');
list($finded) = explode("</div>",substr($text,$x,500));//
list($finded) = explode("аудиозаписей",$finded);
$fc = str_replace("Найдено ","",$finded);
if ($fc) $finded .= " файлов.";


$x = strpos($text,'<td id="results" class="results">');
$y = strpos($text,'<td id="filters" class="filters" style="width:164px;">');
$text = substr($text,$x,$y-$x);

//echo $text;

$i = 0;
$songs = array();
$text = explode("\n",$text);
foreach($text as $t)
{
	$x = strpos($t,'<img class="playimg" onclick="return operate');
	if ($x!==false)
	{
		$t = str_replace('<img class="playimg" onclick="return operate(','',$t);
		$b = explode(",",$t);
		$b[3] = str_replace("'",'',$b[3]);
		$urld = 'http://cs'.$b[1].'.vkontakte.ru/u'.$b[2].'/audio/'.$b[3].'.mp3';
		$songs[$i]["urld"] = $urld;
	}
	$x = strpos($t,'<b id="performer');
	if ($x!==false)
	{
		$b = explode(">",$t);
		list($artist) = explode("<",$b[1]);
		list($song) = explode("<",$b[3]);
		$songs[$i]["artist"] = $artist;
		$songs[$i]["song"] = $song;
	}
	$x = strpos($t,'<div id="lyrics');
	if ($x!==false)
	{
		$i++;
	}
}

$j = 0;
$repeats = ' ';
echo "<b class=ma>".$finded."</b>";
echo "<table class=but style='width:600px;' cellspacing=0>";
foreach ($songs as $s) 
{
	$x = strpos($repeats,'<'.$s["artist"].'|'.$s["song"].'>');
	if ($x>0 and $_POST["norepeat"]) continue;
	
	$repeats .= '<'.$s["artist"].'|'.$s["song"].'>';
	if ($j%2==0)
		echo "<tr>";
	else
		echo "<tr style='background-color:#FFFFFF'>";
		
	$que = explode(" ",$q);
	$s["artist"] = str_replace("_","",$s["artist"]);
	$s["song"] = str_replace("_","",$s["song"]);

	foreach($que as $qu)	
	{
	$s["artist"] = str_replace($qu,"<span class=blue>".$qu."</span>",$s["artist"]);
	$s["song"] = str_replace($qu,"<span class=blue>".$qu."</span>",$s["song"]);
	}
	echo "<td><b>".$s["artist"]."</b> - ".$s["song"]."</td><td><a href=download.php?".base64_encode("^^".base64_encode($s["urld"])."^^")." class=blocked>Скачать!</a></td><td id=".$j."><a href='javascript:void(0);' class=nt onmousedown=\"listen('".$s["urld"]."','".$j."')\">Прослушать!</a></td>";
	echo "</tr>";
	$j++;
}
echo "</table>";

if ($page>=100) 
	echo "<a href=index.php?q=".urlencode($q)."&page=".($page/100-1)." class=nt><<Предыдущая[".($page/100)."]</a>";
	echo "  ";
if ($fc>$page+100) 
	echo "<a href=index.php?q=".urlencode($q)."&page=".($page/100+1)." class=nt>Следующая[".($page/100+2)."]>></a>";

?>


