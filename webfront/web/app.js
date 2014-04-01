define(function(require) {
    //requirements
    var Block = require('kit/core/block.deprecated'),
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

    moment.lang('ru');

    Block.prototype.dictionary = require('dictionary');

    numeral.language(app.locale, require('i18n!nls/numeral'));
    numeral.language(app.locale);

    var sync = Backbone.sync,
        loading,
        routes;

    Backbone.sync = function(method, model, options) {
        var syncing = sync.call(this, method, model, _.extend({}, options, {
            headers: {
                Authorization: 'Bearer ' + cookie.get('token')
            }
        }));

        syncing.fail(function(res) {
            switch (res.status) {
                case 401:
                    if (app.isStarted) {
                        document.location.reload();
                    }
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
            'LH',
            'blocks/navigationBar/navigationBar',
            'blocks/page/page',
            'libs/lhAutocomplete'
        ], function(routes) {
            router.routes = routes;
            router.start();
        });
    });

    return app;
});