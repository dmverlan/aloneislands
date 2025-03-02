<?php
include_once "common.php";
include_once "cleartmpdir.php";
error_reporting(0);

$uploaddir = './files/';
$file=$_FILES["file"];
if($file["name"]==".htaccess")
{
    echo "Такие файлы запрещены!";
}
else
{
    if(! $file['error']) {
        if(sql("INSERT INTO `files` (`id` ,`info` ,`time` ,`size` ,`author` ,`name`) VALUES (NULL , '".$_POST["info"]."',CURRENT_TIMESTAMP , '".$file['size']."', '".$_POST["author"]."', '".filter($file["name"])."');"))
        {
            $ind=mysql_insert_id();
            $uploadfile = $uploaddir . $ind.".vmk";
            if (move_uploaded_file($file['tmp_name'], $uploadfile)) {
                echo "<head><script type='text/javascript'>function selectText(){var oTextBox = document.getElementById('someTextField');oTextBox.focus();oTextBox.select();}</script></head><body>";
                echo "Загружен ". filter($file["name"])." (".$file['size']." байт)<br>";
                echo "Ссылка на загрузку:<br><textarea onfocus='this.select()' rows='1' class=but style=\"height:22px;width:300px;\">http://pingvin.nnov.ru/files/get.php?ind=". $ind."</textarea>";
            } else {
                sql("DELETE FROM `files` WHERE `id` = '".$ind."' LIMIT 1;");
                echo "Ошибка при загрузке ". filter($file["name"])." (".$file['size']." байт)</body>";
            }
        }
        else {
            echo "Ошибка при загрузке ". filter($file["name"])." (".$file['size']." байт)";
        }
    }
    else {
        echo "Ошибка при загрузке ". filter($file["name"]).". Код ошибки: ".$file['error'];
    }
}
cleartmpdir();
?>
