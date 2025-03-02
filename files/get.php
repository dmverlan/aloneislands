<?php
include_once "common.php";
include_once "translit.php";
include_once "cleartmpdir.php";
error_reporting(0);


$uploaddir = './files/';
$tmpdir = 'tmp/';
$r=sql("SELECT * FROM `files` WHERE `id`='".$_GET["ind"]."';");
if($r)
{
    $file = mysql_fetch_row($r);
    $fname=translit($file[5]);
    if($fname=="") {
        echo "Ошибка! Возможно файл не существует!";
    } else {
        sql("DELETE FROM `files_tmp` WHERE `id` = '".$file[0]."' LIMIT 1;");
        sql("INSERT INTO `files_tmp` (`id`, `id_file`, `lastused`) VALUES (NULL, '".$file[0]."', '".time()."');");
        sql("UPDATE files SET dls=dls+1 WHERE `id` = '".$file[0]."' LIMIT 1;");
        mkdir($tmpdir.$file[0]."/");
        copy($uploaddir.$file[0].".vmk","./".$tmpdir.$file[0]."/".$fname);
        if($file[1]!="")
            $descr="<div>Описание: ".$file[1]."</div>";
        else
            $descr="";
        if($file[4]!="")
            $author="<div>Автор: ".$file[4]."</div>";
        else
            $author="";
        echo "<html>";
        echo "<LINK href=css/main.css rel=STYLESHEET type=text/css>";
        echo "<head>";
        echo "<META HTTP-EQUIV=Refresh Content='3; URL=\"".$tmpdir.$file[0]."/".$fname."\"'>";
        echo "</head>";
        echo "<body style=\"background-color: transparent;background-image: url('images/v1.jpg')\">";
        echo "<center style=\"background-image: url('images/bg.png');\"><div>Ждите 3 сек</div>Файл: ".$file[5]."</div>".$author."<div>Размер: ".$file[3]."(байт)</div><div>Загружено: ".$file[2]."</div>".$descr."<div>Прямая ссылка: <a href='".$tmpdir.$file[0]."/".$fname."' class=but>Скачать</a></div></div></center>";
        echo "</body>";
        //header("Location: ".$tmpdir.$file[0]."/".$file[5]);
    }
}
else {
    echo "Ошибка! Возможно файл не найден!";
}

cleartmpdir();
?>
