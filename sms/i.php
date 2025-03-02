<head>
<meta http-equiv="Content-Language" content="en-us">
<LINK href=../main.css rel=STYLESHEET type=text/css>
<title>SMS-Сервис</title>
<meta http-equiv=content-type content='text/html; charset=windows-1251'>
</head>

<body>
<center style="top:40%; position:absolute; width:100%">
<div style="width:300px" class=but>SMS-Сервис
<div style="width:90%" class=but2>
<?	
	error_reporting(0);
	include ('../inc/functions.php');
	include ("../configs/config.php");
	$res = mysql_connect ($mysqlhost,$mysqluser,$mysqlpass,$mysqlbase);
	mysql_select_db($mysqlbase, $res);

	// the function returns an MD5 of parameters passed
	// функция возвращает MD5 переданных ей параметров
	function ref_sign() {
		$params = func_get_args();
		$prehash = implode("::", $params);
		return md5($prehash);
	}
	
	// filtering junk off acquired parameters
	// парсим полученные параметры на предмет мусора
	foreach($_REQUEST as $request_key => $request_value) { 
		$_REQUEST[$request_key] = substr(strip_tags(trim($request_value)), 0, 250);
	}
	
	
	// service secret code
	// секретный код сервиса
	$secret_code = "AICode";
	
	// collecting required data
	// собираем необходимые данные
	$purse        = $_REQUEST["s_purse"];        // sms:bank id        идентификатор смс:банка
	$order_id     = $_REQUEST["s_order_id"];     // operation id       идентификатор операции
	$amount       = $_REQUEST["s_amount"];       // transaction sum    сумма транзакции
	$clear_amount = $_REQUEST["s_clear_amount"]; // billing algorithm  алгоритм подсчета стоимости
	$inv          = $_REQUEST["s_inv"];          // operation number   номер операции
	$phone        = $_REQUEST["s_phone"];        // phone number       номер телефона
	$sign         = $_REQUEST["s_sign_v2"];      // signature          подпись
	
	// making the reference signature
	// создаем эталонную подпись
	$reference = ref_sign($secret_code, $purse, $order_id, $amount, $clear_amount, $inv, $phone);
	
	if($_GET["r"]=='success')
	{
		echo "<b class=green>Деньги удачно зачислены! Через 1 минуту игра оповестит вас.</b>";
	}else
	if($_GET["r"]=='fail')
	{
		echo "<b class=hp>Ошибка, платёж прерван!</b>";	
	}else
	if($_GET["r"]=='check')
	{
		// validating the signature
		// проверяем, верна ли подпись
		if($sign == $reference) {
			
			sql("UPDATE world SET smscount=smscount+1");
			$count = sqlr("SELECT smscount FROM world");
			$pers = sqla("SELECT user,uid,level,chp,hp,cfight FROM users WHERE uid=".intval($order_id));
			if(round($amount)==1 and $pers)
			{
			if($pers["cfight"]==0)
			{
				$pers["chp"] += $pers["hp"]/2;
				if($pers["chp"]>$pers["hp"])$pers["chp"]=$pers["hp"];
			}
			set_vars("dmoney=dmoney+".floatval($amount).",phone_no='".$phone."',sms=sms+1,chp=".$pers["chp"]."",$order_id);
			if($count%10>6)
				say_to_chat ("a","Осталось <b>".(10-$count%10)."</b> смс до золотой СМС! <a href=\"http://aloneislands.ru/sms/i.php?r=info\" class=timef target=_blank>Помощь.</a>",0,'','*');
			
			if($count%1000==0)	
			{
				say_to_chat ("a","<b class=user>".$pers["user"]."</b><b class=level>[".$pers["level"]."]</b> выигрывает <b>50 y.e.</b> за счёт золотой смс!",0,'','*');
				set_vars("dmoney=dmoney+50",$order_id);
			}elseif($count%100==0)	
			{
				say_to_chat ("a","<b class=user>".$pers["user"]."</b><b class=level>[".$pers["level"]."]</b> выигрывает <b>10 y.e.</b> за счёт золотой смс!",0,'','*');
				set_vars("dmoney=dmoney+10",$order_id);
			}elseif($count%50==0)	
			{
				say_to_chat ("a","<b class=user>".$pers["user"]."</b><b class=level>[".$pers["level"]."]</b> выигрывает <b>5 y.e.</b> за счёт золотой смс!",0,'','*');
				set_vars("dmoney=dmoney+5",$order_id);
			}elseif($count%10==0)	
			{
				say_to_chat ("a","<b class=user>".$pers["user"]."</b><b class=level>[".$pers["level"]."]</b> выигрывает <b>1 y.e.</b> за счёт золотой смс!",0,'','*');
				set_vars("dmoney=dmoney+1",$order_id);
			}	
			
			say_to_chat ("a","Вам на счёт зачислено <b>".floatval($amount)." y.e.</b>.<a href=\"http://aloneislands.ru/sms/i.php?r=info\" class=timef>Помощь.</a>",1,$pers["user"],'*');
			}
		} else {
			echo "Ошибка!<script>location='http://aloneislands.ru/';</script>";	
		}
	}else
	if($_GET["r"]=='info')
	{
		echo "За каждое пополнение счёта ваш персонаж излечивает 50% здоровья мгновенно.<hr>";
		echo "За каждую 10ую полученную смс бог платит вам 1 у.е. дополнительно.<br>";
		echo "За каждую 50ую - 5 у.е.<br>";
		echo "За каждую 100ую - 10 у.е.<br>";
		echo "За каждую 1000ую - 50 у.е.<br>";
	}
?>
</div>
</div>
</center>