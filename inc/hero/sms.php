<?php
	// the function returns an MD5 of parameters passed
	// функция возвращает MD5 переданных ей параметров
	function ref_sign() {
		$params = func_get_args();
		$prehash = implode("::", $params);
		return md5($prehash);
	}
	
	// the function prints a request form
	// функция печатает форму запроса
	function print_form($purse, $order_id, $amount, $clear_amount, $description, $secret_code, $submit) {
		// making signature
		// создаем подпись
		$sign = ref_sign($purse, $order_id, $amount, $clear_amount, $description, $secret_code);
		
		// printing the form
		// печатаем форму
		echo <<<Form
		<center class=but><form action="http://11e2.bank.smscoin.com/bank/" method="post" target=_blank>
			<p>
				<input name="s_purse" type="hidden" value="$purse" />
				<input name="s_order_id" type="hidden" value="$order_id" />
				<input name="s_amount" type="hidden" value="$amount" />
				<input name="s_clear_amount" type="hidden" value="$clear_amount" />
				<input name="s_description" type="hidden" value="$description" />
				<input name="s_sign" type="hidden" value="$sign" />
				<input type="submit" value="$submit" class=but2 style="cursor:pointer;" />
			</p>
		</form>
		<a href='main.php?gopers=sms' class=Button>Обновить</a>
		</center>

Form;
	}

	// service secret code
	// секретный код сервиса
	$secret_code = "AICode";
	
	// initializing variables
	// инициализируем переменные
	$purse        = 5397;              // sms:bank id        идентификатор смс:банка
	$order_id     = $pers["uid"];           // operation id       идентификатор операции
	$amount       = 1;            // transaction sum    сумма транзакции
	$clear_amount = 1;              // billing algorithm  алгоритм подсчета стоимости
	$description  = iconv("windows-1251" ,"utf8", "Пополнение счёта для ".$pers["user"]); // operation desc     описание операции
	$submit       = "Пополнить [на 1.5 Бр.]";    // submit label       надпись на кнопке submit
	
	// printing the form
	// печатаем форму
	print_form($purse, $order_id, $amount, $clear_amount, $description, $secret_code, $submit);
?>