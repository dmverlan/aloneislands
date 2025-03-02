<?
// tgn ))
if ($rights['adm'] == 1){



function adm_main(){

  top();

  echo '<a href="index.php?act=adm_razd" class=timef>Редактирование разделов</a><br>';

  echo '<a href="index.php?act=adm_subrazd" class=timef>Редактирование подразделов</a><br>';

  echo '<a href="index.php?act=adm_users" class=timef>Редактирование пользователей</a><br>';

  niz();

}



function adm_razd_form(){

  top();

  echo '<div class=timef>Редактирование разделов</div><br>';

  $mx = sqlr('SELECT count(id) FROM `forum_razd`');

  $sql = sql('SELECT * FROM `forum_razd` ORDER BY `pos`');

  while ($res = mysql_fetch_array($sql)){

    echo '<table class=weapons_box width=100%><tr><td width=50%>'.unhtmlentities($res['title']);

    if ($res['hide'] == 1){

      echo ' <i>[Скрытый раздел]</i>';

    }

    echo '</td><td>';

    if ($res['pos'] != 1)  {

      echo ' <a href="index.php?act=adm_up_razd&id='.$res['id'].'"><img src="img/up.png"></a>';

    }

    if ($res['pos'] != $mx){

      echo ' <a href="index.php?act=adm_down_razd&id='.$res['id'].'"><img src="img/down.png"></a>';

    }

    echo ' <a href="index.php?act=adm_edit_razd_form&id='.$res['id'].'"><img src="img/edit.png"></a>';

    echo ' <a href="index.php?act=adm_del_razd_form&id='.$res['id'].'"><img src="img/del.png"></a>';

    echo '</td></tr></table><br>';

  }

  niz();

  top();

  echo '<div class=timef>Новый раздел: </div><br><form action="index.php?act=adm_new_razd" method=post>Добавить раздел: <input type=textfield class=inv name="title"><br>Скрытый:<input name="hide" type="checkbox" value="1"><br><input type="submit" value="Создать" class=inv></form>';

  niz();

}



function adm_new_razd(){

  $nm = sqlr('SELECT count(id) FROM `forum_razd`');

  $nm++;

  if (!isset($_POST['hide']) && $_POST['hide'] != 1){

    $hide = 0;

  }else{

    $hide = 1;

  }

  if (isset($_POST['title']) && isset($hide)){

    sql('INSERT INTO `forum_razd`(`id`,`title`,`pos`,`hide`) VALUES('.$nm.',"'.htmlspecialchars($_POST['title']).'",'.$nm.','.$hide.')');

  }

  adm_razd_form();

}



function adm_edit_razd_form(){

  top();

  if (isset($_GET['id'])){

    $id = intval($_GET['id']);

    $nm = sqlr('SELECT count(id) FROM `forum_razd` WHERE `id`='.$id);

    if ($nm == 1){

      $sql = sqla('SELECT * FROM `forum_razd` WHERE `id`='.$id);

      echo '<div class=timef>Редактирование раздела:</div><br>

            <form name="form" action="index.php?act=adm_edit_razd&id='.$id.'" method="post">

              <input name="title" type="text" value="'.unhtmlentities($sql['title']).'" class=inv><br>Скрытый: <input name="hide" class=inv type="checkbox" value="ON"';

      if ($sql['hide'] == 1){

        echo 'checked';

      }

      echo '><br><input type="submit" value="Сохранить" class=inv>

            </form>';

    }else{

      show_err('Указан неверный раздел');

    }

  }

  niz();

}



function adm_edit_razd(){

  if (isset($_GET['id'])){

    $id = intval($_GET['id']);

    $nm = sqlr('SELECT count(id) FROM `forum_razd` WHERE `id`='.$id);

    if ($nm == 1){

      if (!isset($_POST['hide']) && $_POST['hide'] != 1){

        $hide = 0;

      }else{

        $hide = 1;

      }

      if (isset($_POST['title'])){

        sql('UPDATE `forum_razd` SET `title`="'.htmlspecialchars($_POST['title']).'", `hide`='.$hide.' WHERE `id`='.$id);

      }

    }else{

      top();

      show_err('Указан неверный раздел');

      niz();

    }

  }

  adm_razd_form();

}



function adm_del_razd_form(){

  top();

  if (isset($_GET['id'])){

    $id = intval($_GET['id']);

    $nm = sqlr('SELECT count(id) FROM `forum_razd` WHERE `id`='.$id);

    if ($nm == 1){
      $rzd = sqla('SELECT `title`,`hide` FROM `forum_razd` WHERE `id`='.$id);

      echo '<div class=timef><form name="form" action="index.php?act=adm_del_razd&id='.$id.'" method="post">Удаление раздела - <strong>'.$rzd['title'];

      if ($rzd['hide'] == 1){
        echo ' [Скрытый раздел]';
      }

      echo '</strong><br><br>';

      $s_nm = sqlr('SELECT count(id) FROM `forum_subrazd` WHERE `cat`='.$id);

      if ($s_nm > 0){

        $s_razd = sql('SELECT * FROM `forum_razd`');

        echo 'Перенести подразделы в: <select name="subrazd" class=items>';

        while ($res = mysql_fetch_array($s_razd)){

          echo '<option value='.$res['id'].'>'.$res['title'].'</option>

          ';

        }

        echo '</select><br>';

      }

    }else{
      echo 'Подразделов нет. ';
    }

    echo 'Удалить раздел? <input type="submit" value="Да" class=inv> <input type="button" onClick="location=\'index.php?act=adm_razd\'" value="Нет" class=inv></form></div>';

  }else{

    show_err('Указан неверный раздел');

  }

  niz();

}



function adm_del_razd(){
  if (isset($_GET['id'])){
    $id = intval($_GET['id']);

    $nm = sqlr('SELECT count(id) FROM `forum_subrazd` WHERE `cat`='.$id);

    if ($nm > 0){
      if (isset($_POST['subrazd'])){
        sql('UPDATE `forum_subrazd` SET `cat`='.intval($_POST['subrazd']).' WHERE `cat`='.$id);
      }
    }

    sql('DELETE FROM `forum_razd` WHERE `id`='.$id);

    $tmp = sql('SELECT * FROM `forum_razd` ORDER BY `pos`');

    $i = 1;

    while($res = mysql_fetch_array($tmp)){

      sql('UPDATE `forum_razd` SET `pos`='.$i.' WHERE `id`='.$res['id']);

      $i++;

    }



  }else{
    show_err('Указан неверный раздел');
  }

  adm_razd_form();
}



function adm_ud_razd($pos){
  if (isset($_GET['id'])){
    $id = intval($_GET['id']);

    $mx = sqlr('SELECT count(id) FROM `forum_razd`');

    if ($id != 1 || $id != $mx){
      $pos1 = sqla('SELECT `pos` FROM `forum_razd` WHERE `id`='.$id);

      if($pos == 'up'){
        $pos2 = $pos1[0]--;
      }else{

        $pos2 = $pos1[0]++;

      }

      sql('UPDATE `forum_razd` SET `pos`='.$pos1[0].' WHERE `pos`='.$pos2);

      sql ('UPDATE `forum_razd` SET `pos`='.$pos2.' WHERE `id`<>'.$id.' AND `pos`='.$pos1[0]);

    }
  }else{
    show_err('Указан неверный раздел');
  }

  adm_razd_form();
}



function adm_subrazd_form(){
  top();

  $tmp = sql('SELECT * FROM `forum_razd` ORDER BY `pos`');

  while ($razd = mysql_fetch_array($tmp)){
    echo '<div class=timef><strong>'.unhtmlentities($razd['title']).'</strong>';

    if ($razd['hide'] == 1){
      echo ' [Скрытый раздел]';
    }

    echo '</div>';

    $tmp1 = sql('SELECT * FROM `forum_subrazd` WHERE `cat`='.$razd['id'].' ORDER BY `pos`');

    while ($subrazd = mysql_fetch_array($tmp1)){
      $mx = sqlr('SELECT max(pos) FROM `forum_subrazd` WHERE `cat`='.$subrazd['cat']);

      echo '<table class=weapons_box width=100%><tr><td width=50%>  '.unhtmlentities($subrazd['title']).'</td><td>';

      if ($subrazd['pos'] != 1){echo ' <a href="index.php?act=adm_up_subrazd&id='.$subrazd['id'].'"><img src="img/up.png"></a>';}

      if ($subrazd['pos'] != $mx){echo ' <a href="index.php?act=adm_down_subrazd&id='.$subrazd['id'].'"><img src="img/down.png"></a>';}

      echo ' <a href="index.php?act=adm_edit_subrazd_form&id='.$subrazd['id'].'"><img src="img/edit.png"></a>';

      echo ' <a href="index.php?act=adm_del_subrazd_form&id='.$subrazd['id'].'"><img src="img/del.png"></a>';

      echo '</td></tr></table><br>';
    }

    $rzd[] = $razd;

  }

  niz();

  top();

  echo '<div class=timef>Новый подраздел:</div><br><form name="form" action="index.php?act=adm_new_subrazd" method="post">          Название подраздела: <input name="title" type="text" class=inv><br>Описание раздела: <input name="about" type="text" class=inv><br>Создать в разделе: <select size=1 class=items name="razd">';

  foreach ($rzd as $razd){echo '<option value='.$razd['id'].'>'.$razd['title'].'</option>';}

  echo '</select><br><input type="submit" value="Создать" class=inv></form>';

  niz();

}



function adm_new_subrazd(){

  if (isset($_POST['title'], $_POST['razd'])){

    $id = sqlr('SELECT max(id) FROM `forum_subrazd`');

    $rzd = intval($_POST['razd']);

    $pos = sqlr('SELECT max(id) FROM `forum_subrazd` WHERE `cat`='.$rzd);
    $id++;

    $pos++;

    if (!isset($_POST['about'])){
      $about = '';
    }else{
      $about = htmlspecialchars($_POST['about']);
    }

    sql('INSERT INTO `forum_subrazd`(`id`, `title`,`pos`,`cat`,`about`)VALUES('.$id.', "'.htmlspecialchars($_POST['title']).'",'.$pos.','.intval($_POST['razd']).',"'.$about.'")');

  }else{
    top();

    show_err('Заполнены не все поля');

    niz();
  }

  adm_subrazd_form();

}



function adm_del_subrazd_form(){
   if (isset($_GET['id'])){
     $id = intval($_GET['id']);

     top();

     $nm = sqlr('SELECT count(id) FROM `forum_subrazd` WHERE `id`='.$id);

     if ($nm == 1){

       $sql = sqla('SELECT * FROM `forum_subrazd` WHERE `id`='.$id);

       $sub_num = sqlr('SELECT count(id) FROM `forum_topics` WHERE `cat`='.$id);

       echo '<div class=timef>Удаление подраздела - <strong>'.$sql['title'].'</strong><form name="form" action="index.php?act=adm_del_subrazd&id='.$id.'" method="post">';

       if ($sub_num > 0){
         echo '<br>Перенести существующие темы в подраздел: <select size=1 name=move class=items>';

         $tmp = sql('SELECT * FROM `forum_subrazd`');

         while($subrazd = mysql_fetch_array($tmp)){
           $razd = sqla('SELECT `title`,`hide` FROM `forum_razd` WHERE `id`='.$subrazd['cat']);

           echo '<option value='.$subrazd['id'].'>'.$subrazd['title'].' ('.$razd['title'];

           if ($razd['hide'] == 1){
             echo '[Скрытый]';
           }

           echo ')</option>';
         }

         echo '</select>';
       }

       echo '<br>Удалить? <input type="submit" value="Да" class=inv> <input type="button" value="Нет" class=inv onClick="location=\'index.php?act=adm_subrazd\'"></form></div>';

     }else{
       show_err('Указан неверный раздел');
     }

     niz();
   }else{
     show_err('Не указан раздел');
   }
}



function adm_del_subrazd(){
  if (isset($_GET['id'])){
    $id = intval($_GET['id']);

    $tmp = sqlr('SELECT count(id) FROM `forum_topics` WHERE `cat`='.$id);

    if ($tmp > 0){
      if (isset($_POST['move'])){
        sql('UPDATE `forum_topics` SET `cat`='.intval($_POST['move']).' WHERE `cat`='.$id);

      }else{
        show_err('Укажите куда переносить темы раздела');
      }
    }

    sql('DELETE FROM `forum_subrazd` WHERE `id`='.$id);

    $tmp = sql('SELECT * FROM `forum_subrazd` WHERE `cat`='.$id.' ORDER BY `pos`');

    $i = 1;

    while($res = mysql_fetch_array($tmp)){
      sql('UPDATE `forum_subrazd` SET `pos`='.$i.' WHERE `id`='.$res['id']);

      $i++;
    }
  }else{
    show_err('Указан неверный подраздел');
  }

  adm_subrazd_form();
}



function adm_ud_subrazd($move){
  if (isset($_GET['id'])){
    $id = intval($_GET['id']);

    $pos1 = sqla('SELECT `pos`,`cat` FROM `forum_subrazd` WHERE `id`='.$id);

    $mn = sqlr('SELECT count(id) FROM `forum_subrazd` WHERE `cat`='.$pos1['cat']);

    if(($pos1['pos'] == 1 && $move == 'up') && ($mn == $pos1['pos'] && $move != 'up')){
      //NOP

    }else{

      if ($move != 'up'){$pos2 = $pos1['pos']++;}else{$pos2 = $pos1['pos']--;}

      sql('UPDATE `forum_subrazd` SET `pos`='.$pos1['pos'].' WHERE `pos`='.$pos2.' AND `cat`='.$pos1['cat']);

      sql ('UPDATE `forum_subrazd` SET `pos`='.$pos2.' WHERE `id`<>'.$id.' AND `pos`='.$pos1['pos'].' AND `cat`='.$pos1['cat']);

    }
  }else{
    show_err('Не указан подраздел');
  }

  adm_subrazd_form();
}



function adm_edit_subrazd_form(){
  top();

  if (isset($_GET['id'])){
    $id = intval($_GET['id']);

    $subrazd = sqla('SELECT * FROM `forum_subrazd` WHERE `id`='.$id);

    echo '<div class=timef>Редактирование подраздела</div><br>';

    echo '<form name="form" action="index.php?act=adm_edit_subrazd&id='.$id.'" method="post"><input name="title" type="text" value="'.$subrazd['title'].'" class=inv size=65><br>

    Перенести подраздел: <input name="move" type="checkbox" value="1">';
    echo 'в <select size=1 name=razd class=items>';

    $tmp = sql('SELECT * FROM `forum_razd`');

    while($razd = mysql_fetch_array($tmp)){

      echo '<option value='.$razd['id'].'>'.$razd['title'];

      if ($razd['hide'] == 1){

        echo ' [Скрытый]';

      }

        echo '</option>';

      }

      echo '</select><br>Созранить изменения <input type="submit" value="Да" class=inv><input type="button" value="Нет" class=inv onClick="location=\'index.php?act=adm_subrzd_form\'"></form>';



  }else{
    show_err('Указан неверный подраздел');
  }

  niz();
}



function adm_edit_subrazd(){
  if (isset($_GET['id'])){

    $id = intval($_GET['id']);

    if (isset($_POST['title'])){
      if (isset($_POST['move'],$_POST['razd']) && intval($_POST['move'] == 1)){
        $razd = intval($_POST['razd']);

        $pos = sqlr('SELECT count(id) FROM `forum_subrazd` WHERE `cat`='.$razd);

        $pos++;

        sql('UPDATE `forum_subrazd` SET `cat`='.$razd.',`pos`='.$pos.' WHERE `id`='.$id);

        $cat = sqla('SELECT `cat` FROM `forum_subrazd` WHERE `id`='.$id);

        $tmp = sqla('SELECT * FROM `forum_subrazd` WHERE `cat`='.$cat[0].' ORDER BY `pos`');

        $i = 1;

        while ($subrazd= mysql_fetch_array($tmp)){
          sql('UPDATE `forum_subrazd` SET `pos`='.$i.' WHERE `id`='.$subrazd['id']);

          $i++;
        }
      }

      sql('UPDATE `forum_subrazd` SET `title`="'.htmlspecialchars($_POST['title']).'" WHERE `id`='.$id);

    }else{
      top();

      show_err('Не указано название подраздела');

      niz();
    }

  }else{

    top();

    show_err('Указан неверный подраздел');

    niz();

  }

  adm_subrazd_form();
}



function adm_users(){
  top();

  echo '<div class=timef>Редактирование пользователей:</div>

        <form name="form" action="index.php?act=adm_users_form" method="post">

        Пользователь: <input name="user" type="text" class=inv> <input type="submit" value="Найти пользователя" class=inv>

        </form>';

  niz();
}



function adm_edit_users_form(){
  top();
  if (isset($_POST['user'])){
    $usr = sqla('SELECT `uid` FROM `users` WHERE `smuser`="'.strtolower($_POST['user']).'"');
    if($usr)
    {    
    $nm = sqlr('SELECT count(uid) FROM `forum_users` WHERE `uid`='.$usr[0]);
    if ($nm == 1){
      $user = sqla('SELECT * FROM `forum_users` WHERE `uid`='.$usr[0]);
      if ($user['molch'] > 0){
        $molch = 'до '.date("d.m.Y H:i",$user['molch']);
      }else{
        $molch = '<i>Наказание отсутсвует</i>';
      }
      echo '<br><form name="form" action="index.php?act=adm_edit_users&id='.$usr[0].'" method="post">
            <div class=timef>
              <strong>'.$_POST['user'].':</strong><br>
              <table><tr><td valign=top>
              Наложено заклинание молчания:<br></td><td>'.$molch.'</td></tr>
              <tr><td><strong>Пользователь может:</strong></td><td></td></tr>
              <tr><td>Применять заклинание молчания:</td><td><input name="ans" type="checkbox" value="1" ';if ($user['ans'] == 1){echo 'checked'; }echo '>';
      echo '</td></tr><tr><td>Редактировать сообщения:</td><td><input name="edit" type="checkbox" value="1" ';if ($user['edit'] == 1){echo 'checked'; }echo '><br>';
      echo '</td></tr><tr><td>Закрывать темы:</td><td><input name="close" type="checkbox" value="1" ';if ($user['close'] == 1){echo 'checked'; }echo '><br>';
      echo '</td></tr><tr><td>Прикреплять темы:</td><td><input name="up" type="checkbox" value="1" ';if ($user['up'] == 1){echo 'checked'; }echo '><br>';
      echo '</td></tr><tr><td>Создавать разделы:</td><td><input name="create" type="checkbox" value="1" ';if ($user['create'] == 1){echo 'checked'; }echo '><br>';
      $adm = sqlr('SELECT count(uid) FROM `forum_admin` WHERE `uid`='.$usr[0]);
      echo '</td></tr><tr><td>Администрировать форум:</td><td><input name="adm" type="checkbox" value="1" ';if ($adm == 1){echo 'checked'; }echo '>';
      echo '  </td></tr></table>
            <input type="submit" value="Сохранить" class=inv> <input type="button" value="Отмена" class=inv onClick="location=\'index.php?act=adm_users\'">
            </div>
            </form>';
    }}else{
      echo 'Пользователь остуствует в базе';
      adm_users();
    }
  }else{
    show_err('Не указан пользователь');
    adm_users();
  }
  niz();
}

function adm_edit_users(){
  if (isset($_GET['id'])){
    $uid = intval($_GET['id']);
    $nm = sqlr('SELECT count(uid) FROM `forum_admin` WHERE `uid`='.$uid);
    if (isset($_POST['adm']) && intval($_POST['adm'] == 1)){
      if ($nm != 1){
        sql('INSERT INTO `forum_admin` VALUES('.$uid.')');
      }
    }else{
      if ($nm == 1){
        sql('DELETE FROM `forum_admin` WHERE `uid`='.$uid);
      }
    }
    if (isset($_POST['ans']) && intval($_POST['ans']) == 1){$ans = 1;}else{$ans = 0;}
    if (isset($_POST['edit']) && intval($_POST['edit']) == 1){$edit = 1;}else{$edit = 0;}
    if (isset($_POST['close']) && intval($_POST['close']) == 1){$close = 1;}else{$close = 0;}
    if (isset($_POST['up']) && intval($_POST['up']) == 1){$up = 1;}else{$up = 0;}
    if (isset($_POST['create']) && intval($_POST['create']) == 1){$create = 1;}else{$create = 0;}
    sql('UPDATE `forum_users` SET `ans`='.$ans.', `edit`='.$edit.',`close`='.$close.', `up`='.$up.',`create`='.$create.' WHERE `uid`='.$uid);
    }else{
      show_err('Не указан пользователь');
    }
    adm_users();

}


}



function adm_close(){
	GLOBAL $rights;
  if (isset($_GET['id'])){
  $id = intval($_GET['id']);
  $auth == sqla('SELECT `author` FROM `forum_msg` WHERE `up`=1 AND `topic_id`='.$id);
  if ($rights['close'] == 1 || $rights['adm'] == 1 || $auth[0] == UID){
    $cl = sqlr('SELECT `closed` FROM `forum_topics` WHERE `id`='.$id);
    if ($cl == 1){$cl = 0;}else{$cl = 1;}
    sql('UPDATE `forum_topics` SET `closed`='.$cl.' WHERE `id`='.$id);
  }
    show_topic();
  }else{
    top();
    show_err('Не выбрана тема');
    niz();
  }
}

function adm_up(){
	GLOBAL $rights;
  if (isset($_GET['id'])){
  $id = intval($_GET['id']);
  $auth == sqla('SELECT `author` FROM `forum_msg` WHERE `up`=1 AND `topic_id`='.$id);
  if ($rights['up'] == 1 || $rights['adm'] == 1 || $auth[0] == UID){
    $cl = sqlr('SELECT `up` FROM `forum_topics` WHERE `id`='.$id);
    if ($cl == 1){$cl = 0;}else{$cl = 1;}
    sql('UPDATE `forum_topics` SET `up`='.$cl.' WHERE `id`='.$id);
  }
    show_topic();
  }else{
    top();
    show_err('Не выбрана тема');
    niz();
  }
}

if ($rights['edit'] == 1 || $rights['adm'] == 1){

  function adm_move_form(){
    top();

    if (isset($_GET['id'])){
      $id = intval($_GET['id']);

      echo '<div class=timef>Перенос темы:</div><form name="form" action="index.php?act=adm_move&id='.$id.'" method="post">';

      echo '<br>Перенести существующие темы в подраздел:<br><select size=1 name=move class=items>';

      $tmp = sql('SELECT * FROM `forum_subrazd`');

      while($subrazd = mysql_fetch_array($tmp)){

        $razd = sqla('SELECT `title`,`hide` FROM `forum_razd` WHERE `id`='.$subrazd['cat']);

        echo '<option value='.$subrazd['id'].'>'.$subrazd['title'].' ('.$razd['title'];

        if ($razd['hide'] == 1){

          echo '[Скрытый]';

        }

        echo ')</option>';

      }

      echo '</select><br><input type="submit" value="Перенести" class=inv> <input type="button" onClick="location=\'index.php?act=show_topic&id='.$id.'\'" value="Отмена" class=inv></form>';

    }else{
      show_err('Не указана тема');

    }

    niz();
  }



  function adm_move(){
    if (isset($_GET['id'])){
      $id = intval($_GET['id']);

      if (isset($_POST['move'])){
        $move = intval($_POST['move']);

        sql('UPDATE `forum_topics` SET `cat`='.$move.' WHERE `id`='.$id);
        sql('UPDATE `forum_msg` SET `cat`='.$move.' WHERE `topic_id`='.$id);
      }else{
        top();

        show_err('Не указан раздел');

        niz();
      }
    }else{
      top();

      show_err('Не указана тема');

      niz();
    }

    show_topic();
  }

  function adm_edit_msg(){
    if (isset($_GET['id'])){
      $id = intval($_GET['id']);
      $up = sqla('SELECT `up` FROM `forum_msg` WHERE `id`='.$id);
      $t_id = sqla('SELECT `topic_id` FROM `forum_msg` WHERE `id`='.$id);
      if (isset($_POST['msg_text'])){
              echo '<br>=====>';print_r($_POST['up']);
        // сравниваем сообщение типа тут =)
        sql('UPDATE `forum_msg` SET `text`="'.htmlspecialchars(nl2br($_POST['msg_text'])).'", `time`='.time().',`edit_by`='.UID.' WHERE `id`='.$id);
        if (@$_POST['title'] and $up[0] == 1){
          sql('UPDATE `forum_topics` SET `title`="'.htmlspecialchars($_POST['title']).'" WHERE `id`=(SELECT `topic_id` FROM `forum_msg` WHERE `id`='.$id.')');
        }
        top();
        echo '<div align=center class=timef>Сообщение отредактировано.<br>Сейчас вы будете перемещены.<br><a onClick="location.href=\'index.php?act=show_topic&id='.$t_id[0].'\'" class=timef>Нажмите сюда, если не хотите ждать.</a></div><script>redir(\'location.href="index.php?act=show_topic&id='.$t_id[0].'"\');</script>';
        niz();
      }
    }else{
      top();
      show_err('Не указано сообщение');
      niz();
      main();
    }
  }

  function del_msg(){
    if (isset($_GET['id'])){
      $id = intval($_GET['id']);
      $up = sqla('SELECT `up` FROM `forum_msg` WHERE `id`='.$id);
      $cat = sqla('SELECT `topic_id` FROM `forum_msg` WHERE `id`='.$id);
      $cid = sqla('SELECT `cat` FROM `forum_topics` WHERE `id`='.$cat[0]);
      sql('DELETE FROM `forum_msg` WHERE `id`='.$id);
      if ($up[0] == 1){
        sql('DELETE FROM `forum_msg` WHERE `topic_id`='.$cat[0]);
      }
      $nm = sqlr('SELECT count(id) FROM `forum_msg` WHERE `topic_id`='.$cat[0]);
      if ($nm < 1 || $up[0] == 1){
        sql('DELETE FROM `forum_topics` WHERE `id`='.$cat[0]);
        $_GET['id'] = $cid[0];
        show_cat();
      }else{
        $_GET['id'] = $cat[0];
        show_topic();
      }
    }else{
      top();
      show_err('Не указано сообщение');
      niz();
    }
  }

}

if ($rights['ans'] ==1 || $rights['adm'] == 1){
  function molch_form(){
    top();

    if (isset($_GET['uid'])){
      $uid = intval($_GET['uid']);

      $usr = sqla('SELECT `user` FROM `users` WHERE `uid`='.$uid);

      echo '<div class=timef>Наказание:</div><br>

      <form name="form" action="index.php?act=molch&uid='.$uid.'" method="post">

      <strong>Персонаж: '.$usr[0].'</strong><br>

      Наложить заклинание молчания:

      <select size="1" name="molch" class=items>

        <option value="0">Снять</option>

        <option value="1">1 час</option>

        <option value="2">6 часов</option>

        <option value="3">Сутки</option>

        <option value="4">2 суток</option>

        <option value="5">3 суток</option>

        <option value="6">Неделя</option>

        <option value="7">2 недели</option>

        <option value="8">Месяц</option>

        <option value="8">Пожизненно (2 года)</option>

      </select><br>
      
      <input type=text size=30 name=reason value="Причина" class=login>

      <input type="submit" value="Применить" class=inv> <input type="button" value="Отмена" class=inv onClick="window.close()">

      </form>';
    }

    niz();

  }



  function molch(){
  GLOBAL $pers;
    if (isset($_GET['uid'])){
      $uid = intval($_GET['uid']);

      if (isset($_POST['molch'])){
        switch(intval($_POST['molch'])){
          case '0':

            $molch = 0;

          break;

          case '1':

            $molch = time() + 3600;

          break;

          case '2':

            $molch = time() + 21600;

          break;

          case '3':

            $molch = time() + 86400;

          break;

          case '4':

            $molch = time() + 172000;

          break;

          case '5':

            $molch = time() + 259200;

          break;

          case '6':

            $molch = time() + 604800;

          break;

          case '7':

            $molch = time() + 1209600;

          break;

          case '8':

            $molch = time() + 2678400;

          break;

          case '9':

            $molch = time() +  64281600;

          break;

        }

        sql('UPDATE `forum_users` SET `molch`='.$molch.' WHERE `uid`='.$uid);
        if($molch)
        sql("INSERT INTO `puns` ( `uid` , `date` , `who` , `type` , `reason` , `duration` ) 
	VALUES (
	".$uid.", ".tme().", '".$pers["user"]."', '1', '[Форумная]".$_POST["reason"]."', '".($molch-tme())."'
	);");
      }

      top();

      echo '<div class=timef align=center>Заклинание успешно наложено.<br>Можно закрыть страницу</div>';

      niz();
    }else{
      top();

      show_err('Вы не можете накладывать заклинание молчания');

      niz();
    }
  }
}



?>