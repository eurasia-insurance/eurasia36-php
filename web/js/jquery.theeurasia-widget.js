if (!host)
	host = window.location.protocol + '//' + window.location.hostname;

;(function($) {

    var container = $("#ogpo-widget-container");
    container.addClass("row");

    var headerTxt = 'Расчёт и&nbsp;заказ полиса ОГПО онлайн';
    var textTxt = ' — по&nbsp;бесплатным телефонам <a href="tel:88000800099" class="ogpo-widget__tel">8 800 080-00-99</a>&nbsp;или&nbsp;<a href="tel:5678" class="ogpo-widget__tel">5678</a>';
    if(container.data('lang') == 'kz') {
        headerTxt = 'АҚЖМС полисін онлайн есептеу жəне тапсырыс беру';
        textTxt = ' — <a href="tel:88000800099" class="ogpo-widget__tel">8 800 080-00-99</a> немесе <a href="tel:5678" class="ogpo-widget__tel">5678</a> тегін телефондары бойынша';
    }

    if(typeof showPhones !== 'undefined' && showPhones === true) {// ws down. show phones

        var widgetCss = $('<link href="' + host + '/css/theeurasia-container.css" rel="stylesheet" type="text/css" />');
        $('head').append(widgetCss);

        var phonesHeader = $('<div class="col-xs-12 col-md-6 col-md-offset-3 ogpo-header text-center">' + headerTxt + textTxt + '</div>');

        container.append(phonesHeader);
    } else {

        var leftCol = $('<div class="col-xs-12 col-md-3 ogpo-header">' + headerTxt + '</div>');
        container.append(leftCol);

        var rightCol = $('<div class="col-xs-12 col-md-8 col-md-offset-1"></div>');
        var widgetRoot = $('<div id="ogpo-widget" data-id="1"></div>');
        if(container.data('lang') != '') {
            widgetRoot.attr('data-lang', container.data('lang'));
        }
        var widgetScript = $('<script src="' + host + '/js/widget.js"></script>');

        rightCol
            .append(widgetRoot)
            .append(widgetScript);

        container.append(rightCol);
    }



}( jQuery ));