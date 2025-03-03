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
require_once 'db.php';
$db = getDatabaseConnection();

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

/**
 * Рассчитывает удар бота по противнику в бою
 * @param string $point Точка удара ('ug' - голова, 'ut' - грудь, 'uj' - живот, 'un' - ноги)
 * @param array $botU Данные бота (атакующего)
 * @return string|null Лог удара для вывода в бою или null при ошибке
 */
function newbot_udar($point, $botU) {
    global $persvs, $pers, $colors, $fight, $kl, $die, $PVS_NICK, $USER_NICK, $pitalsa, $yvvs, $male, $malevs, $pogib, $db;

    // Проверка входных данных
    if (empty($botU[$point]) || !$pers || !$persvs) {
        return null;
    }

    // Проверка невидимости цели
    $invvs = $persvs['invisible'] > tme();
    if ($invvs) {
        $persvs['user'] = '<i>невидимка</i>';
        $persvs['pol'] = 'female';
    }

    // Учёт брони от щитов
    $shitPlus = 0;
    if ($persvs['uid'] && $persvs['sb4']) {
        $stmt = $db->prepare("SELECT SUM(kb) FROM wp WHERE uidp = :uid AND weared = 1 AND stype = 'shit'");
        $stmt->execute([':uid' => (int)$persvs['uid']]);
        $shitPlus = ($stmt->fetchColumn() ?? 0) * $persvs['sb4'] / 33;
    }
    $persvs['kb'] += $shitPlus;

    // Форматирование ников
    $nvs = sprintf('<font class="bnick" color="%s">%s</font>[%s]',
        $colors[$persvs['fteam']],
        $invvs ? '<i>невидимка</i>' : $persvs['user'],
        $invvs ? '??' : $persvs['level']
    );
    $nyou = sprintf('<font class="bnick" color="%s">%s</font>[%s]',
        $colors[$pers['fteam']],
        $pers['user'],
        $pers['level']
    );

    // Гендерные окончания
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
    if (!$bpoint || !$ypoint) return null;

    // Тип удара
    $ud_name = match ($botU[$point]) {
        1 => 'простой ',
        2 => 'прицельный ',
        5 => 'оглушающий ',
        default => ''
    } . $ypoint;

    $fall = '';

    // Основная логика боя
    if ($persvs['chp'] > 0) {
        $kl = 1;
        $block = '';
        $blocked = false;

        // Расчёт урона
        $ydar = ydar($pers, $persvs);
        if ($botU[$point] == 2) $ydar *= 1.1; // Прицельный удар
        if ($botU[$point] == 5) $ydar *= 1.2; // Оглушающий удар

        // Проверка блока
        $kbFactor = mtrunc($persvs['kb']) + 1;
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
        $ylov = ylov($pers, $persvs);
        $sokr = sokr($pers, $persvs);
        $yar = yar($pers, $persvs);

        $persvs['is_art'] = max(1, $persvs['is_art']);
        $pers['is_art'] = max(1, $pers['is_art']);
        $ylov *= $persvs['is_art'];

        $ylov = min(70, $ylov);
        $sokr = min(70, $sokr);

        // Яростный удар
        if ($yar > rand(0, 100)) {
            $ydar *= 1.4;
            $block .= $block ? ',' : '';
            $block .= '<font color="green">нанося яростный удар</font>';
        }

        $ydar = floor($ydar);
        $ksokr = 2;
        $z = 1;

        // Формирование лога удара
        if ($blocked && $z == 1) {
            $z = 0;
            $s = "$nvs <b>заблокировал$malevs</b> <font class=\"timef\">«$ud_name»</font>";
        } elseif ($z == 1 && rand(0, 100) < ($promax ?? 0)) {
            $z = 0;
            $s = "$nyou промах";
        } elseif ($z == 1 && rand(0, 100) < $ylov) {
            $z = 0;
            $s = bit_icon('d', 16) . "$nyou $pitalsa поразить соперника, но $nvs <b>$yvvs</b> от <font class=\"timef\">«$ud_name»</font>";
        } elseif ($z == 1 && rand(0, 100) < $sokr) {
            $z = 0;
            $ydar = round($ydar * $ksokr);
            $persvs['chp'] -= $ydar;
            $pers['fexp'] += $ydar;
            $hpvs = !$invvs ? "<font class=\"hp_in_f\">[" . mtrunc($persvs['chp']) . "/{$persvs['hp']}]</font>" : '';
            $s = bit_icon('s', 16) . "$nyou $block поразил$male $nvs на <font class=\"bnick\" color=\"#CC0000\"><b>-$ydar</b></font> <font class=\"timef\">«cокрушительный $ud_name»</font>$hpvs";
        } elseif ($z == 1) {
            $z = 0;
            $persvs['chp'] -= $ydar;
            $hpvs = !$invvs ? "<font class=\"hp_in_f\">[" . mtrunc($persvs['chp']) . "/{$persvs['hp']}]</font>" : '';
            $s = bit_icon('t', 16) . "$nyou $block поразил$male $nvs на <b class=\"user\">-$ydar</b> <font class=\"timef\">«$ud_name»</font>$hpvs";
        }

        // Обновление опыта
        $pers['exp_in_f'] += experience($ydar, $pers['level'], $persvs['level'], $persvs['uid'], $persvs['rank_i']);

        // Смерть противника
        if ($persvs['chp'] <= 0 && $z != 2) {
            $persvs['chp'] = 0;
            $die = "$nvs <b>$pogib</b>.%$die";
            if ($persvs['uid']) {
                $str = '';
                require 'inc/inc/fights/travm.php';
                $die .= $str;
            }
        }

        $fall .= $s;

        // Обновление данных в базе
        if ($persvs['uid']) {
            $stmt = $db->prepare("UPDATE users SET chp = :chp WHERE uid = :uid");
            $stmt->execute([':chp' => $persvs['chp'], ':uid' => $persvs['uid']]);
        } else {
            $stmt = $db->prepare("UPDATE bots_battle SET chp = :chp WHERE id = :id");
            $stmt->execute([':chp' => $persvs['chp'], ':id' => $persvs['id']]);
        }
        $stmt = $db->prepare("UPDATE bots_battle SET exp_in_f = :exp_in_f WHERE id = :id");
        $stmt->execute([':exp_in_f' => $pers['exp_in_f'], ':id' => $pers['id']]);
    } elseif ($persvs['chp'] <= 0) {
        $fall = "$nyou сделал контрольный удар по трупу";
    }

    if ($fall) $fall .= '.  ';

    $persvs['kb'] -= $shitPlus;

    return $fall;
}

/**
 * Завершает бой и обновляет состояние персонажа
 * @param array $pers Данные персонажа
 * @return array Обновлённые данные персонажа
 */
function end_battle($pers) {
    global $GOOD_DAY, $options, $db;

    // Получаем данные боя
    $stmt = $db->prepare("SELECT * FROM fights WHERE id = :id");
    $stmt->execute([':id' => (int)$pers['cfight']]);
    $fight = $stmt->fetch();

    if ($fight['turn'] === 'finish' && $fight['type'] === 'f') {
        // Устанавливаем минимальное значение времени последней атаки
        if ($pers['lb_attack'] - 40 < tme()) {
            $pers['lb_attack'] = tme() - 40;
        }

        $curstate = 0;
        $win = $pers['f_turn'] == 1 ? 'Победа!' : 'Поражение.';

        // Праздничные события
        if ($fight['special'] == 1) {
            require 'holyday/new_year.php'; // Предполагается обновление позже
        }

        // Обработка турниров
        $tourConditions = [
            1 => 2,
            2 => 3,
            3 => 4
        ];
        if (isset($tourConditions[$pers['tour']])) {
            $tourId = $tourConditions[$pers['tour']];
            $stmt = $db->prepare("SELECT * FROM quest WHERE id = :id");
            $stmt->execute([':id' => $tourId]);
            $t1 = $stmt->fetch();

            if ($pers['f_turn'] != 1) {
                set_vars('tour=0', $pers['uid']);
                say_to_chat('s', 'Вы проиграли турнир...', 1, $pers['user'], '*', 0);
            } elseif ($t1['type'] == 2) {
                say_to_chat('s', 'Вы прошли во вторую стадию турнира!', 1, $pers['user'], '*', 0);
                $db->prepare("UPDATE users SET chp = hp, cma = ma WHERE uid = :uid")->execute([':uid' => $pers['uid']]);
                $db->prepare("UPDATE p_auras SET esttime = 0 WHERE uid = :uid AND special BETWEEN 3 AND 5 AND esttime > :time")->execute([':uid' => $pers['uid'], ':time' => tme()]);
                $pers['chp'] = $pers['hp'];
                $pers['cma'] = $pers['ma'];
            } elseif ($t1['type'] == 3) {
                set_vars('tour=0, coins = coins + 10, exp = exp + 10000, money = money + 100', $pers['uid']);
                say_to_chat('s', 'Вы выиграли турнир!', 1, $pers['user'], '*', 0);
                $db->prepare("UPDATE quest SET finished = 1, time = :time WHERE id = :id")->execute([':time' => tme(), ':id' => $tourId]);
            }
        }

        // Логирование результатов боя
        say_to_chat('s', "<b>Поединок завершен. $win</b> Нанесено урона: <b>{$pers['fexp']}</b> , получено <font class=\"hp\">боевого опыта: <b>{$pers['exp_chat']}</b></font>. Убийства людей: <b>{$pers['kills']}</b> <a href=\"fight.php?id={$pers['cfight']}\" target=\"_blank\" class=\"timef\">Лог боя</a>.", 1, $pers['user'], '*', 0);

        if ($pers['kills'] > 0) {
            $pers['coins'] += $pers['kills'];
            say_to_chat('s', "<i><b>+{$pers['kills']} пергамент.</b></i>", 1, $pers['user'], '*', 0);
        }

        // Обработка состояния персонажа после боя
        if ($pers['gain_time'] > tme() - 1200) {
            $curstate = 2;
            if ($pers['f_turn'] != 1) {
                set_vars('gain_time=0', $pers['uid']);
            }
        }
        if ($pers['f_turn'] != 1) {
            set_vars('tour=0', $pers['uid']);
        }

        // Обновление данных персонажа в базе
        $stmt = $db->prepare("UPDATE users SET curstate = :curstate, cfight = 0, chp = chp + 2, od_b = 0, fexp = 0, exp_in_f = 0, f_turn = 0, exp_chat = 0, apps_id = 0, kills = 0, coins = coins + :kills, lb_attack = :lb_attack WHERE uid = :uid");
        $stmt->execute([
            ':curstate' => $curstate,
            ':kills' => $pers['kills'],
            ':lb_attack' => $pers['lb_attack'],
            ':uid' => $pers['uid']
        ]);

        $pers['cfight'] = 0;
        $pers['curstate'] = $curstate;
        $pers['chp'] += 2;
        $pers['fexp'] = 0;
        $pers['exp_in_f'] = 0;
        $pers['f_turn'] = 0;
        $pers['od_b'] = 0;
        $pers['kills'] = 0;

        // Вывод JavaScript для интерфейса
        if ($options[7] !== 'no') {
            echo "<script>top.flog_unset();</script>";
        }
        echo "<script>top.flog_clear();</script>";

        // Сброс кулдаунов и аур
        $db->prepare("UPDATE u_blasts SET cur_turn_colldown = 0 WHERE uidp = :uid")->execute([':uid' => $pers['uid']]);
        $db->prepare("UPDATE u_auras SET cur_turn_colldown = 0 WHERE uidp = :uid")->execute([':uid' => $pers['uid']]);
        $db->prepare("UPDATE p_auras SET turn_esttime = 0 WHERE uid = :uid")->execute([':uid' => $pers['uid']]);

        // Регенерация при наличии ауры или хорошего дня
        $stmt = $db->prepare("SELECT esttime FROM p_auras WHERE uid = :uid AND special = 16 AND esttime > :time");
        $stmt->execute([':uid' => $pers['uid'], ':time' => tme()]);
        $regenTime = $stmt->fetchColumn();
        $regen = mtrunc($regenTime - tme());

        if ($regen || ($GOOD_DAY & GD_HUMANHEAL)) {
            $db->prepare("UPDATE p_auras SET esttime = 0 WHERE uid = :uid AND special BETWEEN 3 AND 5 AND esttime > :time")->execute([':uid' => $pers['uid'], ':time' => tme()]);
            // Регенерация HP и маны закомментирована в оригинале, оставляем как есть
            // $pers['chp'] = $pers['hp'];
            // $pers['cma'] = $pers['ma'];
        }
    }

    return $pers;
}

/**
 * Возвращает текстовое название навыка или характеристики по её коду
 * @param string $skill Код навыка или характеристики
 * @return string|array Текстовое название или массив названий для групп навыков
 */
function name_of_skill($skill) {
    // Прямое соответствие для одиночных характеристик
    $singleSkills = [
        'ma' => 'Запас маны',
        'hp' => 'Запас жизни',
        'cma' => 'Мана',
        'chp' => 'Жизнь',
        'kb' => 'Класс брони',
        'mf1' => 'Сокрушение',
        'mf2' => 'Уловка',
        'mf3' => 'Точность',
        'mf4' => 'Стойкость',
        'mf5' => 'Ярость',
        'udmin' => 'Минимальный удар',
        'udmax' => 'Максимальный удар',
        'rank_i' => 'Ранк',
        'level' => 'Уровень',
        'colldown' => 'Перезарядка (сек)',
        'turn_colldown' => 'Перезарядка (ходы)',
        'esttime' => 'Время действия',
        'manacost' => 'Стоимость маны',
        'targets' => 'Кол-во целей'
    ];

    if (isset($singleSkills[$skill])) {
        return $singleSkills[$skill];
    }

    // Группы навыков
    $skillGroups = [
        'stats' => ['Сила', 'Реакция', 'Удача', 'Здоровье', 'Интеллект', 'Сила Воли'],
        'skillsb' => [
            'Очки действия', 'Колкий удар', 'Владение ножами', 'Владение щитами', 'Владение мечами',
            'Владение топорами', 'Владение булавами', 'Чтение книг', 'Усиление магии', 'Сопротивление Магии',
            'Сопротивление Физическим повреждениям', 'Сопротивление Отравам', 'Сопротивление Электричеству',
            'Сопротивление Огню', 'Сопротивление Холоду'
        ],
        'skillsm' => ['Атлетизм', 'Эрудиция', 'Тяжеловес', 'Скорость', 'Обаяние', 'Регенерация жизни', 'Регенерация маны'],
        'skillsp' => [
            'Целитель', 'Темное искусство', 'Удар в спину', 'Воровство', 'Кузнец', 'Рыбак', 'Шахтер',
            'Ориентирование на местности', 'Экономист', 'Охотник', 'Алхимик', 'Добыча камней', 'Дровосек', 'Выделка кожи'
        ]
    ];

    if (isset($skillGroups[$skill])) {
        return $skillGroups[$skill];
    }

    // Обработка индексированных навыков (s1-s6, m1-m5, etc.)
    if (preg_match('/^(s|m|a|sb|sm|sp)(\d+)$/', $skill, $matches)) {
        $prefix = $matches[1];
        $index = (int)$matches[2];

        $indexedSkills = [
            's' => ['Сила', 'Реакция', 'Удача', 'Здоровье', 'Интеллект', 'Сила Воли'],
            'm' => ['Религия', 'Некромантия', 'Стихийная магия', 'Магия порядка', 'Вызовы существ'],
            'a' => ['Вертлявость', 'Бронебойность', 'Толстая кожа', 'Расчётливость', 'Быстрота', 'Любовник', 'Пиротехник', 'Электрик'],
            'sb' => $skillGroups['skillsb'],
            'sm' => $skillGroups['skillsm'],
            'sp' => $skillGroups['skillsp']
        ];

        if (isset($indexedSkills[$prefix]) && $index > 0 && $index <= count($indexedSkills[$prefix])) {
            return $indexedSkills[$prefix][$index - 1];
        }
    }

    // Если навык не найден, возвращаем его код как есть
    return $skill;
}

/**
 * Возвращает текстовое описание статуса в клане по его коду
 * @param string $state Код статуса в клане
 * @return string Текстовое описание статуса
 */
function _StateByIndex($state) {
    // Список соответствий кодов статусов и их названий
    $states = [
        'g' => 'Глава клана',
        'z' => 'Заместитель главы',
        'c' => 'Казначей',
        'k' => 'Отдел кадров',
        'b' => 'Боевой отдел',
        'p' => 'Производственный отдел'
    ];

    // Возвращаем название статуса или значение по умолчанию
    return $states[$state] ?? 'Член клана';
}

/**
 * Формирует строку для SQL-запроса на основе изменений в данных персонажа
 * @param array $arr Ассоциативный массив с новыми данными персонажа
 * @return string Строка с обновляемыми полями для SQL-запроса (например, "`field`='value',...")
 */
function aq($arr) {
    global $db, $resault_aq; // $resault_aq используется как глобальная переменная для отладки

    // Получаем текущие данные персонажа из базы
    $stmt = $db->prepare("SELECT * FROM users WHERE uid = :uid");
    $stmt->execute([':uid' => (int)$arr['uid']]);
    $pconnect = $stmt->fetch();

    if (!$pconnect) {
        return '';
    }

    $res = '';
    foreach ($pconnect as $key => $value) {
        // Пропускаем поля, которые не нужно обновлять или которые не изменились
        if (!is_string($key) || // Пропускаем числовые индексы
            in_array($key, ['user', 'smuser', 'uid', 'refr', 'cfight', 'lastom', 'pol']) || // Исключаемые поля
            !array_key_exists($key, $arr) || // Поле отсутствует в новом массиве
            $pconnect[$key] == $arr[$key]) { // Значение не изменилось
            continue;
        }

        // Формируем часть запроса с экранированием значения
        $res .= "`$key`='" . $db->quote($arr[$key]) . "',";
    }

    // Убираем последнюю запятую, если строка не пустая
    $res = rtrim($res, ',');

    // Сохраняем результат в глобальную переменную для отладки
    $resault_aq = $res;

    return $res;
}

/**
 * Преобразует время в секундах в читаемый текстовый формат
 * @param int|float $seconds Время в секундах
 * @return string Текстовое представление времени (например, "1д 2ч 30м 45с")
 */
function tp($seconds) {
    // Усекаем отрицательные значения и приводим к целому числу
    $seconds = max(0, floor((float)$seconds));

    $days = floor($seconds / 86400); // 86400 секунд в сутках
    $seconds %= 86400;
    $hours = floor($seconds / 3600); // 3600 секунд в часе
    $seconds %= 3600;
    $minutes = floor($seconds / 60); // 60 секунд в минуте
    $seconds %= 60;

    $result = '';
    if ($days > 0) {
        $result .= $days . 'д ';
    }
    if ($hours > 0) {
        $result .= $hours . 'ч ';
    }
    if ($minutes > 0) {
        $result .= $minutes . 'м ';
    }
    $result .= $seconds . 'с';

    return trim($result);
}

/**
 * Удаляет первое вхождение подстроки из строки
 * @param string $substring Подстрока для удаления
 * @param string $string Исходная строка
 * @return string Результирующая строка после удаления первого вхождения
 */
function str_once_delete($substring, $string) {
    // Проверяем, что входные данные — строки
    if (!is_string($substring) || !is_string($string) || $substring === '') {
        return $string;
    }

    // Находим позицию первого вхождения
    $position = strpos($string, $substring);

    // Если подстрока не найдена, возвращаем исходную строку
    if ($position === false) {
        return $string;
    }

    // Удаляем подстроку, соединяя части до и после неё
    return substr($string, 0, $position) . substr($string, $position + strlen($substring));
}

/**
 * Заменяет первое вхождение подстроки в строке на заданное значение
 * @param string $substring Подстрока для замены
 * @param string $replacement Замена
 * @param string $string Исходная строка
 * @return string Результирующая строка после замены первого вхождения
 */
function str_once_replace($substring, $replacement, $string) {
    // Проверяем, что входные данные — строки
    if (!is_string($substring) || !is_string($replacement) || !is_string($string) || $substring === '') {
        return $string;
    }

    // Находим позицию первого вхождения
    $position = strpos($string, $substring);

    // Если подстрока не найдена, возвращаем исходную строку
    if ($position === false) {
        return $string;
    }

    // Выполняем замену, соединяя части строки
    return substr($string, 0, $position) . $replacement . substr($string, $position + strlen($substring));
}

/**
 * Отправляет сообщение в чат игры и записывает его в базу данных
 * @param string $whosay Отправитель сообщения (имя или тип)
 * @param string $chmess Текст сообщения
 * @param int $priv Флаг приватности (0 - публичное, 1 - приватное)
 * @param string $towho Получатель сообщения (для приватных сообщений)
 * @param string $location Локация чата (по умолчанию берётся из $pers['location'])
 * @return bool Успешность отправки сообщения
 */
function say_to_chat($whosay, $chmess, $priv, $towho, $location) {
    global $pers, $db, $chatBuffer;
    
    static $chatBuffer = [];
    $location = $location ?: $pers['location'] ?? '';
    $timeToChat = date('H:i:s');
    $timestamp = microtime(true);

    $chatBuffer[] = [
        ':user' => $whosay,
        ':time2' => $timestamp,
        ':message' => $chmess,
        ':private' => (int)$priv,
        ':towho' => $towho,
        ':location' => $location,
        ':time' => $timeToChat,
        ':color' => $location === '*' ? '220000' : '000000'
    ];

    if (count($chatBuffer) >= 10 || php_sapi_name() === 'cli') {
        $values = implode(',', array_fill(0, count($chatBuffer), '(?, ?, ?, ?, ?, ?, ?, ?)'));
        $flatParams = [];
        foreach ($chatBuffer as $msg) {
            $flatParams = array_merge($flatParams, array_values($msg));
        }
        $stmt = $db->prepare("INSERT INTO chat (user, time2, message, private, towho, location, time, color) VALUES $values");
        $stmt->execute($flatParams);
        $chatBuffer = [];
    }
    return true;
}

/**
 * Обновляет здоровье и ману персонажа с учётом регенерации
 * @param int $currentHealth Текущее здоровье (chp)
 * @param int $maxHealth Максимальное здоровье (hp)
 * @param int $currentMana Текущая мана (cma)
 * @param int $maxMana Максимальная мана (ma)
 * @param int $speedHP Скорость регенерации здоровья (shp)
 * @param int $speedMA Скорость регенерации маны (sma)
 * @param int $lastUpdate Время последнего обновления (lastom)
 * @param int $tire Усталость (по умолчанию -1, не влияет)
 * @param bool $battle Флаг боя (по умолчанию false)
 * @return string Строка для SQL-запроса с обновлёнными значениями
 */
function hp_ma_up($currentHealth, $maxHealth, $currentMana, $maxMana, $speedHP, $speedMA, $lastUpdate, $tire = -1, $battle = false) {
    global $sphp, $spma, $hp, $ma;

    // Устанавливаем скорости регенерации
    $spma = max(2, 1500 - $speedMA * 10); // Минимальная скорость маны — 2
    $sphp = max(2, 700 - $speedHP * 3.5); // Минимальная скорость здоровья — 2

    // Усекаем отрицательные значения
    $currentHealth = max(0, (int)$currentHealth);
    $currentMana = max(0, (int)$currentMana);
    $maxHealth = max(0, (int)$maxHealth);
    $maxMana = max(0, (int)$maxMana);
    $lastUpdate = (int)$lastUpdate;

    // Вычисляем прошедшее время
    $elapsed = mtrunc(tme() - $lastUpdate);

    // Расчёт нового здоровья и маны
    $hp = floor($elapsed * $maxHealth / $sphp + $currentHealth);
    $ma = floor($elapsed * $maxMana / $spma + $currentMana);

    // Ограничиваем значения максимальными пределами
    $hp = min($hp, $maxHealth);
    $ma = min($ma, $maxMana);

    // Подготовка строки для SQL-запроса
    $battleClause = $battle ? '' : ', `refr`=0';
    $tireOut = mtrunc($tire - $elapsed / 30);

    if ($tire > 0) {
        return "`chp`='$hp', `cma`='$ma', `tire`=$tireOut, online=1, `lastom`=" . tme() . $battleClause;
    }
    return "`chp`='$hp', `cma`='$ma', online=1, `lastom`=" . tme() . $battleClause;
}

/**
 * Извлекает данные пользователя из базы по ID с опциональной проверкой пароля
 * @param int $uid ID пользователя
 * @param string $passwd Пароль для проверки (по умолчанию пустой)
 * @param bool $check Флаг проверки пароля (по умолчанию false)
 * @return array|null Данные пользователя или null, если пользователь не найден
 */
function catch_user($uid, $passwd = '', $check = false) {
    global $db;

    // Приведение типов для безопасности
    $uid = (int)$uid;
    $passwd = (string)$passwd;
    $check = (bool)$check;

    // Выбор запроса в зависимости от необходимости проверки пароля
    if ($check) {
        $stmt = $db->prepare("SELECT * FROM users WHERE uid = :uid AND pass = :pass");
        $stmt->execute([
            ':uid' => $uid,
            ':pass' => $passwd // Предполагается, что пароль уже в формате password_hash
        ]);
    } else {
        $stmt = $db->prepare("SELECT * FROM users WHERE uid = :uid");
        $stmt->execute([':uid' => $uid]);
    }

    // Возвращаем данные или null, если пользователь не найден
    return $stmt->fetch() ?: null;
}

/**
 * Обновляет данные пользователя в базе с учётом случайных условий и состояния боя
 * @param int $uid ID пользователя
 * @return int Текущее время обновления
 */
function update_user($uid) {
    global $lastom_old, $pers, $db;

    $uid = (int)$uid;
    $time = tme();

    // Случайное обновление с вероятностью 1/200 для игроков выше 5 уровня без привилегий
    if (rand(1, 200) < 2 && !$pers['priveleged'] && $pers['level'] > 5) {
        $stmt = $db->prepare("UPDATE users SET online = 1, refr = 0, lastom = :lastom, action = -1 WHERE uid = :uid");
        $stmt->execute([':lastom' => $time, ':uid' => $uid]);
    } elseif ($pers['cfight'] != 0 || $pers['action'] == -1) {
        // Обновление для пользователей в бою или с action = -1
        $stmt = $db->prepare("UPDATE users SET online = 1, refr = 0, lastom = :lastom WHERE uid = :uid");
        $stmt->execute([':lastom' => $time, ':uid' => $uid]);
    }

    // Сохраняем старое значение lastom (если используется в другом коде)
    $lastom_old = $pers['lastom'];

    return $time;
}

/**
 * Проверяет авторизацию пользователя и выполняет действия для защиты от ботов
 * @param int $uid ID пользователя
 * @param string $pass Пароль пользователя (хеш)
 * @param string $block Статус блокировки (пустая строка, если не заблокирован)
 * @param int $action Текущий статус действия пользователя
 * @param int $waiter Время ожидания для защиты от ботов
 * @param string $spass Второй пароль (цифровой)
 * @return void Может завершать выполнение скрипта с выводом сообщения
 */
function detect_user($uid, $pass, $block, $action, $waiter, $spass) {
    global $R, $lastom_new, $lastom_old, $pers, $db;

    $uid = (int)$uid;
    $pass = (string)$pass;
    $block = (string)$block;
    $action = (int)$action;
    $waiter = (int)$waiter;
    $spass = (string)$spass;
    $time = time();

    // Закомментированная часть с Memcache оставлена для совместимости, но не используется
    /*
    global $memcache;
    $LOCK = $memcache->get('LOCK' . $uid);
    $LOCKR = $memcache->get('LOCKR' . $uid);

    if ($LOCK && $LOCKR && (int)($LOCKR * 10000) != (int)($R * 10000)) {
        echo '<script type="text/javascript" src="js/newup.js?2"></script>';
        echo '<script type="text/javascript">too_fast(\'Конфликт с ' . htmlspecialchars($LOCKR, ENT_QUOTES, 'UTF-8') . '. Наш поток: ' . htmlspecialchars($R, ENT_QUOTES, 'UTF-8') . '\');</script>';
        exit;
    }
    */

    // Сброс блокировки при action = -1
    if ($action == -1) {
        // $memcache->set('LOCK' . $uid, 0, false, time() + 20);
    }

    // Защита от ботов: проверка кода с картинки
    if (empty($_POST['code_img']) && $action == -1 && $waiter < $time) {
        echo '<link href="main.css" rel="stylesheet" type="text/css">';
        echo '<center class="return_win">Извините пожалуйста, но в связи с появлением программ, позволяющих управлять персонажем без участия игрока, мы вводим защиту против этих программ.<br>';
        echo 'Чтобы пройти тест, пожалуйста, введите цифры, которые вы видите на картинке в поле для ввода, и нажмите "ОК".<br>';
        echo '<script type="text/javascript" src="js/imgcode.js?1"></script>';
        echo '<script>imgcode(\'' . md5($lastom_new) . '\');</script></center>';
        exit;
    } elseif (!empty($_POST['code_img']) && $action == -1) {
        if (uncrypt(md5($lastom_old)) == $_POST['code_img']) {
            set_vars('action=0', $uid);
        } else {
            if ($waiter < $time) {
                set_vars('waiter=' . ($time + 10), $uid);
                echo '<script type="text/javascript" src="js/newup.js?1"></script>';
                echo '<center class="return_win">Вы ввели неверный код.<b>Защита от частого ввода кода.</b></center><hr>';
                echo '<center id="waiter" class="inv"></center><script>waiter(10);</script>';
            } else {
                echo '<script type="text/javascript" src="js/newup.js?1"></script>';
                echo '<center class="return_win">Вы ввели неверный код.<b>Защита от частого ввода кода.</b></center><hr>';
                echo '<div id="waiter" class="items" align="center"></div><script>waiter(' . ($waiter - $time) . ');</script>';
            }
            exit;
        }
    }

    // Проверка авторизации
    if (UID != $uid || PASS != $pass || USER === '' || SPASS != $spass) {
        include './error.html';
        exit;
    }

    // Проверка блокировки
    if ($block !== '') {
        echo '<script>top.location="index.php";</script>';
        exit;
    }
}

/**
 * Создаёт новый бой между командами игроков или ботов
 * @param string $names Список игроков первой команды (разделены "|")
 * @param string $namesvs Список игроков второй команды (разделены "|")
 * @param string $type Тип боя
 * @param int $travm Уровень травм
 * @param int $timeout Таймаут боя в секундах
 * @param string $oruj Используемое оружие
 * @param int $loc ID локации боя
 * @param int $battle_type Тип битвы (по умолчанию 0)
 * @param int $closed Флаг закрытого боя (0 или 1, по умолчанию 0)
 * @param int $special Флаг специального боя (по умолчанию 0)
 * @return int|false ID созданного боя или false при ошибке
 */
function begin_fight($names, $namesvs, $type, $travm, $timeout, $oruj, $loc, $battle_type = 0, $closed = 0, $special = 0) {
    global $db;

    // Приведение типов для безопасности
    $closed = (int)$closed;
    $loc = (int)$loc;
    $battle_type = (int)$battle_type;
    $special = (int)$special;
    $bots_in = 0;

    // Отключаем тактические бои (как в оригинале)
    $loc = 0;

    // Определяем размеры поля боя
    $maxx = $loc == 0 ? 1 : 15;
    $maxy = $loc == 0 ? 1 : 5;

    // Получаем данные локации, если она указана
    $bplace = $loc ? $db->query("SELECT * FROM battle_places WHERE id = " . $loc)->fetch() : [];

    // Создание боя с попытками до 100 раз
    $idf = 0;
    $attempts = 0;
    while ($idf < 11 && $attempts < 100) {
        $attempts++;
        $stmt = $db->prepare("INSERT INTO fights (oruj, travm, timeout, ltime, bplace, maxx, maxy, stones, closed, special) VALUES (:oruj, :travm, :timeout, :ltime, :bplace, :maxx, :maxy, :stones, :closed, :special)");
        $stmt->execute([
            ':oruj' => $oruj,
            ':travm' => (int)$travm,
            ':timeout' => (int)$timeout,
            ':ltime' => tme(),
            ':bplace' => $loc,
            ':maxx' => $maxx,
            ':maxy' => $maxy,
            ':stones' => $battle_type,
            ':closed' => $closed,
            ':special' => $special
        ]);
        $idf = (int)$db->lastInsertId();
    }
    if ($idf == 0) return false;

    $bot_id_max = $idf * 100;

    // Очистка строк от завершающих символов "|"
    $names = rtrim($names, '|');
    $namesvs = rtrim($namesvs, '|');

    // Инициализация логов и счётчиков
    $all = 'Бой между ';
    $PLAYERS = 0;
    $persons = [];

    // Обработка первой команды
    $team1 = explode('|', $names);
    $T1_count = count($team1);
    $xf = 4 - (int)($T1_count / $maxy);
    $yf = floor($maxy / 2) - 1;

    foreach ($team1 as $i => $tmp) {
        if ($loc > 0) {
            while (strpos($bplace['xy'], "|{$xf}_{$yf}|") !== false && $xf > 0) {
                $yf++;
                if ($yf % $maxy == 0) {
                    $yf = 0;
                    $xf--;
                }
            }
        }
        $PLAYERS++;
        if ($loc) $bplace['xy'] .= "|{$xf}_{$yf}|";

        if (strpos(" $tmp", 'bot=') === 0) {
            [$_, $botId] = explode('=', $tmp);
            $stmt = $db->prepare("SELECT * FROM bots WHERE id = :id");
            $stmt->execute([':id' => (int)$botId]);
            $p = $stmt->fetch();

            if ($p) {
                $p['rank_i'] = ($p['s1'] + $p['s2'] + $p['s3'] + $p['s4'] + $p['s5'] + $p['s6'] + $p['kb']) * 0.3 +
                               ($p['mf1'] + $p['mf2'] + $p['mf3'] + $p['mf4']) * 0.03 +
                               ($p['hp'] + $p['ma']) * 0.04 + ($p['udmin'] + $p['udmax']) * 0.3;
                $bot_id_max++;
                $stmt = $db->prepare("INSERT INTO bots_battle (user, level, sign, s1, s2, s3, s4, s5, s6, kb, mf1, mf2, mf3, mf4, mf5, udmin, udmax, hp, ma, chp, cma, id, pol, obr, wears, rank_i, cfight, fteam, xf, yf, bid, id_skin, droptype, dropvalue, dropfrequency, magic_resistance, special) VALUES (:user, :level, 'none', :s1, :s2, :s3, :s4, :s5, :s6, :kb, :mf1, :mf2, :mf3, :mf4, :mf5, :udmin, :udmax, :hp, :ma, :chp, :cma, :id, 'male', :obr, '', :rank_i, :cfight, 1, :xf, :yf, :bid, :id_skin, :droptype, :dropvalue, :dropfrequency, :magic_resistance, :special)");
                $stmt->execute([
                    ':user' => $p['user'], ':level' => $p['level'], ':s1' => $p['s1'], ':s2' => $p['s2'], ':s3' => $p['s3'],
                    ':s4' => $p['s4'], ':s5' => $p['s5'], ':s6' => $p['s6'], ':kb' => $p['kb'], ':mf1' => $p['mf1'],
                    ':mf2' => $p['mf2'], ':mf3' => $p['mf3'], ':mf4' => $p['mf4'], ':mf5' => $p['mf5'], ':udmin' => $p['udmin'],
                    ':udmax' => $p['udmax'], ':hp' => $p['hp'], ':ma' => $p['ma'], ':chp' => $p['hp'], ':cma' => $p['ma'],
                    ':id' => -$bot_id_max, ':obr' => $p['obr'], ':rank_i' => $p['rank_i'], ':cfight' => $idf, ':xf' => $xf,
                    ':yf' => $yf, ':bid' => $p['id'], ':id_skin' => $p['id_skin'], ':droptype' => (int)$p['droptype'],
                    ':dropvalue' => (int)$p['dropvalue'], ':dropfrequency' => (int)$p['dropfrequency'], 
                    ':magic_resistance' => (int)$p['magic_resistance'], ':special' => (int)$p['special']
                ]);
                $bots_in = 1;
            } else {
                array_splice($team1, $i, 1);
            }
        } else {
            $stmt = $db->prepare("SELECT user, level, sign, rank_i, chp, hp, cma, ma, sm6, sm7, lastom, uid, invisible, tire FROM users WHERE user = :user");
            $stmt->execute([':user' => $tmp]);
            $p = $stmt->fetch();

            if ($p) {
                $update = hp_ma_up($p['chp'], $p['hp'], $p['cma'], $p['ma'], $p['sm6'], $p['sm7'], $p['lastom'], $p['tire'], true);
                $stmt = $db->prepare("UPDATE users SET xf = :xf, yf = :yf, $update, cfight = :cfight, curstate = 4, refr = 1, damage_get = chp, damage_give = 0, fteam = 1 WHERE uid = :uid");
                $stmt->execute([':xf' => $xf, ':yf' => $yf, ':cfight' => $idf, ':uid' => $p['uid']]);
                $p['lib'] = $p['user'];
                if ($p['invisible'] > tme()) {
                    $p['user'] = 'невидимка';
                    $p['sign'] = 'none';
                    $p['level'] = '??';
                }
                $persons[] = $p['uid'];
            }
        }
        $all .= "<img src=\"images/signs/{$p['sign']}.gif\"><font class=\"bnick\" color=\"#087C20\">{$p['user']}</font>[<font class=\"lvl\">{$p['level']}</font>] ,";
    }

    if ($PLAYERS == 0) return false;

    $all = substr($all, 0, -1) . ' и ';
    $team2 = explode('|', $namesvs);
    $T2_count = count($team2);
    $xf = $maxx - (4 - (int)($T2_count / $maxy));
    $yf = floor($maxy / 2) - 1;

    foreach ($team2 as $i => $tmp) {
        if ($loc > 0) {
            while (strpos($bplace['xy'], "|{$xf}_{$yf}|") !== false && $xf < $maxx) {
                $yf++;
                if ($yf % $maxy == 0) {
                    $yf = 0;
                    $xf++;
                }
            }
        }
        $PLAYERS++;
        if ($loc) $bplace['xy'] .= "|{$xf}_{$yf}|";

        if (strpos(" $tmp", 'bot=') === 0) {
            [$_, $botId] = explode('=', $tmp);
            $stmt = $db->prepare("SELECT * FROM bots WHERE id = :id");
            $stmt->execute([':id' => (int)$botId]);
            $p = $stmt->fetch();

            if ($p) {
                $p['rank_i'] = ($p['s1'] + $p['s2'] + $p['s3'] + $p['s4'] + $p['s5'] + $p['s6'] + $p['kb']) * 0.3 +
                               ($p['mf1'] + $p['mf2'] + $p['mf3'] + $p['mf4']) * 0.03 +
                               ($p['hp'] + $p['ma']) * 0.04 + ($p['udmin'] + $p['udmax']) * 0.3;
                $bot_id_max++;
                $stmt = $db->prepare("INSERT INTO bots_battle (user, level, sign, s1, s2, s3, s4, s5, s6, kb, mf1, mf2, mf3, mf4, mf5, udmin, udmax, hp, ma, chp, cma, id, pol, obr, wears, rank_i, cfight, fteam, xf, yf, bid, id_skin, droptype, dropvalue, dropfrequency, magic_resistance, special) VALUES (:user, :level, 'none', :s1, :s2, :s3, :s4, :s5, :s6, :kb, :mf1, :mf2, :mf3, :mf4, :mf5, :udmin, :udmax, :hp, :ma, :chp, :cma, :id, 'male', :obr, '', :rank_i, :cfight, 2, :xf, :yf, :bid, :id_skin, :droptype, :dropvalue, :dropfrequency, :magic_resistance, :special)");
                $stmt->execute([
                    ':user' => $p['user'], ':level' => $p['level'], ':s1' => $p['s1'], ':s2' => $p['s2'], ':s3' => $p['s3'],
                    ':s4' => $p['s4'], ':s5' => $p['s5'], ':s6' => $p['s6'], ':kb' => $p['kb'], ':mf1' => $p['mf1'],
                    ':mf2' => $p['mf2'], ':mf3' => $p['mf3'], ':mf4' => $p['mf4'], ':mf5' => $p['mf5'], ':udmin' => $p['udmin'],
                    ':udmax' => $p['udmax'], ':hp' => $p['hp'], ':ma' => $p['ma'], ':chp' => $p['hp'], ':cma' => $p['ma'],
                    ':id' => -$bot_id_max, ':obr' => $p['obr'], ':rank_i' => $p['rank_i'], ':cfight' => $idf, ':xf' => $xf,
                    ':yf' => $yf, ':bid' => $p['id'], ':id_skin' => $p['id_skin'], ':droptype' => (int)$p['droptype'],
                    ':dropvalue' => (int)$p['dropvalue'], ':dropfrequency' => (int)$p['dropfrequency'], 
                    ':magic_resistance' => (int)$p['magic_resistance'], ':special' => (int)$p['special']
                ]);
                $bots_in = 1;
            } else {
                array_splice($team2, $i, 1);
            }
        } else {
            $stmt = $db->prepare("SELECT user, level, sign, rank_i, chp, hp, cma, ma, sm6, sm7, lastom, uid, invisible, tire FROM users WHERE user = :user");
            $stmt->execute([':user' => $tmp]);
            $p = $stmt->fetch();

            if ($p) {
                $update = hp_ma_up($p['chp'], $p['hp'], $p['cma'], $p['ma'], $p['sm6'], $p['sm7'], $p['lastom'], $p['tire'], true);
                $stmt = $db->prepare("UPDATE users SET xf = :xf, yf = :yf, $update, cfight = :cfight, curstate = 4, refr = 1, damage_get = chp, damage_give = 0, fteam = 2 WHERE uid = :uid");
                $stmt->execute([':xf' => $xf, ':yf' => $yf, ':cfight' => $idf, ':uid' => $p['uid']]);
                $p['lib'] = $p['user'];
                if ($p['invisible'] > tme()) {
                    $p['user'] = 'невидимка';
                    $p['sign'] = 'none';
                    $p['level'] = '??';
                }
                $persons[] = $p['uid'];
            }
        }
        $all .= "<img src=\"images/signs/{$p['sign']}.gif\"><font class=\"bnick\" color=\"#0052A6\">{$p['user']}</font>[<font class=\"lvl\">{$p['level']}</font>] ,";
    }

    if ($PLAYERS <= count($team1)) return false; // Проверяем, что добавлены участники второй команды

    $bots_in = $bots_in ? 0 : 1;
    $all = addslashes(substr($all, 0, -1) . ".($type)");

    // Обновляем информацию о бое
    $stmt = $db->prepare("UPDATE fights SET players = :players, nobots = :nobots, closed = :closed WHERE id = :id");
    $stmt->execute([':players' => $PLAYERS, ':nobots' => $bots_in, ':closed' => $closed, ':id' => $idf]);
    add_flog($all, $idf);

    // Логирование для участников
    foreach ($persons as $p) {
        $stmt = $db->prepare("INSERT INTO battle_logs (uid, time, cfight, text) VALUES (:uid, :time, :cfight, :text)");
        $stmt->execute([':uid' => $p, ':time' => tme(), ':cfight' => $idf, ':text' => $all]);
    }

    return $idf;
}

/**
 * Обновляет данные пользователя в базе по заданной строке переменных
 * @param string $vars Строка с переменными для обновления (например, "column=value, column2=value2")
 * @param int|null $uid ID пользователя (по умолчанию берётся из $pers['uid'])
 * @return int|false Количество затронутых строк или false, если обновление не выполнено
 */
function set_vars($vars, $uid = null) {
    global $pers, $db;

    // Если UID не передан, используем глобальный $pers['uid']
    $uid = (int)($uid ?? $pers['uid']);

    // Проверяем, что строка переменных не пуста
    if (empty($vars)) {
        return false;
    }

    // Выполняем обновление в базе
    $query = "UPDATE users SET $vars WHERE uid = :uid";
    $stmt = $db->prepare($query);
    $stmt->execute([':uid' => $uid]);

    // Возвращаем количество затронутых строк
    return $stmt->rowCount();
}

/**
 * Активирует ауру для персонажа, обновляя его параметры и записывая эффект в базу
 * @param int $aid ID ауры
 * @param array $pers Данные активирующего персонажа
 * @param array $persto Данные персонажа, на которого применяется аура (по ссылке)
 * @param bool $get_mana Флаг списания маны (по умолчанию true)
 * @return array|null Данные ауры или null, если активация невозможна
 */
function aura_on($aid, $pers, &$persto, $get_mana = true) {
    global $db;

    // Приведение типов для безопасности
    $aid = (int)$aid;
    $get_mana = (bool)$get_mana;

    // Получаем данные ауры
    $stmt = $db->prepare("SELECT * FROM u_auras WHERE id = :id");
    $stmt->execute([':id' => $aid]);
    $a = $stmt->fetch();

    // Проверяем условия активации ауры
    if (!$a || $a['manacost'] > $pers['cma'] || $a['tlevel'] > $pers['level'] ||
        $a['ts6'] > $pers['s6'] || $a['tm1'] > $pers['m1'] || $a['tm2'] > $pers['m2'] ||
        $a['cur_colldown'] > tme() || $a['cur_turn_colldown'] > $pers['f_turn']) {
        return null;
    }

    // Применяем эффекты ауры
    $params = array_filter(explode('@', $a['params']));
    $nparams = '';
    foreach ($params as $par) {
        if (empty($par)) continue;
        [$key, $value] = explode('=', $par);
        if (substr($value, -1) === '%') {
            $res = floor(((int)$value / 100) * $persto[$key]);
            if ($res) {
                $persto[$key] += $res;
                $nparams .= "$key=$res@";
            }
        } else {
            $res = (int)$value;
            $persto[$key] += $res;
            $nparams .= "$key=$res@";
        }
    }

    // Особые эффекты ауры
    if ($a['special'] == 1) {
        $silence = time() + $a['esttime'];
        $persto['silence'] = max($persto['silence'] ?? 0, $silence);
    } elseif ($a['special'] == 2) {
        $inv = time() + $a['esttime'];
        $persto['invisible'] = max($persto['invisible'] ?? 0, $inv);
    }

    // Ограничиваем значения здоровья и маны
    $persto['chp'] = min(max(0, $persto['chp']), $persto['hp']);
    $persto['cma'] = min(max(0, $persto['cma']), $persto['ma']);

    // Если аура применяется на самого себя, обновляем $pers
    if ($pers['uid'] == $persto['uid']) {
        $pers = $persto;
    }

    // Обновляем данные персонажа в базе
    set_vars(aq($persto), $persto['uid']);

    // Записываем эффект ауры в базу
    $stmt = $db->prepare("INSERT INTO p_auras (uid, esttime, turn_esttime, name, image, params, special) VALUES (:uid, :esttime, :turn_esttime, :name, :image, :params, :special)");
    $stmt->execute([
        ':uid' => $persto['uid'],
        ':esttime' => time() + $a['esttime'],
        ':turn_esttime' => $persto['f_turn'] + $a['turn_esttime'],
        ':name' => $a['name'],
        ':image' => $a['image'],
        ':params' => $nparams,
        ':special' => $a['special']
    ]);

    // Автокаст ауры, если применимо
    if ($a['autocast'] && $pers['uid'] == $persto['uid']) {
        $stmt = $db->prepare("INSERT INTO p_auras (uid, esttime, turn_esttime, name, image, params, autocast) VALUES (:uid, :esttime, 0, :name, :image, '', :autocast)");
        $stmt->execute([
            ':uid' => $persto['uid'],
            ':esttime' => tme() + $a['colldown'] + 5,
            ':name' => $a['name'] . ' [Автокаст]',
            ':image' => $a['image'],
            ':autocast' => $a['id']
        ]);
    }

    // Списание маны и обновление навыков магии
    if ($get_mana) {
        $pers['cma'] -= $a['manacost'];
        $pers["m{$a['type']}"] += 1 / ($pers["m{$a['type']}"] + 1);
        set_vars("cma={$pers['cma']}, m1={$pers['m1']}, m2={$pers['m2']}", $pers['uid']);

        $cur_turn_colldown = $pers['curstate'] == 4 ? ", cur_turn_colldown = turn_colldown + {$pers['f_turn']}" : '';
        $stmt = $db->prepare("UPDATE u_auras SET cur_colldown = :colldown $cur_turn_colldown WHERE id = :id");
        $stmt->execute([':colldown' => tme() + $a['colldown'], ':id' => $a['id']]);
    }

    return $a;
}

/**
 * Активирует ауру на персонажа с заданным коэффициентом усиления
 * @param int $aid ID ауры
 * @param mixed $persto Данные персонажа (массив или ID пользователя, обновляется по ссылке)
 * @param float $koef Коэффициент усиления эффекта (по умолчанию 1)
 * @return array|null Данные ауры или null, если активация невозможна
 */
function aura_on2($aid, &$persto, $koef = 1) {
    global $db;

    // Приведение типов для безопасности
    $aid = (int)$aid;
    $koef = (float)$koef;

    // Получаем данные ауры
    $stmt = $db->prepare("SELECT * FROM auras WHERE id = :id");
    $stmt->execute([':id' => $aid]);
    $a = $stmt->fetch();

    if (!$a) {
        return null;
    }

    // Если передан ID, получаем данные персонажа
    if (is_scalar($persto)) {
        $persto = catch_user($persto);
    }

    // Проверяем, что данные персонажа корректны
    if (!is_array($persto)) {
        return $a;
    }

    // Применяем эффекты ауры
    $params = array_filter(explode('@', $a['params']));
    $nparams = '';
    foreach ($params as $par) {
        if (empty($par)) continue;
        [$key, $value] = explode('=', $par);
        if (substr($value, -1) === '%') {
            $res = floor(((int)$value / 100) * $persto[$key] * $koef);
            $persto[$key] += $res;
            $nparams .= "$key=$res@";
        } else {
            $res = (int)$value * $koef;
            $persto[$key] += $res;
            $nparams .= "$key=$res@";
        }
    }

    // Особые эффекты ауры
    if ($a['special'] == 1) {
        $silence = time() + $a['esttime'];
        $persto['silence'] = max($persto['silence'] ?? 0, $silence);
    } elseif ($a['special'] == 2) {
        $inv = time() + $a['esttime'];
        $persto['invisible'] = max($persto['invisible'] ?? 0, $inv);
    }

    // Ограничиваем значения здоровья и маны
    $persto['chp'] = min(max(0, $persto['chp']), $persto['hp']);
    $persto['cma'] = min(max(0, $persto['cma']), $persto['ma']);

    // Обновляем данные персонажа в базе
    set_vars(aq($persto), $persto['uid']);

    // Записываем эффект ауры в базу
    $stmt = $db->prepare("INSERT INTO p_auras (uid, esttime, turn_esttime, name, image, params, special) VALUES (:uid, :esttime, :turn_esttime, :name, :image, :params, :special)");
    $stmt->execute([
        ':uid' => $persto['uid'],
        ':esttime' => time() + $a['esttime'],
        ':turn_esttime' => $persto['f_turn'] + $a['turn_esttime'],
        ':name' => $a['name'],
        ':image' => $a['image'],
        ':params' => $nparams,
        ':special' => $a['special']
    ]);

    return $a;
}

/**
 * Применяет "лёгкую" ауру к пользователю, записывая эффект в базу
 * @param array $a Данные ауры (name, image, params, esttime, turn_esttime, special)
 * @param int $uid ID пользователя
 * @return bool Успешность выполнения операции
 */
function light_aura_on($a, $uid) {
    global $db;

    // Приведение типов для безопасности
    $uid = (int)$uid;

    // Проверка входных данных
    if ($uid === 0 || !is_array($a) || empty($a['name'])) {
        return false;
    }

    // Записываем эффект ауры в базу
    $stmt = $db->prepare("INSERT INTO p_auras (uid, esttime, turn_esttime, name, image, params, special) VALUES (:uid, :esttime, :turn_esttime, :name, :image, :params, :special)");
    $result = $stmt->execute([
        ':uid' => $uid,
        ':esttime' => time() + (int)($a['esttime'] ?? 0),
        ':turn_esttime' => (int)($a['turn_esttime'] ?? 0), // Предполагаем 0, если не указано
        ':name' => $a['name'],
        ':image' => $a['image'] ?? '',
        ':params' => $a['params'] ?? '',
        ':special' => (int)($a['special'] ?? 0)
    ]);

    return $result;
}

/**
 * Формирует HTML-код для отображения данных персонажа в бою
 * @param array $pers Данные персонажа
 * @param int $inv Флаг невидимости (0 или 1)
 * @return string HTML-код для отображения персонажа
 */
function show_pers_in_f($pers, $inv) {
    global $sh, $oj, $or1, $or2, $sa, $na, $po, $pe, $br, $kam1, $kam2, $kam3, $kam4, $z1, $z2, $z3, $ko1, $ko2, $db;

    // Начало HTML-кода
    $s = '<table border="0" cellspacing="0" cellpadding="0"><tr><td valign="top" width="221" colspan="3"><script>';

    // Если персонаж не текущий пользователь, обновляем экипировку
    if ($pers['uid'] != UID) {
        global $pers as $globalPers;
        $tempPers = $globalPers;
        $globalPers = $pers;
        require 'inc/inc/p_clothes.php'; // Предполагается обновление позже
        $pers = $globalPers;
        $globalPers = $tempPers;
        unset($tempPers);
    }

    // Обработка невидимости
    if ($pers['invisible'] > tme() && $pers['uid'] != ($_COOKIE['uid'] ?? 0)) {
        $wears = array_fill(0, 18, ['image' => 'slots/pob' . ($i + 1), 'id' => '0']);
        [$sh, $na, $oj, $pe, $or1, $or2, $po, $z1, $z2, $z3, $sa, $ko1, $ko2, $br, $kam1, $kam2, $kam3, $kam4] = array_values($wears);

        $pers['obr'] = 'invisible';
        $pers['user'] = '<i>невидимка</i>';
        $pers['sign'] = 'none';
        $pers['level'] = '??';
        $pers['aura'] = '';
        $hiddenStats = ['s1', 's2', 's3', 's4', 's5', 's6', 'kb', 'mf1', 'mf2', 'mf3', 'mf4', 'mf5'];
        foreach ($hiddenStats as $stat) {
            $pers[$stat] = '??';
        }
        $pers['hp'] = $pers['chp'] = $pers['ma'] = $pers['cma'] = 1;
    }

    // Генерация JavaScript для отображения персонажа
    $s .= 'InFight=1;';
    $s .= sprintf("show_pers_new('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s_%s',%d,'%s','%s','%s','%s','%s','%s',%d,'%s','%s','%s','%s','%s','%s','%s','%s');",
        htmlspecialchars($sh['image'] ?? '', ENT_QUOTES, 'UTF-8'), htmlspecialchars($sh['id'] ?? '', ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($oj['image'] ?? '', ENT_QUOTES, 'UTF-8'), htmlspecialchars($oj['id'] ?? '', ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($or1['image'] ?? '', ENT_QUOTES, 'UTF-8'), htmlspecialchars($or1['id'] ?? '', ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($po['image'] ?? '', ENT_QUOTES, 'UTF-8'), htmlspecialchars($po['id'] ?? '', ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($z1['image'] ?? '', ENT_QUOTES, 'UTF-8'), htmlspecialchars($z1['id'] ?? '', ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($z2['image'] ?? '', ENT_QUOTES, 'UTF-8'), htmlspecialchars($z2['id'] ?? '', ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($z3['image'] ?? '', ENT_QUOTES, 'UTF-8'), htmlspecialchars($z3['id'] ?? '', ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($sa['image'] ?? '', ENT_QUOTES, 'UTF-8'), htmlspecialchars($sa['id'] ?? '', ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($na['image'] ?? '', ENT_QUOTES, 'UTF-8'), htmlspecialchars($na['id'] ?? '', ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($pe['image'] ?? '', ENT_QUOTES, 'UTF-8'), htmlspecialchars($pe['id'] ?? '', ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($or2['image'] ?? '', ENT_QUOTES, 'UTF-8'), htmlspecialchars($or2['id'] ?? '', ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($ko1['image'] ?? '', ENT_QUOTES, 'UTF-8'), htmlspecialchars($ko1['id'] ?? '', ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($ko2['image'] ?? '', ENT_QUOTES, 'UTF-8'), htmlspecialchars($ko2['id'] ?? '', ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($br['image'] ?? '', ENT_QUOTES, 'UTF-8'), htmlspecialchars($br['id'] ?? '', ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($pers['pol'] ?? '', ENT_QUOTES, 'UTF-8'), htmlspecialchars($pers['obr'] ?? '', ENT_QUOTES, 'UTF-8'),
        (int)$inv,
        htmlspecialchars($pers['sign'] ?? '', ENT_QUOTES, 'UTF-8'), htmlspecialchars($pers['user'] ?? '', ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($pers['level'] ?? '', ENT_QUOTES, 'UTF-8'), htmlspecialchars($pers['chp'] ?? '', ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($pers['hp'] ?? '', ENT_QUOTES, 'UTF-8'), htmlspecialchars($pers['cma'] ?? '', ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($pers['ma'] ?? '', ENT_QUOTES, 'UTF-8'), (int)($pers['tire'] ?? 0),
        htmlspecialchars($kam1['image'] ?? '', ENT_QUOTES, 'UTF-8'), htmlspecialchars($kam2['image'] ?? '', ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($kam3['image'] ?? '', ENT_QUOTES, 'UTF-8'), htmlspecialchars($kam4['image'] ?? '', ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($kam1['id'] ?? '', ENT_QUOTES, 'UTF-8'), htmlspecialchars($kam2['id'] ?? '', ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($kam3['id'] ?? '', ENT_QUOTES, 'UTF-8'), htmlspecialchars($kam4['id'] ?? '', ENT_QUOTES, 'UTF-8')
    );
    $s .= '</script></td></tr><tr><td>';

    // Отображение характеристик, если персонаж видим или это текущий пользователь
    if ($pers['invisible'] < tme() || $pers['uid'] == UID) {
        if ($pers['uid']) {
            $s .= '<br><script>document.write(sbox2b(1,1));</script><div id="prs' . htmlspecialchars($pers['uid'], ENT_QUOTES, 'UTF-8') . '" class="aurasc" style="text-align:center;"></div><script>document.write(sbox2e());</script>';
            $s .= '<table border="0" cellspacing="0" cellpadding="0" width="100%"><tr><td valign="top">';
            
            $r = all_params();
            $r[12] = 'rank_i';
            for ($i = 0; $i < 13; $i++) {
                $td_class = ($r[$i][0] == 's') ? 'user' : 'mf';
                $img = ($r[$i][0] == 's') ? '<img src="images/DS/stats_s' . $r[$i][1] . '.png">' : '';
                $s .= "<tr><td class=\"$td_class\" width=\"150\" nowrap>{$img}" . htmlspecialchars(name_of_skill($r[$i]), ENT_QUOTES, 'UTF-8') . '</td>';

                $value = htmlspecialchars($pers[$r[$i]] ?? '', ENT_QUOTES, 'UTF-8');
                $color = ($pers['uid'] == UID || $globalPers[$r[$i]] == $pers[$r[$i]]) ? '' : ($globalPers[$r[$i]] > $pers[$r[$i]] ? ' style="color:#990000"' : ' style="color:#009900"');
                $suffix = ($i < 6 || $i == 6 || $i == 12) ? '' : '%';
                $class = ($i < 6) ? 'user' : 'mfb';
                $s .= "<td class=\"$class\" align=\"right\"><b$color>$value$suffix</b></td></tr>";
            }
            $s .= '</table></td></tr></table>';

            // Отображение активных аур
            $stmt = $db->prepare("SELECT * FROM p_auras WHERE uid = :uid");
            $stmt->execute([':uid' => $pers['uid']]);
            $txt = '';
            while ($a = $stmt->fetch()) {
                $txt .= htmlspecialchars($a['image'], ENT_QUOTES, 'UTF-8') . '#<b>' . htmlspecialchars($a['name'], ENT_QUOTES, 'UTF-8') . '</b>@';
                $txt .= 'Осталось <i class="timef">' . htmlspecialchars(tp($a['esttime'] - time()), ENT_QUOTES, 'UTF-8') . '</i>';
                $params = explode('@', $a['params']);
                foreach ($params as $par) {
                    if (empty($par)) continue;
                    [$key, $value] = explode('=', $par);
                    $perc = (substr($key, 0, 2) == 'mf') ? '%' : '';
                    if ($value && $key != 'cma' && $key != 'chp') {
                        $txt .= '@' . htmlspecialchars(name_of_skill($key), ENT_QUOTES, 'UTF-8') . ':<b>' . htmlspecialchars(plus_param($value), ENT_QUOTES, 'UTF-8') . $perc . '</b>';
                    }
                }
                $txt .= '|';
            }
            $s .= "<script>view_auras('" . htmlspecialchars($txt, ENT_QUOTES, 'UTF-8') . "','prs" . htmlspecialchars($pers['uid'], ENT_QUOTES, 'UTF-8') . "');</script>";
        }
    } else {
        $s .= '</td></tr></table>';
    }

    return $s;
}

/**
 * Формирует строку с JavaScript-кодом для перехода в локацию с MD5-проверкой
 * @param string $locid ID локации
 * @param int $time Время для создания хеша
 * @return string JavaScript-код для вызова функции goloc с хешем
 */
function build_go_string($locid, $time) {
    // Приведение типов для безопасности
    $locid = (string)$locid;
    $time = (int)$time;

    // Формируем строку для хеширования
    $hashInput = strtoupper((string)$time . $locid . strlen($locid));
    $hash = md5($hashInput);

    // Создаём JavaScript-код с экранированием
    $result = sprintf('onclick="top.goloc(\'%s\',\'%s\')"',
        htmlspecialchars($locid, ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($hash, ENT_QUOTES, 'UTF-8')
    );

    return $result;
}

/**
 * Выполняет SQL-запрос и возвращает первый ряд результата
 * @param string $query SQL-запрос
 * @return array|null Ассоциативный массив с данными первого ряда или null, если результат пуст
 */
function sqla($query) {
    global $db;

    // Проверка входных данных
    if (!is_string($query) || empty(trim($query))) {
        return null;
    }

    // Выполняем запрос
    $stmt = $db->query($query);

    // Возвращаем первый ряд или null
    return $stmt->fetch() ?: null;
}

/**
 * Выполняет SQL-запрос и возвращает значение из первого ряда
 * @param string $query SQL-запрос
 * @param int $column Номер столбца для извлечения (по умолчанию 0)
 * @return mixed|null Значение из указанного столбца первого ряда или null, если результат пуст
 */
function sqlr($query, $column = 0) {
    global $db;

    // Проверка входных данных
    if (!is_string($query) || empty(trim($query))) {
        return null;
    }
    $column = max(0, (int)$column); // Убеждаемся, что номер столбца не отрицательный

    // Выполняем запрос
    $stmt = $db->query($query);
    $row = $stmt->fetch(PDO::FETCH_NUM); // Извлекаем как числовой массив

    // Возвращаем значение из указанного столбца или null
    return $row ? ($row[$column] ?? null) : null;
}

/**
 * Выполняет SQL-запрос, логирует его выполнение и обрабатывает ошибки
 * @param string $query SQL-запрос
 * @return PDOStatement Результат выполнения запроса
 * @throws PDOException при ошибке выполнения запроса
 */
function sql($query) {
    global $sqlQueriesCounter, $sqlQueriesTimer, $sqlLongestQueryTime, $sqlLongestQuery, $sqlAll, $_ECHO_OFF, $db;

    // Проверка входных данных
    if (!is_string($query) || empty(trim($query))) {
        throw new PDOException('Некорректный или пустой SQL-запрос');
    }

    // Замеряем время выполнения
    $startTime = microtime(true);

    try {
        // Выполняем запрос
        $stmt = $db->query($query);
    } catch (PDOException $e) {
        // Обрабатываем ошибку
        $error = $e->getMessage();
        if ($_COOKIE['uid'] == 5 && !$_ECHO_OFF) {
            echo '<font class="hp"><b> ОШИБКА MySQL!!! : ' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . ' <i>[' . htmlspecialchars($query, ENT_QUOTES, 'UTF-8') . ']</i></b></font>';
        }

        // Логируем запрос с ошибкой
        $sqlAll[] = "$query;<b class=\"red\">$error</b>";
        throw $e; // Перебрасываем исключение для обработки выше
    }

    // Вычисляем время выполнения
    $executionTime = microtime(true) - $startTime;

    // Обновляем статистику
    $sqlQueriesCounter++;
    $sqlQueriesTimer += abs($executionTime);

    if (abs($executionTime) > $sqlLongestQueryTime) {
        $sqlLongestQueryTime = abs($executionTime);
        $sqlLongestQuery = "$query  <i>" . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . "</i>";
    }

    // Логируем успешный запрос
    $sqlAll[] = "$query;<b class=\"red\"></b>";

    // Закомментированная часть для отладки медленных запросов
    /*
    if ($executionTime > 0.2) {
        say_to_chat('a', '[' . str_replace("'", "", $query) . '] Время работы: ' . $executionTime, 1, 'sL', '*');
    }
    */

    return $stmt;
}
/**
 * Усекает отрицательные значения до нуля
 * @param int|float $value Входное значение
 * @return int|float Усечённое значение (не меньше 0)
 */
function mtrunc($value) {
    return max(0, (float)$value);
}
/**
 * Возвращает IP-адрес клиента
 * @return string IP-адрес клиента
 */
function show_ip() {
    return getenv('HTTP_CLIENT_IP') ?: (getenv('HTTP_X_FORWARDED_FOR') ?: getenv('REMOTE_ADDR'));
}
/**
 * Вычисляет квадрат числа
 * @param int|float $x Число
 * @return int|float Квадрат числа
 */
function sqr($x) {
    return (float)$x * (float)$x;
}
/**
 * Запускает статистику модуля
 * @param string $name Название модуля
 * @param string $string Дополнительные данные модуля
 * @return void
 */
function mod_st_start($name, $string) {
    global $module_statisticks, $module_statisticks_counter, $sqlQueriesCounter, $sqlQueriesTimer;

    $i = $module_statisticks_counter + 1;
    $module_statisticks[$i] = [
        'name' => (string)$name,
        'strings' => (string)$string,
        'sql_queries' => $sqlQueriesCounter,
        'sql_time' => $sqlQueriesTimer,
        'all_exec_time' => microtime(true)
    ];
}
/**
 * Завершает статистику модуля и вычисляет итоговые значения
 * @return void
 */
function mod_st_fin() {
    global $module_statisticks, $module_statisticks_counter, $sqlQueriesCounter, $sqlQueriesTimer;

    $i = $module_statisticks_counter + 1;
    $module_statisticks[$i]['sql_queries'] = $sqlQueriesCounter - $module_statisticks[$i]['sql_queries'];
    $module_statisticks[$i]['sql_time'] = $sqlQueriesTimer - $module_statisticks[$i]['sql_time'];
    $module_statisticks[$i]['all_exec_time'] = microtime(true) - $module_statisticks[$i]['all_exec_time'];
    $module_statisticks_counter++;
}

/**
 * Добавляет предмет в инвентарь пользователя
 * @param int|array $id ID предмета или массив данных предмета
 * @param int $uid ID пользователя
 * @param int $durability Прочность предмета (по умолчанию -1, берётся максимальная)
 * @param int $weared Флаг экипировки (0 или 1, по умолчанию 0)
 * @param string $user Имя пользователя (по умолчанию пусто, определяется автоматически)
 * @return int ID добавленного предмета или 0 при ошибке
 */
function insert_wp($id, $uid, $durability = -1, $weared = 0, $user = '') {
    global $db;

    $uid = (int)$uid;
    $weared = (int)$weared;

    if (is_scalar($id)) {
        $stmt = $db->prepare("SELECT * FROM weapons WHERE id = :id");
        $stmt->execute([':id' => (int)$id]);
        $v = $stmt->fetch();
    } else {
        $v = $id;
    }

    if (!is_array($v) || empty($v['id'])) return 0;

    $id = $v['id'];
    $durability = $durability == -1 ? (int)$v['max_durability'] : (int)$durability;
    $user = $user ?: sqlr("SELECT user FROM users WHERE uid = :uid", ['uid' => $uid]);

    $columns = '`id`, `uidp`, `weared`, `id_in_w`, `price`, `dprice`, `image`, `index`, `type`, `stype`, `name`, `describe`, `weight`, `where_buy`, `max_durability`, `durability`, `present`, `clan_sign`, `clan_name`, `radius`, `slots`, `arrows`, `arrows_max`, `arrow_name`, `arrow_price`, `tlevel`, `p_type`, `user`, `material_show`, `material`';
    $values = array_merge(
        [0, $uid, $weared, $id, $v['price'], $v['dprice'], $v['image'], $v['index'], $v['type'], $v['stype'], $v['name'], $v['describe'], $v['weight'], $v['where_buy'], $v['max_durability'], $durability, $v['present'], '', '', $v['radius'], $v['slots'], $v['arrows'], $v['arrows_max'], $v['arrow_name'], $v['arrow_price'], $v['tlevel'], $v['p_type'], $user, $v['material_show'], $v['material']],
        array_filter(array_map(function($param) use ($v) {
            return $v[$param] != 0 ? $v[$param] : null;
        }, all_params()), fn($val) => $val !== null),
        array_filter(array_map(function($param) use ($v) {
            return $v["t$param"] != 0 ? $v["t$param"] : null;
        }, all_params()), fn($val) => $val !== null)
    );

    $placeholders = implode(',', array_fill(0, count($values), '?'));
    $stmt = $db->prepare("INSERT INTO wp ($columns) VALUES ($placeholders)");
    $stmt->execute($values);

    return (int)$db->lastInsertId();
}

/**
 * Копирует предмет из существующего в новый для пользователя
 * @param int $uid ID пользователя
 * @param string $teta Условие для выборки существующего предмета
 * @param string $user Имя пользователя (по умолчанию пусто)
 * @return array|false Данные нового предмета или false при ошибке
 */
function insert_wp_new($uid, $teta, $user = '') {
    global $db;

    $uid = (int)$uid;
    $stmt = $db->prepare("SELECT * FROM wp WHERE $teta LIMIT 1");
    $stmt->execute();
    $v = $stmt->fetch();

    if (!$v || empty($v['id'])) return false;

    $user = $user ?: sqlr("SELECT user FROM users WHERE uid = :uid", ['uid' => $uid]);
    $columns = '`id`, `uidp`, `weared`, `id_in_w`, `price`, `dprice`, `image`, `index`, `type`, `stype`, `name`, `describe`, `weight`, `where_buy`, `max_durability`, `durability`, `present`, `clan_sign`, `clan_name`, `radius`, `slots`, `arrows`, `arrows_max`, `arrow_name`, `arrow_price`, `tlevel`, `p_type`, `timeout`, `user`, `material_show`, `material`';
    $values = array_merge(
        [0, $uid, 0, $v['id_in_w'], $v['price'], $v['dprice'], $v['image'], $v['index'], $v['type'], $v['stype'], $v['name'], $v['describe'], $v['weight'], $v['where_buy'], $v['max_durability'], $v['durability'], $v['present'], '', '', $v['radius'], $v['slots'], $v['arrows'], $v['arrows_max'], $v['arrow_name'], $v['arrow_price'], $v['tlevel'], $v['p_type'], $v['timeout'], $user, $v['material_show'], $v['material']],
        array_filter(array_map(function($param) use ($v) {
            return $v[$param] != 0 ? $v[$param] : null;
        }, all_params()), fn($val) => $val !== null),
        array_filter(array_map(function($param) use ($v) {
            return $v["t$param"] != 0 ? $v["t$param"] : null;
        }, all_params()), fn($val) => $val !== null)
    );

    $placeholders = implode(',', array_fill(0, count($values), '?'));
    $stmt = $db->prepare("INSERT INTO wp ($columns) VALUES ($placeholders)");
    $stmt->execute($values);

    $v['id'] = (int)$db->lastInsertId();
    $v['uidp'] = $uid;
    return $v;
}

/**
 * Добавляет взрыв (blast) в инвентарь пользователя
 * @param int $id ID взрыва
 * @param int $uid ID пользователя
 * @return int ID добавленного взрыва или false при ошибке
 */
function insert_blast($id, $uid) {
    global $db;

    $id = (int)$id;
    $uid = (int)$uid;

    $stmt = $db->prepare("SELECT * FROM blasts WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $z = $stmt->fetch();

    if (!$z) return false;

    $columns = array_filter(array_keys($z), fn($key) => is_string($key) && $key !== 'id' && $key !== 'learnall');
    $columns[] = 'uidp';
    $values = array_map(fn($key) => $z[$key], $columns);
    $values[] = $uid;

    $placeholders = implode(',', array_fill(0, count($values) + 2, '?'));
    $stmt = $db->prepare("INSERT INTO u_blasts (id, id_in_w, " . implode(',', array_map(fn($col) => "`$col`", $columns)) . ") VALUES ($placeholders)");
    $stmt->execute(array_merge([0, $z['id']], $values));

    return (int)$db->lastInsertId();
}

/**
 * Добавляет ауру в инвентарь пользователя
 * @param int $id ID ауры
 * @param int $uid ID пользователя
 * @return int ID добавленной ауры или false при ошибке
 */
function insert_aura($id, $uid) {
    global $db;

    $id = (int)$id;
    $uid = (int)$uid;

    $stmt = $db->prepare("SELECT * FROM auras WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $z = $stmt->fetch();

    if (!$z) return false;

    $columns = array_filter(array_keys($z), fn($key) => is_string($key) && $key !== 'id' && $key !== 'learnall');
    $columns[] = 'uidp';
    $values = array_map(fn($key) => $z[$key], $columns);
    $values[] = $uid;

    $placeholders = implode(',', array_fill(0, count($values) + 2, '?'));
    $stmt = $db->prepare("INSERT INTO u_auras (id, id_in_w, " . implode(',', array_map(fn($col) => "`$col`", $columns)) . ") VALUES ($placeholders)");
    $stmt->execute(array_merge([0, $z['id']], $values));

    return (int)$db->lastInsertId();
}

/**
 * Снимает экипированный предмет с персонажа
 * @param int $id ID предмета
 * @param array|null $v Данные предмета (если не переданы, запрашиваются)
 * @return void
 */
function remove_weapon($id, $v = null) {
    global $pers, $db;

    if (!is_array($v)) {
        $stmt = $db->prepare("SELECT * FROM wp WHERE id = :id AND weared = 1 AND uidp = :uidp");
        $stmt->execute([':id' => (int)$id, ':uidp' => (int)$pers['uid']]);
        $v = $stmt->fetch();
    }

    if ($v) {
        foreach (all_params() as $a) {
            if ($v[$a]) $pers[$a] -= $v[$a];
        }
        $pers['hp'] -= 5 * $v['s4'];
        $pers['ma'] -= 9 * $v['s6'];

        $aq = aq($pers);
        if ($aq) {
            $stmt = $db->prepare("UPDATE users SET $aq WHERE uid = :uid");
            $stmt->execute([':uid' => UID]);
        }

        $stmt = $db->prepare("UPDATE wp SET weared = 0 WHERE id = :id");
        $stmt->execute([':id' => $v['id']]);
    }
}

/**
 * Снимает все экипированные предметы с персонажа
 * @return void
 */
function remove_all_weapons() {
    global $pers, $db;

    $stmt = $db->prepare("SELECT * FROM wp WHERE weared = 1 AND uidp = :uidp");
    $stmt->execute([':uidp' => (int)$pers['uid']]);
    $items = $stmt->fetchAll();

    foreach ($items as $v) {
        foreach (all_params() as $a) {
            if ($v[$a]) $pers[$a] -= $v[$a];
        }
        $pers['hp'] -= 5 * $v['s4'];
        $pers['ma'] -= 9 * $v['s6'];
    }

    $aq = aq($pers);
    if ($aq) {
        $stmt = $db->prepare("UPDATE users SET $aq WHERE uid = :uid");
        $stmt->execute([':uid' => (int)$pers['uid']]);
    }

    $stmt = $db->prepare("UPDATE wp SET weared = 0 WHERE uidp = :uidp");
    $stmt->execute([':uidp' => (int)$pers['uid']]);
}

/**
 * Удаляет все истёкшие ауры персонажа и обновляет его параметры
 * @return void
 */
function remove_all_auras() {
    global $pers, $db;

    $stmt = $db->prepare("SELECT * FROM p_auras WHERE uid = :uid AND esttime <= :time AND turn_esttime <= :turn");
    $stmt->execute([':uid' => (int)$pers['uid'], ':time' => tme(), ':turn' => $pers['f_turn']]);
    $auras = $stmt->fetchAll();

    $count = 0;
    $modified = false;
    foreach ($auras as $a) {
        $count++;
        $params = array_filter(explode('@', $a['params']));
        foreach ($params as $par) {
            [$key, $value] = explode('=', $par);
            if ($key !== 'cma' && $key !== 'chp' && (int)$value != 0) {
                $pers[$key] -= (int)$value;
                $modified = true;
            }
        }
        if ($a['special'] == 14) {
            $a['image'] = 68;
            $a['params'] = '';
            $a['esttime'] = 18000 - (tme() - $a['esttime']);
            $a['name'] = 'Отдышка после шахты';
            $a['special'] = 15;
            light_aura_on($a, $pers['uid']);
        }
    }

    if ($modified && set_vars(aq($pers), $pers['uid'])) {
        $stmt = $db->prepare("DELETE FROM p_auras WHERE uid = :uid AND esttime <= :time AND turn_esttime <= :turn AND autocast = 0");
        $stmt->execute([':uid' => $pers['uid'], ':time' => tme(), ':turn' => $pers['f_turn']]);
    } elseif ($count) {
        $stmt = $db->prepare("DELETE FROM p_auras WHERE uid = :uid AND esttime <= :time AND turn_esttime <= :turn AND autocast = 0");
        $stmt->execute([':uid' => $pers['uid'], ':time' => tme(), ':turn' => $pers['f_turn']]);
    }
}

/**
 * Экипирует предмет на персонажа, снимая конфликтующие предметы
 * @param int $id_of_weapon ID предмета
 * @param bool $checker Флаг проверки требований (true - без проверки)
 * @return void
 */
function dress_weapon($id_of_weapon, $checker) {
    global $pers, $db;

    $stmt = $db->prepare("SELECT * FROM wp WHERE id = :id AND uidp = :uidp AND weared = 0");
    $stmt->execute([':id' => (int)$id_of_weapon, ':uidp' => (int)$pers['uid']]);
    $v = $stmt->fetch();

    if (!$v) return;

    $z = $pers['level'] >= $v['tlevel'];
    if (!$checker) {
        foreach ($v as $key => $value) {
            if (str_starts_with($key, 't') && $key !== 'timeout' && $pers[substr($key, 1)] < $value && $value > 0) {
                $z = false;
                break;
            }
        }
    }

    if ($z) {
        foreach (all_params() as $a) {
            if ($v[$a]) $pers[$a] += $v[$a];
        }
        $pers['hp'] += 5 * $v['s4'];
        $pers['ma'] += 9 * $v['s6'];

        if ($v['type'] === 'orujie') {
            $stmt = $db->prepare("SELECT COUNT(id) FROM wp WHERE uidp = :uidp AND weared = 1 AND type = 'orujie'");
            $stmt->execute([':uidp' => $pers['uid']]);
            $tmp = $stmt->fetchColumn();

            if ($tmp >= 2) {
                $condition = ($v['stype'] === 'noji' || $v['stype'] === 'shit') ? "stype IN ('noji', 'shit')" : "stype NOT IN ('noji', 'shit')";
                $stmt = $db->prepare("SELECT * FROM wp WHERE uidp = :uidp AND weared = 1 AND type = 'orujie' AND $condition LIMIT 1");
                $stmt->execute([':uidp' => $pers['uid']]);
                $w_for_remove = $stmt->fetch();
                if ($w_for_remove) remove_weapon($w_for_remove['id'], $w_for_remove);
            } elseif ($tmp == 1) {
                $stmt = $db->prepare("SELECT * FROM wp WHERE uidp = :uidp AND weared = 1 AND type = 'orujie' LIMIT 1");
                $stmt->execute([':uidp' => $pers['uid']]);
                $w_for_remove = $stmt->fetch();
                if ($v['stype'] !== 'noji' && $v['stype'] !== 'shit' && $w_for_remove['stype'] !== 'noji' && $w_for_remove['stype'] !== 'shit') {
                    remove_weapon($w_for_remove['id'], $w_for_remove);
                }
            }
        } elseif ($v['type'] === 'kolco') {
            $stmt = $db->prepare("SELECT COUNT(id) FROM wp WHERE uidp = :uidp AND weared = 1 AND type = 'kolco'");
            $stmt->execute([':uidp' => $pers['uid']]);
            if ($stmt->fetchColumn() >= 2) {
                $stmt = $db->prepare("SELECT * FROM wp WHERE uidp = :uidp AND weared = 1 AND type = 'kolco' LIMIT 1");
                $stmt->execute([':uidp' => $pers['uid']]);
                $w_for_remove = $stmt->fetch();
                if ($w_for_remove) remove_weapon($w_for_remove['id'], $w_for_remove);
            }
        } elseif ($v['type'] === 'kam') {
            $stmt = $db->prepare("SELECT COUNT(id) FROM wp WHERE uidp = :uidp AND weared = 1 AND type = 'kam'");
            $stmt->execute([':uidp' => $pers['uid']]);
            if ($stmt->fetchColumn() == 4) {
                $stmt = $db->prepare("SELECT * FROM wp WHERE uidp = :uidp AND weared = 1 AND type = 'kam' LIMIT 1");
                $stmt->execute([':uidp' => $pers['uid']]);
                $w_for_remove = $stmt->fetch();
                if ($w_for_remove) remove_weapon($w_for_remove['id'], $w_for_remove);
            }
        } else {
            $stmt = $db->prepare("SELECT * FROM wp WHERE uidp = :uidp AND weared = 1 AND type = :type LIMIT 1");
            $stmt->execute([':uidp' => $pers['uid'], ':type' => $v['type']]);
            $w_for_remove = $stmt->fetch();
            if ($w_for_remove) remove_weapon($w_for_remove['id'], $w_for_remove);
        }

        $stmt = $db->prepare("UPDATE wp SET weared = 1 WHERE id = :id");
        $stmt->execute([':id' => $v['id']]);

        $aq = aq($pers);
        if ($aq) {
            $stmt = $db->prepare("UPDATE users SET $aq WHERE uid = :uid");
            $stmt->execute([':uid' => $pers['uid']]);
        }
    }
}

/**
 * Добавляет запись в лог боя
 * @param string $txt Текст лога
 * @param int|null $cfight ID боя (по умолчанию берётся из $pers)
 * @return void
 */
function add_flog($txt, $cfight = null) {
    global $battle_log, $pers, $db;

    $cfight = $cfight ?? $pers['cfight'];
    $txt = rtrim($txt, '%');
    $time = date('H:i');
    $turn = round(microtime(true), 2);

    $stmt = $db->prepare("INSERT INTO fight_log (time, log, cfight, turn) VALUES (:time, :log, :cfight, :turn)");
    $stmt->execute([':time' => $time, ':log' => $txt, ':cfight' => (int)$cfight, ':turn' => $turn]);

    $txt = "<font class=\"timef\">$time</font> " . str_replace('%', "<br><font class=\"timef\">$time</font> ", $txt);
    $battle_log .= $txt;

    $stmt = $db->prepare("UPDATE fights SET all = CONCAT(:txt, ';', all), ltime = :ltime WHERE id = :id");
    $stmt->execute([':txt' => $txt, ':ltime' => time(), ':id' => (int)$cfight]);
}

/**
 * Возвращает знак числа (-1, 0, 1)
 * @param int|float $x Число
 * @return int Знак числа
 */
function signum($x) {
    $x = (float)$x;
    return $x > 0 ? 1 : ($x < 0 ? -1 : 0);
}

/**
 * Выполняет декодирование строки с использованием битовых операций
 * @param string $value Входная строка
 * @return int Результат декодирования
 */
function uncrypt($value) {
    $result = 0;
    $key = 754;
    for ($i = 0; $i < strlen($value); $i++) {
        $result += (ord($value[$i]) << (($i + 23) >> 1) << 1) ^ ($key ^ 9 + $i);
    }
    $result = abs($result % 10000);
    return $result < 1000 ? $result + 2343 : $result;
}

/**
 * Форматирует число с добавлением знака
 * @param int|float $param Число
 * @return string Число со знаком (+ или -)
 */
function plus_param($param) {
    $param = (float)$param;
    return $param > 0 ? "+$param" : ($param < 0 ? '-' . abs($param) : '0');
}

/**
 * Возвращает список всех параметров персонажа
 * @return string[] Массив кодов параметров
 */
function all_params() {
    $r = array_merge(
        array_map(fn($i) => "s$i", range(1, 6)),
        ['kb'],
        array_map(fn($i) => "mf$i", range(1, 5)),
        ['hp', 'ma', 'udmin', 'udmax'],
        array_map(fn($i) => "sp$i", range(1, 14)),
        array_map(fn($i) => "sb$i", range(1, 14)),
        array_map(fn($i) => "sm$i", range(1, 7)),
        array_map(fn($i) => "a$i", range(1, 8)),
        array_map(fn($i) => "m$i", range(1, 5))
    );
    return $r;
}

/**
 * Рассчитывает опыт за нанесённый урон
 * @param int|float $damage Нанесённый урон
 * @param int $yourlvl Уровень атакующего
 * @param int $vslvl Уровень цели
 * @param bool $notnpc Флаг игрока (true - игрок, false - NPC)
 * @param int|float $rank Ранг цели
 * @return int Рассчитанный опыт
 */
function experience($damage, $yourlvl, $vslvl, $notnpc, $rank) {
    $koeff = $notnpc ? 1.9 : 0.6 * sqrt(sqrt(($rank + 1) / 3));
    if ($yourlvl <= 2) $koeff += 1.7;
    if ($yourlvl < 5) $koeff += 0.7;
    if ($notnpc || $yourlvl < 4) $koeff *= sqrt(sqrt($vslvl + 1.1));

    $levelDiff = $vslvl - $yourlvl;
    $koeff *= match (true) {
        $yourlvl >= $vslvl + 3 => 0.2 * (($vslvl + 1) / ($yourlvl + 1)),
        $yourlvl == $vslvl + 2 => 0.5,
        $yourlvl == $vslvl + 1 => 0.7,
        $yourlvl == $vslvl => 1,
        $yourlvl == $vslvl - 1 => $notnpc ? 1.4 : 1.2,
        $yourlvl == $vslvl - 2 => $notnpc ? 1.8 : 1.4,
        $yourlvl == $vslvl - 3 => $notnpc ? 2.6 : 1.6,
        $yourlvl < $vslvl - 3 => ($notnpc ? 3.0 : 2.0) * (($vslvl + 1) / ($yourlvl + 5)),
        default => 1
    };

    $koeff *= mtrunc(0.9 + $levelDiff * 0.10) + 0.1;
    return floor($damage * $koeff);
}

/**
 * Логирует финансовую транзакцию
 * @param int $type Тип транзакции
 * @param int $uid ID пользователя
 * @param string $user Имя пользователя
 * @param int|float $money1 Сумма входа
 * @param int|float $money2 Сумма выхода
 * @param string $title Описание транзакции
 * @param string $ip1 IP отправителя
 * @param string $ip2 IP получателя
 * @return void
 */
function transfer_log($type, $uid, $user, $money1, $money2, $title, $ip1, $ip2) {
    global $db;

    $stmt = $db->prepare("INSERT INTO transfer (date, type, uid, who, transfer_in, transfer_out, title, ip1, ip2) VALUES (:date, :type, :uid, :who, :transfer_in, :transfer_out, :title, :ip1, :ip2)");
    $stmt->execute([
        ':date' => time(),
        ':type' => (int)$type,
        ':uid' => (int)$uid,
        ':who' => $user,
        ':transfer_in' => (float)$money1,
        ':transfer_out' => (float)$money2,
        ':title' => $title,
        ':ip1' => $ip1,
        ':ip2' => $ip2
    ]);
}

/**
 * Рассчитывает шанс уворота персонажа от атаки противника
 * @param array $pers Данные атакующего персонажа
 * @param array $persvs Данные цели
 * @return float Шанс уворота (от 0 до 70)
 */
function ylov($pers, $persvs) {
    $vsR = mtrunc($persvs['s2'] * ($persvs['mf2'] / 100 + 1));
    $yoR = mtrunc($pers['s2'] * ($pers['mf3'] / 100 + 1));
    $ylov = 50 * mtrunc(1 - $yoR / ($vsR ?: 1)) * sqrt($vsR / 4);
    $ylov *= mtrunc($persvs['level'] - $pers['level']) * 0.20 + 1;
    return min(max($ylov, 0), 70);
}

/**
 * Рассчитывает шанс критического удара персонажа против противника
 * @param array $pers Данные атакующего персонажа
 * @param array $persvs Данные цели
 * @return float Шанс критического удара (от 0 до 70)
 */
function sokr($pers, $persvs) {
    $vsR = mtrunc($persvs['s3'] * ($persvs['mf4'] / 100 + 1));
    $yoR = mtrunc($pers['s3'] * ($pers['mf1'] / 100 + 1));
    $sokr = 50 * mtrunc(1 - $vsR / ($yoR ?: 1)) * sqrt($yoR / 4);
    $sokr *= mtrunc($pers['level'] - $persvs['level']) * 0.20 + 1;
    return min(max($sokr, 0), 70);
}

/**
 * Рассчитывает шанс яростного удара персонажа против противника
 * @param array $pers Данные атакующего персонажа
 * @param array $persvs Данные цели
 * @return float Шанс яростного удара (от 2 до 90)
 */
function yar($pers, $persvs) {
    $yar = (3 + $pers['mf5'] / 5 - $pers['mf4'] / 20) / 5;
    $yar *= mtrunc($pers['level'] - $persvs['level']) * 0.20 + 1;
    return min(max($yar, 2), 90);
}

/**
 * Рассчитывает урон удара персонажа по противнику
 * @param array $pers Данные атакующего персонажа
 * @param array $persvs Данные цели
 * @return int Урон удара
 */
function ydar($pers, $persvs) {
    $ydar = rand($pers['udmin'] * 10, $pers['udmax'] * 10 + 10) / 20;
    $ydar = $ydar * sqrt($ydar);
    $ydar *= mtrunc($pers['level'] - $persvs['level']) * 0.20 + 1;
    $kb = mtrunc($persvs['kb'] + $persvs['sb11']) ?: 1;
    $ydar = mtrunc($pers['sb2'] + $pers['s1'] + $ydar);
    $ydar = $ydar * (pow(0.89, sqrt($kb)) + 0.1);
    return floor(mtrunc(rand($ydar - 3, $ydar + 3)));
}

/**
 * Рассчитывает процент уменьшения урона от брони
 * @param array $pers Данные персонажа
 * @return int Процент уменьшения урона
 */
function DecreaseDamage($pers) {
    $kb = mtrunc($pers['kb'] + $pers['sb11']) ?: 1;
    return round(100 - (pow(0.9, sqrt($kb)) + 0.1) * 100);
}

/**
 * Преобразует время в секундах в текстовое представление "назад"
 * @param int|float $seconds Время в секундах
 * @return string Текстовое представление времени
 */
function time_echo($seconds) {
    $seconds = floor((float)$seconds);
    $d = floor($seconds / 86400);
    $h = floor(($seconds % 86400) / 3600);
    $m = floor(($seconds % 3600) / 60);
    $s = $seconds % 60;

    if (!$d && !$h && !$m) return 'только что';
    if (!$d && !$h) {
        return match (true) {
            $m % 10 == 1 && $m != 11 => "$m минуту назад",
            $m % 10 >= 2 && $m % 10 <= 4 && !($m >= 12 && $m <= 14) => "$m минуты назад",
            default => "$m минут назад"
        };
    }
    if (!$d) {
        return match (true) {
            $h % 10 == 1 && $h != 11 => "$h час назад",
            $h % 10 >= 2 && $h % 10 <= 4 && !($h >= 12 && $h <= 14) => "$h часа назад",
            default => "$h часов назад"
        };
    }
    return match (true) {
        $d == 1 => 'вчера',
        $d == 2 => 'позавчера',
        $d >= 3 && $d <= 4 => "$d дня назад",
        $d >= 5 && $d < 7 => "$d дней назад",
        $d >= 7 && $d < 14 => 'неделю назад',
        $d >= 14 && $d < 35 => floor($d / 7) . ' недели назад',
        default => floor($d / 7) . ' недель назад'
    };
}

/**
 * Извлекает старшее слово (16 бит) из числа
 * @param int $value Входное число
 * @return int Старшее слово
 */
function HIWORD($value) {
    return (int)$value >> 16;
}
/**
 * Извлекает младшее слово (16 бит) из числа
 * @param int $value Входное число
 * @return int Младшее слово
 */
function LOWORD($value) {
    return ((int)$value << 16) >> 16;
}
/**
 * Преобразует число в старшее слово (сдвиг на 16 бит влево)
 * @param int $value Входное число
 * @return int Результат сдвига
 */
function TOHIWORD($value) {
    return (int)$value << 16;
}

/**
 * Возвращает эквивалентное значение навыка для расчётов
 * @param string $skill Код навыка
 * @return int Эквивалентное значение
 */
function EqualValueOfSkill($skill) {
    return match (true) {
        str_starts_with($skill, 's') && strlen($skill) == 2 => 1,
        str_starts_with($skill, 'm') && strlen($skill) == 3 => 10,
        $skill === 'kb' || $skill === 'hp' => 10,
        $skill === 'ma' => 12,
        str_starts_with($skill, 's') && in_array($skill[1], ['b', 'm']) && strlen($skill) == 3 => 1,
        str_starts_with($skill, 'sp') && strlen($skill) == 3 => 5,
        $skill === 'udmin' || $skill === 'udmax' => 3,
        default => 0
    };
}

/**
 * Проверяет, является ли предмет экипируемым
 * @param array $item Данные предмета
 * @return bool True, если предмет можно экипировать, иначе false
 */
function IsWearing($item) {
    $wearableTypes = [
        'shlem', 'orujie', 'kolco', 'bronya', 'naruchi',
        'perchatki', 'ojerelie', 'sapogi', 'poyas', 'kam'
    ];
    return in_array($item['type'] ?? '', $wearableTypes);
}
/**
 * Возвращает имя пользователя по его ID
 * @param int $uid ID пользователя (по умолчанию 0)
 * @return string|false Имя пользователя или false, если не найдено
 */
function _UserByUid($uid = 0) {
    return $uid ? sqlr("SELECT user FROM users WHERE uid = :uid", 0, ['uid' => (int)$uid]) : false;
}
/**
 * Возвращает ID пользователя по его имени
 * @param string $user Имя пользователя (по умолчанию пусто)
 * @return int|false ID пользователя или false, если не найдено
 */
function _UidByUser($user = '') {
    if ($user) {
        $user = str_replace(["'", "\\"], '', $user);
        return sqlr("SELECT uid FROM users WHERE smuser = LOWER(:user)", 0, ['user' => $user]);
    }
    return false;
}

/**
 * Возвращает данные об экипированном оружии пользователя
 * @param int $uid ID пользователя (по умолчанию берётся из $pers)
 * @return array Ассоциативный массив с данными оружия
 */
function Weared_Weapons($uid = 0) {
    global $pers, $db;

    $uid = $uid ?: $pers['uid'];
    $stmt = $db->prepare("SELECT stype, udmin, udmax, kb FROM wp WHERE uidp = :uidp AND weared = 1 AND type = 'orujie'");
    $stmt->execute([':uidp' => (int)$uid]);
    $weapons = $stmt->fetchAll();

    $result = [
        'noji' => 0, 'mech' => 0, 'topo' => 0, 'drob' => 0, 'shit' => 0
    ];
    foreach ($weapons as $a) {
        $result[$a['stype']]++;
        $result[$a['stype']] = array_merge($result[$a['stype']] ?: [], [
            'udmin' => $a['udmin'],
            'udmax' => $a['udmax'],
            'kb' => $a['kb']
        ]);
    }
    $result['OD'] = $result['noji'] * 1 + $result['mech'] * 2 + $result['topo'] * 3 + $result['drob'] * 4 + $result['shit'] * 1;

    return $result;
}

/**
 * Возвращает список типов предметов с их названиями
 * @return array Ассоциативный массив типов
 */
function types() {
    return [
        'orujie' => 'Оружие', 'shlem' => 'Шлемы', 'ojerelie' => 'Ожерелья', 'poyas' => 'Пояса',
        'sapogi' => 'Сапоги', 'naruchi' => 'Наручи', 'perchatki' => 'Перчатки', 'kolco' => 'Кольца',
        'bronya' => 'Брони', 'napad' => 'Свитки нападения', 'zakl' => 'Свитки заклинаний',
        'teleport' => 'Свитки телепорта', 'zelie' => 'Зелья/камни', 'kam' => 'Зелья восстановления',
        'potion' => 'Зелья', 'herbal' => 'Травы', 'fishing' => 'Рыболовные снасти', 'fish' => 'Рыба',
        'resources' => 'Ресурсы', 'rune' => 'Руны'
    ];
}

/**
 * Возвращает название типа предмета по его коду
 * @param string $type Код типа предмета
 * @return string|null Название типа или null, если не найдено
 */
function type_names($type) {
    return types()[$type] ?? null;
}

/**
 * Возвращает текстовое описание характера по значению статистики
 * @param int $value Значение статистики
 * @return string Описание характера
 */
function kind_stat($value) {
    $value = (int)$value;
    return match (true) {
        $value > 5 => 'Добряк',
        $value > 2 => 'Добрый',
        $value > 0 => 'Отзывчивый',
        $value == 0 => 'Нейтрален',
        $value > -2 => 'Хитрый',
        $value > -5 => 'Коварный',
        $value > -7 => 'Алчный',
        default => 'Злой'
    };
}
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    if (!is_string($data)) {
        return $data;
    }
    return htmlspecialchars(trim(str_replace(["'", "\\"], '', $data)), ENT_QUOTES, 'UTF-8');
}
?>