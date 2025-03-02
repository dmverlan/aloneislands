<?php
include_once "rmdirr.php";
function cleartmpdir()
{
    $t=time()-36000;
    $r=sql("SELECT * FROM `files_tmp` WHERE `lastused` < '".$t."';");
    if($r)
    {
        while($a=mysql_fetch_row($r))
        {
            rmdirr(".tmp/".$a[1]);
        }
    }
    sql("DELETE FROM `files_tmp` WHERE `lastused` < '".$t."';");
}
?>