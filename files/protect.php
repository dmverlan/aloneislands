<?php
if(@$protected!==1)
{
function filter($v)
{
    return str_replace("'","",str_replace("\\","",htmlspecialchars(urldecode($v))));
}
}
foreach ($_POST as $key=>$value) $_POST[$key] = filter($value);
foreach ($_GET  as $key=>$value) $_GET[$key]  = filter($value);
foreach ($_COOKIE  as $key=>$value) $_COOKIE[$key]  = filter($value);

$protected=1;
?>