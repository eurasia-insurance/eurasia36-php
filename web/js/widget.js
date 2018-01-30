var host = window.location.protocol + '//' + window.location.hostname;

var container = document.getElementById("ogpo-widget");
if(container) {
    var iframe = document.createElement('iframe');
    iframe.setAttribute('frameborder', 0);
    iframe.setAttribute('width', '100%');
    iframe.setAttribute('scrolling', "no");
    iframe.setAttribute('id', "iframe-widget");


    var lang = '?lang=ru';
    if(container.getAttribute('data-lang')) {
        lang = '?lang=' + container.getAttribute('data-lang');
    }

    var widgetId = null;
    if(container.getAttribute('data-id')) {
        lang += '&widgetId=' + container.getAttribute('data-id');

        widgetId = container.getAttribute('data-id');
    }

    var utm = '&utm_medium=widget';
    if(widgetId == 1) {
        utm += '&utm_source=theeurasia';
    } else if(widgetId == 2) {
        utm += '&utm_source=eubank'
    }

    if(location.search) {
        utm = '&' + location.search.substr(1);
    }

    iframe.setAttribute('src', host + "/widget.php" + lang + utm);


    container.appendChild(iframe);
}


var resizeIframe = function (event) {

    if (event.origin !== host) {
        return;
    }

    if(event.data.toString().match(/^\d+$/g)) {

        if (iframe) {
            iframe.style.height = event.data + "px";
        }
    } else if(event.data.toString().match(/^http/gi)) {
        window.setTimeout('document.location.href="' + event.data + '"', 3000);;
    } else if(event.data.toString() == 'reload') {
        iframe.src = iframe.src;
        document.location.hash = 'ogpo-widget';
    }
};

if (window.addEventListener) {
    window.addEventListener("message", resizeIframe, false);
} else if (window.attachEvent) {
    window.attachEvent("onmessage", resizeIframe);
}