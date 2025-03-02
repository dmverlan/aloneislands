<?
$botU= array();
$botU["ug"] = 0;$botU["ut"] = 0;$botU["uj"] = 0;$botU["un"] = 0;
$mm = explode("|","ug|ut|uj|un");
if ($od<3)
{
	$botU[$mm[rand(0,3)]] = 1;
}else
if ($od<5)
{
	$botU[$mm[rand(0,3)]] = 2;
}else
if ($od<8)
{
	$botU[$mm[rand(0,3)]] = 5;
}else
if ($od<10)
{
	$botU[$mm[rand(0,3)]] = 1;
	$botU[$mm[rand(0,3)]] = 1;
}else
if ($od<12)
{
	$botU[$mm[rand(0,3)]] = 2;
	$botU[$mm[rand(0,3)]] = 1;
}else
if ($od<15)
{
	$botU[$mm[rand(0,3)]] = 1;
	$botU[$mm[rand(0,3)]] = 2;
}else
if ($od<18)
{
	$botU[$mm[rand(0,3)]] = 5;
	$botU[$mm[rand(0,3)]] = 2;
}else
if ($od<25)
{
	$botU[$mm[rand(0,3)]] = 5;
	$botU[$mm[rand(0,3)]] = 5;
}else
if ($od<30)
{
	$botU[$mm[rand(0,3)]] = 1;
	$botU[$mm[rand(0,3)]] = 1;
	$botU[$mm[rand(0,3)]] = 1;
}else
if ($od<35)
{
	$botU[$mm[rand(0,3)]] = 1;
	$botU[$mm[rand(0,3)]] = 2;
	$botU[$mm[rand(0,3)]] = 2;
}else
if ($od<40)
{
	$botU[$mm[rand(0,3)]] = 2;
	$botU[$mm[rand(0,3)]] = 2;
	$botU[$mm[rand(0,3)]] = 2;
}else
if ($od<45)
{
	$botU[$mm[rand(0,3)]] = 5;
	$botU[$mm[rand(0,3)]] = 1;
	$botU[$mm[rand(0,3)]] = 1;
}else
if ($od<50)
{
	$botU[$mm[rand(0,3)]] = 5;
	$botU[$mm[rand(0,3)]] = 2;
	$botU[$mm[rand(0,3)]] = 1;
}else
if ($od<55)
{
	$botU[$mm[rand(0,3)]] = 5;
	$botU[$mm[rand(0,3)]] = 2;
	$botU[$mm[rand(0,3)]] = 2;
}else
if ($od<60)
{
	$botU[$mm[rand(0,3)]] = 5;
	$botU[$mm[rand(0,3)]] = 5;
	$botU[$mm[rand(0,3)]] = 1;
}else
if ($od<65)
{
	$botU[$mm[rand(0,3)]] = 5;
	$botU[$mm[rand(0,3)]] = 5;
	$botU[$mm[rand(0,3)]] = 2;
}else
{
	$botU[$mm[rand(0,3)]] = 5;
	$botU[$mm[rand(0,3)]] = 5;
	$botU[$mm[rand(0,3)]] = 5;
}
?>