;(function($) {

    var host = 'https://eurasia36.kz';

    var widgetCss = $('<link href="' + host + '/css/widget-caller.css" rel="stylesheet" type="text/css" />')
    $('head').append(widgetCss);

    var container = $("#ogpo-widget-call");
    var header = $("<span class='ogpo__head'>Раcсчёт и&nbsp;покупка автостраховки онлайн</span>");
    var text = $("<span class='ogpo__text'>+ бонус до&nbsp;30% от&nbsp;суммы при&nbsp;оплате на&nbsp;сайте картой Евразийского банка</span>");
    var btn = $("<button class='ogpo__btn'>Расчитать за 2 минуты</button>");

    btn.click(function(e) {
        $(this).openPopup();
    });
    $("#ogpo-popup__close").click(function(e) {
        $(this).closePopup();
    });

    container.append(header);
    container.append(text);
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

        var leftCol = $('<div id="ogpo-popup__left">' +
                '<span id="ogpo-popup__header">Расчёт и&nbsp;заказ полиса ОГПО онлайн</span>' +
                '<span id="ogpo-popup__text"><strong>Получите бонус до&nbsp;30%</strong> от&nbsp;стоимости страховки при&nbsp;оплате на&nbsp;сайте картой Евразийского банка</span>' +
            '</div>');
        var rightCol = $('<div id="ogpo-popup__right"></div>');
        var widgetRoot = $('<div id="ogpo-widget" data-id="2"></div>');
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