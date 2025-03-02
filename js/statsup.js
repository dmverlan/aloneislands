function getByName(nn)
{
	var a = d.getElementsByName(nn);
	return a[0];
}
var sila = 1;
var lovk = 1;
var udacha = 1;
var zdorov = 1;
var znanya = 1;
var power = 1;
var ups = 0;
var ssila = 1;
var slovk = 1;
var sudacha = 1;
var szdorov = 1;
var sznanya = 1;
var spower = 1;
var nym = 0;
var nmym = 0;
var nsym = 0;
var LEVEL;

function pluses(stat,onclick)
{
	return "<table border=0 cellspacing=1 cellspadding=1 width=60><tr><td width=30 class=Lstat>"+stat+"</td><td width=15><img src='images/DS/plus.png' onclick='"+onclick+"(1)' style='cursor:pointer;'></td><td width=15><img src='images/DS/minus.png' style='cursor:pointer' onclick='"+onclick+"(-1)'></td></tr></table>";
}

function start (ss,sl,su,szd,szn,sp,sup,level)
{
LEVEL = level;
sila = ss;
lovk = sl;
udacha = su;
zdorov = szd;
znanya = szn;
power = sp;
ups = sup;
ssila = ss;
slovk = sl;
sudacha = su;
szdorov = szd;
sznanya = szn;
spower = sp;
if (ups>0){
d.getElementById('sila').innerHTML = pluses(ss,'stups');
d.getElementById('lovk').innerHTML = pluses(sl,'stupl');
d.getElementById('udacha').innerHTML = pluses(su,'stupu');
d.getElementById('zdorov').innerHTML = pluses(szd,'stupzd');
if (level>=5)d.getElementById('znanya').innerHTML = pluses(szn,'stupzn');
else d.getElementById('znanya').innerHTML = szn;
d.getElementById('power').innerHTML = pluses(sp,'stupp');
}else
{
d.getElementById('sila').innerHTML = ss;
d.getElementById('lovk').innerHTML = sl;
d.getElementById('udacha').innerHTML = su;
d.getElementById('zdorov').innerHTML = szd;
d.getElementById('znanya').innerHTML = szn;
d.getElementById('power').innerHTML = sp;
}
if (ups > 0) d.getElementById('ups').innerHTML ='<b class=Lfree style="text-align:left;"><img src="images/DS/attention.png" /> &nbsp;Повышений: '+ups+'</b>';
else d.getElementById('ups').innerHTML = '';
}

function stups (up) {
if ((up==-1 && sila > ssila) | (up==1))
if (up==-1 | ups>0) {
sila += up;
d.getElementById('sila').innerHTML = pluses(sila,'stups');
ups -= up;
d.getElementById('ups').innerHTML = '<b class=Lfree style="text-align:left;"><img src="images/DS/attention.png" /> &nbsp;Повышений: '+ups+'</b>';
}}
function stupl (up) {
if ((up==-1 && lovk > slovk) | (up==1))
if (up==-1 | ups>0) {
lovk += up;
d.getElementById('lovk').innerHTML = pluses(lovk,'stupl');
ups -= up;
d.getElementById('ups').innerHTML = '<b class=Lfree style="text-align:left;"><img src="images/DS/attention.png" /> &nbsp;Повышений: '+ups+'</b>';
}}
function stupu (up) {
if ((up==-1 && udacha > sudacha) | (up==1))
if (up==-1 | ups>0) {
udacha += up;
d.getElementById('udacha').innerHTML = pluses(udacha,'stupu');
ups -= up;
d.getElementById('ups').innerHTML = '<b class=Lfree style="text-align:left;"><img src="images/DS/attention.png" /> &nbsp;Повышений: '+ups+'</b>';
}}
function stupzd (up) {
if ((up==-1 && zdorov > szdorov) | (up==1))
if (up==-1 | ups>0) {
zdorov += up;
d.getElementById('zdorov').innerHTML = pluses(zdorov,'stupzd');
ups -= up;
d.getElementById('ups').innerHTML = '<b class=Lfree style="text-align:left;"><img src="images/DS/attention.png" /> &nbsp;Повышений: '+ups+'</b>';
}}
function stupzn (up) {
if ((up==-1 && znanya > sznanya ) | (up==1))
if (up==-1 | ups>0) {
znanya += up;
d.getElementById('znanya').innerHTML = pluses(znanya,'stupzn');
ups -= up;
d.getElementById('ups').innerHTML = '<b class=Lfree style="text-align:left;"><img src="images/DS/attention.png" /> &nbsp;Повышений: '+ups+'</b>';
}}
function stupp (up) {
if ((up==-1 && power > spower) | (up==1))
if (up==-1 | ups>0) {
power += up;
d.getElementById('power').innerHTML = pluses(power,'stupp');
ups -= up;
d.getElementById('ups').innerHTML = '<b class=Lfree style="text-align:left;"><img src="images/DS/attention.png" /> &nbsp;Повышений: '+ups+'</b>';
}}


function save () {
d.getElementById('SAVEstats').innerHTML = '<form method=post action="services/SAVEstats.php" target="returner" name=stats>'+'<input type=hidden name=stats value=1><input type=hidden name=s1 value='+sila+'>'+'<input type=hidden name=s2 value='+lovk+'>'+'<input type=hidden name=s3 value='+udacha+'>'+'<input type=hidden name=s4 value='+zdorov+'>'+'<input type=hidden name=s5 value='+znanya+'>'+'<input type=hidden name=s6 value='+power+'>'+'<input type=hidden name=ups value='+ups+'>'+'</form>';
top.frames['main_top'].document.stats.submit();
top.set_return_win('SAVEstats');
start (sila,lovk,udacha,zdorov,znanya,power,ups,LEVEL);
}


function s_y() 
{
var max_m = (100+level*70);
var b=0;
var s=0;
var bs='b';
var bf='bs';
nym = document.ym.nbs.value;
nsym = document.ym.nss.value;
for (b=1;b<15;b++) {
bs = 'b' + b;
bf = 'bs' + b;
d.getElementById(bs).innerHTML = '['+getByName(bf).value+'/30]';
if (nym>0) d.getElementById(bs).innerHTML+='<img src=\'images/DS/plus.png\' onclick="um_up(\'b\','+b+')" style="cursor:pointer;"> <img src=\'images/DS/minus.png\' onclick="um_down(\'b\','+b+')" style="cursor:pointer;">';
}

for (m=1;m<15;m++) {
bs = 'm' + m;
bf = 'ms' + m;
if (getByName(bf).value>max_m) getByName(bf).value=max_m;
d.getElementById(bs).innerHTML = '['+getByName(bf).value+'/'+max_m+']<img src="images/skill.gif" height=8 width='+(30*getByName(bf).value/max_m)+'><img src="images/no.png" height=8 width='+(30-30*getByName(bf).value/max_m)+'>';
}

for (m=1;m<8;m++) {
bs = 's' + m;
bf = 'ss' + m;
d.getElementById(bs).innerHTML = '['+getByName(bf).value+'/100]';
if (nsym>0) d.getElementById(bs).innerHTML+='<img src=\'images/DS/plus.png\' onclick="um_up(\'s\','+m+')" style="cursor:pointer;"> <img src=\'images/DS/minus.png\' onclick="um_down(\'s\','+m+')" style="cursor:pointer;">';
}

if (nym != 0) d.getElementById('nymen').innerHTML = nym;
if (nsym != 0) d.getElementById('nsymen').innerHTML = nsym;
}

function um_up(type,num) {
if ((nym>0) && (type=='b') && (d.getElementById('bs'+num).value<30)) {
nym--;
document.ym.nbs.value = nym;
getByName('bs'+num).value++;
d.getElementById('b'+num).innerHTML = '['+getByName('bs'+num).value +'/30]' + '<img src=\'images/DS/plus.png\' onclick="um_up(\'b\','+num+')" style="cursor:pointer;"> <img src=\'images/DS/minus.png\' onclick="um_down(\'b\','+num+')" style="cursor:pointer;">';
d.getElementById('nymen').innerHTML = nym;
}
if ((nsym>0) && (type=='s') && (d.getElementById('ss'+num).value<100)) {
nsym--;
document.ym.nss.value = nsym;
getByName('ss'+num).value++;
d.getElementById('s'+num).innerHTML = '['+getByName('ss'+num).value +'/100]'+ '<img src=\'images/DS/plus.png\' onclick="um_up(\'s\','+num+')" style="cursor:pointer;"> <img src=\'images/DS/minus.png\' onclick="um_down(\'s\','+num+')" style="cursor:pointer;">';
d.getElementById('nsymen').innerHTML = nsym;
}
}

function um_down(type,num) {
if (type=='b') {
var ztemp = getByName('bs'+num).value;
var ctemp = getByName('bf'+num).value;
ztemp++;
ctemp++;
if (ztemp>ctemp){
nym++;
document.ym.nbs.value = nym;
getByName('bs'+num).value--;
d.getElementById('b'+num).innerHTML = '['+getByName('bs'+num).value +'/30]'+ '<img src=\'images/DS/plus.png\' onclick="um_up(\'b\','+num+')" style="cursor:pointer;"> <img src=\'images/DS/minus.png\' onclick="um_down(\'b\','+num+')" style="cursor:pointer;">';
d.getElementById('nymen').innerHTML = nym;
}}
if (type=='s') {
var ztemp = getByName('ss'+num).value;
var ctemp = getByName('sf'+num).value;
ztemp++;
ctemp++;
if (ztemp>ctemp){
nsym++;
document.ym.nss.value = nsym;
getByName('ss'+num).value--;
d.getElementById('s'+num).innerHTML = '['+getByName('ss'+num).value+'/100]' + '<img src=\'images/DS/plus.png\' onclick="um_up(\'s\','+num+')" style="cursor:pointer;"> <img src=\'images/DS/minus.png\' onclick="um_down(\'s\','+num+')" style="cursor:pointer;">';
d.getElementById('nsymen').innerHTML = nsym;
}}
}
