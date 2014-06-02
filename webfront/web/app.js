define(function(require) {
    //requirements
    var currentUserModel = require('models/currentUser.inst'),
        ErrorPage = require('pages/error/error'),
        config = require('config'),
        cookie = require('cookies'),
        router = require('router'),
        $ = require('jquery'),
        _ = require('lodash');

    window.LH = _.extend({}, config);

    var isStarted,
        loading,
        routes;

    $.ajaxSetup({
        headers: {
            Authorization: 'Bearer ' + cookie.get('token')
        }
    });

    $(document).ajaxError(function(e, res) {
        switch (res.status) {
            case 401:
                isStarted && document.location.reload();
                break;
            case 400:
                break;
            case 0:
                break;
            default:
                window.PAGE && window.PAGE.set('error', res);
                break;
        }
    });

    window.onerror = function(error) {
        if (window.PAGE) {
            window.PAGE.set('error', error);
        } else {
            new ErrorPage({
                data: {
                    error: error
                }
            });
        }
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
            _.extend(router.routes, routes);
            router.start();
        });
    });
});