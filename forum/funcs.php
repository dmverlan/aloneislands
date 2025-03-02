<?
function top(){
  echo '<table width="100%" cellspacing="0" cellpadding="0">
  		<tr>
    	  <td class="topBorder" height="79">

		  </td>
        </tr>
        <tr>
          <td> ';
}

function niz(){
  echo '</td>
        </tr>
        <tr>
          <td class="bottomBorder" height="32"></td>
        </tr>
      </table>';
}

function show_err($err){
  echo '<script>Top();</script><div align=center><div class=puns>'.$err.'</div></div><script>Bottom();</script>';
}

function menu(){
  GLOBAL $rights;
  if(!is_array($rights) or ($rights['adm'] == 1))
  {
  echo '<script>Top();Center();</script>';
  $rights = rights();
  if (!is_array($rights)){
    echo 'Вы не вошли в игру. Вы не можете оставлять свои сообщения на форуме.';
  }else{
    if($rights['adm'] == 1){
      echo '<a href="index.php" class=timef>Главная</a> :: <a href="index.php?act=adm_menu" class=timef>Администрирование форума</a>';
    }
  }
  echo '<script>Bottom();</script>';
  }
}

function usr_info($uid,$time){
  $usr = sqla('SELECT `user`, `level`,`clan_name`,`sign`,`state` FROM `users` WHERE `uid`='.$uid);
  if ($usr['clan_name'] != '' && $usr['sign'] != 'none'){
    $clan = '<img src="/images/signs/'.$usr['sign'].'.gif" title="'.$usr['clan_name'].'"> '.$usr['state'];
  }else{
    $clan = 'Нет клана';
  }
  $res=$usr['user'].'<'.$time.'msg>'.$usr['level'].'<'.$time.'msg>'.$clan;
  return $res;
}

//from html_entity_decode() manual page
// thnx to Luiz Miguel Axcar =)
function unhtmlentities ($string) {
   $trans_tbl =get_html_translation_table (HTML_ENTITIES );
   $trans_tbl =array_flip ($trans_tbl );
   return strtr ($string ,$trans_tbl );
}

function rep($str){
  $smiles = Array('O:-)','=)',':(',';)',':-P','8-)',':-D',':-/','=-O',':-*',':\'(',':-X','&gt;:o',':-|',':-\\','*JOKINGLY*',']:-&gt;','[:-}','*KISSED*',':-!','*TIRED*','*STOP*','*KISSING*','@}-&gt;--','*THUMBS UP*','*DRINK*','*IN LOVE*','@=','*HELP*','%)','*OK*','*WASSUP*','*SORRY*','*BRAVO*','*ROFL*','*PARDON*','*NO*','*CRAZY*','*DONT_KNOW*','*DANCE*','*YAHOO*','*HI*','*BYE*',';D','*SCRATCH*');
  $smiles_img = Array('<img src="/images/smiles/smile_009.gif">','<img src="/images/smiles/smile_001.gif">','<img src="/images/smiles/smile_002.gif">','<img src="/images/smiles/smile_003.gif">','<img src="/images/smiles/smile_008.gif">','<img src="/images/smiles/smile_007.gif">','<img src="/images/smiles/smile_004.gif">','<img src="/images/smiles/smile_010.gif">','<img src="/images/smiles/smile_005.gif">','<img src="/images/smiles/smile_011.gif">','<img src="/images/smiles/smile_006.gif">','<img src="/images/smiles/smile_012.gif">','<img src="/images/smiles/smile_013.gif">','<img src="/images/smiles/smile_014.gif">','<img src="/images/smiles/smile_015.gif">','<img src="/images/smiles/smile_016.gif">','<img src="/images/smiles/smile_017.gif">','<img src="/images/smiles/smile_018.gif">','<img src="/images/smiles/smile_019.gif">','<img src="/images/smiles/smile_020.gif">','<img src="/images/smiles/smile_021.gif">','<img src="/images/smiles/smile_022.gif">','<img src="/images/smiles/smile_023.gif">','<img src="/images/smiles/smile_024.gif">','<img src="/images/smiles/smile_025.gif">','<img src="/images/smiles/smile_026.gif">','<img src="/images/smiles/smile_027.gif">','<img src="/images/smiles/smile_028.gif">','<img src="/images/smiles/smile_029.gif">','<img src="/images/smiles/smile_031.gif">','<img src="/images/smiles/smile_032.gif">','<img src="/images/smiles/smile_033.gif">','<img src="/images/smiles/smile_034.gif">','<img src="/images/smiles/smile_035.gif">','<img src="/images/smiles/smile_036.gif">','<img src="/images/smiles/smile_037.gif">','<img src="/images/smiles/smile_038.gif">','<img src="/images/smiles/smile_039.gif">','<img src="/images/smiles/smile_040.gif">','<img src="/images/smiles/smile_041.gif">','<img src="/images/smiles/smile_042.gif">','<img src="/images/smiles/smile_161.gif">','<img src="/images/smiles/smile_045.gif">','<img src="/images/smiles/smile_043.gif">','<img src="/images/smiles/smile_049.gif">');
  $str = str_replace($smiles,$smiles_img, $str);
  $code = Array('[b]','[/b]','[i]','[/i]','[u]','[/u]','[img]','[/img]');
  $rep = Array('<strong>', '</strong>','<i>','</i>','<u>','</u>','<img src="','">');
  $str = str_replace($code,$rep, $str);
  // для УРЛ пиздануто тут:
  // based on http://www.phpit.net/article/create-bbcode-php/
  // modified by www.vision.to
  preg_replace('/\[url\](.*?)\[\/url\]/is','<a href="$1" rel="nofollow">$1</a>', $str);
  return $str;
}

function online(){
  $online = sqlr('SELECT count(uid) FROM `forum_users` WHERE `last_online` > '.(time()-300));
  echo '<script>Top();</script><div align=left>Форум просматривают '.$online[0].' игроков:<br>';
  if ($online[0] > 0){
    $uids = sql('SELECT `uid` FROM `forum_users` WHERE `last_online` > '.(time()-300));
    while ($usrs = mysql_fetch_array($uids)){
      $res = sqla('SELECT `user` FROM `users` WHERE `uid` ='.$usrs[0]);
      echo '<a href="/info.php?id='.$usrs[0].'" class=timef target=_blank>'.$res[0].'</a> ';
    }
  }
  echo '</div><script>Bottom();</script>';
}

function rights(){
  if (UID > 0){
    sql('UPDATE `forum_users` SET `molch`=0 WHERE `molch` < '.time());
    $adm = sqlr('SELECT count(uid) FROM `forum_admin` WHERE `uid`='.UID);
    if ($adm == 1){
      $rights['ans'] = 1;
      $rights['pool'] = 1;
      $rights['edit'] = 1;
      $rights['close'] = 1;
      $rights['up'] = 1;
      $rights['create'] = 1;
      $rights['create_hide'] = 1;
      $rights['molch'] = 0;
      $rights['adm'] = 1;
      $rights['hide'] = 1;
      sql('UPDATE `forum_users` SET `last_online`='.time().' WHERE `uid`='.UID);
    }else{
      $lvl = sqla('SELECT `level`,`sign` FROM `users` WHERE `uid`='.UID);
      if ($lvl[0] > 2){
        $num = sqlr('SELECT count(uid) FROM `forum_users` WHERE `uid`='.UID);
        if ($num != 1){
          sql('INSERT INTO `forum_users` (`uid`,`last_online`,`molch`)VALUES('.UID.','.time().',0)');
        }else{
          sql('UPDATE `forum_users` SET `last_online`='.time().' WHERE `uid`='.UID);
        }
        $rights = sqla('SELECT * FROM `forum_users` WHERE `uid`='.UID);
        if ($rights['molch'] < 1){
          if ($lvl[1] == 'watchers'){
            $rights['hide'] = 1;
          }else{
            $rights['hide'] = 0;
          }
        }
      }else{
        $rights = 0;
      }
    }
  }else{
      $rights['ans'] = 0;
      $rights['pool'] = 0;
      $rights['edit'] = 0;
      $rights['close'] = 0;
      $rights['up'] = 0;
      $rights['create'] = 0;
      $rights['create_hide'] = 0;
      $rights['molch'] = 100500;
      $rights['adm'] = 0;
      $rights['hide'] = 0;
  }
  return $rights;
}

function br2nl($string)
{return preg_replace('/\<br(\s*)?\/?\>/i', "", $string);}

?>