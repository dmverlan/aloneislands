function show_trans(input, type){

  var tmp = new Array();
  var trans = new Array();
  var res = new Array();

  tmp = input.split("LINE");
  for (i = 0; i < tmp.length; i++){
    res[i] = tmp[i].split("EL");
  }

    output = '<br><div align="center"><table  width=70% border="0" cellspacing="0" cellpadding="0" bordercolorlight=#C0C0C0 bordercolordark=#FFFFFF>';
    output = output +  '<tr valign="top"><td width=200>Показать переводы:<br><a class=timef  href="main.php?act=trans">Все</a><br>';
    output = output +  '<a class=timef  href="main.php?act=trans&sort=0")>Вклады на счет</a><br>';
    output = output +  '<a class=timef  href="main.php?act=trans&sort=1">Снятие со счета</a><br>';
    output = output +  '<a class=timef  href="main.php?act=trans&sort=2">Переводы другим игрокам</a><br>';
    output = output +  '<a class=timef  href="main.php?act=trans&sort=3">Переводы мне</a><br>';
    output = output +  '<a class=timef  href=main.php >Закрыть транзакции</a></td><td>';
  if (res.length > 0){
    for (i = 0; i < res.length; i++){
      switch(res[i][2]){
        case '0':
          output = output +  '<span class=time>'+res[i][7]+'</span> <strong><span style="color:#556688">Вы положили на счет '+res[i][5]+' LN</span></strong><br>';
        break;
        case '1':
          output = output +  '<span class=time>'+res[i][7]+'</span> <strong><span style="color:#3a5c45">Вы сняли со счета '+res[i][5]+' LN</span></strong><br>';
        break;
        case '2':
          output = output +  '<span class=time>'+res[i][7]+'</span> <strong><span style="color:#632929">Вы перевели на счет игрока '+res[i][3]+' '+res[i][5]+' LN</span></strong><br>';
        break;
        case '3':
          output = output +  '<span class=time>'+res[i][7]+'</span> <strong><span style="color:#894801">Игрок '+res[i][4]+' перевел на ваш счет '+res[i][5]+' LN</span></strong><br>';
        break;
      }
    }
  }else{
    output = output + '<strong>Транзакции данного типа отсутсвуют.</strong>';
  }
  output = output +  '</td></tr></table></div><br>';
  trans_div = document.getElementById('trans');
  trans_div.innerHTML = output;
}