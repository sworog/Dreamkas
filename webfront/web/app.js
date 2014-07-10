define(function(require) {
    //requirements
    var currentUserModel = require('models/currentUser.inst'),
        Error = require('blocks/error/error'),
        cookie = require('cookies'),
        router = require('router'),
        $ = require('jquery'),
        _ = require('lodash'),
        moment = require('moment'),
        getText = require('kit/getText/getText'),
        numeral = require('numeral');

    getText.dictionary = require('i18n!nls/main');

    moment.lang('root', require('i18n!nls/moment'));
    moment.lang('root');

    numeral.language('root', require('i18n!nls/numeral'));
    numeral.language('root');

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
            case 409:
                break;
            case 0:
                break;
            default:
                new Error({
                    apiError: error
                });
                break;
        }
    });

    window.onerror = function(error, file, line, col, errorObject) {

        var jsError = {
            file: file,
            line: line,
            data: errorObject
        };

        new Error({
            jsError: jsError
        });
    };

    $(document).on('click', '[href]', function(e) {
        e.stopPropagation();

        if (e.currentTarget.dataset.navigate !== '0') {
            e.preventDefault();

            router.navigate(e.currentTarget.href ? e.currentTarget.href.split(document.location.origin)[1] : e.currentTarget.getAttribute('href'));
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