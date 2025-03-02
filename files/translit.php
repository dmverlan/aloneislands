<?php
function translit($p) {
    $s=$p;
    $s=ereg_replace("а|А", "a", $s);
    $s=ereg_replace("б|Б", "b", $s);
    $s=ereg_replace("в|В", "v", $s);
    $s=ereg_replace("г|Г", "g", $s);
    $s=ereg_replace("д|Д", "d", $s);
    $s=ereg_replace("е|Е", "e", $s);
    $s=ereg_replace("ё|Ё", "jo", $s);
    $s=ereg_replace("ж|Ж", "zh", $s);
    $s=ereg_replace("з|З", "z", $s);
    $s=ereg_replace("и|И", "i", $s);
    $s=ereg_replace("й|Й", "j", $s);
    $s=ereg_replace("к|К", "k", $s);
    $s=ereg_replace("л|Л", "l", $s);
    $s=ereg_replace("м|М", "m", $s);
    $s=ereg_replace("н|Н", "n", $s);
    $s=ereg_replace("о|О", "o", $s);
    $s=ereg_replace("п|П", "p", $s);
    $s=ereg_replace("р|Р", "r", $s);
    $s=ereg_replace("с|С", "s", $s);
    $s=ereg_replace("т|Т", "t", $s);
    $s=ereg_replace("у|У", "u", $s);
    $s=ereg_replace("ф|Ф", "f", $s);
    $s=ereg_replace("х|Х", "h", $s);
    $s=ereg_replace("ц|Ц", "c", $s);
    $s=ereg_replace("ч|Ч", "ch", $s);
    $s=ereg_replace("ш|Ш", "sh", $s);
    $s=ereg_replace("щ|Щ", "sh", $s);
    $s=ereg_replace("ъ|Ъ", "", $s);
    $s=ereg_replace("ы|Ы", "i", $s);
    $s=ereg_replace("ь|Ь", "", $s);
    $s=ereg_replace("э|Э", "e", $s);
    $s=ereg_replace("ю|Ю", "y", $s);
    $s=ereg_replace("я|Я", "ja", $s);
    $s=ereg_replace(" ", "_", $s);
    return $s;
}
?>