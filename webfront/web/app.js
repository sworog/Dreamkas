define(function(require) {
    //requirements
    var currentUserModel = require('models/currentUser'),
        cookie = require('utils/cookie'),
        Page = require('blocks/page/page');

    require('LH');

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

    var loading = currentUserModel.fetch(),
        routers;

    $(function() {
        var router = new Backbone.Router();

        new Page({
            el: document.getElementById("page")
        });

        $(document).on('click', '[href]', function(e) {
            e.preventDefault();
            router.navigate($(this).attr('href'), {
                trigger: true
            });
        });
    });

    loading.done(function() {
        routers = 'routers/authorized';
    });

    loading.fail(function() {
        routers = 'routers/unauthorized';
    });

    loading.always(function() {

        if (currentUserModel.stores && currentUserModel.stores.length) {
            window.history.replaceState({}, document.title, '/stores/' + currentUserModel.stores.at(0).id);
        }

        require([routers], function() {

            Backbone.history.start({
                pushState: true
            });

            isAppStarted = true;
        });
    });
});