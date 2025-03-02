<center><font class=user>Тюрьма метрополиса</font><hr><img border="0" src="images/locations/prison.jpg"></center>
<center>
<font class=hp>
<?
if ($prison[0]>time()) echo"<hr>Причина :".$prison[1]."<br>До выхода ".tp($prison[0]-time());
else
echo "Вы можете выйти.";
?>
</font>
</center>