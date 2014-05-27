define(function(require) {
    //requirements
    var Block = require('kit/core/block.deprecated'),
        isAllow = require('kit/isAllow/isAllow'),
        getText = require('kit/getText/getText'),
        dictionary = require('dictionary'),
        currentUserModel = require('models/currentUser'),
        cookie = require('cookies'),
        numeral = require('numeral'),
        router = require('router'),
        moment = require('moment');

    require('jquery');
    require('lodash');
    require('backbone');
    require('bower_components/momentjs/lang/ru');

    var app = {
        locale: 'root'
    };

    getText.dictionary = dictionary;

    moment.lang('ru');

    Block.prototype.dictionary = require('dictionary');

    numeral.language(app.locale, require('i18n!nls/numeral'));
    numeral.language(app.locale);

    var sync = Backbone.sync,
        isStarted,
        loading,
        routes,
        showCHTPN,
        showApiError,
        showJsError;

    showCHTPN = function(template) {
        if (LH.debugLevel > 0) {
            var html = $('<div></div>').html(template);
            if (LH.debugLevel >= 2) {
                html.find('.more-info').show();
            }
            html.find('.close').click(function(event){
                event.preventDefault();

                html.remove();
            });
            html.find('.show-more').click(function(event) {
                event.preventDefault();

                if (html.find('.more-info').is(':visible')) {
                    html.find('.more-info').hide();
                } else {
                    html.find('.more-info').show();
                }

                return false;
            });
            $('body').append(html);
        }
    };

    showApiError = function(response) {
        var chtpnTemplate = require('tpl!./chtpn.html');

        showCHTPN(chtpnTemplate({
            response: response
        }));
    };

    showJsError = function(error, file, line, col, errorObject) {
        var chtpnJSTemplate = require('tpl!./chtpnJS.html');

        showCHTPN(chtpnJSTemplate({
            errorText: error,
            file: file,
            line: line,
            errorObject: errorObject
        }));
    };

    window.onerror = showJsError;

    Backbone.sync = function(method, model, options) {
        var syncing = sync.call(this, method, model, _.extend({}, options, {
            headers: {
                Authorization: 'Bearer ' + cookie.get('token')
            }
        }));

        syncing.fail(function(res) {
            switch (res.status) {
                case 401:
                    if (isStarted) {
                        document.location.reload();
                    }
                    break;
                case 400:
                    break;
                case 0:
                    break;
                default:
                    showApiError(res);
                    break;
            }
        });

        return syncing;
    };

    $(document).on('click', '[href]', function(e) {
        e.stopPropagation();

        var $target = $(e.currentTarget);

        if ($target.data('navigate') !== false) {
            e.preventDefault();

            router.navigate($target.attr('href'));
        }
    });

    loading = currentUserModel.fetch();

    loading.done(function() {
        app.permissions = _.extend(currentUserModel.permissions.toJSON(), {
        });

        if (!currentUserModel.stores.length) {
            delete app.permissions['stores/{store}/orders'];
        }

        isAllow.permissions = app.permissions;

        routes = 'routes/authorized';
    });

    loading.fail(function() {
        routes = 'routes/unauthorized';
    });

    loading.always(function() {
        requirejs([
            routes
        ], function(routes) {
            isStarted = true;
            router.routes = routes;
            router.start();
        });
    });

    return app;
});