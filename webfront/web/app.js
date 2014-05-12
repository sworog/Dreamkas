define(function(require) {
    //requirements
    var Block = require('kit/core/block.deprecated'),
        currentUserModel = require('models/currentUser'),
        cookie = require('cookies'),
        numeral = require('numeral'),
        router = require('kit/router/router'),
        moment = require('moment');

    require('jquery');
    require('lodash');
    require('backbone');
    require('bower_components/momentjs/lang/ru');

    var app = {
        locale: 'root'
    };

    moment.lang('ru');

    Block.prototype.dictionary = require('dictionary');

    numeral.language(app.locale, require('i18n!nls/numeral'));
    numeral.language(app.locale);

    var sync = Backbone.sync,
        isStarted,
        loading,
        routes,
        showCHTPN;

    showCHTPN = function(response) {
        if (LH.debugLevel > 0) {
            var chtpnTemplate = require('tpl!./chtpn.html');
            var html = $('<div></div>').html(chtpnTemplate({
                response: response
            }));
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
                default:
                    showCHTPN(res);
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

        routes = 'routes/authorized';
    });

    loading.fail(function() {
        routes = 'routes/unauthorized';
    });

    loading.always(function() {
        requirejs([
            routes,
            'blocks/page/page',
            'libs/lhAutocomplete'
        ], function(routes) {
            isStarted = true;
            router.routes = routes;
            router.start();
        });
    });

    return app;
});