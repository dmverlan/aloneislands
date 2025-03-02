<?php
error_reporting(0);
include_once "protect.php";
include_once "connect_to_db.php";
function sql($s){
    return mysql_query($s);
}
?>
