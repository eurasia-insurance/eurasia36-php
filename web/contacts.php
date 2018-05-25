<?php

header('Content-Type: text/html; charset=utf-8');

require_once(__DIR__ . '/api/Util.php');

$langs = ['ru' => ['Русский', 'ru_RU'], 'kz' => ['Қазақша', 'kk_KZ']/*, 'en' => ['English', 'en_US']*/];


$lang = (isset($_GET['lang']) && array_key_exists($_GET['lang'], $langs)) ? $_GET['lang'] : 'ru';

putenv("LC_ALL=".$langs[$lang][1]);
setlocale(LC_ALL, $langs[$lang][1]);

bindtextdomain("$lang", "./i18n");
textdomain("$lang");
bind_textdomain_codeset($lang, 'utf-8');

require './api/EurasiaAPI.php';


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


$url = 'eurasia/pos/all/';

$data = '{}';

$apiLang = $lang == 'kz' ? 'kk' : $lang;
$offices = EurasiaAPI::request($url, $data, 'get', $apiLang);
$offices = json_decode($offices, true);

if(isset($offices['code']) && $offices['code'] == 500) {
    throw new Exception("WS down", 500);
}


ob_start("Util::minifyHtml");

?>
<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <link rel="icon" type="image/png" href="./favicon.png" />
        <link rel="apple-touch-icon" href="./apple-touch-favicon.png" />

        <title><?= _("Адреса и телефоны страховой компании \"Евразия\"") ?></title>

        <!-- Bootstrap core CSS -->
        <style>
            <?php require(__DIR__.'/bootstrap/css/bootstrap.min.css') ?>
            <?php require(__DIR__.'/css/styles.css') ?>
        </style>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <?php include './__GA.php'; ?>
        <?php include './__YM.php'; ?>
    </head>

    <body>
        <?php if($_SERVER['HTTP_HOST'] != 'eurasia.loc'): ?>
        <noscript><div><img src="https://mc.yandex.ru/watch/39882375" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
        <?php endif; ?>

        <header>
            <nav class="navbar navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="./<?= $lang != 'ru' ? Util::passParameters($lang) : Util::passParameters() ?>"><img src="./i/logo-<?= $lang ?>.svg" alt="Евразия" class="navbar-brand__logo" /></a>
                    </div>
                    <div id="navbar" class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li><span><a href="tel:88000800099" class="header-tel">8 800 080-00-99</a> <br class="visible-sm-inline"/><?= _("или") ?> <a href="tel:5678" class="header-tel">5678</a><br/><small><?= _("звонок бесплатный") ?></small></span></li>
                            <li><a href="#info" data-toggle="modal" data-target="#delivery"><?= _("Доставка и оплата") ?></a></li>
                            <li><span><?= _("Адреса и телефоны") ?></span></li>
                            <li class="lang-li first-lang-li"><?php if($lang == 'ru'): ?><span class="current-lang">RU</span><?php else: ?><a href="contacts.php<?= Util::passParameters() ?>">RU</a><?php endif; ?></li>
                            <li class="lang-li"><?php if($lang == 'kz'): ?><span class="current-lang">KZ</span><?php else: ?><a href="<?= Util::passParameters('kz') ?>">KZ</a><?php endif; ?></li>
                        </ul>
                    </div><!--/.nav-collapse -->
                </div>
            </nav>

        </header>

        <section>
            <div class="container addresses">

                <div class="row">
                    <div class="col-xs-12">
                        <h1><?= _("Адреса и телефоны") ?></h1>

                        <h4 style="line-height: 1.5">
                            <?= _("8 800 080-00-99 — бесплатная горячая линия по Казахстану") ?><br/>
                            <?= _("5678 — бесплатно с мобильного") ?><br/>
                            <?= _("Эл. почта: <a href=\"mailto:info@theeurasia.kz\">info@theeurasia.kz</a>") ?>
                        </h4>
                    </div>
                </div>

                <?php

                $addrTemplate = function($addr, $showName = false) {

                    if($showName) {
                        echo '<h4>'.htmlspecialchars(str_replace("АО «СК «Евразия»", "", $addr['name'])).'</h4>';
                    }

                    echo htmlspecialchars($addr['address']).'<br/>';

                    foreach($addr['phones'] as $phone) {

                        $number = explode(") ", $phone['fullNumber']);

                        if(strlen($number[1]) == 6) {
                            $number[1] = $number[1][0].$number[1][1].'-'.$number[1][2].$number[1][3].'-'.$number[1][4].$number[1][5];
                        } else {
                            $number[1] = $number[1][0].$number[1][1].$number[1][2].'-'.$number[1][3].$number[1][4].'-'.$number[1][5].$number[1][6];
                        }

                        echo $phone['type'].': '.implode(') ', $number).'<br/>';
                    }
//                    foreach($addr['emails'] as $email) {
//                        echo $email['address'];
//                    }
                };

                if(isset($offices['error'])) {
                    $error = ['error' => true, 'message' => 'Неверно указана эл. почта', 'systemMessage' => $offices['message']];
                    die(json_encode($error));
                } else {

                    $rowOpen = false;

                    foreach ($offices as $key => $city) {

                        if($city['name'] == 'Алматы' || $city['name'] == 'Астана') {
                            ?>
                            <div class="row">
                                <div class="col-xs-12">
                                    <h2><?= htmlspecialchars($city['name']) ?></h2>
                                </div>
                                <?php foreach($city['poses'] as $addr): ?>
                                <div class="col-sm-3">
                                    <?= $addrTemplate($addr, true); ?>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php
                        } else {
                            ?>

                            <?php if(!$rowOpen): ?>
                                <div class="row">
                                <?php $rowOpen = true; ?>
                            <?php endif; ?>

                                <div class="col-sm-3">
                                    <h2><?= htmlspecialchars($city['name']) ?></h2>
                                    <?php
                                    foreach($city['poses'] as $addr) {
                                        echo $addrTemplate($addr);
                                    }
                                    ?>
                                </div>

                            <?php
                        }


                        if($rowOpen && count($city) == $key+1) {
                            echo '</div>';
                            $rowOpen = false;
                        }
                    }
                }

    //            echo '<pre>';
    //            print_r($offices);
    //            echo '</pre>';

                ?>

            </div><!-- /.container -->
        </section>

        <footer>
            <div class="container footer">
                <div class="row">
                    <div class="col-sm-6 footer__copyright">
                        <?= _("© АО «Страховая компания «Евразия»") ?>
                    </div>
                </div>
            </div>
        </footer>


        <!-- Доставка и оплата -->
        <div class="modal fade modal-transparent" id="delivery" tabindex="-1" role="dialog" aria-labelledby="delivery">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">&nbsp;</button>
                        <h4 class="modal-title" id="myModalLabel"><?= _("Доставка и оплата") ?></h4>
                    </div>
                    <div class="modal-body">
                        <p class="delivery-time"><?= _("Доставляем полисы только по г. Алматы<br/>с 9:00 до 19:00 в будние дни.") ?></p>

                        <p><?= _("Самовывоз возможен в Алматы, Астане, Караганде, Усть-Каменогорске, Костанае, Актау, Павлодаре, Атырау и Актобе.") ?></p>

                        <p><?= _("Принимаем заказы круглосуточно, без выходных и праздничных дней.") ?></p>

                        <p class="delivery-time"><?= _("Оплата наличными курьеру при получении.") ?></p>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script>
            <?php require(__DIR__.'/bootstrap/js/bootstrap.min.js') ?>
        </script>

        <?php include './__jivosite.php'; ?>

    </body>
</html>
