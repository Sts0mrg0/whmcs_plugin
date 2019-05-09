(function () {
    var mlt = 'Multiple embed instances not allowed';
    if(window['mw-domain-search-iframe-embed']) {
        console.warn(mlt);
        return;
    }
    window['mw-domain-search-iframe-embed'] = true;

    if(document.querySelectorAll('script[src*="/microweber_addon/"]').length > 1) {
        console.warn(mlt);
        return;
    }

    var $ = function(a){ return document.querySelector(a) };

    var script = $('script[src*="/microweber_addon/"]');

    if (!script) {
        return;
    }

    var path = script.src.split('/');
        path.pop()
        path = path.join('/');
    var $params = script.src.split('?')[1] || '';
    _params = $params.split('&');
    var params = {};
    for( var i = 0; i < _params.length; i++ ) {
        var item = _params[i].split('=');
        params[decodeURIComponent(item[0]).trim()] = decodeURIComponent(item[1]).trim();
    }

    var iframe = document.createElement("iframe");
    iframe.src = path + '/index.php?' + $params;
    iframe.id = 'domain-search-iframe';
    iframe.style.width = "100%";
    iframe.style.height = "100px";
    iframe.scrolling = 'no';
    iframe.frameBorder = 0;
    iframe.allowtransparency = 1;

    script.parentNode.insertBefore(iframe, script.nextSibling);

    addEventListener('message', function (e) {
        if (typeof e.data === 'string' && e.data.indexOf('documentHeight:') > -1) {
            var height = e.data.split('documentHeight:')[1];
            $('#domain-search-iframe').style.height = height + 'px';
        }
    }, false);
})();
