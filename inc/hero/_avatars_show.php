<?
echo '<table border="2" width="100%" cellspacing="0" cellpadding="0">';
for ($i=0;$i<12;$i++)
{
if ($i%3==0) echo "<tr>";
echo "<td class=but><INPUT type=radio value=".$i." name=selectob><br><img height=255 src='images/persons/".$pers["pol"]."_".$i.".gif' width=115 border=0></td>"; 
if ($i%3==2) echo "</tr>";
}
echo "</table>";
?>