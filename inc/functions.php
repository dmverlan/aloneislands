<?php
// Защита от прямого доступа к файлу
defined('ACCESS') or define('ACCESS', true) or die('Access denied');

// Глобальные переменные для статистики и состояния
$sqlQueriesCounter = 0;      // Счётчик SQL-запросов
$sqlQueriesTimer = 0;        // Суммарное время выполнения запросов
$sqlLongestQueryTime = 0;    // Время самого долгого запроса
$sqlLongestQuery = '';       // Текст самого долгого запроса
$lastSayToChat = 0;          // Время последнего сообщения в чат
$sqlAll = [''];              // Лог всех SQL-запросов
$globalTime = time();        // Глобальное время (замена time())
$battleLog = '';             // Лог боя

// Подключение к базе данных (используем настройки из configs/config.php)
require_once 'configs/config.php';
try {
    $db = new PDO(
        "mysql:host=$mysqlhost;dbname=$mysqlbase;charset=utf8",
        $mysqluser,
        $mysqlpass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Выбрасывать исключения при ошибках
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Ассоциативные массивы по умолчанию
        ]
    );
} catch (PDOException $e) {
    error_log("Ошибка подключения к базе данных: " . $e->getMessage());
    die("Ошибка подключения к базе данных");
}

// Фильтрация входных данных для предотвращения XSS и инъекций
foreach ($_POST as $key => $value) {
    $_POST[$key] = filter($value);
}
foreach ($_GET as $key => $value) {
    $_GET[$key] = filter($value);
}
foreach ($_COOKIE as $key => $value) {
    $_COOKIE[$key] = filter($value);
}

/**
 * Возвращает глобальное время
 * @return int Текущее время в секундах
 */
function tme() {
    global $globalTime;
    return $globalTime;
}

/**
 * Фильтрует строку для предотвращения XSS и SQL-инъекций
 * @param mixed $value Входные данные
 * @return string Отфильтрованная строка
 */
function filter($value) {
    if (!is_string($value)) {
        return $value; // Если не строка, возвращаем как есть (например, числа)
    }
    return htmlspecialchars(str_replace(["'", "\\"], '', $value), ENT_QUOTES, 'UTF-8');
}

/**
 * Генерирует HTML-код иконки для типа удара в бою
 * @param string $type Тип удара ('s' - сокрушительный, 'd' - уворот, 't' - точный)
 * @param int $size Размер иконки в пикселях (по умолчанию 60)
 * @return string HTML-код иконки
 */
function bit_icon($type, $size = 0) {
    $size = $size <= 0 ? 60 : (int)$size; // Устанавливаем размер по умолчанию и защищаем от некорректных значений

    switch ($type) {
        case 's':
            $rand = rand(1, 13);
            return "<img src=\"images/arena/bits/s/a{$rand}.gif\" height=\"{$size}\" title=\"Сокрушительный\">";
        case 'd':
            $rand = rand(1, 7);
            return "<img src=\"images/arena/bits/d/a{$rand}.gif\" height=\"{$size}\" title=\"Уворот\">";
        default:
            $rand = rand(1, 11);
            return "<img src=\"images/arena/bits/t/a{$rand}.gif\" height=\"{$size}\" title=\"Точный\">";
    }
}
/**
 * Рассчитывает удар человека по противнику в бою
 * @param string $point Точка удара ('ug' - голова, 'ut' - грудь, 'uj' - живот, 'un' - ноги)
 * @param array $pers Данные атакующего персонажа
 * @param array $persvs Данные цели
 * @param array $req Требования для удара (например, тип атаки)
 * @param bool $en Флаг противника (true - враг, false - игрок)
 * @param float $delta Множитель урона
 * @return string Лог удара для вывода в бою
 */
function human_udar($point, $pers, $persvs, $req, $en, $delta) {
    global $colors, $fight, $kl, $die, $db;

    // Устанавливаем минимальные значения урона
    $pers['udmin'] = max(1, (int)$pers['udmin']);
    $pers['udmax'] = max(1, (int)$pers['udmax']);
    $delta = max(1, (float)$delta);

    // Проверка невидимости
    $invyou = $pers['invisible'] > tme();
    $invvs = $persvs['invisible'] > tme();
    if ($invyou) {
        $pers['user'] = '<i>невидимка</i>';
        $pers['pol'] = 'female';
    }
    if ($invvs) {
        $persvs['user'] = '<i>невидимка</i>';
        $persvs['pol'] = 'female';
    }

    // Форматирование ников с учётом команд и невидимости
    $nyou = sprintf('<font class="bnick" color="%s">%s</font>[%s]',
        $colors[$pers['fteam']],
        $invyou ? '<i>невидимка</i>' : $pers['user'],
        $invyou ? '??' : $pers['level']
    );
    $nvs = sprintf('<font class="bnick" color="%s">%s</font>[%s]',
        $colors[$persvs['fteam']],
        $invvs ? '<i>невидимка</i>' : $persvs['user'],
        $invvs ? '??' : $persvs['level']
    );

    // Гендерные окончания для текста
    $male = $pers['pol'] === 'female' ? 'а' : '';
    $pitalsa = $male === 'а' ? 'пыталась' : 'пытался';
    $malevs = $persvs['pol'] === 'female' ? 'а' : '';
    $pogib = $persvs['pol'] === 'female' ? 'погибла' : 'погиб';
    $yvvs = $persvs['pol'] === 'female' ? 'увернулась' : 'увернулся';

    // Определение точки удара
    $points = [
        'ug' => ['bpoint' => 'bg', 'ypoint' => 'удар в голову'],
        'ut' => ['bpoint' => 'bt', 'ypoint' => 'удар в грудь'],
        'uj' => ['bpoint' => 'bj', 'ypoint' => 'удар по животу'],
        'un' => ['bpoint' => 'bn', 'ypoint' => 'удар по ногам']
    ];
    $bpoint = $points[$point]['bpoint'] ?? '';
    $ypoint = $points[$point]['ypoint'] ?? '';
    if (!$bpoint || !$ypoint) return false;

    // Расчёт характеристик оружия
    $weapons = weared_weapons($pers['uid']);
    if ($req[$point] !== 'magic') {
        $req[$point] -= $weapons['OD'];
    }

    // Увеличение урона от навыков и оружия
    foreach (['udmin', 'udmax'] as $damage) {
        $pers[$damage] += $pers[$damage] * ($pers['sb2'] / 100) +
                          $weapons['noji'][$damage] * ($pers['sb3'] / 200) +
                          $weapons['mech'][$damage] * ($pers['sb5'] / 200) +
                          $weapons['topo'][$damage] * ($pers['sb6'] / 200) +
                          $weapons['drob'][$damage] * ($pers['sb7'] / 200);
    }

    // Учёт брони противника
    if ($persvs['uid'] && $persvs['sb4']) {
        $stmt = $db->prepare("SELECT SUM(kb) FROM wp WHERE uidp = :uid AND weared = 1 AND stype = 'shit'");
        $stmt->execute([':uid' => (int)$persvs['uid']]);
        $persvs['kb'] += ($stmt->fetchColumn() ?? 0) * $persvs['sb4'] / 33;
    }

    // Определение типа удара
    $ud_name = '';
    switch ($req[$point]) {
        case 3: $ud_name = 'простой '; break;
        case 5: $ud_name = 'прицельный '; break;
        case 7: $ud_name = 'оглушающий '; break;
        default:
            if ($req[$point] !== 'magic' && $req[$point] !== 'kid') {
                $stmt = $db->prepare("SELECT * FROM u_special_dmg WHERE uid = :uid AND od = :od");
                $stmt->execute([':uid' => (int)$pers['uid'], ':od' => (int)$req[$point]]);
                $spd = $stmt->fetch();
                if ($spd) $ud_name = "<b>{$spd['name']}</b> ";
            }
    }
    $ud_name .= $ypoint;
    if (!$ud_name) return false;

    $fall = '';
    if (!$persvs['uid']) {
        $persvs[$bpoint] = mtrunc(rand(-2, 1));
    }

    // Основная логика боя
    if ($persvs['chp'] > 0 && !empty($req[$point]) && ($req[$point] !== 'magic' || !empty($req[$point . 'p'])) && ($req[$point] !== 'kid' || !empty($req[$point . 'p'])) && (is_numeric($req[$point]) || $req[$point] === 'kid' || $req[$point] === 'magic')) {
        $kl = 1;
        $block = '';
        $blocked = false;

        // Особый случай для состояния персонажа
        if ($pers['fstate'] == 2) {
            $stmt = $db->prepare("SELECT * FROM wp WHERE uidp = :uid AND weared = 1 AND stype = 'kid'");
            $stmt->execute([':uid' => (int)$pers['uid']]);
            $f_wp = $stmt->fetch();
            if ($f_wp) {
                $pers['udmin'] = $f_wp['udmin'];
                $pers['udmax'] = $f_wp['udmax'];
                $ud_name = "[<font class=\"time\">{$f_wp['arrow_name']} :: " . ($f_wp['arrow_price'] / 10) . " LN</font>]$ud_name";
                $stmt = $db->prepare("UPDATE wp SET arrows = arrows - 1 WHERE id = :id");
                $stmt->execute([':id' => $f_wp['id']]);
                $promax = rand(1, 10) - $pers['mf3'] / 100;
            }
        }

        // Усиление урона от специальных атак
        if ($spd && $spd['type'] == 1) {
            $pers['udmin'] *= 1 + $spd['value'] / 100;
            $pers['udmax'] *= 1 + $spd['value'] / 100;
        }

        // Расчёт базовых параметров удара
        $ydar = ydar($pers, $persvs) / $delta;
        if ($req[$point] == 5) $ydar *= 1.1; // Прицельный удар
        if ($req[$point] == 7) $ydar *= 1.2; // Оглушающий удар

        $ylov = ylov($pers, $persvs);
        $sokr = sokr($pers, $persvs);
        $yar = yar($pers, $persvs);

        $pers['is_art'] = max(1, $pers['is_art']);
        $persvs['is_art'] = max(1, $persvs['is_art']);
        $yar *= $pers['is_art'];
        $ylov *= $persvs['is_art'];
        $sokr *= $pers['is_art'];
        $ydar = floor($ydar * $pers['is_art']);

        $ylov = min(70, $ylov);
        $sokr = min(70, $sokr);

        // Модификаторы от специальных атак
        if ($spd) {
            if ($spd['type'] == 2) $sokr += $sokr * $spd['value'] / 70;
            if ($spd['type'] == 3) $ylov -= $ylov * $spd['value'] / 70;
            if ($spd['type'] == 1) {
                $pers['udmin'] /= 1 + $spd['value'] / 100;
                $pers['udmax'] /= 1 + $spd['value'] / 100;
            }
        }

        // Яростный удар
        if ($yar > rand(0, 100)) {
            $ydar = round($ydar * 1.7);
            $block .= $block ? ',' : '';
            $block .= '<font color="green">нанося яростный удар</font>';
        }

        $ksokr = 2;
        $CRITISISED = rand(0, 100) < $sokr;
        if ($CRITISISED) {
            $ydar = round($ydar * $ksokr);
        }

        // Проверка блока противника
        $kbFactor = mtrunc($persvs['kb']) / 3 + 1;
        if ($persvs[$bpoint] == 1) {
            if ($ydar / $kbFactor > 2) {
                $ydar *= 0.3;
                $block = ", пробивая простой блок ,";
            } else {
                $ydar = 0;
                $blocked = true;
            }
        } elseif ($persvs[$bpoint] == 2) {
            if ($ydar / $kbFactor > 3) {
                $ydar *= 0.2;
                $block = ", пробивая усиленный блок ,";
            } else {
                $ydar = 0;
                $blocked = true;
            }
        } elseif ($persvs[$bpoint] == 5) {
            if ($ydar / $kbFactor > 5) {
                $ydar *= 0.1;
                $block = ", пробивая крепчайший блок ,";
            } else {
                $ydar = 0;
                $blocked = true;
            }
        }

        $ydar = floor($ydar);
        $z = 1;

        // Магия (если применимо)
        if ($req[$point] === 'magic' && !empty($req[$point . 'p'])) {
            $zid = $req[$point . 'p'];
            require 'inc/inc/magic.php'; // Предполагается, что magic.php обновляет $z и $s
        }

        // Формирование лога удара
        if ($blocked && $z == 1) {
            $z = 0;
            $s = "$nvs <b>заблокировал$malevs</b> <font class=\"timef\">«$ud_name»</font>";
        } elseif ($z == 1 && rand(0, 100) < ($promax ?? 0)) {
            $z = 0;
            $s = "$nyou промах";
            $ydar = 0;
        } elseif ($z == 1 && rand(0, 100) < $ylov) {
            $z = 0;
            $s = bit_icon('d', 16) . "$nyou $pitalsa поразить соперника, но $nvs <b>$yvvs</b> от <font class=\"timef\">«$ud_name»</font>";
            $ydar = 0;
        } elseif ($z == 1 && $CRITISISED) {
            $z = 0;
            $s = bit_icon('s', 16) . "$nyou $block поразил$male $nvs на <font class=\"bnick\" color=\"#CC0000\"><b>-$ydar</b></font> <font class=\"timef\">«cокрушительный $ud_name»</font>";
        } elseif ($z == 1) {
            $z = 0;
            $s = bit_icon('t', 16) . "$nyou $block поразил$male $nvs на <b class=\"user\">-$ydar</b> <font class=\"timef\">«$ud_name»</font>";
        }

        // Эффекты специальных атак
        if ($spd && $spd['type'] == 4 && !$blocked) {
            $persvs['cma'] -= $spd['value'];
            $persvs['cma'] = mtrunc($persvs['cma']);
            $s .= "(<font class=\"ma\">-{$spd['value']} МАНЫ</font>)";
        }

        // Применение урона
        if ($z == 0) {
            $persvs['chp'] -= $ydar;
            $pers['fexp'] += $ydar;
            if (!$invvs) {
                $s .= "<font class=\"hp_in_f\">[" . mtrunc($persvs['chp']) . "/{$persvs['hp']}]</font>";
            }
        }

        global $MAGIC_LOG;
        if ($MAGIC_LOG) $s = $MAGIC_LOG;

        // Обработка смерти противника
        if ($persvs['chp'] <= 0 && $z != 2) {
            $pers['fexp'] += -$persvs['chp'];
            $ydar += -$persvs['chp'];
            $persvs['chp'] = 0;
            $killCondition = ($persvs['uid'] || $persvs['bid'] < 0 || ($persvs['level'] > $pers['level'] + 1 && $persvs['rank_i'] > $pers['rank_i'] - 20 * $pers['is_art'] && rand(0, 100) < 10)) && $persvs['level'] > $pers['level'] - 2 && $fight['travm'] >= 10;
            if ($killCondition) {
                $die = "$nvs <b>$pogib</b> , $nyou опыт <font class=\"green\">+" . ($pers['level'] * 10) . "</font>.%$die";
                $pers['kills']++;
            } else {
                $die = "$nvs <b>$pogib</b>.%$die";
            }
            $str = '';
            if (!$persvs['uid']) {
                require 'inc/inc/bots/drop.php';
            } else {
                require 'inc/inc/fights/travm.php';
            }
            $die .= $str;
        }

        // Обновление опыта и урона
        if ($z != 2) {
            $pers['exp_in_f'] += !$persvs['id_skin'] ? experience($ydar, $pers['level'], $persvs['level'], $persvs['uid'], $persvs['rank_i']) : experience($ydar * 0.3, $pers['level'], $persvs['level'], $persvs['uid'], $persvs['rank_i']);
            $pers['damage_give'] = $ydar;
        }
        $pers['chp'] = max(0, $pers['chp']);

        // Эффект вампиризма
        global $DAY_TIME, $no_mana;
        if (strpos($pers['aura'], 'vampire') !== false && round($ydar / 10) > 0 && !$no_mana && $pers['chp'] > 0 && $z != 2) {
            $heal = round($ydar / ($DAY_TIME == 0 ? 9 : 10));
            $pers['chp'] += $heal;
            $s .= ".Вампиризм <font class=\"hp\">+$heal HP</font>";
        }

        $fall .= $s;

        // Обновление данных в базе
        if ($z != 2) {
            if ($persvs['uid']) {
                $stmt = $db->prepare("UPDATE users SET chp = :chp, cma = :cma" . ($en ? '' : ', refr = 1') . " WHERE uid = :uid");
                $stmt->execute([':chp' => $persvs['chp'], ':cma' => $persvs['cma'], ':uid' => $persvs['uid']]);
            } else {
                $stmt = $db->prepare("UPDATE bots_battle SET chp = :chp, cma = :cma WHERE id = :id");
                $stmt->execute([':chp' => $persvs['chp'], ':cma' => $persvs['cma'], ':id' => $persvs['id']]);
            }
        }

        $stmt = $db->prepare("UPDATE users SET fexp = :fexp, chp = :chp, exp_in_f = :exp_in_f, damage_give = :damage_give, kills = :kills WHERE uid = :uid");
        $stmt->execute([
            ':fexp' => $pers['fexp'],
            ':chp' => $pers['chp'],
            ':exp_in_f' => $pers['exp_in_f'],
            ':damage_give' => $pers['damage_give'],
            ':kills' => $pers['kills'],
            ':uid' => $pers['uid']
        ]);
    } else {
        $fall = "$nyou сделал$malevs контрольный удар по трупу";
    }

    if ($fall) $fall .= '. &nbsp;';

    // Обновление глобальных данных персонажей
    global $pers, $persvs;
    $pers = catch_user($pers['uid']);
    if ($persvs['chp'] > 0) {
        $persvs = $persvs['uid'] ? catch_user($persvs['uid']) : $db->query("SELECT * FROM bots_battle WHERE id = " . (int)$persvs['id'])->fetch();
    } else {
        $stmt = $db->prepare("SELECT * FROM users WHERE cfight = :cfight AND fteam != :fteam AND chp > 0 LIMIT 1");
        $stmt->execute([':cfight' => $pers['cfight'], ':fteam' => $pers['fteam']]);
        $persvs = $stmt->fetch() ?: $db->query("SELECT * FROM bots_battle WHERE cfight = " . (int)$pers['cfight'] . " AND fteam != " . (int)$pers['fteam'] . " AND chp > 0 LIMIT 1")->fetch();
    }

    return $fall;
}

function newbot_udar ($point,$_botU) {
GLOBAL $persvs,$pers,$colors,$fight,$kl,$die,$PVS_NICK,$USER_NICK,$pitalsa,$yvvs,$male,$malevs,$pogib;

if ($_botU[$point]==0 or !$pers or !$persvs) return;

//var_dump($persvs);

if ($persvs["invisible"]>tme()){$persvs["user"] = '<i>невидимка</i>';$invvs=1;$persvs["pol"]='female';} else $invvs=0;

$_SHIT_PLUS = 0;
if($persvs["uid"] and $persvs["sb4"])
	$_SHIT_PLUS += sqlr("SELECT SUM(kb) FROM wp WHERE uidp=".intval($_persvs["uid"])." and weared=1 and stype='shit'")*$persvs["sb4"]/33;

$persvs["kb"] += $_SHIT_PLUS;

if (!$invvs)
$nvs = "<font class=bnick color=".$colors[$persvs["fteam"]].">".$persvs["user"]."</font>[".$persvs["level"]."]";
else
$nvs = "<font class=bnick color=".$colors[$persvs["fteam"]]."><i>невидимка</i></font>[??]";
$nyou = "<font class=bnick color=".$colors[$pers["fteam"]].">".$pers["user"]."</font>[".$pers["level"]."]";

	if ($pers["pol"]=='female') $male='а'; else $male='';
		if ($male=='а')
		$pitalsa = 'пыталась';
		else
		$pitalsa = 'пытался';

		if ($persvs["pol"]=='female')
		 {
			$pogib = 'погибла';
			$malevs='а';
			$yvvs = 'увернулась';
		 }
		else
		 {
			$pogib = 'погиб';
			$malevs='';
			$yvvs = 'увернулся';
		 }
switch ($point) {
case ("ug"): {$bpoint="bg";$ypoint="удар в голову";break;}
case ("ut"): {$bpoint="bt";$ypoint="удар в грудь";break;}
case ("uj"): {$bpoint="bj";$ypoint="удар по животу";break;}
case ("un"): {$bpoint="bn";$ypoint="удар по ногам";break;}
}
if ($_botU[$point]==1) $ud_name = 'простой ';
if ($_botU[$point]==2) $ud_name = 'прицельный ';
if ($_botU[$point]==5) $ud_name = 'оглушающий ';
$ud_name .= $ypoint;
$ud_name = $ud_name;
$fall = '';
//var_dump($_botU);
if (isset ($_botU[$point]) and $persvs["chp"]>0)
 {
	$kl=1;
	$block='';
	$blocked=0;
	$ydar = ydar($pers,$persvs);
	if ($_botU[$point]==2) $ydar *= 1.1;
	if ($_botU[$point]==5) $ydar *= 1.2;
	if ($persvs[$bpoint]==1)
	 {
		if ($ydar/(mtrunc($persvs["kb"])+1)>2)
		{$ydar*=0.3;
		$block=", пробивая простой блок ,";}
		else
		{
		$ydar=0;
		$blocked = 1;
		}
	 }
	if ($persvs[$bpoint]==2)
	 {
		if ($ydar/(mtrunc($persvs["kb"])+1)>3)
		{$ydar*=0.2;
		$block=", пробивая усиленный блок ,";}
		else
		{
		$ydar=0;
		$blocked = 1;
		}
	 }

	if ($persvs[$bpoint]==5)
	 {
		if ($ydar/(mtrunc($persvs["kb"])+1)>5)
		{$ydar*=0.1;
		$block=", пробивая крепчайший блок ,";}
		else
		{
		$ydar=0;
		$blocked = 1;
		}
	 }
	$ydar = floor($ydar);
	$ylov = ylov($pers,$persvs);
	$sokr = sokr($pers,$persvs);
	$yar  = yar ($pers,$persvs);

if ($persvs["is_art"]<1) $persvs["is_art"]=1;
if ($pers["is_art"]<1) $pers["is_art"]=1;

$ylov *= $persvs["is_art"];

if ($ylov>70) $ylov = 70;
if ($sokr>70) $sokr = 70;

if ($yar>rand(0,100))
{
	$ydar *= 1.4;
	if ($block=='') $block=',';
	$block.='<font color=green>нанося яростный удар</font>,';
}

$ydar = floor($ydar);

$ksokr = 2;

$z=1;
 if ($blocked and $z==1)
 {
	$z=0;
	$s=$nvs." <b>заблокировал".$malevs."</b> <font class=timef>«".$ud_name."»</font>";
 }
if ($z==1 and rand(0,100)<$promax)
 {
	$z=0;
	$s=$nyou." промах";
 }
if ($z==1 and rand(0,100)<$ylov)
 {
	$z=0;
	$s= bit_icon("d",16).$nyou." ".$pitalsa." поразить соперника, но ".$nvs." <b>".$yvvs."</b> от <font class=timef>«".$ud_name."»</font>";

 }
if ($z==1 and rand(0,100)<$sokr)
 {
	$z=0;
	$ydar=round($ydar*$ksokr);
	$persvs["chp"]-=$ydar;
	$pers["fexp"]+=$ydar;
	if (!$invvs)$hpvs = "<font class=hp_in_f>[".mtrunc($persvs["chp"])."/".$persvs["hp"]."]</font>"; else $hpvs='';
	$s= bit_icon("s",16).$nyou." ".$block." поразил".$male." ".$nvs." на	<font class=bnick color=#CC0000><b>-".$ydar."</b></font> <font class=timef>«cокрушительный ".$ud_name."»</font>".$hpvs;
 }
if ($z==1)
 {
	$z=0;
	$persvs["chp"]-=$ydar;
	if (!$invvs)$hpvs = "<font class=hp_in_f>[".mtrunc($persvs["chp"])."/".$persvs["hp"]."]</font>"; else $hpvs='';
	$s= bit_icon("t",16).$nyou." ".$block." поразил".$male." ".$nvs." на <b class=user>-".$ydar."</b> <font class=timef>«".$ud_name."»</font>".$hpvs;
 }
 		$pers["exp_in_f"]+= experience($ydar,
				$pers["level"],$persvs["level"],$persvs["uid"],$persvs["rank_i"]);
		if ($persvs["chp"]<=0 and $z<>2)
		 {
			$persvs["chp"]=0;
			$die=$nvs." <b>".$pogib."</b>.%".$die;
			if($persvs["uid"])
				include ('inc/inc/fights/travm.php');
			$die.=$str;
		 }
		$fall=$fall.$s;
		if($persvs["uid"])
			sql ("UPDATE `users` SET `chp`='".$persvs["chp"]."'	WHERE `uid`='".$persvs["uid"]."'");
		else
			sql ("UPDATE `bots_battle` SET `chp`='".$persvs["chp"]."'	WHERE `id`='".$persvs["id"]."'");
		sql ("UPDATE `bots_battle` SET `exp_in_f`='".$pers["exp_in_f"]."' WHERE `id`='".$pers["id"]."'");
 }
 elseif($persvs["chp"]<=0)
 	$fall = $nyou." сделал контрольный удар по трупу";
 if ($fall) $fall = $fall.". &nbsp;";
 $persvs["kb"] -= $_SHIT_PLUS;
 return $fall;
}

function end_battle($pers)
{
	GLOBAL $GOOD_DAY,$options;

// В $pers["f_turn"] хранится переменная победа. =1 - победили. =0 - проиграли.
$fight = sqla("SELECT * FROM `fights` WHERE `id`='".$pers["cfight"]."'");
if ($fight["turn"]=="finish" and $fight["type"]=='f')
{
	if(($pers["lb_attack"]-40)<tme())
		$pers["lb_attack"] = tme()-40;
	$curstate = 0;
	$win = ($pers["f_turn"]==1)?"Победа!":"Поражение.";
	######Праздник
	if($fight["special"]==1)
	{
		include("holyday/new_year.php");
	}
	######Турниры
if($pers["tour"]==1)
	{
		$t1 = sqla("SELECT * FROM quest WHERE id = 2");
		if($pers["f_turn"]!=1)
		{
			set_vars("tour=0",$pers["uid"]);
			say_to_chat('s',"Вы проиграли турнир...",1,$pers["user"],'*',0);
		}
		elseif($t1["type"]==2)
		{
			say_to_chat('s',"Вы прошли во вторую стадию турнира!",1,$pers["user"],'*',0);
			sql("UPDATE `users` SET chp=hp,cma=ma WHERE `uid`='".$pers["uid"]."' ");
			sql("UPDATE p_auras SET esttime=0 WHERE uid=".$pers["uid"]." and special>=3 and special<=5 and esttime>".tme());
			$pers["chp"]=$pers["hp"];
			$pers["cma"]=$pers["ma"];
		}
		elseif($t1["type"]==3)
		{
			set_vars("tour=0,coins=coins+10,exp=exp+10000,money=money+100",$pers["uid"]);
			say_to_chat('s',"Вы выиграли турнир!",1,$pers["user"],'*',0);
			sql("UPDATE quest SET finished=1,time=".tme()." WHERE id = 2");
		}
	}
	if($pers["tour"]==2)
	{
		$t1 = sqla("SELECT * FROM quest WHERE id = 3");
		if($pers["f_turn"]==0)
		{
			set_vars("tour=0",$pers["uid"]);
			say_to_chat('s',"Вы проиграли турнир...",1,$pers["user"],'*',0);
		}
		elseif($t1["type"]==2)
		{
			say_to_chat('s',"Вы прошли во вторую стадию турнира!",1,$pers["user"],'*',0);
			sql("UPDATE `users` SET chp=hp,cma=ma WHERE `uid`='".$pers["uid"]."' ");
			sql("UPDATE p_auras SET esttime=0 WHERE uid=".$pers["uid"]." and special>=3 and special<=5 and esttime>".tme());
			$pers["chp"]=$pers["hp"];
			$pers["cma"]=$pers["ma"];
		}
		elseif($t1["type"]==3)
		{
			set_vars("tour=0,coins=coins+10,exp=exp+10000,money=money+100",$pers["uid"]);
			say_to_chat('s',"Вы выиграли турнир!",1,$pers["user"],'*',0);
			sql("UPDATE quest SET finished=1,time=".tme()." WHERE id = 3");
		}
	}
	if($pers["tour"]==3)
	{
		$t1 = sqla("SELECT * FROM quest WHERE id = 4");
		if($pers["f_turn"]==0)
		{
			set_vars("tour=0",$pers["uid"]);
			say_to_chat('s',"Вы проиграли турнир...",1,$pers["user"],'*',0);
		}
		elseif($t1["type"]==2)
		{
			say_to_chat('s',"Вы прошли во вторую стадию турнира!",1,$pers["user"],'*',0);
			sql("UPDATE `users` SET chp=hp,cma=ma WHERE `uid`='".$pers["uid"]."' ");
			sql("UPDATE p_auras SET esttime=0 WHERE uid=".$pers["uid"]." and special>=3 and special<=5 and esttime>".tme());
			$pers["chp"]=$pers["hp"];
			$pers["cma"]=$pers["ma"];
		}
		elseif($t1["type"]==3)
		{
			set_vars("tour=0,coins=coins+10,exp=exp+10000,money=money+100",$pers["uid"]);
			say_to_chat('s',"Вы выиграли турнир!",1,$pers["user"],'*',0);
			sql("UPDATE quest SET finished=1,time=".tme()." WHERE id = 4");
		}
	}
	####### Турниры кончились
	say_to_chat('s',"<b>Поединок завершен. ".$win."</b> Нанесено урона: <b>".$pers["fexp"]."</b> , получено <font class=hp>боевого опыта: <b>".$pers["exp_chat"]."</b></font>. Убийства людей: <b>".$pers["kills"]."</b> <a href=\"fight.php?id=".$pers["cfight"]."\" target=_blank class=timef>Лог боя</a>.",1,$pers["user"],'*',0);
	if ($pers["kills"]>0)
	 {
		$pers["coins"]+=$pers["kills"];
		say_to_chat('s',"<i><b>+".$pers["kills"]." пергамент.</b></i>",1,$pers["user"],'*',0);
	 }
	if ($pers["gain_time"]>(tme()-1200))
	 {
	 	 $curstate = 2;
		 if ($pers["f_turn"]!=1) set_vars("gain_time=0",$pers["uid"]);
	 }
	if ($pers["f_turn"]!=1)
		set_vars("tour=0",$pers["uid"]);
	sql ("UPDATE `users` SET `curstate`=".$curstate." ,`cfight`=0 , `chp`=`chp`+2 , `od_b`=0 ,`fexp`=0 ,`exp_in_f`=0,f_turn=0,exp_chat=0,apps_id=0,kills=0,coins=coins+".$pers["kills"].",lb_attack=".$pers["lb_attack"]." WHERE `uid`='".$pers["uid"]."' ");
	$pers["cfight"]=0;
	$pers["curstate"]=$curstate;
	$pers["chp"]+=2;
	$pers["fexp"]=0;
	$pers["exp_in_f"]=0;
	$pers["f_turn"]=0;
	$pers["od_b"]=0;
	$pers["kills"]=0;

	if ($options[7]<>"no")
		echo "<script>top.flog_unset();</script>";
	echo "<script>top.flog_clear();</script>";

	sql ("UPDATE `u_blasts` SET cur_turn_colldown=0 WHERE uidp=".$pers["uid"]);
	sql ("UPDATE `u_auras` SET cur_turn_colldown=0 WHERE uidp=".$pers["uid"]);
	sql ("UPDATE `p_auras` SET turn_esttime=0 WHERE uid=".$pers["uid"]);

	$tmp = sqlr("SELECT esttime FROM p_auras
	WHERE uid=".$pers["uid"]." and special=16 and esttime>".tme());

	$_REGEN = mtrunc($tmp - tme());
	if($_REGEN || ($GOOD_DAY&GD_HUMANHEAL))
	{
		//sql("UPDATE `users` SET chp=hp,cma=ma WHERE `uid`='".$pers["uid"]."' ");
		sql("UPDATE p_auras SET esttime=0 WHERE uid=".$pers["uid"]." and special>=3 and special<=5 and esttime>".tme());
		/*$pers["chp"]=$pers["hp"];
		$pers["cma"]=$pers["ma"];*/
	}
}
return $pers;
}

function name_of_skill ($skill)
{
if ($skill=='ma') return "Запас маны";
elseif ($skill=='hp') return "Запас жизни";
elseif ($skill=='cma') return "Мана";
elseif ($skill=='chp') return "Жизнь";
elseif ($skill=='kb') return "Класс брони";
elseif ($skill=='mf1') return "Сокрушение";
elseif ($skill=='mf2') return "Уловка";
elseif ($skill=='mf3') return "Точность";
elseif ($skill=='mf4') return "Стойкость";
elseif ($skill=='mf5') return "Ярость";
elseif ($skill=='udmin') return "Минимальный удар";
elseif ($skill=='udmax') return "Максимальный удар";
elseif ($skill=='rank_i') return "Ранк";

if ($skill=='stats')
{
	$r = array ("Сила","Реакция","Удача","Здоровье","Интеллект","Сила Воли");
	return $r;
}
if ($skill=='skillsb')
{
	$r = array ("Очки действия","Колкий удар","Владение ножами","Владение щитами","Владение мечами","Владение топорами","Владение булавами","Чтение книг","Усиление магии","Сопротивление Магии","Сопротивление Физическим повреждениям","Сопротивление Отравам","Сопротивление Электричеству","Сопротивление Огню","Сопротивление Холоду");
	return $r;
}
if ($skill=='skillsm')
{
	$r = array ("Атлетизм","Эрудиция","Тяжеловес","Скорость","Обаяние","Регенерация жизни","Регенерация маны");
	return $r;
}
if ($skill=='skillsp')
{
	$r = array ("Целитель","Темное искусство","Удар в спину","Воровство","Кузнец","Рыбак","Шахтер","Ориентирование на местности","Экономист","Охотник","Алхимик","Добыча камней","Дровосек","Выделка кожи");
	return $r;
}
$r = array ("Сила","Реакция","Удача","Здоровье","Интеллект","Сила Воли");
$num = 0;
if ($skill=='s1')$num=1;
if ($skill=='s2')$num=2;
if ($skill=='s3')$num=3;
if ($skill=='s4')$num=4;
if ($skill=='s5')$num=5;
if ($skill=='s6')$num=6;
if ($num<>0) return $r[$num-1];
$r = array ("Атлетизм","Эрудиция","Тяжеловес","Скорость","Обаяние","Регенерация жизни","Регенерация маны");
if (substr_count($skill,"sm"))
return $r[str_replace("sm","",$skill)-1];
	$r = array ("Очки действия","Колкий удар","Владение ножами","Владение щитами","Владение мечами","Владение топорами","Владение булавами","Чтение книг","Усиление магии","Сопротивление Магии","Сопротивление Физическим повреждениям","Сопротивление Отравам","Сопротивление Электричеству","Сопротивление Огню","Сопротивление Холоду");
if (substr_count($skill,"sb"))
return $r[str_replace("sb","",$skill)-1];
$r = array ("Целитель","Темное искусство","Удар в спину","Воровство","Кузнец","Рыбак","Шахтер","Ориентирование на местности","Экономист","Охотник","Алхимик","Добыча камней","Дровосек","Выделка кожи");
if (substr_count($skill,"sp"))
return $r[str_replace("sp","",$skill)-1];
$r = array ("Вертлявость","Бронебойность","Толстая кожа","Расчётливость","Быстрота","Любовник","Пиротехник","Электрик");
if (substr_count($skill,"a") and $skill<>'ma' and $skill<>'udmax' and $skill<>'сma')
return $r[str_replace("a","",$skill)-1];

$r = array ("Религия","Некромантия","Стихийная магия","Магия порядка","Вызовы существ");
$num = 0;
if ($skill=='m1')$num=1;
if ($skill=='m2')$num=2;
if ($skill=='m3')$num=3;
if ($skill=='m4')$num=4;
if ($skill=='m5')$num=5;
if ($num<>0) return $r[$num-1];

if ($skill=='level') return "Уровень";
if ($skill=='colldown') return "Перезарядка(сек)";
if ($skill=='turn_colldown') return "Перезарядка(ходы)";
if ($skill=='esttime') return "Время действия";
if ($skill=='manacost') return "Стоимость маны";
if ($skill=='targets') return "Кол-во целей";
return $skill;
}

function _StateByIndex($a)
{
	if ($a=='g') return 'Глава клана';
	if ($a=='z') return 'Заместитель главы';
	if ($a=='c') return 'Казначей';
	if ($a=='k') return 'Отдел кадров';
	if ($a=='b') return 'Боевой отдел';
	if ($a=='p') return 'Производственный отдел';
	return 'Член клана';
}

function aq($arr)
{
  $pconnect = sqla("SELECT * FROM `users` WHERE `uid`='".$arr["uid"]."'");
  GLOBAL $resault_aq;
  $res = "";
  foreach($pconnect as $key => $value)
  	if ($pconnect[$key]<>$arr[$key] and $key<>'user' and $key<>'smuser' and $key<>'uid' and $key<>'refr' and $key<>'cfight' and $key<>'lastom' and $key<>'pol' and !is_integer($key) and $key<>'')
  		$res .= "`".$key."`='".$arr[$key]."',";
  $res = substr($res,0,strlen($res)-1);
  $resault_aq = $res;
  return $res;
}

function tp($l) {
$l = mtrunc($l);
 $n='';
 if ((floor($l/86400))<>0) {$n = $n.(floor($l/86400))."д&nbsp;";$l=$l%86400;}
 if ((floor($l/3600))<>0) $n = $n.(floor($l/3600))."ч&nbsp;";
 if ((floor(($l%3600)/60))<>0) $n = $n.(floor(($l%3600)/60))."м&nbsp;";
 $n = $n.(($l%3600)%60)."с";
 return $n;
 }
function str_once_delete($sub,$str) {
$p = strpos (" ".$str,$sub);
if ($p>0) {
 $p--;
 $sl = strlen($sub);$sl_str = strlen($str);
 $part1 = substr ($str,0,$sl+$p);
 $part2 = substr ($str,$sl+$p,$sl_str-($sl+$p));
 $part1 = str_replace ($sub,"",$part1);
 $str = $part1.$part2;
 }
return $str;
 }
 function str_once_replace($sub,$sub_replacement,$str) {
$p = strpos (" ".$str,$sub);
if ($p>0) {
 $p--;
 $sl = strlen($sub);$sl_str = strlen($str);
 $part1 = substr ($str,0,$sl+$p);
 $part2 = substr ($str,$sl+$p,$sl_str-($sl+$p));
 $part1 = str_replace ($sub,$sub_replacement,$part1);
 $str = $part1.$part2;
 }
return $str;
 }

function say_to_chat ($whosay,$chmess,$priv,$towho,$location)
	{
	$time_to_chat = 0;
	GLOBAL $pers;
	if ($location==0 and $location<>'*') $location=$pers["location"];
	if ($time_to_chat==0 or empty($time_to_chat))
	 {
		$time_to_chat = date("H:i:s");
	 }
	 GLOBAL $last_say_to_chat;
	 if ($last_say_to_chat==0) $last_say_to_chat = time()+microtime();
	 else $last_say_to_chat+=0.1;
	 $color = '000000';
	 if ($location=='*') $color = '220000';
	if (sql ("INSERT INTO `chat` (`user`,`time2`,`message`,`private`,`towho`,`location`,`time`,`color`) VALUES ('".$whosay."',".$last_say_to_chat.",'".$chmess."','".$priv."','".$towho."','".$location."','".$time_to_chat."','".$color."')"))
	return true;
	else
	return false;
	}

function hp_ma_up($chealth,$health,$cmana,$mana,$shp,$sma,$lastom,$tire=-1,$battle=0)
 {
	GLOBAL $sphp,$spma,$hp,$ma;
	$spma = (1500-$sma*10);
	$sphp = (700-$shp*3.5);
	if ($sphp<2) $sphp=2;
	if ($spma<2) $spma=2;

	if ($chealth<0) $chealth=0;
	if ($cmana<0) $cmana=0;

	$p=mtrunc(tme() - $lastom);

	$hp=$p*$health/$sphp+$chealth;
	if ($hp>$health) $hp=$health;
	$ma=$p*$mana/$spma+$cmana;
	if ($ma>$mana) $ma=$mana;
	$hp = floor($hp);
	$ma = floor($ma);

	if(!$battle)
	{
		$battle = ',`refr`=0';
	}
	else
		$battle = '';
	$tireout = mtrunc($tire - $p/30);
	if ($tire>0)
		return "`chp` = '".$hp."',`cma` = '".$ma."',`tire`=".$tireout.",online=1,`lastom`=".tme()."".$battle;
	else
		return "`chp` = '".$hp."',`cma` = '".$ma."',online=1,`lastom`=".tme()."".$battle;
 }
function catch_user ($uid,$passwd = '',$check = 0)
{
	if(!$passwd)
		$passwd = filter($passwd);
	if(!$check)
		return sqla ("SELECT * FROM `users` WHERE `uid` = ".intval($uid));
	else
		return sqla ("SELECT * FROM `users` WHERE `uid` = ".intval($uid)." and pass='".$passwd."'");
}

function update_user($uid)
{
GLOBAL $lastom_old,$pers;
$t = tme();
if (rand(1,200)<2 and !$pers["priveleged"] and $pers["level"]>5)
	sql ("UPDATE `users` SET online=1,`refr`=0,`lastom`=".$t.",action=-1 WHERE `uid`=".$uid);
elseif($pers["cfight"]!=0 or $pers["action"]==-1)
	sql ("UPDATE `users` SET online=1,`refr`=0,`lastom`=".$t." WHERE `uid`=".$uid);
return $t;
}

function detect_user($uid,$pass,$block,$action,$waiter,$spass)
{
	//GLOBAL $memcache;
	GLOBAL $R;
	$t=time();
	GLOBAL $lastom_new;
	GLOBAL $lastom_old;
	GLOBAL $pers;

	/*$LOCK = $memcache->get('LOCK'.$uid);
	$LOCKR = $memcache->get('LOCKR'.$uid);

	## Too fast

if ($LOCK and $LOCKR and intval($LOCKR*10000)!=intval($R*10000))
{
	echo '<script type="text/javascript" src="js/newup.js?2"></script>';
	echo '<script type="text/javascript">too_fast(\'Конфликт с '.$LOCKR.'. Наш поток: '.$R.'\');</script>';
	exit;
}
	##
	*/
if($action==-1)
{
	//$memcache->set('LOCK'.$uid, 0, false, time()+20);
}
if (empty($_POST["code_img"]) and $action==-1 and $waiter<$t)
{
 echo '<LINK href=main.css rel=STYLESHEET type=text/css><center class=return_win>Извините пожалуйста, но в связи с появлением программ , позволяющих управлять персонажем без участия игрока, мы вводим защиту против этих программ.<br> Чтобы пройти тест, пожалуйста, введите цифры которые вы видите на картинке в поле для ввода, и нажмите "ОК".<br><script type="text/javascript" src="js/imgcode.js?1"></script><script> imgcode(\''.md5($lastom_new).'\') </script></center>';
 exit;
}elseif(@$_POST["code_img"] and $action==-1)
{
 if (uncrypt(md5($lastom_old))==$_POST["code_img"])
 {
 	set_vars("action=0",$uid);
 }
 else
 {
 if ($waiter<$t)
 {
 set_vars("waiter=".($t+10)."",$uid);
 echo '<script type="text/javascript" src="js/newup.js?1"></script>';
 echo "<center class=return_win>Вы ввели неверный код.<b>Защита от частого ввода кода.</b></center><hr><center id=waiter class=inv></center><script>waiter(".(10).");</script>";
 exit;
 }else{
 echo '<script type="text/javascript" src="js/newup.js?1"></script>';
 echo "<center class=return_win>Вы ввели неверный код.<b>Защита от частого ввода кода.</b></center><hr><div id=waiter class=items align=center></div><script>waiter(".($waiter-$t).");</script>";
 exit;
 }
 }
}

if (UID<>$uid or PASS<>$pass or USER=='' or SPASS<>$spass)
 {
	include ("./error.html");
	exit;
 }
if ($block<>'')
 {
	echo "<script>top.location='index.php';</script>";
	exit;
 }
}

function begin_fight($names,$namesvs,$type,$travm,$timeout,$oruj,$loc,$battle_type = 0,$closed = 0,$special = 0)
{
$closed = intval($closed);
$bots_in = 0;
$loc = intval($loc);
#Отключаем тактические бои.
$loc = 0;
if ($loc==0)
{
	$maxx=1;
	$maxy=1;
}
elseif ($loc<6)
{
	$maxx = 15;
	$maxy = 5;
}
if ($loc)$bplace = sqla("SELECT * FROM battle_places WHERE id=".$loc);
GLOBAL $k,$main_conn;

$idf = 0;
$help_param = 0;
while(($idf<11) and ($help_param<100))
{
$help_param++;
sql ("INSERT INTO `fights` (`oruj`,`travm`,`timeout`,`ltime`,`bplace`,`maxx`,`maxy`,`stones`,`closed`,`special`)
VALUES ('".$oruj."','".$travm."',".$timeout." ,".tme().",".$loc.",".$maxx.",".$maxy.",".intval($battle_type).",".$closed.",".intval($special).")" );
$idf = mysql_insert_id($main_conn);
}
$bot_id_max = $idf*100;

if ($names[strlen($names)-1]=='|') $names = substr($names,0,strlen($names)-1);
if ($namesvs[strlen($namesvs)-1]=='|') $namesvs = substr($namesvs,0,strlen($namesvs)-1);


$all = 'Бой между ';
unset ($turns);
$turns[0] = '';
unset ($exps);
$exps[0] = 0;
$n = -1;$i=0;
$PLAYERS = 0;
$tmp1 = explode("|",$names);
$T1_count = count($tmp1);
$xf=4-intval($T1_count/$maxy);
$yf=floor($maxy/2)-1;
$persons = array();
foreach ($tmp1 as $tmp) {
if($loc>0)
while (substr_count($bplace["xy"],"|".$xf."_".$yf."|") and $xf>0){
	$yf++;
	if ($yf%$maxy==0)
	{
		$yf=0;
		$xf--;
	}
}
$PLAYERS++;
$bplace["xy"] .= "|".$xf."_".$yf."|";
if (strpos(" ".$tmp,"bot=")>0)
 {
	$e = explode("=",$tmp);
	$p = sqla("SELECT * FROM `bots` WHERE `id`='".$e[1]."'");
	if (@$p["id"])
	{
	$p["rank_i"] = ($p["s1"]+$p["s2"]+$p["s3"]+$p["s4"]+$p["s5"]+$p["s6"]+$p["kb"])*0.3 + ($p["mf1"]+$p["mf2"]+$p["mf3"]+$p["mf4"])*0.03 + ($p["hp"]+$p["ma"])*0.04+($p["udmin"]+$p["udmax"])*0.3;
	$bot_id_max++;
	sql ("INSERT INTO `bots_battle` ( `user` , `level` , `sign` , `s1` , `s2` , `s3` , `s4` , `s5` , `s6` , `kb` , `mf1` , `mf2` , `mf3` , `mf4` , `mf5` , `udmin` , `udmax` , `hp` , `ma` , `chp` , `cma` , `id` , `pol` , `obr` , `wears` , `rank_i` , `cfight` , `fteam` , `xf` , `yf` , `bid`, `id_skin` , `droptype`,`dropvalue`,`dropfrequency`,`magic_resistance`,`special`)
VALUES (
'".$p["user"]."', '".$p["level"]."', 'none', '".$p["s1"]."', '".$p["s2"]."', '".$p["s3"]."', '".$p["s4"]."', '".$p["s5"]."', '".$p["s6"]."', '".$p["kb"]."', '".$p["mf1"]."', '".$p["mf2"]."', '".$p["mf3"]."', '".$p["mf4"]."', '".$p["mf5"]."', '".$p["udmin"]."', '".$p["udmax"]."', '".$p["hp"]."', '".$p["ma"]."', '".$p["hp"]."', '".$p["ma"]."', '".(-1*$bot_id_max)."' , 'male', '".$p["obr"]."', '', '".$p["rank_i"]."', '".$idf."', '1', '".$xf."', '".$yf."', '".$p["id"]."',".$p["id_skin"].",".intval($p["droptype"]).",".intval($p["dropvalue"]).",".intval($p["dropfrequency"]).",".intval($p["magic_resistance"]).",".intval($p["special"]).");");
	$bots_in = 1;
	}else
	array_splice($tmp,$i,1);
 }
else
 {
	$p = sqla("SELECT user,level,sign,rank_i,chp,hp,cma,ma,sm6,sm7,lastom,uid,invisible,tire FROM `users` WHERE `user`='".$tmp."'");
	sql ("UPDATE `users` SET `xf`=".$xf.",`yf`=".$yf.",".hp_ma_up($p["chp"],$p["hp"],$p["cma"],$p["ma"],$p["sm6"],$p["sm7"],$p["lastom"],$p["tire"],1).",`cfight`='".$idf."' ,`curstate`=4 , `refr`=1 , damage_get=chp , damage_give=0 , fteam = 1 WHERE `uid`='".$p["uid"]."'");
	$p["lib"] = $p["user"];
	if ($p["invisible"]>tme()) {$p["user"]='невидимка';$p["sign"]='none';$p["level"]='??';}
	$persons[] = $p["uid"];
 }

$all .= "<img src=images/signs/".$p['sign'].".gif><font class=bnick color=#087C20>".$p["user"]."</font>[<font class=lvl>".$p["level"]."</font>] ,";
$i++;
}

if($PLAYERS==0) return false;

$all = substr ($all,0,strlen ($all)-1);
$all .= 'и ';
$tmp2 = explode("|",$namesvs);
$i=0;
$T2_count = count($tmp2);
$xf=$maxx-(4-intval($T2_count/$maxy));
$yf=floor($maxy/2)-1;
foreach ($tmp2 as $tmp) {
if($loc>0)
while (substr_count($bplace["xy"],"|".$xf."_".$yf."|") and $xf<$maxx){
	$yf++;
	if ($yf%$maxy==0)
	{
		$yf=0;
		$xf++;
	}
}
$PLAYERS++;
$bplace["xy"] .= "|".$xf."_".$yf."|";
if (strpos(" ".$tmp,"bot=")>0)
 {
	$e = explode("=",$tmp);
	$p = sqla("SELECT * FROM `bots` WHERE `id`='".$e[1]."'");
	if (@$p["id"])
	{
	$p["rank_i"] = ($p["s1"]+$p["s2"]+$p["s3"]+$p["s4"]+$p["s5"]+$p["s6"]+$p["kb"])*0.3 + ($p["mf1"]+$p["mf2"]+$p["mf3"]+$p["mf4"])*0.03 + ($p["hp"]+$p["ma"])*0.04+($p["udmin"]+$p["udmax"])*0.3;
	$bot_id_max++;
	sql ("INSERT INTO `bots_battle` ( `user` , `level` , `sign` , `s1` , `s2` , `s3` , `s4` , `s5` , `s6` , `kb` , `mf1` , `mf2` , `mf3` , `mf4` , `mf5` , `udmin` , `udmax` , `hp` , `ma` , `chp` , `cma` , `id` , `pol` , `obr` , `wears` , `rank_i` , `cfight` , `fteam` , `xf` , `yf` , `bid`, `id_skin` , `droptype`,`dropvalue`,`dropfrequency`,`magic_resistance`,`special`)
VALUES (
'".$p["user"]."', '".$p["level"]."', 'none', '".$p["s1"]."', '".$p["s2"]."', '".$p["s3"]."', '".$p["s4"]."', '".$p["s5"]."', '".$p["s6"]."', '".$p["kb"]."', '".$p["mf1"]."', '".$p["mf2"]."', '".$p["mf3"]."', '".$p["mf4"]."', '".$p["mf5"]."', '".$p["udmin"]."', '".$p["udmax"]."', '".$p["hp"]."', '".$p["ma"]."', '".$p["hp"]."', '".$p["ma"]."', '".(-1*$bot_id_max)."' , 'male', '".$p["obr"]."', '', '".$p["rank_i"]."', '".$idf."', '2', '".$xf."', '".$yf."', '".$p["id"]."',".$p["id_skin"].",".intval($p["droptype"]).",".intval($p["dropvalue"]).",".intval($p["dropfrequency"]).",".intval($p["magic_resistance"]).",".intval($p["special"]).");");
	$bots_in = 1;
	}else
	array_splice($tmp2,$i,1);
 }
else
 {
	$p = sqla("SELECT user,level,sign,rank_i,chp,hp,cma,ma,sm6,sm7,lastom,uid,invisible,tire FROM `users` WHERE `user`='".$tmp."'");
	sql ("UPDATE `users` SET `xf`=".$xf.",`yf`=".$yf.",".hp_ma_up($p["chp"],$p["hp"],$p["cma"],$p["ma"],$p["sm6"],$p["sm7"],$p["lastom"],$p["tire"],1).",`cfight`='".$idf."' ,`curstate`=4 , `refr`=1 , damage_get=chp , damage_give=0 , fteam = 2 WHERE `uid`='".$p["uid"]."'");
	$p["lib"] = $p["user"];
	if ($p["invisible"]>tme()) {$p["user"]='невидимка';$p["sign"]='none';$p["level"]='??';}
	$persons[] = $p["uid"];
 }

$all .= "<img src=images/signs/".$p['sign'].".gif><font class=bnick color=#0052A6>".$p["user"]."</font>[<font class=lvl>".$p["level"]."</font>] ,";
$i++;
}

if($i==0) return false;

$bots_in = ($bots_in)?0:1;
$all = addslashes ( substr ($all,0,strlen ($all)-1).".(".$type.")" );

sql("UPDATE fights SET players=".$PLAYERS." , nobots=".intval($bots_in).", closed=".$closed." WHERE id=".$idf."");
add_flog($all,$idf);


$names = $tmp1;
$namesvs = $tmp2;
$query1 = '';
$query2 = '';
foreach ($names as $n)
$query1 .= "`user`='".$n."' or";
foreach ($namesvs as $n)
$query2 .= "`user`='".$n."' or";
$query1 = substr ($query1,0,strlen ($query1)-2);
$query2 = substr ($query2,0,strlen ($query2)-2);

foreach($persons as $p)
{
	sql("INSERT INTO `battle_logs` (`uid` ,`time` ,`cfight` ,`text` )
VALUES ('".$p."', '".tme()."', '".$idf."', '".$all."');");
}

return $idf;
}

function set_vars($vars,$uid){
if (!$uid) {GLOBAL $pers;$uid=$pers["uid"];}
if ($vars)
{
	sql("UPDATE users SET ".$vars." WHERE uid=".intval($uid)."");
	return mysql_affected_rows();
}
else
 return false;
}

function aura_on($aid,$pers,$persto,$get_mana = 1)
{
	$a = sqla("SELECT * FROM u_auras WHERE id=".intval($aid)."");
	if ($a and $a["manacost"]<=$pers["cma"] and $a["tlevel"]<=$pers["level"]
	and $a["ts6"]<=$pers["s6"] and $a["tm1"]<=$pers["m1"] and $a["tm2"]<=$pers["m2"] and $a["cur_colldown"]<=tme() and $a["cur_turn_colldown"]<=$pers["f_turn"])
	{
		$params = explode("@",$a["params"]);
		$nparams = '';
		foreach($params as $par)
		{
			if(!$par) continue;
			$p = explode("=",$par);
			if ($p[1][strlen($p[1])-1]=='%')
			{
			 $res = floor((intval($p[1])/100)*$persto[$p[0]]);
			 if ($res)
			 {
			  $persto[$p[0]] += $res;
			  $nparams .= $p[0].'='.$res.'@';
			 }
			}
			else
			{
			 $persto[$p[0]] += $p[1];
			 $nparams .= $p[0].'='.$p[1].'@';
			}
		}
		if ($a["special"]==1)
		{
			$silence = time() + $a["esttime"];
			if ($persto["silence"]<$silence) $persto["silence"] = $silence;
		}
		if ($a["special"]==2)
		{
			$inv = time() + $a["esttime"];
			if ($persto["invisible"]<$inv) $persto["invisible"] = $inv;
		}
		if ($persto["chp"]>$persto["hp"]) $persto["chp"]=$persto["hp"];
		if ($persto["cma"]>$persto["ma"]) $persto["cma"]=$persto["ma"];
		if ($persto["chp"]<0) $persto["chp"] = 0;
		if ($persto["cma"]<0) $persto["cma"] = 0;
		if ($pers["uid"]==$persto["uid"]) $pers = $persto;
		set_vars(aq($persto),$persto["uid"]);
		sql("INSERT INTO `p_auras`
		( `uid` , `esttime` , `turn_esttime` , `name` , `image` , `params` , `special`)
		VALUES (
		'".$persto["uid"]."', '".(time()+$a["esttime"])."', '".($persto["f_turn"]+$a["turn_esttime"])."', '".$a["name"]."', '".$a["image"]."', '".$nparams."' , ".$a["special"]."
		);
		");
		if($a["autocast"] and $pers["uid"]==$persto["uid"])
		{
			$autocast = $a["id"];
			sql("INSERT INTO `p_auras`
			( `uid` , `esttime` , `turn_esttime` , `name` , `image` , `params` , `autocast`)
			VALUES (
			'".$persto["uid"]."', '".(tme()+$a["colldown"]+5)."', '0', '".$a["name"]." [Автокаст]', '".$a["image"]."', '', ".$autocast."
			);
			");
		}
		if ($get_mana)
		{
		$pers["cma"] -= $a["manacost"];
		$pers["m".$a["type"]] += 1/($pers["m".$a["type"]]+1);
		set_vars("cma=".$pers["cma"].",m1=".$pers["m1"].",m2=".$pers["m2"],$pers["uid"]);
		if ($pers["curstate"]==4)
		 $cur_turn_colldown = ",cur_turn_colldown=turn_colldown+".$pers["f_turn"]."";
		else
		 $cur_turn_colldown = "";
		sql ("UPDATE `u_auras` SET cur_colldown=".tme()."+colldown".$cur_turn_colldown." WHERE id=".$a["id"]);
		//echo "UPDATE `u_auras` SET cur_colldown=".tme()."+colldown".$cur_turn_colldown." WHERE id=".$a["id"];
		}
	}
	return $a;
}

function aura_on2($aid,$persto,$koef=1)
{
	$a = sqla("SELECT * FROM auras WHERE id=".intval($aid)."");
	if (is_scalar($persto))
	 $persto = catch_user($persto);
	if ($a)
	{
		$params = explode("@",$a["params"]);
		$nparams = '';
		foreach($params as $par)
		{
			if(!$par) continue;
			$p = explode("=",$par);
			if ($p[1][strlen($p[1])-1]=='%')
			{
			 $res = floor((intval($p[1])/100)*$persto[$p[0]])*$koef;
			 $persto[$p[0]] += $res;
			 $nparams .= $p[0].'='.$res.'@';
			}
			else
			{
			 $res = $p[1]*$koef;
			 $persto[$p[0]] += $res;
			 $nparams .= $p[0].'='.$res.'@';
			}
		}
		if ($a["special"]==1)
		{
			$silence = time() + $a["esttime"];
			if ($persto["silence"]<$silence) $persto["silence"] = $silence;
		}
		if ($a["special"]==2)
		{
			$inv = time() + $a["esttime"];
			if ($persto["invisible"]<$inv) $persto["invisible"] = $inv;
		}
		if ($persto["chp"]>$persto["hp"]) $persto["chp"]=$persto["hp"];
		if ($persto["cma"]>$persto["ma"]) $persto["cma"]=$persto["ma"];
		if ($persto["chp"]<0) $persto["chp"] = 0;
		if ($persto["cma"]<0) $persto["cma"] = 0;
		set_vars(aq($persto),$persto["uid"]);
		sql("INSERT INTO `p_auras`
		( `uid` , `esttime` , `turn_esttime` , `name` , `image` , `params` , `special`)
		VALUES (
		'".$persto["uid"]."', '".(time()+$a["esttime"])."', '".($persto["f_turn"]+$a["turn_esttime"])."', '".$a["name"]."', '".$a["image"]."', '".$nparams."', ".$a["special"]."
		);
		");
	}
	return $a;
}

function light_aura_on($a,$uid)
{
		if(intval($uid)==0) return;
		sql("INSERT INTO `p_auras`
		( `uid` , `esttime` , `turn_esttime` , `name` , `image` , `params` , `special`)
		VALUES (
		'".intval($uid)."', '".(time()+$a["esttime"])."', '".($persto["f_turn"]+$a["turn_esttime"])."', '".$a["name"]."', '".$a["image"]."', '".$a["params"]."', ".$a["special"]."
		);
		");
}

function show_pers_in_f($_pers,$inv)
{
$s = '<table border=0 cellspacing=0 cellpadding=0><tr><td valign=top width=221 colspan=3><script>';
GLOBAL $sh,$oj,$or1,$or2,$sa,$na,$po,$pe,$br,$kam1,$kam2,$kam3,$kam4,$z1,$z2,$z3,$ko1,$ko2,$pers;
if ($_pers["uid"]<>UID)
{
	$perst = $pers;
	$pers = $_pers;
	include('inc/inc/p_clothes.php');
	$pers = $perst;
	unset($perst);
}
if ($_pers["invisible"]>tme() and $_pers["uid"]<>$_COOKIE["uid"])
{
	$wears = array();
for ($i=0;$i<18;$i++)
 {
	$m = array();
	$m["image"]='slots/pob'.($i+1);
	$m["id"]="0";
	$wears[$i]=$m;
 }

$sh = $wears[0];
$na = $wears[8];
$oj = $wears[1];
$pe = $wears[9];
$or1 = $wears[2];
$or2 = $wears[10];
$po = $wears[3];
$z1 = $wears[4];
$z2 = $wears[5];
$z3 = $wears[6];
$sa = $wears[7];
$ko1 = $wears[11];
$ko2 = $wears[12];
$br = $wears[13];
$kam1 = $wears[14];
$kam2 = $wears[15];
$kam3 = $wears[16];
$kam4 = $wears[17];
	$_pers["obr"]='invisible';
	$_pers["user"]='<i>невидимка</i>';
	$_pers["sign"]='none';
	$_pers["level"]='??';
	$_pers["aura"]='';
	$_pers["s1"]='??';
	$_pers["s2"]='??';
	$_pers["s3"]='??';
	$_pers["s4"]='??';
	$_pers["s5"]='??';
	$_pers["s6"]='??';
	$_pers["kb"]='??';
	$_pers["mf1"]='??';
	$_pers["mf2"]='??';
	$_pers["mf3"]='??';
	$_pers["mf4"]='??';
	$_pers["mf5"]='??';
	$_pers["hp"]='1';
	$_pers["chp"]='1';
	$_pers["ma"]='1';
	$_pers["cma"]='1';
}
$s .= "InFight=1;";
$s .= "show_pers_new('".$sh["image"]."','".$sh["id"]."','".$oj["image"]."','".$oj["id"]."','".$or1["image"]."','".$or1["id"]."','".$po["image"]."','".$po["id"]."','".$z1["image"]."','".$z1["id"]."','".$z2["image"]."','".$z2["id"]."','".$z3["image"]."','".$z3["id"]."','".$sa["image"]."','".$sa["id"]."','".$na["image"]."','".$na["id"]."','".$pe["image"]."','".$pe["id"]."','".$or2["image"]."','".$or2["id"]."','".$ko1["image"]."','".$ko1["id"]."','".$ko2["image"]."','".$ko2["id"]."','".$br["image"]."','".$br["id"]."','".$_pers["pol"]."_".$_pers["obr"]."',".$inv.",'".$_pers["sign"]."','".$_pers["user"]."','".$_pers["level"]."','".$_pers["chp"]."','".$_pers["hp"]."','".$_pers["cma"]."','".$_pers["ma"]."',".intval($_pers["tire"]).",'".$kam1["image"]."','".$kam2["image"]."','".$kam3["image"]."','".$kam4["image"]."','".$kam1["id"]."','".$kam2["id"]."','".$kam3["id"]."','".$kam4["id"]."');";
$s .= '</script></td></tr><tr><td>';

if ($_pers["invisible"]<tme() or $pers["uid"]==$_pers["uid"])
{
	if ($_pers["uid"])
		//$s .= "<div id=prs".$_pers["uid"]." class=aurasc></div>";
		$s .= '<br><script>document.write(sbox2b(1,1));</script><div id=prs'.$_pers["uid"].' class=aurasc style="text-align:center;"></div><script>document.write(sbox2e());</script>';
$s.= "<table border=0 cellspacing=0 cellpadding=0 width=100%><tr><td valign=top>";
$r = all_params();
$r[12] = 'rank_i';
for ($i=0;$i<13;$i++)
{
	//if ($_pers[$r[$i]]==0) continue;
	if($r[$i][0]=='s')
	{
		$td_class = 'user';
		$img = '<img src="images/DS/stats_s'.$r[$i][1].'.png">';
	}
	else
	{
		$td_class = 'mf';
		$img = '';
	}
	$s .= '<tr>';
	$s .= '<td class='.$td_class.' width=150 nowrap>'.$img.name_of_skill($r[$i]);
	$s .= '</td>';
	if ($i<6)
	{
		if ($_pers["uid"]==UID || $pers[$r[$i]]==$_pers[$r[$i]])
			$s .= '<td class=user align=right>'.$_pers[$r[$i]].'</td>';
		elseif($pers[$r[$i]]>$_pers[$r[$i]])
			$s .= '<td class=user align=right><b style="color:#990000">'.$_pers[$r[$i]].'</b></td>';
		else
			$s .= '<td class=user align=right><b style="color:#009900">'.$_pers[$r[$i]].'</b></td>';
	}
	elseif($i == 6 or $i==12)
	{
		if ($_pers["uid"]==UID || $pers[$r[$i]]==$_pers[$r[$i]])
			$s .= '<td class=mfb align=right><b>'.$_pers[$r[$i]].'</b></td>';
		elseif($pers[$r[$i]]>$_pers[$r[$i]])
			$s .= '<td class=mfb align=right><b style="color:#990000">'.$_pers[$r[$i]].'</b></td>';
		else
			$s .= '<td class=mfb align=right><b style="color:#009900">'.$_pers[$r[$i]].'</b></td>';
	}
	else
	{
		if ($_pers["uid"]==UID || $pers[$r[$i]]==$_pers[$r[$i]])
			$s .= '<td class=mfb align=right><b>'.$_pers[$r[$i]].'%</b></td>';
		elseif($pers[$r[$i]]>$_pers[$r[$i]])
			$s .= '<td class=mfb align=right><b style="color:#990000">'.$_pers[$r[$i]].'%</b></td>';
		else
			$s .= '<td class=mfb align=right><b style="color:#009900">'.$_pers[$r[$i]].'%</b></td>';
	}
	$s .= '</tr>';
}
$s .= '</table>';
$s .= '</td></tr></table>';

if ($_pers["uid"])
{
$as = sql("SELECT * FROM p_auras WHERE uid=".$_pers["uid"]."");
$txt = '';
while($a = mysql_fetch_array($as))
{
	$txt .= $a["image"].'#<b>'.$a["name"].'</b>@';
	$txt .= 'Осталось <i class=timef>'.tp($a["esttime"]-time()).'</i>';
	$params = explode("@",$a["params"]);
		foreach($params as $par)
		{
			$p = explode("=",$par);
			$perc = '';
			if (substr($p[0],0,2)=='mf') $perc = '%';
			if ($p[1] and $p[0]<>'cma' and $p[0]<>'chp')
			$txt .= '@'.name_of_skill($p[0]).':<b>'.plus_param($p[1]).$perc.'</b>';
		}
	$txt .= '|';
}
$s .= "<script>view_auras('".$txt."','prs".$_pers["uid"]."');</script>";
}
}else
{
	$s .= '</td></tr></table>';
}
return $s;
}

function build_go_string($locid,$time)
{
	$str = md5(strtoupper($time.$locid.count($locid)));
	$str = "onclick=\"top.goloc('".$locid."','".$str."')\"";
	return $str;
}

function sqla($q)
{
	return mysql_fetch_array(sql($q));
}

function sqlr($q,$count=0)
{
	if (empty($count)) return mysql_result(sql($q),0);
	else
	return mysql_result(sql($q),$count);
}

function sql($q)
{
	GLOBAL $sql_queries_counter,$sql_queries_timer,$sql_longest_query_t,$sql_longest_query,$sql_all,$_ECHO_OFF;
	$t = time()+round(microtime(),3);
	$r = mysql_query($q);
	$t = time()+round(microtime(),3) - $t;
	//if($t>0.2)
	//	say_to_chat ("a",'['.str_replace("'","",$q).'] Время работы: '.$t.'',1,'sL','*');
	$error = mysql_error();
	if ($error and $_COOKIE["uid"]==5 and !$_ECHO_OFF)echo "<font class=hp><b> ОШИБКА MySQL!!! : ".$error." <i>[".$q."]</i></b></font>";
	/*elseif ($error)
	 {
		$sql_errors = mysql_fetch_array(mysql_query("SELECT sql_errors FROM configs"));
		if (!substr_count($sql_errors[0],"<".$error." [".$q."]>")) mysql_query("UPDATE configs SET sql_errors='".$sql_errors[0]."<".addslashes($error)." [".addslashes($q)."]>"."'");
	 }*/
	$sql_queries_counter++;
	$sql_queries_timer+=abs($t);
	if (abs($t)>$sql_longest_query_t)
	{
		$sql_longest_query_t=abs($t);
		$sql_longest_query = $q." &nbsp;<i>".$_SERVER['PHP_SELF']."</i>";
	}
		$sql_all[] = $q.";<b class=red>".$error."</b>";
	return $r;
}

function mtrunc($q)
{
	if ($q<0) $q=0;
	return $q;
}
function show_ip()
{
if($ip_address=getenv("HTTP_CLIENT_IP"));
elseif($ip_address=getenv("HTTP_X_FORWARDED_FOR"));
else $ip_address=getenv("REMOTE_ADDR");
return $ip_address;
}
function sqr($x)
{
	return $x*$x;
}
function mod_st_start($name,$string)
{
GLOBAL $module_statisticks,$module_statisticks_counter,$sql_queries_counter,$sql_queries_timer;
$i = $module_statisticks_counter+1;
$module_statisticks[$i]["name"]=$name;
$module_statisticks[$i]["strings"]=$string;
$module_statisticks[$i]["sql_queries"]=$sql_queries_counter;
$module_statisticks[$i]["sql_time"]=$sql_queries_timer;
$module_statisticks[$i]["all_exec_time"]=time()+microtime();
}
function mod_st_fin()
{
GLOBAL $module_statisticks,$module_statisticks_counter,$sql_queries_counter,$sql_queries_timer;
$i = $module_statisticks_counter+1;
$module_statisticks[$i]["sql_queries"]=$sql_queries_counter-
$module_statisticks[$i]["sql_queries"];
$module_statisticks[$i]["sql_time"]=$sql_queries_timer-
$module_statisticks[$i]["sql_time"];
$module_statisticks[$i]["all_exec_time"]=time()+microtime()-
$module_statisticks[$i]["all_exec_time"];
$module_statisticks_counter++;
}

function insert_wp($id,$uid,$durability = -1,$weared = 0 ,$user = '')
{
	$uid = intval($uid);
	if(is_scalar($id))
		$v = sqla("SELECT * FROM weapons WHERE id='".$id."'");
	else
		$v = $id;
	$id = $v["id"];
	if ($durability==-1)$durability=$v["max_durability"];
	if (empty($v["id"])) return 0;
GLOBAL $main_conn,$pers;
	$user = sqlr("SELECT user FROM users WHERE uid=".$uid);
	$_colls = '';
	$_params = '';
	$r = all_params();
	foreach ($r as $param)
	{
		if($v[$param]!=0)
		{
			$_colls .= ',`'.$param.'`';
			$_params .= ",'".$v[$param]."'";
		}
		$param = 't'.$param;
		if($v[$param]!=0)
		{
			$_colls .= ',`'.$param.'`';
			$_params .= ",'".$v[$param]."'";
		}
	}
	sql("INSERT INTO `wp` ( `id` , `uidp` , `weared` ,`id_in_w`, `price` , `dprice` , `image` , `index` , `type` , `stype` , `name` , `describe` , `weight` , `where_buy` , `max_durability` , `durability` , `present` , `clan_sign` , `clan_name` ,`radius` , `slots` ,`arrows` ,`arrows_max` ,`arrow_name` , `arrow_price` , `tlevel` ,`p_type` , `user`, `material_show`, `material` ".$_colls.")
VALUES (0, '".$uid."', '".$weared."','".$id."','".$v["price"]."', '".$v["dprice"]."', '".$v["image"]."', '".$v["index"]."', '".$v["type"]."', '".$v["stype"]."', '".$v["name"]."', '".$v["describe"]."', '".$v["weight"]."', '".$v["where_buy"]."', '".$v["max_durability"]."', '".$durability."', '".$v["present"]."', '', '', '".$v["radius"]."', '".$v["slots"]."', '".$v["arrows"]."', '".$v["arrows_max"]."', '".$v["arrow_name"]."', '".$v["arrow_price"]."', '".$v["tlevel"]."','".$v["p_type"]."', '".$user."', '".$v["material_show"]."', '".$v["material"]."' ".$_params.");");

	return mysql_insert_id($main_conn);
}

function insert_wp_new($uid,$teta,$user='')
{
	$v = sqla("SELECT * FROM wp WHERE ".$teta." LIMIT 1;");
	if (!$v["id"]) return false;
		$_colls = '';
	$_params = '';
	$r = all_params();
	foreach ($r as $param)
	{
		if($v[$param]!=0)
		{
			$_colls .= ',`'.$param.'`';
			$_params .= ",'".$v[$param]."'";
		}
		$param = 't'.$param;
		if($v[$param]!=0)
		{
			$_colls .= ',`'.$param.'`';
			$_params .= ",'".$v[$param]."'";
		}
	}
	GLOBAL $main_conn;
	$user = sqlr("SELECT user FROM users WHERE uid=".$uid);
	sql("INSERT INTO `wp` ( `id` , `uidp` , `weared` ,`id_in_w`, `price` , `dprice` , `image` , `index` , `type` , `stype` , `name` , `describe` , `weight` , `where_buy` , `max_durability` , `durability` , `present` , `clan_sign` , `clan_name` ,`radius` , `slots` ,`arrows` ,`arrows_max` ,`arrow_name` , `arrow_price` , `tlevel` ,`p_type`,`timeout` , `user`,`material_show`,`material` ".$_colls.")
	VALUES (0, '".$uid."', 0,'".$v["id_in_w"]."','".$v["price"]."', '".$v["dprice"]."', '".$v["image"]."', '".$v["index"]."', '".$v["type"]."', '".$v["stype"]."', '".$v["name"]."', '".$v["describe"]."', '".$v["weight"]."', '".$v["where_buy"]."', '".$v["max_durability"]."', '".$v["durability"]."', '".$v["present"]."', '', '', '".$v["radius"]."', '".$v["slots"]."', '".$v["arrows"]."', '".$v["arrows_max"]."', '".$v["arrow_name"]."', '".$v["arrow_price"]."', '".$v["tlevel"]."','".$v["p_type"]."','".$v["timeout"]."', '".$v["user"]."', '".$v["material_show"]."', '".$v["material"]."' ".$_params.");");

	$v["id"] = mysql_insert_id($main_conn);
	$v["uidp"] = $uid;
	return $v;
}

function insert_blast($id,$uid)
{
	$z = sqla ("SELECT * FROM blasts WHERE `id`=".intval($id));
	if (!$z) return false;
	$q = 'INSERT INTO `u_blasts` ( `id` , `id_in_w`';
	$v = ")VALUES ('0', '".$z["id"]."'";
	foreach($z as $key=>$value)
	{
		if (is_string($key) and $key<>"id" and $key<>"learnall")
		{
		$q .= ',`'.$key.'`';
		$v .= ",'".$value."'";
		}
	}
	$q .= ',`uidp`';
	$v .= ','.intval($uid).');';
	sql($q.$v);
	GLOBAL $main_conn;
	return mysql_insert_id($main_conn);
}

function insert_aura($id,$uid)
{
	$z = sqla ("SELECT * FROM auras WHERE `id`=".intval($id));
	if (!$z) return false;
	$q = 'INSERT INTO `u_auras` ( `id` , `id_in_w`';
	$v = ")VALUES ('0', '".$z["id"]."'";
	foreach($z as $key=>$value)
	{
		if (is_string($key) and $key<>"id" and $key<>"learnall")
		{
		$q .= ',`'.$key.'`';
		$v .= ",'".$value."'";
		}
	}
	$q .= ',`uidp`';
	$v .= ','.intval($uid).');';
	sql($q.$v);
	GLOBAL $main_conn;
	return mysql_insert_id($main_conn);
}

function remove_weapon($id,$v)
 {
	GLOBAL $pers;
	if (!is_array($v)) $v = sqla ("SELECT * FROM `wp` WHERE `id` = '".$id."' and weared=1 and uidp=".$pers["uid"]."");
	if ($v)
	{
	$r = all_params();
	foreach ($r as $a)
	if ($v[$a]) $pers[$a] -= $v[$a];
	$pers["hp"]-=5*$v["s4"];
	$pers["ma"]-=9*$v["s6"];
	if ($aq=aq($pers))
	sql ("UPDATE `users` SET ".$aq." WHERE `uid` = ".UID." ;");
	sql ("UPDATE wp SET weared=0 WHERE id=".$v["id"]."");
	}
 }

function remove_all_weapons ()
 {
	GLOBAL $pers;
	$res = sql ("SELECT * FROM `wp` WHERE `weared` = 1 and uidp=".$pers["uid"]."");
	while($v = mysql_fetch_array ($res))
	{
	$r = all_params();
	foreach ($r as $a)
	if ($v[$a]) $pers[$a] -= $v[$a];
	$pers["hp"]-=5*$v["s4"];
	$pers["ma"]-=9*$v["s6"];
	}
	if ($aq=aq($pers))
	sql ("UPDATE `users` SET ".$aq." WHERE `uid` = ".$pers["uid"]." ;");
	sql	("UPDATE wp SET weared=0 WHERE uidp=".$pers["uid"]."");
 }

function remove_all_auras()
{
	GLOBAL $pers;
$as = sql("SELECT * FROM p_auras
 WHERE uid=".$pers["uid"]." and esttime<=".tme()." and (turn_esttime<=".$pers["f_turn"].")");

$count = 0;
//$autoAS = Array();
$modified = 0;
while($a = mysql_fetch_array($as))
{
		$count++;
		$params = explode("@",$a["params"]);
		foreach($params as $par)
		{
			$p = explode("=",$par);
			if ($p[0]<>'cma' and $p[0]<>'chp' and intval($p[1])!=0)
			{
				$pers[$p[0]] -= $p[1];
				$modified = 1;
			}
		}
		if ($a["special"]==14)
		{
			$a["image"] = 68;
			$a["params"] = '';
			$a["esttime"] = 18000 - (tme() - $a["esttime"]);
			$a["name"] = 'Отдышка после шахты';
			$a["special"] = 15;
			light_aura_on($a,$pers["uid"]);
		}
	/*	if($a["autocast"])
			$autoAS[] = $a["autocast"];*/
}
		if ($modified)
		{
			if(set_vars(aq($pers),$pers["uid"]))
				sql("DELETE FROM p_auras WHERE uid=".$pers["uid"]." and esttime<=".tme()." and (turn_esttime<=".$pers["f_turn"].") and autocast=0");
		}
		elseif($count)
			sql("DELETE FROM p_auras WHERE uid=".$pers["uid"]." and esttime<=".tme()." and (turn_esttime<=".$pers["f_turn"].") and autocast=0");
/*
if(!$pers["cfight"])
	foreach($autoAS as $a)
	{
		aura_on($a,$pers,$pers);
		sql("DELETE FROM p_auras WHERE uid=".$pers["uid"]." and autocast=".intval($a));
	}*/
}

function dress_weapon ($id_of_weapon,$checker) {
GLOBAL $pers;
$i = 5;
$v = sqla("SELECT * FROM `wp` WHERE `id`= ".$id_of_weapon." and uidp=".$pers["uid"]." and weared=0");
if (@$v["id"])
{
$z=1;
if ($pers["level"]<$v["tlevel"]) $z=0;
if (!$checker)
foreach ($v as $key => $value)
{
	if ($key[0]=='t' and $key<>'timeout')
	 if ($pers[substr($key,1,strlen($key)-1)]<$value and $value>0) $z =0;
	if ($z==0) break;
}

if ($z==1) {
	$r = all_params();
	foreach ($r as $a)
	if ($v[$a]) $pers[$a] += $v[$a];
	$pers["hp"]+=5*$v["s4"];
	$pers["ma"]+=9*$v["s6"];
//Снимаем то что было
$z=0;
if ($v["type"]=='orujie')
{
$tmp = sqlr("SELECT COUNT(id) FROM wp WHERE uidp=".$pers["uid"]."
and weared=1 and type='orujie'");
if ($tmp>=2)
{
if ($v["stype"]=='noji' or $v["stype"]=='shit')
{
$w_for_remove = sqla("SELECT * FROM wp WHERE uidp=".$pers["uid"]."
and weared=1 and type='orujie' and (stype='noji' or stype='shit')");
if (@$w_for_remove["id"])
remove_weapon ($w_for_remove["id"],$w_for_remove);
}else
{
$w_for_remove = sqla("SELECT * FROM wp WHERE uidp=".$pers["uid"]."
and weared=1 and type='orujie' and stype<>'noji' and stype<>'shit'");
if (@$w_for_remove["id"])
remove_weapon ($w_for_remove["id"],$w_for_remove);
}
}elseif ($tmp==1)
{
	$w_for_remove = sqla("SELECT * FROM wp WHERE uidp=".$pers["uid"]."
and weared=1 and type='orujie'");
	if ($v["stype"]<>'noji' and $v["stype"]<>'shit' and $w_for_remove["stype"]<>'noji' and $w_for_remove["stype"]<>'shit') remove_weapon ($w_for_remove["id"],$w_for_remove);
}
}elseif ($v["type"]=='kolco')
{
	$tmp = sqlr("SELECT COUNT(id) FROM wp WHERE uidp=".$pers["uid"]."
	and weared=1 and type='kolco'");
	if ($tmp>=2)
	{
	$w_for_remove = sqla("SELECT * FROM wp WHERE uidp=".$pers["uid"]."
	and weared=1 and type='kolco'");
	if (@$w_for_remove["id"])
	remove_weapon ($w_for_remove["id"],$w_for_remove);
	}
}elseif ($v["type"]=='kam')
{
	$tmp = sqlr("SELECT COUNT(id) FROM wp WHERE uidp=".$pers["uid"]."
	and weared=1 and type='kam'");
	if ($tmp==4)
	{
	$w_for_remove = sqla("SELECT * FROM wp WHERE uidp=".$pers["uid"]."
	and weared=1 and type='kam'");
	if (@$w_for_remove["id"])
	remove_weapon ($w_for_remove["id"],$w_for_remove);
	}
}else
	{
	$w_for_remove = sqla("SELECT * FROM wp WHERE uidp=".$pers["uid"]."
	and weared=1 and type='".$v["type"]."'");
	if (@$w_for_remove["id"])
	remove_weapon ($w_for_remove["id"],$w_for_remove);
	}
sql ("UPDATE wp SET weared=1 WHERE id=".$v["id"]."");
if ($aq=aq($pers))
	sql ("UPDATE `users` SET ".$aq." WHERE `uid` = ".$pers["uid"]." ;");
}
}
}

function add_flog ($txt,$cfight)
{
GLOBAL $battle_log;
	if (empty($cfight))
{
	GLOBAL $pers;
	$cfight=$pers["cfight"];
}
if ($txt[strlen($txt)-1]=='%') $txt = substr($txt,0,strlen($txt)-1);
	sql("INSERT INTO `fight_log` ( `time` , `log` , `cfight` , `turn` )
VALUES (
'".date("H:i")."', '".addslashes($txt)."', '".$cfight."', '".round((time()+microtime()),2)."'
);");
$txt = "<font class=timef>".date("H:i")."</font> ".$txt;
$txt = str_replace("%","<br><font class=timef>".date("H:i")."</font> ",$txt);
$battle_log .= $txt;
sql ("UPDATE `fights`
SET `all`=CONCAT('".addslashes($txt).";',`all`) , `ltime`='".time()."'
WHERE `id`='".$cfight."' ;");
}

function signum($x)
{
	if ($x>0) return 1;
	if ($x==0) return 0;
	if ($x<0) return -1;
}

function uncrypt($value)
{
	$a=0;
	$key = 754;
	for($i=0;$i<strlen($value);$i++)
	$a += (ord($value[$i])<<(($i+23)>>1)<<1)^($key^9+$i);
	$a %= 10000;
	$a = abs($a);
	if ($a<1000) $a+=2343;
	return $a;
}

function plus_param($param)
{
	if ($param>0) return "+".$param;
	elseif ($param<0) return "-".abs($param);
	else return "0";
}

function all_params()
{
	$r = array ();
	for ($i=1;$i<7;$i++) $r[]='s'.$i;
	$r[]='kb';
	for ($i=1;$i<6;$i++) $r[]='mf'.$i;
	$r[]='hp';
	$r[]='ma';
	$r[]='udmin';
	$r[]='udmax';
	for ($i=1;$i<15;$i++)
{
	$r[]='sp'.$i;
}
	for ($i=1;$i<15;$i++)
{
	$r[]='sb'.$i;
}
	for ($i=1;$i<8;$i++)
{
	$r[]='sm'.$i;
}
	for ($i=1;$i<9;$i++)
{
	$r[]='a'.$i;
}
	for ($i=1;$i<6;$i++)
{
	$r[]='m'.$i;
}
return $r;
}

function experience($damage,$yourlvl,$vslvl,$notnpc,$rank)
{
			if ($notnpc)
				$koeff = 1.9;
			else
				$koeff = 0.6*sqrt(sqrt(($rank+1)/3));
			if ($yourlvl<=2)
				$koeff += 1.7;
			if ($yourlvl<5) $koef += 0.7;
			if ($notnpc or $yourlvl<4) $koeff *= sqrt(sqrt($vslvl+1.1));
			if ($notnpc)
			{
			if ($yourlvl>=($vslvl+3)) $koeff *= 0.2*(($vslvl+1)/($yourlvl+1));
			if ($yourlvl==($vslvl+2)) $koeff *= 0.5;
			if ($yourlvl==($vslvl+1)) $koeff *= 0.7;
			if ($yourlvl==($vslvl))   $koeff *= 1;
			if ($yourlvl==($vslvl-1)) $koeff *= 1.4;
			if ($yourlvl==($vslvl-2)) $koeff *= 1.8;
			if ($yourlvl==($vslvl-3)) $koeff *= 2.6;
			if ($yourlvl<($vslvl-3))  $koeff *= 3.0*(($vslvl+1)/($yourlvl+5));
			}else
			{
			if ($yourlvl>=($vslvl+3)) $koeff *= 0.2*(($vslvl+1)/($yourlvl+1));
			if ($yourlvl==($vslvl+2)) $koeff *= 0.5;
			if ($yourlvl==($vslvl+1)) $koeff *= 0.7;
			if ($yourlvl==($vslvl))   $koeff *= 1;
			if ($yourlvl==($vslvl-1)) $koeff *= 1.2;
			if ($yourlvl==($vslvl-2)) $koeff *= 1.4;
			if ($yourlvl==($vslvl-3)) $koeff *= 1.6;
			if ($yourlvl<($vslvl-3))  $koeff *= 2.0*(($vslvl+1)/($yourlvl+5));
			}
			$koeff *= mtrunc(0.9+($vslvl-$yourlvl)*0.10)+0.1;
			return floor($damage*$koeff);
}

function transfer_log($type,$uid,$user,$money1,$money2,$title,$ip1,$ip2)
{
	sql("INSERT INTO `transfer` ( `date` , `type` , `uid` , `who` , `transfer_in` , `transfer_out` , `title` , `ip1` , `ip2`)
VALUES (
'".time()."', ".$type." ,'".$uid."', '".$user."', '".$money1."', '".$money2."', '".$title."', '".$ip1."' , '".$ip2."'
);");
}

function ylov($_pers,$_persvs)
{
	$vsR = mtrunc($_persvs["s2"]*($_persvs["mf2"]/100+1));
	$yoR = mtrunc($_pers["s2"]*($_pers["mf3"]/100+1));
	$ylov = 50*mtrunc(1-$yoR/$vsR)*sqrt($vsR/4);
	$ylov *= mtrunc($_persvs["level"]-$_pers["level"])*0.20+1;
	if ($ylov>70)
	 $ylov=70;
	if ($ylov<1)
	 $ylov=0;
	return $ylov;
}

function sokr($_pers,$_persvs)
{
	$vsR = mtrunc($_persvs["s3"]*($_persvs["mf4"]/100+1));
	$yoR = mtrunc($_pers["s3"]*($_pers["mf1"]/100+1));
	$ylov = 50*mtrunc(1-$vsR/$yoR)*sqrt($yoR/4);
	$ylov *= mtrunc($_pers["level"]-$_persvs["level"])*0.20+1;
	if ($ylov>70)
	 $ylov=70;
	if ($ylov<1)
	 $ylov=0;
	return $ylov;
}

function yar($_pers,$_persvs)
{
	$yar=(3+$_pers["mf5"]/5-$_pers["mf4"]/20)/5;
	$yar *= mtrunc($_pers["level"]-$_persvs["level"])*0.20+1;
	if($yar<2)
	 $yar=2;
	if($yar>90)
	 $yar=90;
	return $yar;
}

function ydar($_pers,$_persvs)
{
	$ydar = rand($_pers["udmin"]*10,$_pers["udmax"]*10+10)/20;
	$ydar = $ydar*sqrt($ydar);
	$ydar *= mtrunc($_pers["level"]-$_persvs["level"])*0.20+1;
	$kb = mtrunc($_persvs["kb"]+$_persvs["sb11"]);
	$ydar = mtrunc($_pers["sb2"]+$_pers["s1"]+$ydar);
	if ($kb<1) $kb = 1;
	$ydar = $ydar*(pow(0.89,sqrt($kb))+0.1);
	$ydar = mtrunc(rand($ydar-3,$ydar+3));
	return floor($ydar);
}

function DecreaseDamage($pers)
{
	$kb = mtrunc($pers["kb"]+$pers["sb11"]);
	if ($kb<1) $kb = 1;
	return round(100-(pow(0.9,sqrt($kb))+0.1)*100);
}

function time_echo($l)
{
 $d=0;
 $h=0;
 $m=0;
 $s=0;
 if ((floor($l/86400))<>0) {$d = (floor($l/86400));$l=$l%86400;}
 if ((floor($l/3600))<>0) $h = (floor($l/3600));
 if ((floor(($l%3600)/60))<>0) $m = (floor(($l%3600)/60));
 if ((($l%3600)%60)<>0) $s = (($l%3600)%60);
 if (!$d and !$h and !$m) $r = 'только что';
 if (!$d and !$h and $m%10==1) $r = $m.' минуту назад';
 if (!$d and !$h and $m%10>1) $r = $m.' минуты назад';
 if (!$d and !$h and ($m>4 and $m<21)) $r = $m.' минут назад';
 if (!$d and $h%10==1) $r = $h.' час назад';
 if (!$d and $h%10==2) $r = $h.' часа назад';
 if (!$d and $h%10==3) $r = $h.' часа назад';
 if (!$d and $h%10==4) $r = $h.' часа назад';
 if (!$d and ($h>4 and $h<21)) $r = $h.' часов назад';
 if ($d==1) $r = 'вчера';
 if ($d==2) $r = 'позавчера';
 if ($d/7<1 and ($d==3 or $d==4)) $r = $d.' дня назад';
 if ($d/7<1 and $d>4) $r = $d.' дней назад';
 if ($d>=7 and $d<14) $r = 'неделю назад';
 if (floor($d/7)<5 and $d>=14) $r = floor($d/7).' недели назад';
 if (floor($d/7)>=5) $r = floor($d/7).' недель назад';
 return $r;
}

function HIWORD($a)
{
	return $a>>16;
}
function LOWORD($a)
{
	return ($a<<16)>>16;
}
function TOHIWORD($a)
{
	return $a<<16;
}

function EqualValueOfSkill($skill)
{
// Для статов = 1, для мф = 10, для хп = 10, для кб = 10, для маны = 12, для умений = 1, для мирных умений = 5 , для удара  = 3
	if ($skill[0]=='s' and strlen($skill)==2) return 1;
	if ($skill[0]=='m' and strlen($skill)==3) return 10;
	if ($skill=='kb') return 10;
	if ($skill=='hp') return 10;
	if ($skill=='ma') return 12;
	if ($skill[0]=='s' and ($skill[1]=='b' or $skill[1]=='m') and strlen($skill)==3) return 1;
	if ($skill[0]=='s' and $skill[1]=='p' and strlen($skill)==3) return 5;
	if ($skill=='udmin' or $skill=='udmax') return 3;
	return 0;
}

function IsWearing($v)
{
// Одеваемая ли это вещь
	if ($v["type"]=='shlem' or $v["type"]=='orujie' or $v["type"]=='kolco' or $v["type"]=='bronya' or $v["type"]=='naruchi' or $v["type"]=='perchatki' or $v["type"]=='ojerelie' or $v["type"]=='sapogi' or $v["type"]=='poyas' or $v["type"]=='kam') return 1;
	return 0;
}
function _UserByUid($uid=0)
{
	if ($uid)
		return sqlr("SELECT user FROM users WHERE uid=".intval($uid));
	else
		return false;
}
function _UidByUser($user='')
{
	if ($user)
		{
			$user = str_replace("'","",$user);
			$user = str_replace("\\","",$user);
			return sqlr("SELECT uid FROM users WHERE smuser=LOWER('".$user."')");
		}
	else
		return false;
}

function Weared_Weapons($uid = 0)
{
	if(!$uid)
	{
		GLOBAL $pers;
		$uid = $pers["uid"];
	}
	$array = sql("SELECT stype,udmin,udmax,kb FROM wp WHERE uidp=".intval($uid)." and weared=1 and type='orujie'");
	$_W["noji"] = 0;
	$_W["mech"] = 0;
	$_W["topo"] = 0;
	$_W["drob"] = 0;
	$_W["shit"] = 0;
	while($a = mysql_fetch_array($array,MYSQL_ASSOC))
	{
		$_W[$a["stype"]] += 1;
		$_W[$a["stype"]]["udmin"] = $a["udmin"];
		$_W[$a["stype"]]["udmax"] = $a["udmax"];
		$_W[$a["stype"]]["kb"] = $a["kb"];
	}
	$_W["OD"] = $_W["noji"]*1 +
				$_W["mech"]*2 +
				$_W["topo"]*3 +
				$_W["drob"]*4 +
				$_W["shit"]*1;
	return $_W;
}

function types()
{
	$r = Array();
	$r['orujie'] = 'Оружие';
	$r['shlem'] = 'Шлемы';
	$r['ojerelie'] = 'Ожерелья';
	$r['poyas'] = 'Пояса';
	$r['sapogi'] = 'Сапоги';
	$r['naruchi'] = 'Наручи';
	$r['perchatki'] = 'Перчатки';
	$r['kolco'] = 'Кольца';
	$r['bronya'] = 'Брони';
	$r['napad'] = 'Свитки нападения';
	$r['zakl'] = 'Свитки заклинаний';
	$r['teleport'] = 'Свитки телепорта';
	$r['zelie'] = 'Зелья/камни';
	$r['kam'] = 'Зелья восстановления';
	$r['potion'] = 'Зелья';
	$r['herbal'] = 'Травы';
	$r['fishing'] = 'Рыболовные снасти';
	$r['fish'] = 'Рыба';
	$r['resources'] = 'Ресурсы';
	$r['rune'] = 'Руны';
	return $r;
}

function type_names($tp)
{
	$r = types();
	return $r[$tp];
}

function kind_stat($i)
{
	if($i>5) return "Добряк";
	elseif($i>2) return "Добрый";
	elseif($i>0) return "Отзывчивый";
	elseif($i==0) return "Нейтрален";
	elseif($i>-2) return "Хитрый";
	elseif($i>-5) return "Коварный";
	elseif($i>-7) return "Алчный";
	else return "Злой";
}
?>