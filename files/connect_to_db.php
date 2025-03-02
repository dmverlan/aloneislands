<?php
include_once "db_user_pass.php";
error_reporting(0);
$dbcnx = @mysql_connect($dblocation, $dbuser, $dbpassw);
if(! $dbcnx) {
 exit;
}
@mysql_select_db($dbname, $dbcnx);
?>