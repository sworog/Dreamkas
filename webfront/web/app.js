define(function(require) {
    //requirements
    var app = require('kit/core/app'),
        Block = require('kit/core/block'),
        currentUserModel = require('models/currentUser'),
        cookie = require('cookies'),
        numeral = require('libs/numeral'),
        moment = require('moment');

    require('jquery');
    require('lodash');
    require('backbone');
    require('bower_components/momentjs/lang/ru');

    app.locale = 'root';

    moment.lang('ru');

    Block.prototype.dictionary = require('dictionary');

    numeral.language(app.locale, require('i18n!nls/numeral'));
    numeral.language(app.locale);

    var sync = Backbone.sync,
        loading,
        routers;

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

    loading = currentUserModel.fetch();

    loading.done(function() {
        app.set('permissions', currentUserModel.permissions.toJSON());
        routers = 'routers/authorized';
    });

    loading.fail(function() {
        routers = 'routers/unauthorized';
    });

    loading.always(function() {
        app.start([
            'LH',
            'blocks/navigationBar/navigationBar',
            'blocks/page/page',
            'libs/lhAutocomplete',
            routers
        ]);
    });

    return app;
});