<?	
	error_reporting(0);
	$i=0;
	$im = imagecreatefromjpeg("images/map_new.jpg");
	for ($x=0;$x<64;$x++)
	for ($y=0;$y<45;$y++){
	if (!file_exists("images/map/".($x)."_".($y).".jpg"))
	{
	$id = imagecreatetruecolor(80,80);
	imagecopy($id,$im,0,0,80*$x,$y*80,80,80);
	ImageJpeg($id,"images/map/".($x)."_".($y).".jpg");
	//if ($x==22 and $y==26) break;
	}
	$i++;
	}
	echo $i;
?>

