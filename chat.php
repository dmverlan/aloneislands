<META Content="text/html; Charset=utf-8" Http-Equiv=Content-type>
<LINK href=ch_main.css rel=STYLESHEET type=text/css>

<script type="text/javascript" language="javascript" src="js/jquery.js"></script>
<SCRIPT type="text/javascript" language="javascript" src="js/tools/scrollto.js"></SCRIPT>

<body LeftMargin=0 TopMargin=0 RightMargin=0 MarginHeight=0 MarginWidth=0 background="images/DS/chat_bg.jpg" scroll="no">
<div style="position:absolute;width:30%;z-index:3;text-align:right;height:14px;top:0px;left:68%;display:block;"><div id="tbox" onmouseover="jQuery('#tbox').stop();jQuery('#tbox').animate({opacity:'1'},200);" onmouseout="jQuery('#tbox').stop();jQuery('#tbox').animate({opacity:'0.2'},200);"><a class=ActiveBc href="javascript:changeChatOrientation(1)" id=ch1>Общий</a> <a class=ActiveBc href="javascript:changeChatOrientation(2)" id=ch2>Торговый</a> <a class=ActiveBc href="javascript:changeChatOrientation(3)" id=ch3>Лог&nbsp;Боя</a></div></div>

<DIV id=menu class="menu"  style="display:none;"></DIV>

<script LANGUAGE="JavaScript" src="js/chat.js"></script>

<div id="chat" style="position:absolute;z-index:0;overflow-x:hidden;overflow-y:auto;display:block;width:100%;height:100%;"><div id=c1 style="display:none;"></div><div id=c2 style="display:none;"></div><div id=c3 style="display:none;"></div><div id=scrollitem style="display:block;"></div></div>


<SCRIPT LANGUAGE="JavaScript">
<?
error_reporting(0);
include ('inc/functions.php');
include ("configs/config.php");
$res = mysql_connect ($mysqlhost,$mysqluser,$mysqlpass,$mysqlbase);
mysql_select_db($mysqlbase, $res);

define("UID",intval($_COOKIE["uid"]));
$images = "images";
$pers = catch_user(UID);
if (!$pers) echo "\jQuery(\"#chat\").html(\"Error:: Authentification;\");";
echo "var nick = '".$pers["user"]."';";
if (substr_count($pers["rank"],"<molch>") or $pers["diler"]=='1' or $pers["priveleged"])
 echo "var molch=1;";
else
 echo "var molch=0;";
?>

changeChatOrientation(1);
</SCRIPT>

<SCRIPT LANGUAGE="JavaScript" src="js/ch_msg.js"></SCRIPT>
</body>