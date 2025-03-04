<META HTTP-EQUIV="Page-Enter" CONTENT="BlendTrans(Duration=0.5)">
<LINK href="css/main.css" rel=STYLESHEET type=text/css>
<body style="background-color:transparent;">
<center>
<br>
<br>
<table border="0" width="95%" cellspacing="0" cellpadding="0" style="border-bottom-style: solid; border-top-style: solid; border-top-width: 3px; border-bottom-width: 2px; border-color:#777799">
    <tr>
        <td align="center" valign="bottom"></td>
        <td align="center"><a href="ch.php" class="bga">НАЗАД</a></td>
        <td align="center" valign="bottom"></td>
    </tr>
    <tr>
        <td align="center" class="bnick" height="21">&nbsp;</td>
        <td align="center" class="dark" height="21">
            <b class="user"><?= htmlspecialchars($weather['name'], ENT_QUOTES, 'UTF-8') ?></b>[<?= htmlspecialchars($weatherChangeTime, ENT_QUOTES, 'UTF-8') ?>]<br>
            <img border="0" src="images/weather/seasons/<?= (int)$seasonData['id'] ?>.gif" width="100" height="100">
            <img border="0" src="images/weather/<?= (int)$weatherId ?>.gif" width="100" height="100"><br>
            <?= str_replace(';', '<br>', htmlspecialchars($weather['describe'], ENT_QUOTES, 'UTF-8')) ?>
        </td>
        <td align="center" class="bnick" height="21">&nbsp;</td>
    </tr>
    <tr>
        <td align="center" bgcolor="#AAAAAA" height="4"></td>
        <td align="center" bgcolor="#AAAAAA" height="4"></td>
        <td align="center" bgcolor="#AAAAAA" height="4"></td>
    </tr>
    <tr>
        <td align="center" class="bnick" valign="top"></td>
        <td align="center" class="about">
            <b class="user"><?= htmlspecialchars($seasonData['name'], ENT_QUOTES, 'UTF-8') ?></b>[<?= htmlspecialchars($seasonData['changes'], ENT_QUOTES, 'UTF-8') ?>]<br>
        </td>
        <td align="center" class="bnick" valign="top"></td>
    </tr>
</table>
</center>
<?= $playScript ?>
<script>
var interv = setTimeout("location = 'ch.php?rand=" + Math.random() + "'", <?= WEATHER_REDIRECT_DELAY * 1000 ?>);
</script>
</body>