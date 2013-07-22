define(function(require) {
    //requirements
    var currentUserModel = require('models/currentUser'),
        userPermissionsModel = require('models/userPermissions'),
        cookie = require('utils/cookie');

    require('LH');

    var sync = Backbone.sync,
        isAppStarted = false;

    Backbone.sync = function(method, model, options) {
        return sync.call(this, method, model, _.extend({}, options, {
            headers: {
                Authorization: 'Bearer ' + cookie.get('token')
            },
            error: function(res) {
                switch (res.status) {
                    case 401:
                        if (isAppStarted) {
                            document.location.reload();
                        }
                        break;
                }
            }
        }));
    };

    var loading = $.when(currentUserModel.fetch(), userPermissionsModel.fetch()),
        routers;

    $(function() {
        var router = new Backbone.Router();

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
        require([routers], function() {
            Backbone.history.start({
                pushState: true
            });
            isAppStarted = true;
        });
    });
});