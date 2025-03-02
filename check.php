<?
header("Content-type: image/gif");
error_reporting(0);
putenv('GDFONTPATH=' . realpath('fonts/'));
function uncrypt($value,$key)
{
	$a=0;
	for($i=0;$i<strlen($value);$i++)
	$a += (ord($value[$i])<<(($i+23)>>1)<<1)^($key^9+$i);
	$a %= 10000;
	$a = abs($a);
	if ($a<1000) $a+=2343;
	return $a;
}
if (empty($_GET["a1"]))$_GET["a1"]='123';
if (empty($_GET["a2"]))$_GET["a2"]=123;
$code = uncrypt($_GET["a1"],$_GET["a2"]);

$image=imagecreatetruecolor(180,135);
$white = imagecolorallocate($image, 255, 255, 255);
imagefilledrectangle($image, 0, 0, 180, 135, $white);

$color=ImageColorAllocate($image,rand(230,255),rand(230,255),rand(230,255));
$grey = imagecolorallocate($image, 100, 100, 100);

$fonts = Array("Gigi","Century","Corbel","Forte","Papyrus");

$color=ImageColorAllocate($image,rand(100,255),rand(100,255),rand(100,255));
for ($x=0;$x<180;$x++)
 for ($y=0;$y<135;$y++)
  imagesetpixel($image,$x,$y,$color);
  
if(isset($code)){
$grey = $color - 0x090909;
$image = put_number($image,rand(0,9),1);
$image = put_number($image,rand(0,9),2);
$image = put_number($image,rand(0,9),3);
$image = put_number($image,rand(0,9),4);
}

if(isset($code)){
$grey = imagecolorallocate($image, 100, 100, 100);
$color = $color + 0x111111;
$image = put_number($image,substr($code,0,1),1);
$image = put_number($image,substr($code,1,1),2);
$image = put_number($image,substr($code,2,1),3);
$image = put_number($image,substr($code,3,1),4);
}

function put_number($image,$number,$left)
{
	GLOBAL $fonts,$color,$grey;
	$font = $fonts[rand(0,count($fonts)-1)].".ttf";
	$black = $color;
	$rnd = rand(1,60)+40;
	$rnd2 = rand(-30,30);
	$rnd3 = rand(30,40);
	imagettftext($image, $rnd3, $rnd2, ($left-1)*40+1+5, $rnd+1, $grey, $font,$number);
	imagettftext($image, $rnd3, $rnd2, ($left-1)*40+5, $rnd, $black, $font, $number);
	return $image;
}

imagealphablending ( $image  ,6);
ImageGIF($image);
?>