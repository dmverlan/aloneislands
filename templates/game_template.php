<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>AloneIslands[<?= htmlspecialchars($pers['user'], ENT_QUOTES, 'UTF-8') ?>]</title>
    <link rel="icon" href="images/icon.ico">
    <link rel="stylesheet" href="main.css">
    <script src="js/cookie.js" defer></script>
    <script src="js/jquery.js" defer></script>
    <script src="js/game.js?2" defer></script>
</head>
<body style="overflow: hidden;">
    <script>
        const today = new Date();
        var hours = <?= json_encode(date('H', $currentTime)) ?>;
        var minutes = <?= json_encode(date('i', $currentTime)) ?>;
        var seconds = <?= json_encode(date('s', $currentTime)) ?>;
        var ctip = <?= json_encode((int)$pers['ctip']) ?>;
        var SoundsOn = <?= json_encode($pers['sound'] == 1 ? 0 : 1) ?>;
        var csrfToken = '<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>';
        view_frames(<?= json_encode($night) ?>);
    </script>
    <input type="hidden" id="csrf-token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
</body>
</html>