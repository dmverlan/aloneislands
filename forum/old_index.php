<?php //>
//
//
// ПОЧИСТИ ПОТОМ ЗА СОБОЙ КОД!!!!!!!!!!!!
//
//

if (isset($_COOKIE['uid'])){
  define('UID', $_COOKIE['uid']);
}else{
  define('UID', -1);
}

if (UID != 19424){
  error_reporting(0);
}

include ("../configs/config.php");
include ("../inc/functions.php");
$main_conn = mysql_connect ($mysqlhost,$mysqluser,$mysqlpass,$mysqlbase);
mysql_select_db($mysqlbase, $main_conn);
include("funcs.php");
$rights = rights();
include("adm.php");
echo '<!--[if lte IE 7]>
<link rel="stylesheet" type="text/css" href="/ie.css" />
<![endif]-->
<!--[if lte IE 6]>
<link rel="stylesheet" type="text/css" href="/ie6.css" />
<![endif]-->
<link rel="stylesheet" type="text/css" href="/main.css" />';


$razd_img = '/images/smiles/smile_039.gif';
if (isset($_GET['act'])){
  $act = $_GET['act'];
}else{
  $act = 'main';
}

echo '<div align=center><table width=800 height=100% cellspacing=0 cellpadding=10><tr><td valign=top bgcolor="D7D7D7">';
menu();
switch($act){
  case 'main':
    main();
  break;
  case 'show_cat':
    show_cat();
  break;
  case 'show_topic':
    show_topic();
  break;
  case 'add_msg':
    $tm = sqla('SELECT max(time) FROM `forum_msg` WHERE `uid`='.UID.'');
    if ($tm[0] > time()+15){
      add_msg();
    }else{
      show_err('Вы не можете добавлять сообщения раньше, чем через 15 секунд, после последнего сообщения');
      show_topic();
    }
  break;
  case 'new_topic':
    $tm = sqla('SELECT max(time) FROM `forum_msg` WHERE `uid`='.UID.'');
    if ($tm[0] > time()+15){
      new_topic();
    }else{
      show_err('Вы не можете создавать новые темы раньше, чем через 15 секунд, после последнего сообщения');
      show_topic();
    }
  break;
  case 'add_topic':
    $tm = sqla('SELECT max(time) FROM `forum_msg` WHERE `uid`='.UID.'');
    if ($tm[0] > time()+15){
      add_topic();
    }else{
      show_err('Вы не можете создавать новые темы раньше, чем через 15 секунд, после последнего сообщения');
      show_topic();
    }
  break;
  case 'adm_menu':
    adm_main();
  break;
  case 'adm_razd':
    adm_razd_form();
  break;
  case 'adm_new_razd':
    adm_new_razd();
  break;
  case 'adm_edit_razd_form':
    adm_edit_razd_form();
  break;
  case 'adm_edit_razd':
    adm_edit_razd();
  break;
  case 'adm_del_razd_form':
    adm_del_razd_form();
  break;
  case 'adm_del_razd':
    adm_del_razd();
  break;
  case 'adm_up_razd':
    adm_ud_razd('up');
  break;
  case 'adm_down_razd':
    adm_ud_razd('down');
  break;
  case 'adm_subrazd':
    adm_subrazd_form();
  break;
  case 'adm_new_subrazd':
    adm_new_subrazd();
  break;
  case 'adm_del_subrazd_form':
    adm_del_subrazd_form();
  break;
  case 'adm_del_subrazd':
    adm_del_subrazd();
  break;
  case 'adm_up_subrazd':
    adm_ud_subrazd('up');
  break;
  case 'adm_down_subrazd':
    adm_ud_subrazd('down');
  break;
  case 'adm_edit_subrazd_form':
    adm_edit_subrazd_form();
  break;
  case 'adm_edit_subrazd':
    adm_edit_subrazd();
  break;
  case 'adm_users':
    adm_users();
  break;
  case 'adm_users_form':
    adm_edit_users_form();
  break;
  case 'adm_edit_users':
    adm_edit_users();
  break;
  case 'adm_close':
    adm_close();
  break;
  case 'adm_move_form':
    adm_move_form();
  break;
  case 'adm_move':
    adm_move();
  break;
  case 'molch_form':
    molch_form();
  break;
  case 'molch':
    molch();
  break;
  case 'adm_delete_msg':
    del_msg();
  break;
  case 'add_quote':
    ans_form(intval($_GET['id']),'quote');
  break;
  case 'adm_edit_msg_form':
    if (isset($_GET['id'])){
      $id = intval($_GET['id']);
      if ($rights['edit']== 1){
        $cat = sqla('SELECT `topic_id` FROM `forum_msg` WHERE `id`='.intval($id));
        $cl = sqla('SELECT `closed` FROM `forum_topics` WHERE `id`='.$cat[0]);
        if ($cl[0] != 1){
          ans_form($id,'edit_msg');
        }else{
          top();
          show_err('Тема закрыта. Вы не можете редактировать сообщения в ней.');
          niz();
        }
      }else{
        top();
        show_err('Вы не можете редактировать сообщения');
        niz();
      }
    }else{
      top();
      show_err('Не указано сообщение');
      niz();
    }
  break;
  case 'adm_edit_msg':
    adm_edit_msg();
  break;
  default:
    main();
  break;
}
echo '</td></tr></table></div>';

function main(){
  GLOBAL $razd_img, $rights;
  $title = 'Форум AloneIslands.ru :: Главная';
  ?><script language="javascript">document.title='<? echo $title;?>'</script><?
  if ($rights['hide'] == 0){
    $tmp1 = sql('SELECT * FROM `forum_razd` WHERE `hide`=0 ORDER BY `pos`');
  }else{
    $tmp1 = sql('SELECT * FROM `forum_razd` ORDER BY `pos`');
  }
  while ($sql = mysql_fetch_array($tmp1)){
    $zapr = sqlr('SELECT count(*) FROM forum_subrazd WHERE `cat`='.$sql['id']);
    if ($zapr > 0){
      top();
      echo '<div align=left><strong><img src="'.$razd_img.'"> '.unhtmlentities($sql['title']).'</strong><br></div><br>
    <table width=100% cellspacing=0 cellpadding=0><tr bgcolor=AAAAAA align=center><td width=50%>Название раздела:</td>
        <td>Тем:</td><td>Сообщений:</td><td>Последнее сообщение:</td></tr>';
      $tmp = sql('SELECT * FROM forum_subrazd WHERE `cat`='.$sql['id'].' ORDER BY `pos`');
      while ($subrazd = mysql_fetch_array($tmp)){
        $cat_num = sqlr('SELECT count(id) FROM `forum_topics` WHERE `cat`='.$subrazd['id']);
        $msg_num = sqlr('SELECT count(id) FROM `forum_msg` WHERE `cat`='.$subrazd['id']);
        echo '<tr><td class=weapons_box valign=top>
        <a href="/forum/?act=show_cat&id='.$subrazd['id'].'" class=timef>'.unhtmlentities($subrazd['title']).'</a>
        <br><div class=timef>'.unhtmlentities($subrazd['about']).' </div></td>
        <td class=weapons_box align=center valign=top width=50>'.$cat_num.'
        </td><td class=weapons_box align=center valign=top width=60>'.$msg_num.'
        </td>
        <td class=weapons_box align=left valign=top><div class=timef>';
        $tmp3 = sqlr('SELECT count(id) FROM `forum_msg` WHERE `cat`='.$subrazd['id']);
        if ($tmp3 > 0){
          $tmp2 = sqla('SELECT max(last_msg) FROM `forum_topics` WHERE `cat`='.$subrazd['id']);
          $topic = sqla('SELECT `title`,`id` FROM `forum_topics` WHERE `last_msg`='.$tmp2[0].' AND `cat`='.$subrazd['id']);
          $msg = sqla('SELECT max(id) FROM `forum_msg` WHERE `topic_id`='.$topic[1]);
          $msg_data = sqla('SELECT `time`,`author` FROM `forum_msg` WHERE `id`='.$msg[0]);
          $author = sqla('SELECT `user` FROM `users` WHERE `uid`='.$msg_data['author']);
          echo '<a href="/forum/?act=show_topic&id='.$topic['id'].'" class=timef>'.unhtmlentities($topic['title']).'</a><br>';
          echo date("d.m.Y H:i",$msg_data['time']).': Автор:'.$author['user'];
        }else{
          echo 'Новых сообщений нет';
        }
        echo '</div></td></tr>';
      }
      echo '</table>';
      niz();
    }
  }
//  online();
}

function show_cat(){
  if (isset($_GET['id'])){
    GLOBAL $razd_img, $rights;
    $cat = intval($_GET['id']);
    $r_id = sqla('SELECT `cat` FROM `forum_subrazd` WHERE `id`='.$cat);
    $hide = sqla('SELECT `hide` FROM `forum_razd` WHERE `id`='.$r_id[0]);
    if (($hide[0] != 1) || ($rights['hide'] == 1)){
      $num = sqlr('SELECT count(id) FROM `forum_topics` WHERE `cat`='.$cat);
      top();
      $tmp1 = sqla('SELECT `title` FROM `forum_razd` WHERE `id`='.$r_id[0]);
      $title = 'Форум AloneIslands.ru :: '.$tmp1['title'];
      ?><script language="javascript">document.title='<? echo $title;?>'</script><? //>
      echo '<div align=left class=timef><strong><img src="'.$razd_img.'"> <a href=index.php class=timef>Главная</a> :: '.unhtmlentities($tmp1['title']).'</strong><br></div><br>';
      if ($rights['molch'] == 0){
        echo '<div align=right><input type=button value="Новая тема" onClick="location.href=\'index.php?act=new_topic&id='.$cat.'\'" class=inv></div><br>';
      }
      if ($num > 0){
        echo '<table width=100% cellspacing=0 cellpadding=0><tr bgcolor=AAAAAA align=center>
          <td width=60%>Название раздела:</td>
          <td width=60>Сообщений:</td>
          <td>Последнее сообщение:</td></tr>';
        $tmp = sql('SELECT * FROM `forum_topics` WHERE `cat`='.$cat.' ORDER BY `last_msg` DESC');
        while($res = mysql_fetch_array($tmp)){
          $msg_num = sqlr('SELECT count(id) FROM `forum_msg` WHERE `topic_id`='.$res['id']);
          echo '<tr valign=top>
          <td class=weapons_box>';
          if ($res['closed'] == 1){
            echo '<strong>Закрыто: </strong>';
          }
          echo '<a href="/forum/?act=show_topic&id='.$res['id'].'" class=timef>'.unhtmlentities($res['title']).'</a>
          </td>
          <td class=weapons_box align=center>'.$msg_num.'
          </td>
          <td class=weapons_box><div class=timef>';
          $tmp2 = sqla('SELECT max(id) FROM `forum_msg` WHERE `topic_id`='.$res['id']);
          $msg_data = sqla('SELECT `time`,`author` FROM `forum_msg` WHERE `id`='.$tmp2['0']);
          $author = sqla('SELECT `user` FROM `users` WHERE `uid`='.$msg_data['author']);
          echo '<a href="/forum/?act=show_topic&id='.$res['id'].'" class=timef>'.$res['title'].'</a><br>';
          echo date("d.m.Y H:i",$msg_data['time']).': Автор:'.$author['user'];
          echo '</div></td>
          </tr>';
        }
        echo '</table>';
      }else{
        show_err('Не создано еще ни одной темы.');
      }
    }else{
      show_err('Вы не можете просматривать скрытые разделы');
    }
    niz();
  }else{
    show_err('Указан неверный раздел');
  }
}

function show_topic(){
  GLOBAL $razd_img, $rights;
  top();
  if (isset($_GET['id'])){
    $id = intval($_GET['id']);
    $num = sqlr('SELECT count(id) FROM `forum_topics` WHERE `id`='.$id);
    $r_id = sqla('SELECT `cat` FROM `forum_topics` WHERE `id`='.$id);
    $hide = sqla('SELECT `hide` FROM `forum_razd` WHERE `id`='.$r_id[0]);
    if (($hide[0] != 1) || ($rights['hide'] == 1)){
      if ($num > 0){
      $topic = sqla('SELECT * FROM `forum_topics` WHERE `id`='.$id);
      $razd = sqla('SELECT `title`,`id` FROM `forum_subrazd` WHERE `id`='.$topic['cat']);
      $title = 'Форум AloneIslands.ru :: '.$razd['title'].' :: '.$topic['title'];
      ?><script language="javascript">document.title='<? echo $title;?>'</script><? //>
      echo '<div align=left class=timef><img src="'.$razd_img.'"><strong><a href=index.php class=timef>Главная</a> :: <a href=index.php?act=show_cat&id='.$razd['id'].' class=timef>'.$razd['title'].'</a> :: '.unhtmlentities($topic['title']).'</strong></div><br>';
      echo '<div align=right>';
      $auth = sqla('SELECT `author` FROM `forum_msg` WHERE `up`=1 AND `topic_id`='.$topic['id']);
      if ($rights['close'] == 1 || $auth[0] == UID){
        echo '<input type="button" value="';
        if ($topic['closed'] == 0){
          echo 'Закрыть тему';
        }else{
          echo 'Открыть тему';
        }
        echo '" class=inv onClick="location=\'index.php?act=adm_close&id='.$id.'\'"> ';
      }
      if ($rights['edit'] == 1){echo '<input type="button" value="Перенести тему" class=inv onClick="location=\'index.php?act=adm_move_form&id='.$id.'\'">';}
      echo '</div>';
      $tmp3 = sql('SELECT * FROM `forum_msg` WHERE `topic_id`='.$topic['id'].' ORDER BY `id`');
      while ($msg = mysql_fetch_array($tmp3)){
        $author = usr_info($msg['author']);
        echo '<table width=100%>
                <tr>
                  <td width=150 class=weapons_box valign=top>'.$author.'</td>
                  <td align=left class=weapons_box valign="top">'.rep(unhtmlentities($msg['text']));
        if ($msg['edit_by'] != 0){
          $who = sqla('SELECT `user` FROM `users` WHERE `uid`='.$msg['edit_by']);
          echo '<div align=right valign=bottom><i>Сообщение отредактировал: '.$who[0].'</i></div>';
        }
        echo '</td>
                </tr>
              </table>
              <table><tr>
                <td width=50% align=left>
                  <input type=button value="Профиль" class=inv onClick="info('.$msg['author'].')">';
        if ($rights['ans'] == 1){
          echo '<input type=button value="Наказание" class=inv onClick="molch('.$msg['author'].')">';
        }
        echo '</td><td> </td>
                <td align=right width=50%>';
        if ($rights['molch'] == 0 && $topic['closed'] != 1){echo '<input type=button value="Цитировать" class=inv onClick="location=\'index.php?act=add_quote&id='.$msg['id'].'\'">';}
        if ($rights['edit'] == 1 || ($topic['closed'] != 1 && $msg['edit_by'] == 0 && $msg['author'] == UID)){echo '<input type=button value="Редактировать" class=inv onClick="location=\'index.php?act=adm_edit_msg_form&id='.$msg['id'].'\'"> ';}
        if ($rights['edit'] == 1){echo '<input type=button value="Удалить" class=inv onClick="del_msg('.$msg['id'].')">';}
        echo '</td></tr></table>';
      }
      niz();
      if ($rights['molch'] < 1 && is_array($rights) && $topic['closed'] != 1){
        ans_form($topic['id'],'msg');
       }
    }else{
      top();
      show_err('Указана неверная тема');
      niz();
    }
    }else{
      top();
      show_err('Вы не можете просматривать скрытые темы');
      niz();
    }
  }else{
    top();
    show_err('Указан неверный раздел');
    niz();
  }
}

function add_msg(){
  GLOBAL $rights;
  if (isset($_POST['msg_text'])){
    $usr = sqla('SELECT `level` FROM `users` WHERE `uid`='.UID);
    if ($usr['level'] > 2 && UID > 0){
      $id = sqla('SELECT max(id) FROM `forum_msg`');
      $id[0]++;
      $text = htmlspecialchars($_POST['msg_text'],ENT_QUOTES);
      $text = preg_replace("/(\r\n)+|(\n|\r)+/", "<br />", $text);
      $text = str_replace('<br /><br />','<br />',$text);
      $t_id = $_GET['id'];
      $r_id = sqla('SELECT `cat` FROM `forum_topics` WHERE `id`='.$t_id);
      $r_id = sqla('SELECT `cat` FROM `forum_subrazd` WHERE `id`='.$r_id[0]);
      $hide = sqla('SELECT `hide` FROM `forum_razd` WHERE `id`='.$r_id[0]);
      if (($hide[0] != 1) || ($hide[0] == 1 && $rights['hide'] == 1)){
        sql('INSERT INTO `forum_msg` (`id` ,`author` ,`time` ,`edit_by` ,`topic_id` ,`up` ,`text` ,`cat`) VALUES ('.$id[0].', '.UID.', '.time().' ,0, '.$t_id.', 0, "'.$text.'", 0)');
        sql('UPDATE `forum_topics` SET `last_msg`='.time().' WHERE `id`='.$t_id);
        top();
        echo '<div align=center class=timef>Ваше сообщение отправлено.<br>Сейчас вы будете перемещены.<br><a onClick="location.href=\'index.php?act=show_topic&id='.$t_id.'\'" class=timef>Нажмите сюда, если не хотите ждать.</a></div><script>redir(\'location.href="index.php?act=show_topic&id='.$t_id.'"\');</script>';
        niz();
      }else{
        show_err('Вы не можете добавлять сообщения в скрытом разделе');
      }
    }else{
      show_err('Вы не можете оставлять сообщения до 3го уровня');
    }
  }else{
    show_err('Текст сообщения не указан');
  }
}

function new_topic(){
  GLOBAL $rights;
  top();
  if (isset($_GET['id'])){
    $id = intval($_GET['id']);
    $num = sqlr('SELECT count(id) FROM `forum_subrazd` WHERE `id`='.$id);
    $r_id = sqla('SELECT `cat` FROM `forum_subrazd` WHERE `id`='.$id);
    $hide = sqla('SELECT `hide` FROM `forum_razd` WHERE `id`='.$r_id[0]);
    $usr = sqla('SELECT `level` FROM `users` WHERE `uid`='.UID);
    if ($usr['level'] > 2 && UID > 0){
      if (($hide[0] != 1) || ($hide[0] == 1 && $rights['hide'] == 1)){
        if ($num > 0){
          ans_form($id,'topic');
        }else{
          show_err('Указан неверный раздел');
        }
      }else{
        show_err('Вы не можете создавать новые темы в скрытом разделе');
      }
    }else{
      show_err('Вы не можете оставлять сообщения до 3го уровня');
    }
  }else{
    show_err('Указан неверный раздел');
  }
  niz();
}

function add_topic(){
  GLOBAL $rights;
  top();
  if (isset($_GET['id'])){
    $id = intval($_GET['id']);
    $num = sqlr('SELECT count(id) FROM `forum_subrazd` WHERE `id`='.$id);
    $r_id = sqla('SELECT `cat` FROM `forum_subrazd` WHERE `id`='.$id);
    $hide = sqla('SELECT `hide` FROM `forum_razd` WHERE `id`='.$r_id[0]);
    $usr = sqla('SELECT `level` FROM `users` WHERE `uid`='.UID);
    if ($usr['level'] > 2 && UID > 0){
      if (($hide[0] != 1) || ($hide[0] == 1 && $rights['hide'] == 1)){
        if ($num > 0){
          $t_num = sqla('SELECT max(id) FROM `forum_topics`');
          $t_num[0]++;
          $m_num = sqla('SELECT max(id) FROM `forum_msg`');
          $m_num[0]++;
          if (isset($_POST['msg_text'])){
            $msg_text = preg_replace("/(\r\n)+|(\n|\r)+/", "<br />", $_POST['msg_text']);
            if (isset($_POST['title']) && $_POST['title'] != ''){
              sql('INSERT INTO `forum_topics` (`id` ,`title` ,`author` ,`type` ,`cat`,`last_msg`)VALUES ('.$t_num[0].', "'.htmlspecialchars($_POST['title']).'", '.UID.', 0, '.$id.', '.time().')');
              sql('INSERT INTO `forum_msg` (`id` ,`author` ,`time` ,`edit_by` ,`topic_id` ,`up` ,`text` ,`cat`) VALUES ('.$m_num[0].', '.UID.', '.time().' , 0, '.$t_num[0].', 1, "'.htmlspecialchars($msg_text).'", '.$id.')');
              echo '<div align=center class=timef>Новая тема создана.<br>Сейчас вы будете перемещены.<br><a onClick="location.href=\'index.php?act=show_topic&id='.$t_num[0].'\'" class=timef>Нажмите сюда, если не хотите ждать.</a></div><script>redir(\'location.href="index.php?act=show_topic&id='.$t_num[0].'"\');</script>';
            }else{
              show_err('Не указан заголовок темы');
            }
          }else{
            show_err('Не указан текст темы');
          }
        }else{
            show_err('Указан неверный раздел');
        }
      }else{
        show_err('Вы не можете создавать новые темы в закрытом разделе');
      }
    }else{
      show_err('Вы не можете оставлять сообщения до 3го уровня');
    }
  }else{
    show_err('Указан неверный раздел');
  }
  niz();
}

function ans_form($id, $type){
  top();
  switch($type){
    case 'quote':
      $action = 'add_msg';
      $qid = $id;
      $id = sqla('SELECT `topic_id` FROM `forum_msg` WHERE `id`='.$id);
      $id = $id[0];
    break;
    case 'msg':
      $action = 'add_msg';
    break;
    case 'topic':
      $action = 'add_topic';
    break;
    case 'edit_msg':
      $action = 'adm_edit_msg';
    break;
    default:
    break;
  }

  echo '<form method=post action="index.php?act='.$action.'&id='.$id.'">
          <table><tr><td width=25%>
          <div align=center>';
  top();
  $smiles = Array('009','001','002','003','008','007','004','010','005','011','006','012','013','015','016','017','018','019','020','021','022','023','024','025','026','027','028','029','031','032','033','034','035','036','037','038','039','040','041','042','161','045','043','049');
  $k = 0;
  foreach($smiles as $smile){
    ?><img src="/images/smiles/smile_<? echo $smile; ?>.gif" onClick="add_smile('<? echo $k; ?>')"> <? //>
    $k++;
  }
  niz();
  echo '</div><br></td><td width=75% align=center>';
  if ($type == 'topic'){
    echo 'Название темы: <input type=textfield class=inv name="title" size="65" maxlength="255"><br>';
  }
  echo '  <input type=button value="Ж" class=inv onClick="add_code(\'b\')">
          <input type=button value="К" class=inv onClick="add_code(\'i\')">
          <input type=button value="П" class=inv onClick="add_code(\'u\')">
          <input type=button value="URL" class=inv onClick="add_code(\'url\')">
          <input type=button value="IMG" class=inv onClick="add_code(\'img\')"><br>
          <br>
          <textarea id="text" name="msg_text" rows="10" wrap="physical" style="width:500">';
  if ($type == 'edit_msg'){
    $msg = sqla('SELECT `text` FROM `forum_msg` WHERE `id`='.$id);
    echo br2nl(unhtmlentities($msg[0]));
  }
  if ($type == 'quote'){
    $msg = sqla('SELECT `author`,`text` FROM `forum_msg` WHERE `id`='.$qid);
    $author = sqla('SELECT `user` FROM `users` WHERE `uid`='.$msg['author']);
    echo '[i] Цитата '.$author[0].':
'.br2nl(unhtmlentities($msg[1])).'[/i]';
  }
  echo '</textarea><br><input type=submit value="Отправить" class=inv><input type=reset value="Очистить" class=inv></td></tr></table>
          </form>
        </div>';
  niz();
}
?>