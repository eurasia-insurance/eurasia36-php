if (!host)
	host = window.location.protocol + '//' + window.location.hostname;

;(function($) {

    var widgetCss = $('<link href="' + host + '/css/widget-caller.css" rel="stylesheet" type="text/css" />')
    $('head').append(widgetCss);

    var container = $("#ogpo-widget-call");

    var headerTxt = 'Раcсчёт и&nbsp;покупка автостраховки онлайн';
    var textTxt = '+ бонус до&nbsp;30% от&nbsp;суммы при&nbsp;оплате на&nbsp;сайте картой Евразийского банка';
    var btnTxt = 'Расчитать за 2 минуты';
    if(container.data('lang') == 'kz') {
        headerTxt = 'Автосақтандыруды онлайн есептеу және сатып алу';
        textTxt = '+ сайтта Еуразиялық банктің картасымен төлеу кезінде сомадан 30%  дейін бонус';
        btnTxt = '2 минут ішінде есептеу';
    }

    var header = $("<span class='ogpo__head'>" + headerTxt + "</span>");
    var text = $("<span class='ogpo__text'>" + textTxt + "</span>");
    var btn = $("<button class='ogpo__btn'>" + btnTxt + "</button>");

    btn.click(function(e) {
        $(this).openPopup();
    });
    $("#ogpo-popup__close").click(function(e) {
        $(this).closePopup();
    });

    container.append(header);
    if(showBonus == 1) {
        container.append(text);
    }
    container.append(btn);


    var fader = $('<div id="ogpo-popup__fader"></div>');
    fader.click(function(e) {
            $(this).closePopup();

            e.preventDefault();
        });
    $('body').append(fader);


    $.fn.openPopup = function() {

        fader.show();

        var popup = $('<div id="ogpo-popup"></div>');
        var popupcloser = $('<a href="#" id="ogpo-popup__close"><img alt="" src="https://www.eubank.kz/images/wcl.png" /></a>')
            .click(function(e) {
                $(this).closePopup();

                e.preventDefault();
            });
        popup.append(popupcloser);


        var headTxt = 'Расчёт и&nbsp;заказ полиса ОГПО онлайн';
        var txtTxt = '<strong>Получите бонус до&nbsp;30%</strong> от&nbsp;стоимости страховки при&nbsp;оплате на&nbsp;сайте картой Евразийского банка';
        if(container.data('lang') == 'kz') {
            headTxt = 'АҚЖМС полисін онлайн есептеу және тапсырыс беру';
            txtTxt = 'Сайтта Еуразиялық банктің картасымен төлеу кезінде сақтандырудың құнынан <strong>30% дейін бонус алыңыз</strong>';
        }

        if(showBonus != 1) {
            txtTxt = '';
        }


        var leftCol = $('<div id="ogpo-popup__left">' +
                '<span id="ogpo-popup__header">' + headTxt + '</span>' +
                '<span id="ogpo-popup__text">' + txtTxt + '</span>' +
            '</div>');
        var rightCol = $('<div id="ogpo-popup__right"></div>');
        var widgetRoot = $('<div id="ogpo-widget" data-id="2"></div>');
        if(container.data('lang') != '') {
            widgetRoot.attr('data-lang', container.data('lang'));
        }

        var widgetScript = $('<script src="' + host + '/js/widget.js"></script>');

        popup
            .append(leftCol)
            .append(rightCol);
        rightCol.append(widgetRoot);

        $('body').append(popup);
        popup.css({top: ($(window).scrollTop() + 50)}).show();

        rightCol.append(widgetScript);

    };

    $.fn.closePopup = function() {
        $('#ogpo-popup').remove();
        fader.hide();
    };

}( jQuery ));