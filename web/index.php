<?php

header('Content-Type: text/html; charset=utf-8');

session_start();

$_SESSION['initMain'] = 1;

$language = 'ru';
$timestamp = time();

$tsstring = gmdate('D, d M Y H:i:s ', $timestamp) . 'GMT';
$etag = $language . $timestamp;

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

?>
<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <link rel="icon" type="image/png" href="/favicon.png" />
        <link rel="apple-touch-icon" href="/apple-touch-favicon.png" />

        <title>Обязательная страховка автомобиля (ОГПО) с бесплатной доставкой — страховая компания "Евразия"</title>

        <!-- Bootstrap core CSS -->
        <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
        <link href="/css/styles.css?<?= time() ?>" rel="stylesheet"/>

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
                        <span class="navbar-brand"><img src="/i/eurasia.svg" alt="Евразия" class="navbar-brand__logo" /></span>
                    </div>
                    <div id="navbar" class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li><span><a href="tel:88000800099" class="header-tel">8 800 080-00-99</a> <br class="visible-sm-inline"/>или <a href="tel:5678" class="header-tel">5678</a><br/><small>звонок бесплатный</small></span></li>
                            <li><a href="#info" data-toggle="modal" data-target="#delivery">Доставка и оплата</a></li>
                            <li><a href="/contacts.php">Адреса и телефоны</a></li>
                        </ul>
                    </div><!--/.nav-collapse -->
                </div>
            </nav>

            <div class="container">
                <span class="ogpo-vts">ОГПО ВТС</span> <a href="/order/casco/index.html" class="avto-kasko">Авто Каско</a>
                <h1>Обязательная автостраховка с&nbsp;бесплатной доставкой</h1>

                <div class="row pluses">
                    <div class="col-sm-4"><img src="/i/check.svg" alt="✓" /> Быстрое оформление полиса</div>
                    <div class="col-sm-4"><img src="/i/check.svg" alt="✓" /> Минимальная цена + скидка</div>
                    <div class="col-sm-4"><img src="/i/check.svg" alt="✓" /> Работаем c 1995 года</div>
                </div>
            </div>
        </header>

        <div class="container">

            <div class="row">
                <div class="col-sm-8">
                    <div class="main-form">
                        <form action="/price.php" method="post" class="form-horizontal" id="main-form">
                            <div class="form-group">
                                <label for="inputInn" class="col-sm-3 control-label"><strong>Ваш ИИН</strong></label>
                                <div class="col-sm-9">
                                    <input type="text" name="drivers[0][idNumber]" class="form-control inn" id="inputInn" placeholder="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label toggle-label">за рулем</label>
                                <div class="col-sm-9 radio-toggles">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="drivers[0][expirienceClass]" id="optionsRadios1" value="LESS2" />
                                            менее 2 лет
                                        </label>
                                    </div><!--
                                    --><div class="radio active">
                                        <label>
                                            <input type="radio" name="drivers[0][expirienceClass]" id="optionsRadios2" value="MORE2" checked />
                                            2 года и больше
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-9 col-sm-offset-3">
                                    <div class="checkbox">
                                        <input type="hidden" value="0"  name="drivers[0][privileger]" />
                                        <label>
                                            <input type="checkbox" value="1"  name="drivers[0][privileger]" />
                                            льготные условия для пенсионеров и инвалидов
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputAuto" class="col-sm-3 control-label"><strong>Ваше авто</strong></label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="inputAuto" name="vehicles[0][typeClass]">
                                        <option value="CAR" selected="selected">Легковая машина</option>
                                        <option value="MOTO">Мотоцикл</option>
                                        <option value="CARGO">Грузовик</option>
                                        <option value="TRAILER">Прицеп</option>
                                        <option value="BUS16">Автобус (до 16 пассажиров)</option>
                                        <option value="BUSOVR16">Автобус (более 16 пассажиров)</option>
                                        <option value="TRAM">Троллейбус или трамвай</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label toggle-label">год выпуска</label>
                                <div class="col-sm-9 radio-toggles">
                                    <div class="radio active">
                                        <label>
                                            <input type="radio" name="vehicles[0][ageClass]" id="optionsRadios1" value="UNDER7" checked>
                                            до <?= date('Y') - 7 ?>
                                        </label>
                                    </div><!--
                                    --><div class="radio">
                                        <label>
                                            <input type="radio" name="vehicles[0][ageClass]" id="optionsRadios2" value="OVER7">
                                            <?= date('Y') - 6 ?> и новее
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputReg" class="col-sm-3 control-label">на учёте в</label>
                                <div class="col-sm-9" id="inputReg">
                                    <select class="form-control region-select" name="vehicles[0][area]">
                                        <option value="GAST">Астане</option>
                                        <option value="GALM" selected="selected">Алматы</option>
                                        <option value="OAKM">Акмолинской области</option>
                                        <option value="OALM">Алматинской области</option>
                                        <option value="OAKT">Актюбинской области</option>
                                        <option value="OATY">Атырауской области</option>
                                        <option value="OVK">Восточно-Казахстанской области</option>
                                        <option value="OZHM">Жамбылской области</option>
                                        <option value="OZK">Западно-Казахстанской области</option>
                                        <option value="OKGD">Карагандинской области</option>
                                        <option value="OKST">Костанайской области</option>
                                        <option value="OKZL">Кызылординской области</option>
                                        <option value="OMNG">Мангистауской области</option>
                                        <option value="OPVL">Павлодарской области</option>
                                        <option value="OSK">Северо-Казахстанской области</option>
                                        <option value="OUK">Южно-Казахстанской области</option>
                                    </select>
                                </div>
                                <div class="col-sm-9 col-sm-offset-3 major-city__container">
                                    <div class="checkbox">
                                        <input type="hidden" name="vehicles[0][majorCity]" value="0" />
                                        <label>
                                            <input type="checkbox" name="vehicles[0][majorCity]" class="majorCity" checked="checked" value="1" />
                                            город областного значения
                                        </label>
                                    </div>
                                </div>
                                <div class="form-fader"></div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-9 col-sm-offset-3">
                                    <div class="checkbox">
                                        <input type="hidden" value="0" name="vehicles[0][temporaryEntry]" />
                                        <label>
                                            <input type="checkbox" value="1" name="vehicles[0][temporaryEntry]" class="temporary-entry" />
                                            временный въезд на территорию РК
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group main-form__btn-container">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button type="submit" class="btn btn-blue" disabled="disabled" id="how-much" onclick="goal('new_policy_calculate');">Рассчитать стоимость</button>
                                </div>
                            </div>
                        </form>
                        <form action="policy-request.php" method="post" class="form-horizontal order-form" id="order-form">
                            <input type="hidden" name="language" value="RUSSIAN" />
                            <div class="form-group">
                                <div class="col-sm-9 col-sm-offset-3">
                                    Стоимость вашей страховки

                                    <strong class="price">&nbsp;</strong>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-3 control-label">Ваше имя</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name" id="inputName" required="required" value="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPhone" class="col-sm-3 control-label">Телефон</label>
                                <div class="col-sm-9">
                                    <input type="tel" class="form-control" name="phone" id="inputPhone" placeholder="+7 (___) ___-__-__" required="required" value="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail" class="col-sm-3 control-label toggle-label">Эл. почта</label>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control" name="email" id="inputEmail" value="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <?php if(isset($_SESSION['policyRequest']) && $_SESSION['policyRequest'] > 5): ?>
                                    <div class="g-recaptcha" data-sitekey="6LdQBBsUAAAAADlPZcaxD-VkvvhC6-3K6SjVQ1_a"></div>
                                    <?php endif; ?>
                                    <button type="submit" class="btn btn-blue" onclick="goal('new_policy_request');">Заказать полис</button>
                                    <div>
                                        <strong id="result-msg" style="display: none">Спасибо. Мы получили вашу заявку</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9 order-hint">
                                    Перезвоним в течение 10 минут. Спросите менеджера о действующих скидках.
                                </div>
                                <div class="col-sm-offset-3 col-sm-9 one-more-form">
                                    <a href="/">Заполнить еще одну заявку</a>
                                </div>
                            </div>
                        </form>

                        <div class="more-details">
                            <div class="row">
                                <div class="col-sm-offset-3 col-sm-9">
                                    Добавить ещё<br class="visible-xs"/> <a href="" class="btn-additional add-driver">водителя</a><span class="add-car"><span class="add-car__or"> <br class="visible-sm"/>или</span>&nbsp;<a href="" class="btn-additional add-auto">автомобиль</a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 hidden-xs">
                    <!--<div class="bought-today"><div class="bought-today__number">14</div> полисов заказали<br/>на сайте сегодня</div>-->

                    <div class="rating">
                        <img src="/i/rating.png" alt="" />
                        У нас наивысший рейтинг среди частных финансовых компаний Казахстана: BB+/kzAA- (S&P, 2016).
                    </div>
                </div>
            </div>

            <div class="row about-company">
                <div class="col-sm-8 about-company__text">
                    <h2>О страховой компании «Евразия»</h2>

                    <p>«Евразия» основана в 1995 году, и работает в 75 странах мира.</p>

                    <p>Мы предлагаем все виды страхования для компаний и частных лиц.</p>

                    <div class="about-company__payments visible-xs">
                        <h2>10 000 тенге  в&nbsp;минуту</h2>
                        или 5,9 млн. тенге в день выплачивает «Евразия» своим клиентам
                    </div>

                    <p>Компании страхуют у нас сотрудников, транспорт, имущество и гражданско-правовую ответственность. Частные лица — автомобили (КАСКО и ОГПО ВТС), имущество и здоровье при выезде за рубеж.</p>

                    <p>Офисы компании работают в Алматы, Астане, Караганде и других крупных городах Казахстана.</p>

                    <p>В 2016 Standard & Poor’s присвоил «Евразии» наивысший среди частных финансовых компаний Казахстана рейтинг «ВВ+/kzAA-» («позитивный»).</p>

                    <p><a href="http://theeurasia.kz" target="_blank">theeurasia.kz</a></p>

                    <p class="about-company__links"><a href="#main-form" class="btn btn-blue">Узнать стоимость страховки</a> <span class="ili">или</span> <a href="" data-toggle="modal" data-target="#callback">заказать звонок</a></p>
                </div>
                <div class="col-sm-4 about-company__right-col hidden-xs">
                    <h2>10 000 тенге  в&nbsp;минуту</h2>
                    или 5,9 млн. тенге в день выплачивает «Евразия» своим клиентам
                </div>
            </div>

        </div><!-- /.container -->

        <footer>
            <div class="container footer">
                <div class="row">
                    <div class="col-sm-4 col-xs-12 footer__copyright">
                        © АО «Страховая компания «Евразия»
                    </div>
                    <div class="col-sm-3 col-xs-12 footer__box">
                        <a href="https://box.eurasia36.kz">Отправить нам документы</a>
                    </div>
                    <div class="col-sm-5 col-xs-12">
                        <div class="footer__grafica">
                            <a href="http://grafica.kz">
                                <img src="/i/grafica.svg" alt="Grafica" />
                                Сайт сделан<br/>в&nbsp;<span>студии&nbsp;«Графика»</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <div class="main-form__additional-driver" id="driverTemplate" style="display: none">
            <a href="" class="close-form">&nbsp;</a>
            <div class="form-group">
                <div class="col-xs-12">
                    <h3 class="driver-num">Ещё один водитель</h3>
                </div>
            </div>
            <div class="form-group">
                <label for="inputInn" class="col-sm-3 control-label"><strong>ИИН</strong></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control inn" name="drivers[][idNumber]" id="inputInn" placeholder="">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label toggle-label">за рулем</label>
                <div class="col-sm-9 radio-toggles">
                    <div class="radio">
                        <label>
                            <input type="radio" name="drivers[][expirienceClass]" value="LESS2" />
                            менее 2 лет
                        </label>
                    </div><!--
                    --><div class="radio active">
                        <label>
                            <input type="radio" name="drivers[][expirienceClass]" value="MORE2" checked />
                            2 года и больше
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-9 col-sm-offset-3">
                    <div class="checkbox">
                        <input type="hidden" value="0"  name="drivers[][privileger]" />
                        <label>
                            <input type="checkbox" value="1"  name="drivers[][privileger]" />
                            льготные условия для пенсионеров и инвалидов
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-form__additional-auto" id="autoTemplate" style="display: none">
            <a href="" class="close-form">&nbsp;</a>
            <div class="form-group">
                <div class="col-xs-12">
                    <h3 class="driver-num">Ещё один автомобиль</h3>
                </div>
            </div>
            <div class="form-group">
                <label for="inputAuto" class="col-sm-3 control-label"><strong>Ваше авто</strong></label>
                <div class="col-sm-9">
                    <select class="form-control" id="inputAuto" name="vehicles[][typeClass]">
                        <option value="CAR" selected="selected">Легковая машина</option>
                        <option value="MOTO">Мотоцикл</option>
                        <option value="CARGO">Грузовик</option>
                        <option value="TRAILER">Прицеп</option>
                        <option value="BUS16">Автобус (до 16 пассажиров)</option>
                        <option value="BUSOVR16">Автобус (более 16 пассажиров)</option>
                        <option value="TRAM">Троллейбус или трамвай</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label toggle-label">год выпуска</label>
                <div class="col-sm-9 radio-toggles">
                    <div class="radio active">
                        <label>
                            <input type="radio" value="UNDER7" name="vehicles[][ageClass]" checked />
                            до <?= date('Y') - 7 ?>
                        </label>
                    </div><!--
                    --><div class="radio">
                        <label>
                            <input type="radio" value="OVER7" name="vehicles[][ageClass]" />
                            <?= date('Y') - 6 ?> и новее
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputReg" class="col-sm-3 control-label">на учёте в</label>
                <div class="col-sm-9" id="inputReg">
                    <select class="form-control region-select" name="vehicles[][area]">
                        <option value="GAST">Астане</option>
                        <option value="GALM" selected="selected">Алматы</option>
                        <option value="OAKM">Акмолинской области</option>
                        <option value="OALM">Алматинской области</option>
                        <option value="OAKT">Актюбинской области</option>
                        <option value="OATY">Атырауской области</option>
                        <option value="OVK">Восточно-Казахстанской области</option>
                        <option value="OZHM">Жамбылской области</option>
                        <option value="OZK">Западно-Казахстанской области</option>
                        <option value="OKGD">Карагандинской области</option>
                        <option value="OKST">Костанайской области</option>
                        <option value="OKZL">Кызылординской области</option>
                        <option value="OMNG">Мангистауской области</option>
                        <option value="OPVL">Павлодарской области</option>
                        <option value="OSK">Северо-Казахстанской области</option>
                        <option value="OUK">Южно-Казахстанской области</option>
                    </select>
                </div>
                <div class="col-sm-9 col-sm-offset-3 major-city__container">
                    <div class="checkbox">
                        <input type="hidden" name="vehicles[][majorCity]" value="0" />
                        <label>
                            <input type="checkbox" name="vehicles[][majorCity]" class="majorCity" value="1" checked="checked" />
                            город областного значения
                        </label>
                    </div>
                </div>
                <div class="form-fader"></div>
            </div>
            <div class="form-group">
                <div class="col-sm-9 col-sm-offset-3">
                    <div class="checkbox">
                        <input type="hidden" value="0" name="vehicles[0][temporaryEntry]" />
                        <label>
                            <input type="checkbox" value="1" name="vehicles[0][temporaryEntry]" class="temporary-entry" />
                            временный въезд на территорию РК
                        </label>
                    </div>
                </div>
            </div>
        </div>


        <!-- Доставка и оплата -->
        <div class="modal fade modal-transparent" id="delivery" tabindex="-1" role="dialog" aria-labelledby="delivery">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">&nbsp;</button>
                        <h4 class="modal-title" id="myModalLabel">Доставка и оплата</h4>
                    </div>
                    <div class="modal-body">
                        <p class="delivery-time">Доставляем полисы только по г. Алматы<br/>с 9:00 до 19:00 в будние дни.</p>

                        <p>Самовывоз возможен в Алматы, Астане, Караганде, Усть-Каменогорске, Костанае, Актау, Павлодаре, Атырау и Актобе.</p>

                        <p>Принимаем заказы круглосуточно, без выходных и праздничных дней.</p>

                        <p class="delivery-time">Оплата наличными курьеру при получении.</p>
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
                        <h4 class="modal-title" id="myModalLabel">Заказ звонка</h4>
                    </div>
                    <div class="modal-body">

                        <form method="post" action="callback.php" id="callback-form">
                            <?php if(isset($_GET['utm_source']) && trim($_GET['utm_source']) != ''): ?>
                            <input type="hidden" name="utm[source]" value="<?= isset($_GET['utm_source']) ? $_GET['utm_source'] : '' ?>" />
                            <input type="hidden" name="utm[medium]" value="<?= isset($_GET['utm_medium']) ? $_GET['utm_medium'] : '' ?>" />
                            <input type="hidden" name="utm[campaign]" value="<?= isset($_GET['utm_campaign']) ? $_GET['utm_campaign'] : '' ?>" />
                            <input type="hidden" name="utm[content]" value="<?= isset($_GET['utm_content']) ? $_GET['utm_content'] : '' ?>" />
                            <input type="hidden" name="utm[term]" value="<?= isset($_GET['utm_term']) ? $_GET['utm_term'] : '' ?>" />
                            <?php endif; ?>

                            <input type="hidden" name="requester[language]" value="RUSSIAN" />
                            <input type="hidden" name="requester[name]" value="Не указано" />
                            <input type="tel" name="requester[phone]" class="form-control" placeholder="Телефон" />
                            <button type="submit" class="btn btn-blue" onclick="goal('new_callback_request');">Заказать</button>
                            <div class="help-block" style="display: none"><span class="text-danger"></span></div>
                        </form>

                        <p>Перезвоним в течение 10 минут.<br/>Спросите менеджера о действующих скидках.</p>
                    </div>
                </div>
            </div>
        </div>


        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="/bootstrap/js/bootstrap.min.js"></script>
        <script src="/js/jquery.maskedinput.min.js"></script>
        <script>

            var iinCache = {};

            // сюда запишем расчитанную стоимость
            var policyCost = null;

            var utm = {
                "source": '<?= isset($_GET['utm_source']) ? $_GET['utm_source'] : null ?>',
                "medium": '<?= isset($_GET['utm_medium']) ? $_GET['utm_medium'] : null ?>',
                "campaign": '<?= isset($_GET['utm_campaign']) ? $_GET['utm_campaign'] : null ?>',
                "content": '<?= isset($_GET['utm_content']) ? $_GET['utm_content'] : null ?>',
                "term": '<?= isset($_GET['utm_term']) ? $_GET['utm_term'] : null ?>'
            };

            $(function() {

                checkIin(".inn");

                checkMajorityCity(".region-select");
            });

            var driversCountRu = {
                2: 'Второй',
                3: 'Третий',
                4: 'Четвертый',
                5: 'Пятый',
            };
            var initialDriver = 1;
            var initialAuto = 1;



            $(".main-form").on('keyup', ".inn", function(e) {

                checkIin(this);
            });


            var iinNumber = false;
            var iinInput = false;

            function checkIin(input) {

                var $iin = $(input);

                if(iinInput != $iin.val()) {
                    $iin.next(".help-block").hide();

                    $("#how-much").prop('disabled', true);

                    iinInput = $iin.val();
                }

                if( $iin.val() && !$iin.val().match(/_/) ) {// заполнен ли иин полностью?

                    if(iinNumber != $iin.val()) {// введен новый иин?

                        iinNumber = $iin.val();

                        $iin.next(".help-block").remove();

                        $iin.addClass("loading");

                        $.ajax({
                            method: "POST",
                            url: "driver.php",
                            data:  { "iin" : $iin.val() },
                            dataType: "json"
                        })
                        .done(function( data ) {

                            iinCache[$iin.val().replace(/\s/g, '')] = data;

                            if(data.error != true) {

                                if(data.personal) {
                                    var $name = $('<div class="help-block text">' + data.personal.name + " " + data.personal.surename + ', Класс ' + data.insuranceClass.replace(/CLASS_/, '') + '</div>');
                                } else {
                                    var $name = $('<div class="help-block">Класс ' + data.insuranceClass.replace(/CLASS_/, '') + '</div>');
                                }

                                $("#how-much").prop('disabled', false);
                            } else {

                                $("#how-much").prop('disabled', true);

                                var $name = $('<div class="help-block text">' + '<span class="text-danger">Неверно введен ИИН. Пожалуйста, проверьте еще раз.</span>' + '</div>');

//                                console.log(data.code + " " + data.message);
                            }

                            $iin.removeClass("loading");

                            // показываем имя и класс или ошибку
                            $iin.after($name);
                            $iin.next(".help-block").fadeIn();
                        })
                        .fail(function(jqXHR, textStatus) {
                            $iin.removeClass("loading");
                            $("#how-much").prop('disabled', false);
                            console.log(textStatus);
                        });

                    } else {
                        $iin.next(".help-block").fadeIn();
                        $("#how-much").prop('disabled', false);

                        if(!$iin.next(".help-block").find('span').hasClass("text-danger")) {
                            $("#how-much").prop('disabled', false);
                        }
                    }
                }
            }

            // добавляем еще одно авто
            $(".add-auto").click(function(e) {
                e.preventDefault();

                ++initialAuto;

                var autoTemplate = $("#autoTemplate").clone();
                autoTemplate.attr('id', 'autoTemplate' + initialAuto);

                $(".main-form__btn-container").before(autoTemplate);

                autoTemplate.find(".driver-num").text(driversCountRu[initialAuto] + ' автомобиль');

                $(".add-driver").hide();
                $(".add-car__or").hide();

                if(initialAuto == 5) {
                    $(".more-details").hide();
                }

                autoTemplate.find("input, select").each(function() {
                    name = $(this).attr('name');
                    $(this).attr('name', name.replace(/\[\]/, '[' + (initialAuto - 1) + ']'));

                    if($(this).hasClass("region-select")) {
                        checkMajorityCity(this);
                    }
                });

                autoTemplate.slideDown();
            });

            // добавляем еще одного водителя
            $(".add-driver").click(function(e) {
                e.preventDefault();

                // отключаем кнопку расчета
                $("#how-much").prop('disabled', true);

                ++initialDriver;

                var driverTemplate = $("#driverTemplate").clone();
                driverTemplate.attr('id', 'driverTemplate' + initialDriver);

                $(".main-form__btn-container").before(driverTemplate);

                driverTemplate.find(".driver-num").text(driversCountRu[initialDriver] + ' водитель');

                $(".add-car").hide();

                if(initialDriver == 5) {
                    $(".more-details").hide();
                }

                driverTemplate.find("input").each(function() {
                    name = $(this).attr('name');
                    $(this).attr('name', name.replace(/\[\]/, '[' + (initialDriver - 1) + ']'));
                });

                driverTemplate.slideDown();

                $('.inn').mask('999 999 999 999',{placeholder:"_"});
            });


            // удаляем еще одного водителя или авто
            $(".main-form").on('click', '.close-form',  function(e) {
                e.preventDefault();

                if($(this).parent().hasClass('main-form__additional-auto')) {
                    --initialAuto;

                    if(initialAuto < 5) {
                        $(".more-details").show();
                    }

                    if(initialAuto == 1) {
                        $(".add-car__or").show();
                        $(".add-driver").show();
                    }
                } else {
                    --initialDriver;

                    if(initialDriver < 5) {
                        $(".more-details").show();
                    }

                    if(initialDriver == 1) {
                        $(".add-car").show();
                    }
                }

                $(this).parent().slideUp().remove();

                $("#how-much").prop('disabled', false);

                // проверяем заполненность иин полей
                $(".main-form .inn").each(function() {

                    var iinValue = $(this).val().replace(/\s/g, '');

                    if(iinValue.match(/_/) || iinValue == '') {// пусто
                        $("#how-much").prop('disabled', true);

                        return false;
                    } else if(iinCache.iinValue && iinCache.iinValue.error == true) {// с ошибкой
                        $("#how-much").prop('disabled', true);

                        return false;
                    }
                });

            });

            $(".main-form").on('click', '.radio-toggles .radio', function(e) {
                $(this).parent().find(".radio.active").removeClass("active");
                $(this).addClass("active");
            });

            $(".main-form").on('change', '.region-select', function() {
                checkMajorityCity(this);
            });

            // город областного значения для алматы и астаны всегда отмечен
            function checkMajorityCity(select) {
                if($(select).val().match(/^O/)) {
                    $(select).parent().next().find('[type=checkbox]').prop('checked', false);
                } else {
                    $(select).parent().next().find('[type=checkbox]').prop('checked', 'checked');
                }
            }

            // город областного значения для алматы и астаны всегда отмечен
            $(".main-form").on('change', '.majorCity', function(e) {
                if(!$(this).parents(".major-city__container").prev().find('select').val().match(/^O/)) {
                    $(this).prop("checked", "checked");
                }
            });

            // временный въезд
            $(".main-form").on('change', ".temporary-entry", function() {
                if($(this).is(":checked")) {
                    $(this).parents(".form-group").prev().find(".form-fader").show();
                } else {
                    $(this).parents(".form-group").prev().find(".form-fader").hide();
                }
            });

            // отправляем форму расчета страховки
            $("#main-form").submit(function(e) {
                e.preventDefault();

                $("#how-much").prop("disabled", true).text("Расчитываем стоимость...");

                $(".more-details").hide();

                $.ajax({
                    method: "POST",
                    url: $(this).attr("action"),
                    data:  $(this).serialize(),
                    dataType: "json"
                })
                .done(function( data ) {

//                    $(".main-form__btn-container").hide();

                    if(data.cost) {

                        price = Math.round(data.cost) + '';
                        price = price.replace(/(.)/g, function(c, i, o, a) {
                                    return (o == (a.length - 3) || o == (a.length - 6)) ? " " + c : c;
                                });

                        $("#how-much").prop("disabled", false).text("Расчитать стоимость");

                        $("#order-form").find(".price").text(price + " тенге");
                    } else {
                        $("#order-form").find(".price").parent().text("Сервис временно недоступен. Но вы можете оставить заявку.")
                    }

                    policyCost = data;

                    if(data.drivers[0].personal.name && data.drivers[0].personal.surename) {
                        $("#order-form #inputName").val(data.drivers[0].personal.name + " " + data.drivers[0].personal.surename);
                    }

                    $("#order-form").slideDown();

                    var target = $("#order-form");
                    if( target.length ) {
                        $('html, body').stop().animate({
                            scrollTop: target.offset().top
                        }, 500);
                    }
                })
                .fail(function(jqXHR, textStatus) {
                    console.log(textStatus);
                });
            });

            // отправляем заявку на полис
            $("#order-form").submit(function(e) {
                e.preventDefault();

                $("#result-msg").hide();

                $("#order-form button").text("Отправляем заявку...").prop("disabled", true);
                $(".more-details").slideUp();


                requester = {};
                formData = $(this).serializeArray();
                $(formData).each(function(i, e) {

                    if(e.value) {
                        requester[e.name] = e.value;
                    }
                });

                $.ajax({
                    method: "POST",
                    url: $(this).attr("action"),
                    data: { requester: requester<?= isset($_GET['utm_source']) && trim($_GET['utm_source']) != '' ? ', utm: utm' : '' ?>, policy: policyCost },
                    dataType: "json"
                })
                .done(function( data ) {

                    if(data.message == 'Success') {
                        $("#order-form button").fadeOut(function() {
                            $("#result-msg").text("Спасибо. Мы получили вашу заявку").removeClass('text-danger').fadeIn();
                        });

                        $(".one-more-form").fadeIn();
                    } else {

                        console.log(data.message)

                        $("#result-msg").html('Кажется, произошла ошибка. Пожалуйста, заполните форму ещё раз или <a href="" data-toggle="modal" data-target="#callback">закажите звонок</a> — мы сами перезвоним и всё рассчитаем.').addClass('text-danger').fadeIn();

                        $("#order-form button").text("Заказать полис").prop('disabled', false);
                    }
                })
                .fail(function(jqXHR, textStatus) {
                    console.log(textStatus);
                });

            });

            // отправляем форму обратной связи
            $("#callback-form").submit(function(e) {
                e.preventDefault();

                $form = $(this);

                $("#callback-form .form-control").addClass("loading");
                $("#callback-form button").prop("disabled", true);

                $.ajax({
                    method: "POST",
                    url: $(this).attr("action"),
                    data: $form.serialize(),
                    dataType: "json"
                })
                .done(function( data ) {

                    $("#callback-form .form-control").removeClass("loading");

                    if(data.message == 'Success') {

                        $($form).replaceWith("<strong class='callback-sent'>Спасибо, мы приняли вашу заявку.</strong>");

                    } else {

                        $form.find(".help-block span").text(data.message);
                        $form.find(".help-block").slideDown();

                        $("#callback-form button").prop("disabled", false);
                    }
                })
                .fail(function(jqXHR, textStatus) {
                    console.log(textStatus);
                });


            });

            $('.inn').mask('999 999 999 999',{placeholder:"_"});
            $('input[type=tel]').mask('+7 (999) 999-99-99',{placeholder:"_"});

            $('a[href^="#"]').on('click', function(event) {
                var target = $(this.getAttribute('href'));
                if( target.length ) {
                    event.preventDefault();
                    $('html, body').stop().animate({
                        scrollTop: target.offset().top - 45
                    }, 500);
                }
            });

            function goal(id) {
                <?php if (defined('PARAM_YM_ID')): ?>

                yaCounter<?= PARAM_YM_ID ?>.reachGoal(id);

                <?php endif; ?>
            }
        </script>

        <?php include './__jivosite.php'; ?>

    </body>
</html>
