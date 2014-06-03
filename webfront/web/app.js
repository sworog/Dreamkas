define(function(require) {
    //requirements
    var currentUserModel = require('models/currentUser.inst'),
        ErrorPage = require('pages/error/error'),
        cookie = require('cookies'),
        router = require('router'),
        $ = require('jquery'),
        _ = require('lodash');

    //deprecated
    require('LH');
    currentUserModel.stores = [];

    var isStarted,
        loading,
        routes;

    $.ajaxSetup({
        headers: {
            Authorization: 'Bearer ' + cookie.get('token')
        }
    });

    $(document).ajaxError(function(event, error) {
        switch (error.status) {
            case 401:
                isStarted && document.location.reload();
                break;
            case 400:
                break;
            case 0:
                break;
            default:
                if (window.PAGE instanceof ErrorPage){
                    window.PAGE.data.apiErrors.push(error);
                } else {
                    new ErrorPage({
                        data: {
                            apiErrors: [error]
                        }
                    });
                }
                break;
        }
    });

    window.onerror = function(error, file, line, col, errorObject) {

        var jsError = {
            file: file,
            line: line,
            data: errorObject
        };

        if (window.PAGE instanceof ErrorPage){
            window.PAGE.data.jsErrors.push(jsError);
        } else {
            new ErrorPage({
                data: {
                    jsErrors: [jsError]
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