var host = 'https://webtest01.theeurasia.kz';

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

    var utm = '';
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
    }
};

if (window.addEventListener) {
    window.addEventListener("message", resizeIframe, false);
} else if (window.attachEvent) {
    window.attachEvent("onmessage", resizeIframe);
}