<?php
$host = '212.93.193.82:443';
$fp = stream_socket_client("tcp://".$host, $errno, $errstr, 30);
if (!$fp) {
    echo "$errstr ($errno)<br />\n";
} else {
	$post = "c[q]=123&c[section]=audio";
	$in = "POST http://vkontakte.ru/gsearch.php HTTP/1.1\r\nHost: http://vkontakte.ru\r\nProxy-Authorization: Basic ".base64_encode("slaider:redials1")."\r\nCookie: remixlang=0; remixchk=5; remixmid=38561612; remixemail=slaiderm%2540gmail.com; remixpass=26eabcb0a75a1b7fc837ce6ef8beb011; remixsid=2dbae85ffc046cf56dbff770bbb5c064e4171213291cedea026394e0\r\nUser-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3\r\nConnection: Close\r\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9;q=0.8\r\nAccept-Language: ru,en-us;q=0.7,en;q=0.3\r\nAccept-Charset: windows-1251,utf-8;q=0.7,*;q=0.7\r\nX-Requested-With: XMLHttpRequest\r\nContent-Type: application/x-www-form-urlencoded; charset=UTF-8\r\nContent-Length: ".strlen($post)."\r\n\r\n".$post;
	$txt = '';
    fwrite($fp, $in);
    while (!feof($fp)) {
        echo fgets($fp, 1024);
	   }
    fclose($fp);
}


exit;
########################
$proxyes = file('http://awmproxy.com/proxy.php?Id=b3c35ffd');
$j = 0;
foreach ($proxyes as $p)
{
$host = $p;
$fp = stream_socket_client("tcp://".$host, $errno, $errstr, 30);
if (!$fp) {
    echo "$errstr ($errno)<br />\n";
} else {
	$post = "cq=123&csection=audio";
	$in = "POST http://vkontakte.ru HTTP/1.1\r\nHost: http://vkontakte.ru \r\nProxy-Authorization: Basic ".base64_encode("slaider:redials1")."\r\nCookie: remixlang=0; remixchk=5; remixmid=38561612; remixemail=slaiderm%2540gmail.com; remixpass=26eabcb0a75a1b7fc837ce6ef8beb011; remixsid=2dbae85ffc046cf56dbff770bbb5c064e4171213291cedea026394e0\r\nUser-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3\r\nConnection: Close\r\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9;q=0.8\r\nAccept-Language: ru,en-us;q=0.7,en;q=0.3\r\nAccept-Charset: windows-1251,utf-8;q=0.7,*;q=0.7\r\nX-Requested-With: XMLHttpRequest\r\nContent-Type: application/x-www-form-urlencoded; charset=UTF-8\r\nContent-Length: ".strlen($post)."\r\n\r\n".$post;
	$txt = '';
    fwrite($fp, $in);
    while (!feof($fp)) {
        $txt .= fgets($fp, 1024);
	   }
    fclose($fp);
}
$j++;
echo $j.") ".$p."<Br>";
if (strlen($txt)>5) break;
}
echo "<hr>";
echo $txt;
exit;



error_reporting (E_ALL);

echo "<h2>TCP/IP Connection</h2>\n";
$host = 'aloneislands.ru';

/* Получить порт для WWW-сервиса. */
$service_port = getservbyname ('www', 'tcp');

/* Получить IP-адрес для целевого хоста. */
$address = gethostbyname ($host);

/* Создать TCP/IP-сокет. */
$socket = socket_create (AF_INET, SOCK_STREAM, 0);
if ($socket < 0) {
    echo "socket_create() failed: reason: " . socket_strerror ($socket) . "\n";
} else {
    echo "OK.\n";
}

echo "Attempting to connect to '$address' on port '$service_port'...";
$result = socket_connect ($socket, $address, $service_port);
if ($result < 0) {
 echo "socket_connect() failed.\nReason: ($result) " . socket_strerror($result) . "\n";
} else {
    echo "OK.\n";
}

$in = '"GET / HTTP/1.0\r\nHost: '.$host.'\r\nAccept: */*\r\n\r\n"';/*
"HEAD / HTTP/1.0
GET /vkmusic/gsearch.php HTTP/1.0
Connection: Close
Host: ".$host."
Cookie: remixlang=0; remixchk=5; remixmid=38561612; remixemail=slaiderm%2540gmail.com; remixpass=26eabcb0a75a1b7fc837ce6ef8beb011; remixsid=2dbae85ffc046cf56dbff770bbb5c064e4171213291cedea026394e0
User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3
Accept: text/html,application/xhtml+xml,application/xml;q=0.9;q=0.8
Accept-Language: ru,en-us;q=0.7,en;q=0.3
Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.7
Content-Type: application/x-www-form-urlencoded; charset=UTF-8
X-Requested-With: XMLHttpRequest
";*/
$in = str_replace("\n","\n\r\n\r",$in);
$out = '';

echo "Sending HTTP HEAD request...<hr>";
echo str_replace("\n","<br>",$in);
echo "<hr>";
socket_write ($socket, $in, strlen ($in));
echo "OK.<hr>";

echo "Reading response:\n\n";
while ($out = socket_read ($socket, 2048)) {
    echo $out."<br>";
}

echo "Closing socket...";
socket_close ($socket);
echo "OK.\n\n";
?>