<?php

header('Content-Type: text/html; charset=utf-8');

require_once(__DIR__ . '/api/EurasiaAPI.php');
require_once(__DIR__ . '/api/Util.php');

$ping = EurasiaAPI::ping();
if($ping != '0') {

    throw new Exception($ping['message'], 500);
}


session_start();


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


ob_start("Util::minifyHtml");

?>
<!DOCTYPE html>
<html lang="ru">
    <head>
	<?php include './__GTM-head.php'; ?>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <link rel="icon" type="image/png" href="./favicon.png" />
        <link rel="apple-touch-icon" href="./apple-touch-favicon.png" />

        <title><?= _("Обязательная страховка автомобиля (ОГПО) с бесплатной доставкой — страховая компания \"Евразия\"") ?></title>

        <style>
            <?php require(__DIR__.'/bootstrap/css/bootstrap.min.css') ?>
            <?php require(__DIR__.'/css/styles.css') ?>
        </style>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <script src='https://www.google.com/recaptcha/api.js'></script>

        <?php include './__GA.php'; ?>
        <?php include './__YM.php'; ?>
    </head>

    <body>
	<?php include './__GTM-body.php'; ?>
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
                        <span class="navbar-brand"><img src="./i/logo-<?= $lang ?>.svg" alt="Евразия" class="navbar-brand__logo" /></span>

<!--                        <div class="dropdown langs">
                            <button class="btn dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <?= $langs[$lang][0] ?>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                <?php foreach($langs as $key => $l): ?>
                                <?php if($key != $lang): ?>
                                <li><a href="./<?= $key != 'ru' ? $key : '' ?>"><?= $l[0] ?></a></li>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>-->
                    </div>
                    <div id="navbar" class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li>
                                <span>
                                    <a href="tel:88000800099" class="header-tel">8 800 080-00-99</a>&nbsp;<br class="visible-sm-inline"/><?= _("или") ?> <a href="tel:5678" class="header-tel">5678</a><br/><small><?= _("звонок бесплатный") ?></small>
                                </span>
                                <br/>
                                <a href="#callback"
                                   data-toggle="modal" data-target="#callback"
                                   style="display: inline-block; font-size: 12px;padding-top: 0;padding-left: 10px;"
                                   class=""><?= _("Перезвоните мне") ?></a>
                            </li>
                            <li><a href="#info" data-toggle="modal" data-target="#delivery"><?= _("Доставка и оплата") ?></a></li>
                            <li><a href="https://box.eurasia36.kz"><?= _("Отправить документы") ?></a></li>
                            <li><a href="./contacts.php<?= $lang != 'ru' ? Util::passParameters($lang) : Util::passParameters() ?>"><?= _("Контакты") ?></a></li>
                            <li class="lang-li first-lang-li"><?php if($lang == 'ru'): ?><span class="current-lang">RU</span><?php else: ?><a href="./<?= Util::passParameters() ?>">RU</a><?php endif; ?></li>
                            <li class="lang-li"><?php if($lang == 'kz'): ?><span class="current-lang">KZ</span><?php else: ?><a href="./kz<?= Util::passParameters() ?>">KZ</a><?php endif; ?></li>
                        </ul>
                    </div><!--/.nav-collapse -->
                </div>
            </nav>

            <div class="container">
                <!--
                <span class="ogpo-vts"><?= _("ОГПО ВТС") ?></span> <a href="./order/casco/index.html" class="avto-kasko">Авто Каско</a>
                -->
                <h1><?= _("Обязательная автостраховка с&nbsp;бесплатной доставкой") ?></h1>

                <div class="row pluses">
                    <div class="col-sm-4"><img src="./i/check.svg" alt="✓" /> <?= _("Быстрое оформление полиса") ?></div>
                    <div class="col-sm-4"><img src="./i/check.svg" alt="✓" /> <?= _("Минимальная цена + бонусы") ?></div>
                    <div class="col-sm-4"><img src="./i/check.svg" alt="✓" /> <?= _("Работаем c 1995 года") ?></div>
                </div>
            </div>
        </header>

        <div class="container">

            <div class="row">
                <div class="col-xs-12 col-sm-8">
                    <?php require('./__form.php') ?>
                </div>
                <div class="col-xs-12 col-sm-4">
                    <?php if(defined('PARAM_SHOW_EUBANK_BONUS') && PARAM_SHOW_EUBANK_BONUS == 1): ?>

                        <?php if($lang == 'ru'): ?>
                        <a href="" class="bonus-teaser" data-toggle="modal" data-target="#cashback">
                            <span class="bonus-teaser__header">Получите<br/>бонус до 30%</span>
                            <span class="bonus-teaser__text">от стоимости страховки при оплате на сайте картой Евразийского банка</span>
                            <img src="./i/eubank-logo.png" alt="" />
                        </a>
                        <?php else: ?>
                        <a href="" class="bonus-teaser" data-toggle="modal" data-target="#cashback">
                            <span class="bonus-teaser__text bonus-teaser__text_kz">Сайтта Еуразиялық банктің картасымен төлеу кезінде сақтандыру құнынан</span>
                            <span class="bonus-teaser__header">30% дейін бонус алыңыз</span>
                            <img src="./i/eubank-logo.png" alt="" />
                        </a>
                        <?php endif; ?>

                    <?php endif; ?>

                    <div class="check-policy">
                        <h3><?= _("Проверка подлинности страхового полиса Евразии") ?></h3>

                        <p><?= _("Введите 12 цифр номера страховки") ?></p>

                        <form class="form-inline check-policy__form" action="check-policy.php" method="post">
                            <input
                                    type="text"
                                    name="policyNumber"
                                    class="form-control"
                                    id="check-policy"
                                    placeholder="<?= _("Номер полиса") ?>"
                                    required
                            />
                            <button type="submit" class="btn btn_transparent pull-right"><?= _("Проверить") ?></button>
                        </form>
                        <div class="check-policy__result"></div>
                    </div>

                    <div class="rating hidden-xs">
                        <img src="./i/rating.png" alt="" />
                        <?= _("У нас наивысший рейтинг среди частных финансовых компаний Казахстана: BB+/kzAA- (S&P, 2017).") ?>
                    </div>

                </div>
            </div>

            <div class="row about-company">
                <div class="col-sm-8 about-company__text">
                    <h2><?= _("О страховой компании «Евразия»") ?></h2>

                    <p><?= _("«Евразия» основана в 1995 году, и работает в 75 странах мира.") ?></p>

                    <p><?= _("Мы предлагаем все виды страхования для компаний и частных лиц.") ?></p>

                    <div class="about-company__payments visible-xs">
                        <h2><?= _("10 000 тенге  в&nbsp;минуту") ?></h2>
                        <?= _("или 5,9 млн. тенге в день выплачивает «Евразия» своим клиентам") ?>
                    </div>

                    <p><?= _("Компании страхуют у нас сотрудников, транспорт, имущество и гражданско-правовую ответственность. Частные лица — автомобили (КАСКО и ОГПО ВТС), имущество и здоровье при выезде за рубеж.") ?></p>

                    <p><?= _("Офисы компании работают в Алматы, Астане, Караганде и других крупных городах Казахстана.") ?></p>

                    <p><?= _("В октябре 2017 года Standard & Poor’s присвоил «Евразии» наивысший среди частных финансовых компаний Казахстана рейтинг «ВВ+/kzAA-» (прогноз позитивный).") ?></p>

                    <p><a href="http://theeurasia.kz" target="_blank">theeurasia.kz</a></p>

                    <p class="about-company__links"><a href="#main-form" class="btn btn-blue"><?= _("Узнать стоимость страховки") ?></a> <span class="ili"><?= _("или") ?></span> <a href="" data-toggle="modal" data-target="#callback"><?= _("заказать звонок") ?></a></p>
                </div>
                <div class="col-sm-4 about-company__right-col hidden-xs">
                    <h2><?= _("10 000 тенге  в&nbsp;минуту") ?></h2>
                    <?= _("или 5,9 млн. тенге в день выплачивает «Евразия» своим клиентам") ?>
                </div>
            </div>

        </div><!-- /.container -->

        <footer>
            <div class="container footer">
                <div class="row">
                    <div class="col-sm-4 col-xs-12 footer__copyright">
                        <?= _("© АО «Страховая компания «Евразия»") ?>
                    </div>
                    <div class="col-sm-3 col-xs-12 footer__box">
                        <a href="https://box.eurasia36.kz"><?= _("Отправить нам документы") ?></a>
                        <?php
                        /**
                        <br/>
                        <a href="https://creditpolis.theeurasia.kz/"><?= _("Страховка в рассрочку") ?></a>
                         */
                        ?>
                    </div>
                    <div class="col-sm-5 col-xs-12">
                        <div class="footer__grafica <?= $lang ?>-footer__grafica">
                            <a href="http://grafica.kz">
                                <img src="./i/grafica.svg" alt="Grafica" />
                                <?= _("Сайт сделан<br/>в&nbsp;<span>студии&nbsp;«Графика»") ?></span>
                            </a>
                        </div>
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
                        <p class="delivery-time"><?= _("Доставляем полисы по&nbsp;Алматы и&nbsp;Астане в&nbsp;будние дни с&nbsp;9:00 до&nbsp;22:00, в&nbsp;субботу с&nbsp;9:00 до&nbsp;18:00.") ?></p>

                        <p><?= _("Самовывоз возможен в Алматы, Астане, Караганде, Усть-Каменогорске, Костанае, Актау, Павлодаре, Атырау и Актобе.") ?></p>

                        <p><?= _("Принимаем заказы круглосуточно, без выходных и праздничных дней.") ?></p>

                        <p class="delivery-time"><?= _("Оплата наличными курьеру при получении.") ?></p>
                    </div>
                </div>
            </div>
        </div>


        <!-- Заказ звонка -->
        <div class="modal fade modal-transparent" id="callback" tabindex="-1" role="dialog" aria-labelledby="delivery">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">&nbsp;</button>
                        <h4 class="modal-title" id="myModalLabel"><?= _("Заказ звонка") ?></h4>
                    </div>
                    <div class="modal-body">

                        <form method="post" action="callback.php" id="callback-form">
                            <?php if(isset($_GET['utm_source']) && trim($_GET['utm_source']) != ''): ?>
                            <input type="hidden" name="utm[source]" value="<?= isset($_GET['utm_source']) ? urldecode($_GET['utm_source']) : '' ?>" />
                            <input type="hidden" name="utm[medium]" value="<?= isset($_GET['utm_medium']) ? urldecode($_GET['utm_medium']) : '' ?>" />
                            <input type="hidden" name="utm[campaign]" value="<?= isset($_GET['utm_campaign']) ? urldecode($_GET['utm_campaign']) : '' ?>" />
                            <input type="hidden" name="utm[content]" value="<?= isset($_GET['utm_content']) ? urldecode($_GET['utm_content']) : '' ?>" />
                            <input type="hidden" name="utm[term]" value="<?= isset($_GET['utm_term']) ? urldecode($_GET['utm_term']) : '' ?>" />
                            <?php elseif(isset($_GET['gclid'])) : ?>
                            <input type="hidden" name="utm[source]" value="google" />
                            <input type="hidden" name="utm[medium]" value="cpc" />
                            <input type="hidden" name="utm[campaign]" value="undefined" />
                            <input type="hidden" name="utm[content]" value="gclid-<?= isset($_GET['gclid']) ? urldecode($_GET['gclid']) : '' ?>" />
                            <?php elseif(isset($_GET['yclid'])) : ?>
                            <input type="hidden" name="utm[source]" value="yandex" />
                            <input type="hidden" name="utm[medium]" value="cpc" />
                            <input type="hidden" name="utm[campaign]" value="undefined" />
                            <input type="hidden" name="utm[content]" value="yclid-<?= isset($_GET['yclid']) ? urldecode($_GET['yclid']) : '' ?>" />
                            <?php endif; ?>
                            <input type="hidden" name="requester[language]" value="<?= $langs[$lang][2] ?>" />
                            <input type="text" name="requester[name]" class="form-control" placeholder="<?= _("Имя") ?>" />
                            <input type="tel" name="requester[phone]" class="form-control" placeholder="<?= _("Телефон") ?>" />
                            <button type="submit" class="btn btn-blue goal-callback-request <?= $lang ?>-btn"><?= _("Заказать") ?></button>
                            <div class="help-block" style="display: none"><span class="text-danger"></span></div>
                        </form>

                        <p><?= _("Перезвоним в течение 10 минут.") ?><br/><?= _("Спросите менеджера о действующих скидках.") ?></p>
                    </div>
                </div>
            </div>
        </div>


    <?php if(defined('PARAM_SHOW_EUBANK_BONUS') && PARAM_SHOW_EUBANK_BONUS == 1): ?>
    <!-- Кэшбэк -->
    <div class="modal fade modal-transparent" id="cashback" tabindex="-1" role="dialog" aria-labelledby="cashback">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&nbsp;</button>
                </div>
                <div class="modal-body">

                    <h3><?= _("Как получить бонусы") ?></h3>
                    <p><?= _("Оплатите страховку на&nbsp;сайте eurasia36.kz картой Евразийского Банка.") ?></p>

                    <p>
                        <?= _('Если карты нет, закажите её&nbsp;с&nbsp;доставкой на&nbsp;дом:
                        <a href="https://pay.smartbank.kz/cards" target="_blank">https://pay.smartbank.kz/cards</a>.') ?>
                    </p>

                    <h3><?= _("Сколько я&nbsp;получу?") ?></h3>
                    <p>
                        <?= _("16% от&nbsp;стоимости страховки при оплате &laquo;Картой рассрочки&raquo;
                        и&nbsp;30% при оплате любой другой картой Евразийского Банка.") ?>
                    </p>

                    <h3><?= _("Как воспользоваться бонусами") ?></h3>
                    <p>
                        <?= _("Банк зачисляет бонусы в&nbsp;течение 3&nbsp;дней, сразу после списания оплаты за&nbsp;страховку.
                        После этого бонусами можно пользоваться&nbsp;&mdash; оплачивать покупки или переводить в&nbsp;&laquo;живые&raquo; деньги.") ?>
                    </p>
                    <p><?= _('Подробнее на&nbsp;<a href="https://bonus.eubank.kz" target="_blank">https://bonus.eubank.kz/</a>') ?></p>

                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script
                src="https://cdnjs.cloudflare.com/ajax/libs/TypeWatch/3.0.0/jquery.typewatch.min.js"
                integrity="sha256-FxujckmwH9va59KHuSlfQWni2g0vQ6Fr+jWNzfcsROc="
                crossorigin="anonymous"></script>

        <script>
            <?php require(__DIR__.'/bootstrap/js/bootstrap.min.js') ?>
            <?php require(__DIR__.'/js/jquery.maskedinput.min.js') ?>
            <?php require('./__form.js.php') ?>
            <?php require('./__check-policy.js.php') ?>
        </script>

        <?php include './__jivosite.php'; ?>

    </body>
</html>