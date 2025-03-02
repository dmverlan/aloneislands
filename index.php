<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<link rel="stylesheet" href="main.css" type="text/css" media="screen">
<link rel="stylesheet" href="css/squeezebox.css" type="text/css" media="screen">

<style type="text/css">
   .indexFont {
	   color: #4E312D;
	   font-family: Arial;
	   font-size: 11px;
   }
   .loginBox{
   		border-width: 1px;
		border-color: #717171;
		border-style: solid;
   		font-family: Tahoma;
   		color: #4E312D;
	  	font-size: 11px;
	  	background-color: transparent;
		background-image: url('images/emp.gif');
	  	cursor:pointer;
   }
</style>

<link rel='shortcut icon' href='images/icon.ico'>
<link rel='shortcut icon' href='images/pict.png'>
<title>Aloneislands: бесплатная ролевая браузерная онлайн (online) игра MMORPG free games on-line</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name=description content='Фэнтези онлайн игра с элементами стратегии, квестовой частью и возможностью самим участвовать в создании нового мира , присутвует механика и магия. Возможность выбора расы!'>
<meta name='keywords' content='игра, играть, рпг, онлайн, online, fantasy, фэнтези, квест, алхимия, мир, земли, стратегия, магия, стихия, арена, бои, клан, семья, братство, сражение, тьма, свет, хаос, сумерки, удар, меч, нож, топор, дубина, щит, броня, доспех, шлем, перчатки, амулет, кулон, кольцо, пояс, зелья, карта, замки, шахты, лавка, таверна, артефакты, раритеты, свитки, свиток, школа, од, рыцарь, маг, друид, гоблин, орк, призрак, эльф, отдых, развлечение, чат, общение, знакомства, форум, власть, золото, серебро, телепорт, банк, рынок, мастерская, тактика, больница, храм, бог, демон, защита, сила, удача, ловкость, война, орден, аптека, почта, реторта, ступка, пестик, дистиллятор , механоид , оборотень , осрова , alone , islands , aloneislands , сила , реакция , воля , интелект , оружие , пистолет , балиста'>

<script type="text/javascript" language="javascript" src="js/mootools.js"></script>
<script type="text/javascript" language="javascript" src="js/SqueezeBox.js"></script>
<script type="text/javascript" language="javascript" src="js/newmain.js"></script>
<script type="text/javascript" language="javascript" src="js/cookie.js"></script>
<!-- script type="text/javascript" language="javascript" src="js/snowstorm.js"></script -->
<SCRIPT type="text/javascript" src="js/tools/sm2.js"></SCRIPT>

</head>

<script>
window.addEvent('domready', function() {
SqueezeBox.initialize({
size: {x: 350, y: 400},
ajaxOptions: {
method: 'get'
}
});


$$('a.boxed').each(function(el) {
el.addEvent('click', function(e) {
new Event(e).stop();
SqueezeBox.fromElement(el);
});
});

$$('.panel-toggler').each(function(el) {
var target = el.getLast().setStyle('display', 'none');
el.getFirst().addEvent('click', function() {
target.style.display = (target.style.display == 'none') ? '' : 'none';
});
});
});

<?
	echo  "index('".$_GET['error']."');";
?>

</script>


<!-- Yandex.Metrika -->
<script src="//mc.yandex.ru/resource/watch.js" type="text/javascript"></script>
<script type="text/javascript">
try { var yaCounter184038 = new Ya.Metrika(184038); } catch(e){}
</script>
<noscript><div style="position: absolute;"><img src="//mc.yandex.ru/watch/184038" alt="" /></div></noscript>
<!-- Yandex.Metrika -->

</html>