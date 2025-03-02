<?php
header("Cache-Control: no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
 //>
//
//
// ПОЧИСТИ ПОТОМ ЗА СОБОЙ КОД!!!!!!!!!!!!
//
//
echo '<script src=/js/forum.js></script>';
if (isset($_COOKIE['uid'])){
  define('UID', intval($_COOKIE['uid']));
}else{
  define('UID', -1);
}

if (UID != 19424){  error_reporting(0);}

include ("../configs/config.php");
include ("../inc/functions.php");
$main_conn = mysql_connect ($mysqlhost,$mysqluser,$mysqlpass,$mysqlbase);
mysql_select_db($mysqlbase, $main_conn);
include("funcs.php");
$rights = rights();
$pers = catch_user(UID);
include("adm.php");
/*echo '<!--[if lte IE 7]>
<link rel="stylesheet" type="text/css" href="/ie.css" />
<![endif]-->
<!--[if lte IE 6]>
<link rel="stylesheet" type="text/css" href="/ie6.css" />
<![endif]-->
<link rel="stylesheet" type="text/css" href="/main.css" />'; */
echo '<link rel="stylesheet" type="text/css" href="/main.css" />';
echo '<link rel="stylesheet" type="text/css" href="/forum/style.css" />';
$razd_img = '/images/emp.gif';
if (isset($_GET['act'])){
  $act = $_GET['act'];
}else{
  $act = 'main';
}
echo '<center style="background-image:url(img/topLine.gif); width:100%; height:45px; padding:0; margin:0; display:block;font-size: 26px;font-weight: bold;cursor:pointer;font-family: \'Trebuchet MS\',Verdana;" onclick="location=\'index.php\'">AloneIslands.Ru ФОРУМ</center>';

echo '<table width="800" cellspacing="0" cellpadding="0" align="center"><tr><td class="sideBorder" width="22"></td> <td width="*">';
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
    add_msg();
  break;
  case 'new_topic':
    new_topic();
  break;
  case 'add_topic':
    add_topic();
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
  case 'adm_up':
    adm_up();
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
          show_err('Тема закрыта. Вы не можете редактировать сообщения в ней.');
        }
      }else{
        show_err('Вы не можете редактировать сообщения');
      }
    }else{
      show_err('Не указано сообщение');
    }
  break;
  case 'adm_edit_msg':
    adm_edit_msg();
  break;
  case 'search':
    search();
  break;
  default:
    main();
  break;
}
echo '</td><td class="sideBorder" width="22"></td></tr></table>';

function main(){
  GLOBAL $razd_img, $rights;
  $razd = '';
  $title = 'Форум AloneIslands.ru :: Главная';
  ?><script language="javascript">document.title='<? echo $title;?>'</script><?
  if ($rights['hide'] == 0){
    $tmp1 = sql('SELECT * FROM `forum_razd` WHERE `hide`=0 ORDER BY `pos`');
  }else{
    $tmp1 = sql('SELECT * FROM `forum_razd` ORDER BY `pos`');
  }
  $time = tme();
  while ($sql = mysql_fetch_array($tmp1)){
    $zapr = sqlr('SELECT count(*) FROM forum_subrazd WHERE `cat`='.$sql['id']);
    if ($zapr > 0){
      $razd.= unhtmlentities($sql['title']).'<'.$time.'subrazdel>'; // заголовок раздела
      $tmp = sql('SELECT * FROM forum_subrazd WHERE `cat`='.$sql['id'].' ORDER BY `pos`');
      while ($subrazd = mysql_fetch_array($tmp)){
        $cat_num = sqlr('SELECT count(*) FROM `forum_topics` WHERE `cat`='.$subrazd['id']);
        $msg_num = sqlr('SELECT posts FROM `forum_subrazd` WHERE `id`='.$subrazd['id']);
        $razd.=$subrazd['id'].'<'.$time.'subrazdel>';
        $razd.=unhtmlentities($subrazd['title']).'<'.$time.'subrazdel>';
        $razd.=unhtmlentities($subrazd['about']).'<'.$time.'subrazdel>';
        $razd.=$cat_num.'<'.$time.'subrazdel>';
        $razd.=$msg_num.'<'.$time.'subrazdel>';
        $tmp3 = sqlr('SELECT count(id) FROM `forum_msg` WHERE `cat`='.$subrazd['id']);
        if ($tmp3 > 0){
          $tmp2 = sqla('SELECT max(last_msg) FROM `forum_topics` WHERE `cat`='.$subrazd['id']);
          $topic = sqla('SELECT `title`,`id` FROM `forum_topics` WHERE `last_msg`='.$tmp2[0].' AND `cat`='.$subrazd['id']);
          $msg = sqla('SELECT max(id) FROM `forum_msg` WHERE `topic_id`='.$topic[1]);
          $msg_data = sqla('SELECT `time`,`author` FROM `forum_msg` WHERE `id`='.$msg[0]);
          $author = sqla('SELECT `user` FROM `users` WHERE `uid`='.$msg_data['author']);
          $razd.=$topic['id'].'<'.$time.'subrazdel>';
          $razd.=unhtmlentities($topic['title']).'<'.$time.'subrazdel>';
          $razd.=date("d.m.Y H:i",$msg_data['time']).'<'.$time.'subrazdel>';
          $razd.=$author['user'].'<'.$time.'subrazdel>';
        }else{
          $razd.='-666<'.$time.'subrazdel>';
          $razd.='<'.$time.'subrazdel>';
          $razd.='<'.$time.'subrazdel>';
          $razd.='<'.$time.'subrazdel>';
        }
      }
      $razd.='<'.$time.'razdel>';
    }
  }
  echo '<script>razd(\''.$razd.'\','.$time.');</script>';
  online();
}

function show_cat(){
  if (isset($_GET['id'])){
    GLOBAL $razd_img, $rights;
    $cat = intval($_GET['id']);
    $r_id = sqla('SELECT `cat` FROM `forum_subrazd` WHERE `id`='.$cat);
    sql("UPDATE `forum_subrazd` SET `reads`=`reads`+1 WHERE `id`=".$cat);
    $hide = sqla('SELECT `hide` FROM `forum_razd` WHERE `id`='.$r_id[0]);
    if (($hide[0] != 1) || ($hide[0] == 1 && $rights['hide'] == 1)){
      $num = sqlr('SELECT count(id) FROM `forum_topics` WHERE `cat`='.$cat);
      $tmp1 = sqla('SELECT `title` FROM `forum_razd` WHERE `id`='.$r_id[0]);
      $title = 'Форум AloneIslands.ru :: '.$tmp1['title'];
      ?><script language="javascript">document.title='<? echo $title;?>'</script><? //>
      $time = time();
      echo '<script>Top();</script><div align=left class=header><a href=index.php class=linkHeader>Главная</a> : '.unhtmlentities($tmp1['title']).'</div><script>Center();</script>';
      if ($rights['molch'] == 0 && UID > 0){
        echo '<div align=right><input type=button value="Новая тема" onClick="location.href=\'index.php?act=new_topic&id='.$cat.'\'" class=login></div><br>';
      }
      if ($num > 0){
//		top();
        $tmp = sql('SELECT * FROM `forum_topics` WHERE `cat`='.$cat.' ORDER BY CONCAT(`up`,`last_msg`) DESC');
        $razd = '';
        while($res = mysql_fetch_array($tmp)){
          $msg_num = sqlr('SELECT count(id) FROM `forum_msg` WHERE `topic_id`='.$res['id']);
          $msg_data = sqla('SELECT `time`,`author` FROM `forum_msg` WHERE `id`=(SELECT max(id) FROM `forum_msg` WHERE `topic_id`='.$res['id'].')');
          $author = sqla('SELECT `user` FROM `users` WHERE `uid`='.$msg_data['author']);
		  if (!$res['title']) $res['title'] = 'Без темы';
          $razd .= $res['up'].'<'.$time.'subrazdel>';
          $razd .= $res['closed'].'<'.$time.'subrazdel>';
          $razd .= $res['id'].'<'.$time.'subrazdel>';
          $razd .= unhtmlentities($res['title']).'<'.$time.'subrazdel>';
          $razd .= $msg_num.'<'.$time.'subrazdel>';
          $razd .= $res['id'].'<'.$time.'subrazdel>';
          $razd .= $res['title'].'<'.$time.'subrazdel>';
          $razd .= date("d.m.Y H:i",$msg_data['time']).'<'.$time.'subrazdel>';
          $razd .= $author['user'].'<'.$time.'subrazdel>';
          $razd .= $res['reads'].'<'.$time.'razdel>';
        }
        echo '<script>subrazd(\''.$razd.'\','.$time.');</script>';
//        niz();
      }else{
        show_err('Не создано еще ни одной темы.');
      }
    }else{
      show_err('Вы не можете просматривать скрытые разделы');
    }
  }else{
    show_err('Указан неверный раздел');
  }
}

function show_topic(){
  $max_show = 10;
  $pages_show = 5;
  GLOBAL $razd_img, $rights;
  $time = time();
  if (isset($_GET['id'])){
    $id = intval($_GET['id']);
    $num = sqlr('SELECT count(id) FROM `forum_topics` WHERE `id`='.$id);
    sql("UPDATE `forum_topics` SET `reads`=`reads`+1 WHERE `id`=".$id);
    $hide = sqla('SELECT `hide` FROM `forum_razd` WHERE `id`=(SELECT `cat` FROM `forum_topics` WHERE `id`='.$id.')');
    if (($hide[0] != 1) || ($rights['hide'] == 1)){
      if ($num > 0){
      $topic = sqla('SELECT * FROM `forum_topics` WHERE `id`='.$id);
      $razd = sqla('SELECT `title`,`id` FROM `forum_subrazd` WHERE `id`='.$topic['cat']);
      $title = 'Форум AloneIslands.ru :: '.$razd['title'].' :: '.$topic['title'];
      ?><script language="javascript">document.title='<? echo $title;?>'</script><? //>
      echo '<script>Top();</script><div align=left class=header><img src="'.$razd_img.'"><a href=index.php class=linkHeader>Главная</a> :: <a href=index.php?act=show_cat&id='.$razd['id'].' class=linkHeader>'.$razd['title'].'</a> :: '.unhtmlentities($topic['title']).'</div><script>Center();</script>';
      echo '<div align=right>';
      if ($rights['close'] == 1){
        echo '<input type="button" value="';
        if ($topic['closed'] == 0){
          echo 'Закрыть тему';
        }else{
          echo 'Открыть тему';
        }
        echo '" class=login onClick="location=\'index.php?act=adm_close&id='.$id.'\'"> ';
      }
      if ($rights['up'] == 1){
        echo '<input type="button" value="';
        if ($topic['up'] == 0){
          echo 'Прикрепить';
        }else{
          echo 'Открепить';
        }
        echo '" class=login onClick="location=\'index.php?act=adm_up&id='.$id.'\'"> ';
      }
      if ($rights['edit'] == 1){echo '<input type="button" value="Перенести тему" class=login onClick="location=\'index.php?act=adm_move_form&id='.$id.'\'">';}
      echo '</div>';
      // навигация пошла.
   	  $p_num = sqlr('SELECT count(*) FROM `forum_msg` WHERE `topic_id`='.$topic['id']);
      $pages = ceil(($p_num-1)/$max_show);
      if (isset($_GET['page'])){
  	    $cur_page = intval($_GET['page']);
        $page = intval($_GET['page']);
      }else{
 	    $cur_page = 1;
        $page = 1;
  	  }
	  if ($p_num > $max_show){
        $start = $max_show*($cur_page-1);
        $stop = $max_show;
      }else{
        $start = 0;
        $stop = $max_show;
      }
      if ($start < 1){
        $start = 0;
      }

      $msg=sqla('SELECT * FROM `forum_msg` WHERE `topic_id`='.$topic['id'].' AND `up`=1');
      $author = usr_info($msg['author'],$time);
      $razd=$author.'<'.$time.'msg>';
      $razd.=rep(unhtmlentities($msg['text'])).'<'.$time.'msg>';
      $razd.=$msg['author'].'<'.$time.'msg>';
      $razd.=$msg['id'].'<'.$time.'msg>';
      if ($msg['edit_by'] != 0){
        $who = sqla('SELECT `user` FROM `users` WHERE `uid`='.$msg['edit_by']);
        $razd.=$who[0].'<'.$time.'msg>';
      }else{
        $razd.='0<'.$time.'msg>';
      }
      if ($rights['ans'] == 1){$razd.='1<'.$time.'msg>';}else{$razd.='0<'.$time.'msg>';}
      if ($rights['molch'] == 0 && $topic['closed'] != 1){$razd.='1<'.$time.'msg>';}else{$razd.='0<'.$time.'msg>';}
      if ($rights['edit'] == 1 || ($topic['closed'] != 1 && $msg['edit_by'] == 0 && $msg['author'] == UID)){$razd.='1<'.$time.'msg>';}else{$razd.='0<'.$time.'msg>';}
      if ($rights['edit'] == 1){$razd.='1<'.$time.'msg>';}else{$razd.='0<'.$time.'msg>';}
      $razd.= date("d.m.Y H:i",$msg["time"]).'<'.$time.'next_msg>';

      $tmp3 = sql('SELECT * FROM `forum_msg` WHERE `topic_id`='.$topic['id'].' AND `up`<>1 ORDER BY `id` LIMIT '.$start.','.$stop.'');
      while ($msg = mysql_fetch_array($tmp3)){
        $author = usr_info($msg['author'],$time);
        $razd.=$author.'<'.$time.'msg>';
        $razd.=rep(unhtmlentities($msg['text'])).'<'.$time.'msg>';
        $razd.=$msg['author'].'<'.$time.'msg>';
        $razd.=$msg['id'].'<'.$time.'msg>';
		// редактировалось сообщение или нет
        if ($msg['edit_by'] != 0){
          $who = sqla('SELECT `user` FROM `users` WHERE `uid`='.$msg['edit_by']);
          $razd.=$who[0].'<'.$time.'msg>';
        }else{
          $razd.='0<'.$time.'msg>';
        }
        // молчанки
        if ($rights['ans'] == 1){$razd.='1<'.$time.'msg>';}else{$razd.='0<'.$time.'msg>';}
        // цитирование(????)
        if ($rights['molch'] == 0 && $topic['closed'] != 1){$razd.='1<'.$time.'msg>';}else{$razd.='0<'.$time.'msg>';}
        // кнопачка "редактировать" (??????
        if ($rights['edit'] == 1 || ($topic['closed'] != 1 && $msg['edit_by'] == 0 && $msg['author'] == UID)){$razd.='1<'.$time.'msg>';}else{$razd.='0<'.$time.'msg>';}
        // удалить
        if ($rights['edit'] == 1){$razd.='1<'.$time.'msg>';}else{$razd.='0<'.$time.'msg>';}
       $razd.= date("d.m.Y H:i",$msg["time"]).'<'.$time.'next_msg>';
      }

      if($pages > 1){
	    for ($i = 1; $i < $pages_show; $i++){
          if ($page - $i > 0){
            $tmp[] = $page - $i;
          }
          if ($page + $i <= $pages){
            $tmp[] = $page + $i;
          }
        }
        $tmp[] = $page;
        sort($tmp);
        echo '<center><center class=puns>Страница '.$page.' из '.$pages.': ';
        $m = 0; $n = 0;
        foreach($tmp as $key){
          if ($key == 1){
            $m = 1;
          }
          if ($key == $pages){
            $n = 1;
          }
        }
        if ($m == 0){
          echo '<a class=pagerS href="?act=show_topic&id='.$id.'&page=1">Первая</a> ';
        }
  	    for ($i = 0; $i < count($tmp); $i++){
          echo '<a class=pager href="?act=show_topic&id='.$id.'&page='.$tmp[$i].'">'.$tmp[$i].'</a> ';
	    }
        if ($n == 0){
          echo '<a class=pagerS href="?act=show_topic&id='.$id.'&page='.$pages.'">Последняя</a> ';
        }
        echo "</center></center>";
      }

      $razd = preg_replace("/(\r\n)+|(\n|\r)+/", "<br />", $razd);
      echo '<script>razd = \''.$razd.'\';
      topic(razd,'.$time.');</script>';
      if ($rights['molch'] < 1 && is_array($rights) && $topic['closed'] != 1 && UID > 0){
        ans_form($topic['id'],'msg');
       }
    }else{
      show_err('Указана неверная тема');
    }
    }else{
      show_err('Вы не можете просматривать скрытые темы');
    }
  }else{
    show_err('Указан неверный раздел');
  }
}

function add_msg(){
  GLOBAL $rights;
  if (isset($_POST['msg_text']) && str_replace('  ', ' ', trim($_POST['msg_text'])) != ''){
    $usr = sqla('SELECT `level` FROM `users` WHERE `uid`='.UID);
    if ($usr['level'] > 2 && UID > 0){
      $id = sqla('SELECT max(id) FROM `forum_msg`');
      $id[0]++;
      $text = htmlspecialchars($_POST['msg_text'],ENT_QUOTES);
      $text = preg_replace("/(\r\n)+|(\n|\r)+/", "<br />", $text);
      $text = str_replace('<br /><br />','<br />',$text);
      $t_id = $_GET['id'];
      $r_id = sqlr('SELECT `cat` FROM `forum_topics` WHERE `id`='.$t_id);
      $z_id = sqlr('SELECT `cat` FROM `forum_subrazd` WHERE `id`='.$r_id[0]);
      $hide = sqla('SELECT `hide` FROM `forum_razd` WHERE `id`='.$r_id[0]);
      if (($hide[0] != 1) || ($hide[0] == 1 && $rights['hide'] == 1)){
        sql('INSERT INTO `forum_msg` (`id` ,`author` ,`time` ,`edit_by` ,`topic_id` ,`up` ,`text` ,`cat`) VALUES ('.$id[0].', '.UID.', '.tme().' ,0, '.$t_id.', 0, "'.$text.'", 0)');
        sql("UPDATE forum_subrazd SET posts=posts+1 WHERE id=".$r_id);
        sql('UPDATE `forum_topics` SET `last_msg`='.tme().' WHERE `id`='.$t_id);
        top();
        echo '<div align=center class=timef>Ваше сообщение отправлено.<br>Сейчас вы будете перемещены.<br><a onClick="location.href=\'index.php?act=show_topic&id='.$t_id.'\'" class=timef>Нажмите сюда, если не хотите ждать.</a></div><script>location="index.php?act=show_topic&id='.$t_id.'";</script>';
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
}

function add_topic(){
  GLOBAL $rights;
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
          if (isset($_POST['msg_text']) && $_POST['msg_text'] != ''){
            $msg_text = preg_replace("/(\r\n)+|(\n|\r)+/m", "<br />", $_POST['msg_text']);
            $msg_text = str_replace('<br /><br />','<br />',$msg_text);
            if (isset($_POST['title']) && $_POST['title'] != ''){
              sql('INSERT INTO `forum_topics` (`id` ,`title` ,`author` ,`type` ,`cat`,`last_msg`)VALUES ('.$t_num[0].', "'.htmlspecialchars($_POST['title']).'", '.UID.', 0, '.$id.', '.time().')');
              sql('INSERT INTO `forum_msg` (`id` ,`author` ,`time` ,`edit_by` ,`topic_id` ,`up` ,`text` ,`cat`) VALUES ('.$m_num[0].', '.UID.', '.time().' , 0, '.$t_num[0].', 1, "'.htmlspecialchars($msg_text).'", '.$id.')');
              echo '<script>Top();</script><div align=center class=timef>Новая тема создана.<br>Сейчас вы будете перемещены.<br><a onClick="location.href=\'index.php?act=show_topic&id='.$t_num[0].'\'" class=timef>Нажмите сюда, если не хотите ждать.</a></div><script>redir(\'location.href="index.php?act=show_topic&id='.$t_num[0].'"\');</script><script>Bottom();</script>';
            }else{
              show_err('Не указан заголовок темы');
            }
          }else{
            show_err('Не указан текст темы');
            ans_form($id,'topic');
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

  echo '<form method=post action="index.php?act='.$action.'&id='.$id.'&rand='.tme().'">
          <table><tr><td width=25%>
          <div align=center class=fightlong>';
  $smiles = Array('009','001','002','003','008','007','004','010','005','011','006','012','013','015','016','017','018','019','020','021','022','023','024','025','026','027','028','029','031','032','033','034','035','036','037','038','039','040','041','042','161','045','043','049');
  $k = 0;
  foreach($smiles as $smile){
    ?><img src="/images/smiles/smile_<? echo $smile; ?>.gif" onClick="add_smile('<? echo $k; ?>')" height=20 style="cursor:pointer;"> <? //>
    $k++;
  }
  echo '</div><br></td><td width=75% align=center>';
  if ($type == 'topic'){
    echo 'Название темы: <input type=textfield class=login name="title" size="65" maxlength="255"><br>';
  }
  echo '  <input type=button value="Ж" class=login onClick="add_code(\'b\')">
          <input type=button value="К" class=login onClick="add_code(\'i\')">
          <input type=button value="П" class=login onClick="add_code(\'u\')">
          <input type=button value="URL" class=login onClick="add_code(\'url\')">
          <input type=button value="IMG" class=login onClick="add_code(\'img\')"><br>
          <br>';
  if ($type == 'edit_msg'){
    $msg = sqla('SELECT `text`,`up`,`topic_id` FROM `forum_msg` WHERE `id`='.$id);
    if ($msg['up'] == 1)
    {
    	$tmp_topic = sqlr("SELECT title FROM `forum_topics` WHERE `id`=(SELECT `topic_id` FROM `forum_msg` WHERE `id`='.$id.')");
    	echo "<input type=text name=title value='".$tmp_topic."' style='width:500' class=login>";
    }
    echo '<textarea id="text" name="msg_text" rows="10" wrap="physical" style="width:500">'.br2nl(unhtmlentities($msg[0])).'</textarea>';
    if ($msg['up'] == 1){
      $up = sqla('SELECT `type` FROM `forum_topics` WHERE `id`='.$msg['topic_id']);
      echo '<div align=left>Прикреплена: <input name="up" type="checkbox" value="';
      if ($up[0] == 0){
      	echo 'off';
      }else{
      	echo 'on';
      }
      echo '"></div>';
    }
  }elseif($type != 'quote'){
    echo '<textarea id="text" name="msg_text" rows="10" wrap="physical" style="width:500" class=fightlong></textarea>';
  }
  if ($type == 'quote'){
    $msg = sqla('SELECT `author`,`text` FROM `forum_msg` WHERE `id`='.$qid);
    $author = sqla('SELECT `user` FROM `users` WHERE `uid`='.$msg['author']);
    echo '<textarea id="text" name="msg_text" rows="10" wrap="physical" style="width:500" class=fightlong>[i] Цитата '.$author[0].':
'.br2nl(unhtmlentities($msg[1])).'[/i]</textarea>';
  }
  echo '<br><input type=submit value="Отправить" class=login><input type=reset value="Очистить" class=login></td></tr></table>
          </form>
        </div>';
  niz();
}

function search(){
  if (isset($_GET['q'])){
   $q = $_GET['q'];
   $num = sqlr('SELECT `id` FROM `forum_topics` WHERE `title`=\''.$q.'\'');
   if ($num > 0){
     $_GET["id"] = $num;
     show_topic();
   }else{
   	 show_err('Искомый текст не найден ['.$q.']');
   }
  }else{
  	show_err('Не указан текст поиска');
  }
}

?><center class=timef><a class=timef href='http://aloneislands.ru'>AloneIslands.Ru</a> Форум.</center>
<SCRIPT SRC='/js/c.js'></SCRIPT>