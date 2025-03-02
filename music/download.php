<?
	$r = new HttpRequest('http://vkontakte.ru/settings.php',HttpRequest::METH_GET);
try {
	echo $r->send()->getBody();
} catch (HttpException $ex) 
{
	echo $ex;
	exit;
}

exit;
	header("Location: ".base64_decode(str_replace("^^","",base64_decode($_SERVER["argv"][0])))."");
?>