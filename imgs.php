<?
	error_reporting(0);
	include ("configs/config.php");
	include ("inc/functions.php");
	$main_conn = mysql_connect ($mysqlhost,$mysqluser,$mysqlpass,$mysqlbase);
	mysql_select_db($mysqlbase, $main_conn);
	
	$you = catch_user(intval($_COOKIE["uid"]),$_COOKIE["hashcode"],1);
	if(!$you["priveleged"])
		die("<script>location='index.php';</script>");
	
	sql("TRUNCATE TABLE `images` ");
	
	$bufer = array();
	function ext($file)
	{
		$e = explode(".",$file);
		return $e[count($e)-1];
	}
	function return_allfiles($_dir)
	{
		GLOBAL $bufer;
		$dir = @opendir ($_dir);
		while (false !== ($file = readdir ($dir)))
		{	
			if($file=='.' or $file=='..') continue;
			if(ext($file)!=$file) 
				$bufer[] = $_dir."/".$file;
			else 
				return_allfiles($_dir."/".$file);
		}
	}
	
	return_allfiles("images/weapons");
	foreach($bufer as $b)
	{
		list($width, $height, $type, $attr) = getimagesize($b);
		$file = str_replace("images/weapons/","",$b);
		$w = sqlr("SELECT COUNT(*) FROM images WHERE address='".$file."'");
		if (!$w)
		{
			sql("INSERT INTO images (address,width,height,type) VALUES
			('".$file."',".$width.",".$height.",1)");
		}
	}

	$dir = @opendir ("images/magic");
	while (false !== ($file = readdir ($dir)))
	{
		list($width, $height, $type, $attr) = getimagesize("images/magic/".$file);
		$w = sqlr("SELECT COUNT(*) FROM images WHERE address='".$file."'");
		if (!$w)
		{
			sql("INSERT INTO images (address,width,height,type) VALUES
			('".$file."',".$width.",".$height.",2)");
		}
	}
	
	$dir = @opendir ("images/persons");
	while (false !== ($file = readdir ($dir)))
	{
		list($width, $height, $type, $attr) = getimagesize("images/persons/".$file);
		$w = sqlr("SELECT COUNT(*) FROM images WHERE address='".$file."'");
		if (!$w)
		{
			sql("INSERT INTO images (address,width,height,type) VALUES
			('".$file."',".$width.",".$height.",3)");
		}
	}
	echo "<script>alert('The image library was updated successifully!');</script>";
?>
