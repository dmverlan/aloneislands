<?
	$q = "123";
	$page = $_GET["page"]*100;
	$postdata = array("c[q]"=>$q,"c[section1]"=>"audio","offset"=>$page);
	$headers = array('USER-AGENT' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3', 'ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8', 'ACCEPT-LANGUAGE' => 'ru,en-us;q=0.7,en;q=0.3','ACCEPT-CHARSET' => 'windows-1251,utf-8;q=0.7,*;q=0.7','CONTENT-TYPE' => 'application/x-www-form-urlencoded; charset=UTF-8', 'X-REQUESTED-WITH' => 'XMLHttpRequest');
	$cookies = array(
	"remixlang"=>'0', "remixchk"=>'5', "remixmid"=>'38561612', "remixemail"=>'slaiderm%40gmail.com', "remixpass"=>'26eabcb0a75a1b7fc837ce6ef8beb011', "remixsid"=>'2dbae85ffc046cf56dbff770bbb5c064e4171213291cedea026394e0');
	//$r = new HttpRequest('http://alon/vkmusic/gsearch.php?section=audio', 	HttpRequest::METH_POST);
	$r = new HttpRequest(HttpRequest::METH_POST);
	$r->setUrl('http://87.118.86.206:8080');
	$r->setPostFields($postdata);
	$r->setHeaders($headers);
	$r->setCookies($cookies);
		
try {
	echo $r->send()->getBody();
} catch (HttpException $ex) 
{
	echo $ex;
	exit;
}
?>