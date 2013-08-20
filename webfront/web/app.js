define(function(require) {
    //requirements
    var KitApp = require('kit/app'),
        Backbone = require('backbone'),
        _ = require('underscore'),
        currentUserModel = require('models/currentUser'),
        cookie = require('utils/cookie'),
        Page = require('blocks/page/page');

    require('LH');
    require('libs/lhAutocomplete');

    var sync = Backbone.sync,
        isAppStarted = false;

    Backbone.sync = function(method, model, options) {
        var syncing = sync.call(this, method, model, _.extend({}, options, {
            headers: {
                Authorization: 'Bearer ' + cookie.get('token')
            }
        }));

        syncing.fail(function(res) {
            switch (res.status) {
                case 401:
                    if (isAppStarted) {
                        document.location.reload();
                    }
                    break;
            }
        });

        return syncing;
    };

    var App = KitApp.extend({
        initialize: function(){
            var loading = currentUserModel.fetch(),
                routers;

            $(function() {
                new Page();
            });

            loading.done(function() {
                routers = 'routers/authorized';
            });

            loading.fail(function() {
                routers = 'routers/unauthorized';
            });

            loading.always(function() {

                require([routers], function() {

                    Backbone.history.start({
                        pushState: true
                    });

                    isAppStarted = true;
                });
            });
        }
    });

    return new App();
});