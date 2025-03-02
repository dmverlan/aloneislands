<LINK href=main.css rel=STYLESHEET type=text/css>
<center>
<form action=game.php method=post>
Login:<input type=text name=user class=laar value=<? echo $_COOKIE["nick"];?>><br>
Pass:<input type=password name=pass class=laar value="********">
<input type=hidden name=passnmd class=laar value=<? echo $_COOKIE["hashcode"];?>><br>
<input type=submit class=laar value="¬ход">
<script>
var swc = screen.width-50;
document.write('<input type=hidden name=screen value='+swc+'>');
</script>
</form>
</center>
</body>