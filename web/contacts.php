<?php

header('Content-Type: text/html; charset=utf-8');

require './api/EurasiaAPI.php';

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



$url = 'pos/all/ru';

$data = '{}';

$offices = EurasiaAPI::request($url, $data, 'get');
$offices = json_decode($offices, true);

?><!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <link rel="icon" type="image/png" href="/favicon.png" />
        <link rel="apple-touch-icon" href="/apple-touch-favicon.png" />

        <title>Евразия</title>

        <!-- Bootstrap core CSS -->
        <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
        <link href="/css/styles.css?<?= time() ?>" rel="stylesheet"/>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <?php if($_SERVER['HTTP_HOST'] != 'eurasia.loc'): ?>
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-84812378-1', 'auto');
            ga('send', 'pageview');

        </script>

        <script type="text/javascript">
            (function (d, w, c) {
                (w[c] = w[c] || []).push(function() {
                    try {
                        w.yaCounter39882375 = new Ya.Metrika({
                            id:39882375,
                            clickmap:true,
                            trackLinks:true,
                            accurateTrackBounce:true,
                            webvisor:true
                        });
                    } catch(e) { }
                });

                var n = d.getElementsByTagName("script")[0],
                    s = d.createElement("script"),
                    f = function () { n.parentNode.insertBefore(s, n); };
                s.type = "text/javascript";
                s.async = true;
                s.src = "https://mc.yandex.ru/metrika/watch.js";

                if (w.opera == "[object Opera]") {
                    d.addEventListener("DOMContentLoaded", f, false);
                } else { f(); }
            })(document, window, "yandex_metrika_callbacks");
        </script>
        <?php endif; ?>
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
                        <a class="navbar-brand" href="/"><img src="/i/eurasia.svg" alt="Евразия" class="navbar-brand__logo" /></a>
                    </div>
                    <div id="navbar" class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li><span><a href="tel:88000800099" class="header-tel">8 800 080-00-99</a> <br class="visible-sm"/>или <a href="tel:5678" class="header-tel">5678</a><br/><small>звонок бесплатный</small></span></li>
                            <li><a href="#info" data-toggle="modal" data-target="#delivery">Доставка и оплата</a></li>
                            <li><span>Адреса и телефоны</span></li>
                        </ul>
                    </div><!--/.nav-collapse -->
                </div>
            </nav>

        </header>

        <section>
            <div class="container addresses">

                <div class="row">
                    <div class="col-xs-12">
                        <h1>Адреса и телефоны</h1>

                        <h4 style="line-height: 1.5">
                            8 800 080-00-99 — бесплатная горячая линия по Казахстану<br/>
                            5678 — бесплатно с мобильного<br/>
                            Эл. почта: <a href="mailto:info@theeurasia.kz">info@theeurasia.kz</a>
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
    //                die(json_encode($error));
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
                        © АО «Страховая компания «Евразия»
                    </div>
<!--                    <div class="col-sm-6">
                        <div class="footer__grafica">
                            <a href="http://grafica.kz">
                                <img src="/i/grafica.svg" alt="Grafica" />
                                Сайт сделан<br/>в&nbsp;<span>студии&nbsp;«Графика»</span>
                            </a>
                        </div>
                    </div>-->
                </div>
            </div>
        </footer>


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

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="/bootstrap/js/bootstrap.min.js"></script>

        <?php if($_SERVER['HTTP_HOST'] != 'eurasia.loc'): ?>

        <!-- BEGIN JIVOSITE CODE {literal} -->
        <script type='text/javascript'>
        (function(){ var widget_id = 'ZXsNf2bpSa';var d=document;var w=window;function l(){
        var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);}if(d.readyState=='complete'){l();}else{if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();</script>
        <!-- {/literal} END JIVOSITE CODE -->

        <?php endif; ?>
    </body>
</html>
