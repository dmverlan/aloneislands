<? //>
if (isset($_GET['act'])){
  $act = $_GET['act'];
}else{
  if (isset($_FILTER)){
    $pos = strpos($_FILTER['lavkatype'],'_');
    if ($pos === false){
      $act = $_FILTER['lavkatype'];
    }else{
      $filtr = explode('_',$_FILTER['lavkatype']);
      $act = $filtr[0];
    }
  }else{
    $act = 'menu';
  }
}
menu();
switch($act){
  case 'my_lots':
    my_lots();
  break;
  case 'add_lot':
    add_lot();
  break;
  case 'up_lot':
    up_lot();
  break;
  case 'add_form':
    add_form();
  break;
  case 'set_lot':
    set_lot();
  break;
  case 'torgi':
    torgi();
  break;
  case 'my_up_lots';
    my_up_lots();
  break;
  case 'lots':
    my_lots();
  break;
  case 'form':
    add_form();
  break;
  case 'uplots':
    my_up_lots();
  break;
  default:
  break;
}

function menu(){
  end_auction();
?>
<div align=center>
<table border="0" width="800" cellspacing="9" cellpadding="0" class=weapons_box>
  <tr>
    <td align=center>

    </td>
  </tr>
  <tr>
    <td align=center class=but>
      <table border="0" width=100%>
      <tr>
        <td class=but2 width=25%>
          <a class=bg href='main.php?act=add_form&set_type=add_form'>Выставить лот на продажу</a></td>
        <td class=but2 width=25%>
          <a class=bg href='main.php?act=torgi&set_type=torgi'>Просмотр торгов</a></td>
        <td class=but2 width=25%>
          <a class=bg href='main.php?act=my_lots&set_type=lots'>Ваши лоты</a></td>
        <td class=but2 width=25%>
          <a class=bg href='main.php?act=my_up_lots&set_type=uplots'>Ваши текущие ставки</a></td>
      </tr>
      </table>
    </td>
  </tr>
</table>
</div>
<? //>
}

function add_form(){
?>
<div align=center><br>
<table id="table1" border="0" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td align="middle">
        <img title="Ножи" style="cursor: pointer;" onClick="location='main.php?act=add_form&stype=noji&set_type=form_noji'" src="/images/gameplay/noz.gif" width="40" border="0" height="50">
        <img title="Мечи" style="cursor: pointer;" onClick="location='main.php?act=add_form&stype=mech&set_type=form_mech'" src="/images/gameplay/me4i.gif" width="40" border="0" height="50">
        <img title="Дробящее" style="cursor: pointer;" onClick="location='main.php?act=add_form&stype=drob&set_type=form_drob'" src="/images/gameplay/drobja6ee.gif" width="40" border="0" height="50">
        <img title="Топоры" style="cursor: pointer;" onClick="location='main.php?act=add_form&stype=topo&set_type=form_topo'" src="/images/gameplay/topory.gif" width="40" border="0" height="50">
        <img title="Книги заклинаний" style="cursor: pointer;" onClick="location='main.php?act=add_form&stype=book&set_type=form_book'" src="/images/gameplay/book.gif" width="40" border="0" height="50">
        <img title="Щиты" style="cursor: pointer;" onClick="location='main.php?act=add_form&stype=shit&set_type=form_shit'" src="/images/gameplay/6it.gif" width="40" border="0" height="50">
        <img title="Оружие дальнего действия" style="cursor: pointer;" onClick="location='main.php?act=add_form&stype=kid&set_type=form_kid'" src="/images/gameplay/metatelnoe.gif" width="40" border="0" height="50">
        <img title="Шлемы" style="cursor: pointer;" onClick="location='main.php?act=add_form&stype=shle&set_type=form_shle'" src="/images/gameplay/6lemi.gif" width="40" border="0" height="50">
        <img title="Брони" style="cursor: pointer;" onClick="location='main.php?act=add_form&stype=bron&set_type=form_bron'" src="/images/gameplay/bronja.gif" width="40" border="0" height="50">
        <img title="Наручи" style="cursor: pointer;" onClick="location='main.php?act=add_form&stype=naru&set_type=form_naru'" src="/images/gameplay/naru4i.gif" width="40" border="0" height="50">
        <img title="Перчатки" style="cursor: pointer;" onClick="location='main.php?act=add_form&stype=perc&set_type=form_perc'" src="/images/gameplay/per4atki.gif" width="40" border="0" height="50">
        <img title="Сапоги" style="cursor: pointer;" onClick="location='main.php?act=add_form&stype=sapo&set_type=form_sapo'" src="/images/gameplay/sapogi.gif" width="40" border="0" height="50">
        <img title="Кольца" style="cursor: pointer;" onClick="location='main.php?act=add_form&stype=kolc&set_type=form_kolc'" src="/images/gameplay/kolco.gif" width="40" border="0" height="50">
        <img title="Кулоны" style="cursor: pointer;" onClick="location='main.php?act=add_form&stype=kylo&set_type=form_kylo'" src="/images/gameplay/kulon.gif" width="40" border="0" height="50">
        <img title="Пояса" style="cursor: pointer;" onClick="location='main.php?act=add_form&stype=poya&set_type=form_poya'" src="/images/gameplay/pojas.gif" width="40" border="0" height="50">
      </td>
    </tr>
    <tr>
      <td align="middle">
        <img title="Свитки нападения" style="cursor: pointer;" onClick="location='main.php?act=add_form&stype=napad&set_type=form_napad'" src="/images/gameplay/napadenija.gif" width="40" border="0" height="50">
        <img title="Свитки заклинаний и лицензии" style="cursor: pointer;" onClick="location='main.php?act=add_form&stype=zakl&set_type=form_zakl'" src="/images/gameplay/svitki.gif" width="40" border="0" height="50">
        <img title="Фляги восстановления в бою" style="cursor: pointer;" onClick="location='main.php?act=add_form&stype=kam&set_type=form_kam'" src="/images/gameplay/zaklinanija.gif" width="40" border="0" height="50">
        <img title="Зелья алхимические" style="cursor: pointer;" onClick="location='main.php?act=add_form&stype=potion&set_type=form_potion'" src="/images/gameplay/zelja.gif" width="40" border="0" height="50">
        <img title="Руны" style="cursor: pointer;" onClick="location='main.php?act=add_form&stype=rune&set_type=form_rune'" src="/images/gameplay/rune.gif" width="40" border="0" height="50">
        <img title="Травы алхимические" style="cursor: pointer;" onClick="location='main.php?act=add_form&stype=herbal&set_type=form_herbal'" src="/images/gameplay/travy.gif" width="40" border="0" height="50">
        <img title="Телепорт" style="cursor: pointer;" onClick="location='main.php?act=add_form&stype=teleport&set_type=form_teleport'" src="/images/gameplay/teleport.gif" width="40" border="0" height="50">
        <img src="/images/gameplay/fish.gif" title="Рыба и снасти" style="cursor: pointer;" onClick="location='main.php?act=add_form&stype=fishing&set_type=form_fishing'" width="40" border="0" height="50">
        <img src="/images/gameplay/instruments.gif" title="Инструменты" style="cursor: pointer;" onClick="location='main.php?act=add_form&stype=instrument&set_type=form_instrument'" width="40" border="0" height="50">
        <img src="/images/gameplay/resources.gif" title="Ресурсы" style="cursor: pointer;" onClick="location='main.php?act=add_form&stype=resources&set_type=form_resources'" width="40" border="0" height="50">
      </td>
    </tr>
  </tbody>
</table>
</div>
<? //>
  GLOBAL $pers;
  if (isset($_GET['stype'])){
    $stype = $_GET['stype'];
  }else{
    GLOBAL $filtr;
    if (isset($filtr[1])){
      $stype = $filtr[1];
    }else{
      $stype = 'shit';
    }
  }
  $num = sqlr('SELECT count(*) FROM `wp` WHERE `auction`<> 1 AND `stype`="'.$stype.'" AND in_bank=0 AND clan_sign=\'\' AND`uidp`='.UID);
  if ($num < 1){
    show_err('У вас нет предметов этого типа','e');
  }else{
    echo '<div align=center><table width=800>';
    $sql = sql('SELECT * FROM `wp` WHERE `uidp`= '.UID.' AND `stype`="'.$stype.'" AND weared=0 AND `auction` <> 1');
    while ($res = mysql_fetch_array($sql)){
      echo '<tr><td  class=weapons_box>';
      $vesh = $res;
      $lavka = 0;
      include("./inc/inc/weapon.php");
      echo '<div align=right><input type=button class=inv_but onClick="location=\'main.php?act=set_lot&lot_id='.$res['id'].'\'" value="Выставить на продажу"></div></td></tr>';
    }
    echo '</table></div>';
  }
}

function add_lot(){
echo '<div align=center>';
  if (isset($_GET['lot_id'])){
    $lot_id = intval($_GET['lot_id']);
    $sql = 'SELECT `uidp`,`stype` FROM `wp` WHERE `id`='.$lot_id." AND in_bank=0 AND clan_sign='' AND uidp=".UID;
    $res = sqla($sql);
    if (UID == $res['uidp']){
      switch (intval($_POST['time'])){
        case '1':
          $time = 259200;
        break;
        case '2':
          $time = 432000;
        break;
        case '3':
          $time = 604800;
        break;
        case '4':
          $time = 1209600;
        break;
        default:
          $time = 1209600;
        break;
      }
      if ($time > 0){
        $time = time() + $time;
        $price = intval($_POST['price']);
        if ($price > 0){
          GLOBAL $pers;
          $usrm = sqla('SELECT `money` FROM `users` WHERE `uid`='.UID);
          $money = $usrm['money'] - (100 + $price*0.3);
          if ($money > 0){
            $sql = sql('UPDATE `wp` SET `auction`= 1 WHERE `id`='.$lot_id.' AND `uidp`='.UID);
            $sql = sql('INSERT INTO `auction` VALUES('.$lot_id.','.$time.','.$price.','.UID.','.UID.',"'.$res['stype'].'")');
            $sql = sql('UPDATE `users` SET `money`='.$money.' WHERE `uid`='.UID);
            show_err('Лот добавлен на аукцион','n');
          }else{
            show_err('У вас недостаточно денег для выставления лота','e');
          }
        }else{
          show_err('Указана неверная цена лота','e');
        }
      }else{
        show_err('Указана неверная продолжительность торгов','e');
      }
    }else{
      show_err('Выбран неверный предмет','e');
    }
  }else{
    show_err('Не выбран предмет','e');
  }
  echo '</div>';
}

function set_lot(){
  GLOBAL $pers;
  $lot_id = intval($_GET['lot_id']);
  if (isset($_GET['lot_id'])){
    $lot_id = intval($_GET['lot_id']);
    $sql = 'SELECT `uidp` FROM `wp` WHERE `id`='.$lot_id;
    $res = sqla($sql);
    if (UID == $res[0]){
      echo '<br><div align=center><form id="form1" name="form1" method="post" action="main.php?act=add_lot&lot_id='.$lot_id.'"><table width=800  class=weapons_box><tr><td>';
	  $sql ='SELECT * FROM `wp` WHERE `id`='.$lot_id.' AND weared=0 AND `uidp`='.UID;
      $vesh = sqla($sql);
      $lavka = 0;
      include("./inc/inc/weapon.php");
      echo '<br><table width=100%><tr><td width=33%>Начальная цена торгов:<br><input type=text name=price class=login></td>
<td >Продолжительность торгов: <select class=items name=time><option value=1 selected>3 дня</option><option value=2>5 дней</option><option value=3>1 неделя</option><option value=4>2 недели</option></select></td>
<td valign=bottom align=right width=33%><input type=submit class=inv_but value="Выставить на продажу">
<input type=button class=inv_but onClick="location=\'main.php\'" value="Отменить продажу">
</td></tr></table></form></div>';
    }
  }
}

// лоты, выставленные на продажу персом
function my_lots(){
  $num = sqlr('SELECT count(*) FROM `auction` WHERE `owner`='.UID);
  if ($num > 0){
    $sql = sql('SELECT * FROM `wp` WHERE `uidp`='.UID.' AND `auction`=1');
    echo '<div align=center><table width=800>';
    while ($res = mysql_fetch_array($sql)){
      echo '<tr><td  class=weapons_box>';
      $vesh = $res;
      $lavka = 0;
      include("./inc/inc/weapon.php");
      $zapr = sqla('SELECT `wanner`,`price`,`time` FROM `auction` WHERE `id`='.$res['id']);
      $wanner = sqla('SELECT `user` FROM `users` WHERE uid='.$zapr['wanner']);
      echo '<table><tr><td>';
      if ($zapr['wanner'] != UID){
        echo 'Последнюю ставку сделал: <strong>'.$wanner['user'].'</strong>';
      }else{
        echo 'На этот лот нет ставок.';
      }
      echo '<br>Текущая цена лота: <strong>'.$zapr['price'].' ЛН</strong><br>Торги окончатся: <strong>'.date("d.m.Y H:i",$zapr['time']).'</strong></td></tr></table></tr></td>';
    }
    echo '</table></div>';
  }else{
    show_err('Вы не выставляли лоты на аукцион','e');
  }
}

// "апнутые" лоты
function my_up_lots(){
  $num = sqlr('SELECT count(*) FROM `auction` WHERE `wanner`='.UID);
  if ($num > 0){
    $sql = sql('SELECT `id` FROM `auction` WHERE `wanner`='.UID.' AND `wanner`<>`owner`');
    echo '<div align=center><table width=800>';
    while ($r = mysql_fetch_array($sql)){
      $zapr = sqla('SELECT * FROM `wp` WHERE id='.$r['id']);
      echo '<tr><td  class=weapons_box>';
      $vesh = $zapr;
      $lavka = 0;
      include("./inc/inc/weapon.php");
      $zapr = sqla('SELECT `owner`,`price`,`time` FROM `auction` WHERE `id`='.$zapr['id']);
      $owner = sqla('SELECT `user` FROM `users` WHERE uid='.$zapr['owner']);
      echo '<table><tr><td>';
      echo 'Лот выставил на продажу: <strong>'.$owner['user'].'</strong><br>Текущая цена лота: <strong>'.$zapr['price'].' ЛН</strong><br>Торги окончатся: <strong>'.date("d.m.Y H:i",$zapr['time']).'</strong></td></tr></table></tr></td>';
      echo '</td></tr>';
    }
    echo '</table></div>';
    echo '<div align=center><table width=800><tr><td class=weapons_box><i><strong>Текущая цена лота</i></strong> - цена предмета в данным момент.
          <br><i><strong>Торги окончатся</i></strong> - Дата и время окончания торгов. После этого предмет перейдет к игроку, который сделал ставку последним, или вернется к владельцу, если ставок не было.
          <br><br>Если ставка будет перебита другим игроком, предмет исчезнет из этого меню. Деньги за ставку будут возвращены вам. Вы сможете поставить еще раз на этот лот, если торги не закончились.</td></tr></table></div>';

  }else{
    show_err('Текущих ставок нет','e');
  }
}

function end_auction(){
  $cur_time = time();
  $sql = 'SELECT count(*) FROM `auction` WHERE time < '.$cur_time;
  $num = sqlr($sql);
  if ($num > 0){
    $sql = sql('SELECT * FROM `auction` WHERE time < '.$cur_time);
    while ($zapr = mysql_fetch_array($sql)){
      if ($zapr['wanner'] != $zapr['owner']){
        $us = sqla('SELECT `user` FROM `users` WHERE `uid`='.$zapr['wanner']);
        sql('UPDATE `wp` SET `auction`="", `uidp`='.$zapr['wanner'].', `user`="'.$us['user'].'" WHERE `id`='.$zapr['id']);
        sql('DELETE FROM `auction` WHERE `id`='.$zapr['id']);
        $m = sqla('SELECT `money` FROM `users` WHERE `uid`='.$zapr['owner']);
        $m = $m['money'] + $zapr['price'];
        sql('UPDATE `users` SET `money`='.$m.' WHERE `uid`='.$zapr['owner']);
      }else{
        sql('UPDATE `wp` SET `auction`="", `uidp`='.$zapr['wanner'].' WHERE `id`='.$zapr['id']);
        sql('DELETE FROM `auction` WHERE `id`='.$zapr['id']);
      }
    }
  }
}

function up_lot(){
  $lot_id = intval($_GET['lot_id']);
  $sql = sqla('SELECT * FROM `auction` WHERE `id`='.$lot_id);
  $wan_m = sqla('SELECT `money` FROM `users` WHERE `uid`='.UID);
  $up_m = $wan_m['money'] - ($sql['price']*1.1);
  if ($up_m > 0){
    if (UID != $sql['owner']){
      sql('UPDATE `users` SET `money`='.$up_m.' WHERE `uid`='.UID);
      if ($sql['owner'] != $sql['wanner']){
        $wan_m = sqla('SELECT `money` FROM `users` WHERE `uid`='.$sql['wanner']);
        $wan_m = $wan_m['money'] + $sql['price'];
        sql('UPDATE `users` SET `money`='.$wan_m.' WHERE `uid`='.$sql['wanner']);
      }
      show_err('Ставка сделана','n');
      $price = $sql['price']*1.1;
      sql('UPDATE `auction` SET `price`='.$price.', `wanner`='.UID.' WHERE `id`='.$sql['id']);
    }
  }else{
    show_err('У вас нехватает денег, для поднятия ставки','e');
  }
}

function torgi(){
?>
<div align=center><br>
</div>
<? //>
  if (isset($_GET['stype'])){
    $stype = $_GET['stype'];
  }else{
    GLOBAL $filtr;
    if (isset($filtr[1])){
      $stype = $filtr[1];
    }else{
      $stype = 'shit';
    }
  }
  $num = sqlr('SELECT count(*) FROM `auction`');
  if ($num > 0){
    echo '<div align=center><table width=800>';
    $sql = sql('SELECT * FROM `wp` WHERE `auction` = 1');
    while ($res = mysql_fetch_array($sql)){
      echo '<tr><td class=weapons_box>';
      $vesh = $res;
      $lavka = 0;
      include("./inc/inc/weapon.php");
      $zp = sqla('SELECT * FROM auction WHERE id='.$res['id']);
      $own = sqla('SELECT `user` FROM `users` WHERE `uid`='.$zp['owner']);
      $wan = sqla('SELECT `user` FROM `users` WHERE `uid`='.$zp['wanner']);
      echo '<table width=100%><tr><td>Текущая цена лота: <strong>'.$zp['price'].' ЛН</strong></td><td>Сумма следующей ставки составит: <strong>'.($zp['price']*1.1).' ЛН</strong></td></tr>';
      if ($zp['owner'] != UID){
        echo '<tr><td>Лот выставил на продажу : <strong>'.$own['user'].'</strong></td>';
      }else{
        echo '<tr><td>Это ваш лот</td>';
      }
      if ($zp['wanner'] != UID && $zp['wanner'] != $zp['owner']){
        echo '<td>Последнюю ставку сделал: <strong>'.$wan['user'].'</strong></td></tr>';
      }
      if ($zp['wanner'] == $zp['owner']){
        echo '<td>На лот ставок нет</td></tr>';
      }
      if($zp['wanner'] == UID && $zp['owner'] != UID){
        echo '<td>Вы сделали ставку последним</td></tr>';
      }
      echo '<tr><td>Торги окончатся: <strong>'.date("d.m.Y H:i",$zp['time']).'</strong></td></tr>';
      echo '</table>';
      if ($zp['owner'] != UID && $zp['wanner'] != UID){
        echo '<div align=left><input type=button class=inv_but onClick="location=\'main.php?act=up_lot&lot_id='.$res['id'].'\'" value="Поднять ставку"></div></td></tr>';
      }
    }
    echo '</td></tr></table></div>';
    echo '<div align=center><table width=800><tr><td class=weapons_box><i><strong>Текущая цена лота</i></strong> - цена предмета в данным момент
          <br><i><strong>Сумма следующей ставки</i></strong> - Сумма, которую вы отдадите при повышении ставки. При повышении ставки цена лота увеличивается на 10%.
          <br><i><strong>Последнюю ставку сделал</i></strong> - Имя игрока, сделавшего последнюю ставку.
          <br><i><strong>На лот ставок нет</i></strong> - Никто еще не делал ставки на этот предмет.
          <br><i><strong>Вы сделали ставку последним</i></strong> - Вы удерживаете первенство в торгах по этому предмету.
          <br><i><strong>Торги окончатся</i></strong> - Дата и время окончания торгов. После этого предмет перейдет к игроку, который сделал ставку последним, или вернется к владельцу, если ставок не было.</td></tr></table></div>';
  }else{
    show_err('Лоты данной категории не выставлены на торги','n');
  }
}

function show_err($err,$errt){
  switch($errt){
    case 'e':
      echo '<br><div align=center><table width=800 class=but2><tr><td align=center><div class=hp align=center>'.$err.'</div></td></tr></table></div>';
    break;
    case 'n':
      echo '<br><div align=center><table width=800 class=but2><tr><td align=center><div class=ma align=center>'.$err.'</div></td></tr></table></div>';
    break;
  }
}
?>