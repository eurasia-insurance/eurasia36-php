<?php

header('Content-Type: text/html; charset=utf-8');

session_start();

require_once(__DIR__.'/.settings.php');

$langs = ['ru' => ['Русский', 'ru_RU', 'RUSSIAN'], 'kz' => ['Қазақша', 'kk_KZ', 'KAZAKH']/*, 'en' => ['English', 'en_US']*/];


$lang = (isset($_GET['lang']) && array_key_exists($_GET['lang'], $langs)) ? $_GET['lang'] : 'ru';
$apiLang = $lang == 'kz' ? 'kk' : $lang;

$_SESSION['apiLang'] = $apiLang;

putenv("LC_ALL=".$langs[$lang][1]);
setlocale(LC_ALL, $langs[$lang][1]);

bindtextdomain("$lang", "./i18n");
textdomain("$lang");
bind_textdomain_codeset($lang, 'utf-8');



$_SESSION['initMain'] = 1;

$timestamp = time();

$tsstring = gmdate('D, d M Y H:i:s ', $timestamp) . 'GMT';
$etag = $lang . $timestamp;

$if_modified_since = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false;
$if_none_match = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? $_SERVER['HTTP_IF_NONE_MATCH'] : false;
if ((($if_none_match && $if_none_match == $etag) || (!$if_none_match)) &&
($if_modified_since && $if_modified_since == $tsstring)) {

header('HTTP/1.1 304 Not Modified');
exit();
} else {
header("Last-Modified: $tsstring");
header("ETag: \"{$etag}\"");
}


$widgetId = false;
if(isset($_GET['widgetId']) && (int)$_GET['widgetId'] != 0) {
    $widgetId = (int)$_GET['widgetId'];
}


$widgets = [
    1 => 'theeurasia',
    2 => 'eubank',
];



?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= _("Обязательная страховка автомобиля (ОГПО) с бесплатной доставкой — страховая компания \"Евразия\"") ?></title>

    <!-- Bootstrap core CSS -->
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="/css/widget.css?<?= filemtime(__DIR__ .'/css/widget.css') ?>" rel="stylesheet"/>
    <?php if($widgetId): ?>
    <link href="/css/<?= $widgets[$widgetId] ?>.css?<?= filemtime(__DIR__ .'/css/'.$widgets[$widgetId].'.css') ?>" rel="stylesheet"/>
    <?php endif; ?>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
    <?php $formFromWidget = true; ?>
    <?php require('./__form.php') ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="/bootstrap/js/bootstrap.min.js"></script>
    <script src="/js/jquery.maskedinput.min.js"></script>

    <script type="text/javascript" src="/js/detect-resize/jquery.resize.js"></script>

    <script>

        <?php require('./__form.js.php') ?>

        var iframeHeight = function() {

            var height = $("#form-container").height() + 40;

            if (parent.postMessage) {
                parent.postMessage(height, '<?= $_SERVER['HTTP_REFERER'] ?>');
            }
        }

        $('#form-container').resize(iframeHeight);

        $(document).ready(function () {
            iframeHeight();
        });

        //Ещё одна заявка
        $("#oneMorePolicy").click(function(e) {

            e.preventDefault();

            if (parent.postMessage) {
                parent.postMessage('reload', '<?= $_SERVER['HTTP_REFERER'] ?>');
            }
        });



    </script>
</body>
</html>