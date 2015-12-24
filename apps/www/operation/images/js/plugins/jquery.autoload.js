/**
 * Created by Loin on 14-7-22.
 */
(function($) {
    var autoload = {
        counter: 0,
        resource: [],
        css: function(node, href) {
            var sheet = node.sheet, isLoaded = false;
            if(sheet) {
                try {
                    if(sheet.cssRules) {
                        isLoaded = true;
                    }
                } catch(e) {
                    if(e.name === 'NS_ERROR_DOM_SECURITY_ERR') {
                        isLoaded = true;
                    }
                }
            }

            setTimeout(function() {
                if(isLoaded) {
                    autoload.complete(href);
                } else {
                    autoload.css(node, href);
                }
            }, 20);
        },
        complete: function(url) {
            $.each(autoload.resource, function(index, value) {
                var urls = value.urls,
                    callback = value.callback;
                for(var i=0; i<urls.length; i++) {
                    if(url === urls[i]) {
                        autoload.counter++;
                    }
                }
                if(urls.length == autoload.counter) {
                    callback();
                }
            });
        },
        load: function(urls, callback) {
            autoload.counter = 0;
            if(!urls || ($.isArray(urls) && urls.length === 0)) {
                if(typeof callback === 'function') {
                    callback();
                }
            } else {
                if(!$.isArray(urls)) {
                    urls = [urls];
                }
                if(typeof callback === 'function') {
                    autoload.resource.push({urls: urls, callback: callback});
                }
                $.each(urls, function(index, value) {
                    var isCSS = ~value.indexOf('.css');
                    if (isCSS) {
                        if($.browser.msie) {
                            var element = window.document.createStyleSheet(value);
                            $(element).attr({
                                'media': 'all'
                            });
                        } else {
                            $('<link/>').attr({
                                'href': value,
                                'media': 'all',
                                'rel': 'stylesheet',
                                'type': 'text/css'
                            }).appendTo('head');
                        }
                        autoload.complete(value);
                    } else {
                        $.ajax({
                            url: value,
                            dataType: 'script',
                            success: function() {
                                autoload.complete(value);
                            }
                        });
                    }
                });
            }
        }
    };

    $.autoloader = {
        load: function(urls, callback) {
            autoload.load(urls, callback);
        }
    };
})(jQuery);