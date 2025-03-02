<?php
include_once "common.php";
error_reporting(1);
function cutstr($s)
{
    if(strlen($s)<50)
        return $s;
    else
        return substr($s,0,47)."...";
}

function memory_transform($a)
{
	$r = '';
	if(floor($a/(1<<30)))
	{
		$r .= floor($a/(1<<30))." Гб. ";
		$a %= (1<<30);
	}
	if(floor($a/(1<<20)))
	{
		$r .= floor($a/(1<<20))." Мб. ";
		$a %= (1<<20);
	}
	if(floor($a/(1<<10)))
	{
		$r .= floor($a/(1<<10))." Кб. ";
		$a %= (1<<10);
	}
	else
		$r .= $a." Б. ";
	return $r;
}

$r=sql("SELECT * FROM `files`;");
if($r)
{
    echo "<LINK href=css/main.css rel=STYLESHEET type=text/css>";
	 echo "<body style=\"background-color: transparent;background-image: url('images/v1.jpg')\">";
    echo "<center><table width='100%' cellspacing='0' border='0' style=\"background-image: url('images/bg.png');border-color:#AAAAAA;border-style:solid;\">";
    echo "<tr><th>Скачки</th><th>Имя файла</th><th>Размер</th><th width=150 style='overflow:hidden;'>Автор</th><th width=150 style='overflow:hidden;'>Дата</th><th>Описание</th></tr>";
    $i=0;
    while($file=mysql_fetch_array($r))
    {
    	$ext = explode(".",$file["name"]);
    	$ext = strtolower($ext[count($ext)-1]);
    	$img = 'unknown';
		$addon = '';
    	if ($ext=='gif' || $ext=='jpg' || $ext=='jpeg' || $ext=='bmp' || $ext=='png')
		{
			$img = 'image';
			$addon = "onclick = \"top.$('#image').get(0).href='tmp/".$file["id"]."/".$file["name"]."';alert(123);top.$('#image').click();\" style='cursor:pointer;'";
		}
    	if ($ext=='rar') $img = 'rar';
    	if ($ext=='zip') $img = 'zip';
    	if ($ext=='tar') $img = 'tar';
    	if ($ext=='mp3')
		{
			$img = 'sound';
			$addon = "onclick = \"top.listen('files/files/".$file["id"].".vmk','music')\" style='cursor:pointer;'";
		}
    	if ($ext=='avi' || $ext=='mpg' || $ext=='mpeg') 
		{
			$img = 'video'; 
		}
    	if ($ext=='php') $img = 'source_php';
    	if ($ext=='txt') $img = 'text';
    	if ($ext=='pdf') $img = 'pdf';
    	$img = '<img src=images/'.$img.".png height=20 ".$addon.">";
    	$i++;
    	list($date,$time) = explode(" ",$file["time"]);
    	if ($i%2==0) $color = 'style="background-image: url(\'images/bg.png\')"'; else $color = '';
        echo "<tr ".$color.">";
		  echo "<td class=mfb width=10 style='overflow:hidden;'>".$file["dls"]."</td>";
        echo "<td nowrap>".$img."<a href='get.php?ind=".$file["id"]."' class=timef><img src=images/save.png height=16></a> <b class=mfb>".$file["name"]."</b></td>";
        echo "<td class=ma>".memory_transform($file["size"])."</td>";
		  if(!$file["author"]) $file["author"] = '<b class=green>Анонимус</b>';
		  if(strlen($file["author"])>15) $file["author"] = substr($file["author"],0,15)."...";
        echo "<td class=user width=150 style='overflow:hidden;'>".$file["author"]."</td>";
        echo "<td style='overflow:hidden;' width=150><b>".$date."</b> <span class=timef>".$time."</span></td>";
        echo "<td style='overflow:scroll;'>".cutstr($file["info"])."</td>";
        echo "</tr>";
    }
    echo "</table></center>";
	 echo "</body>";
}
else {
    echo "Ошибка! Невозможно отобразить список файлов!";
}
?>