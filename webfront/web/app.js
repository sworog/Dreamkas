define(function(require) {
    //requirements
    var currentUserModel = require('models/currentUser/currentUser.inst'),
        Error = require('blocks/error/error'),
        cookies = require('cookies'),
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

    var isStarted,
        loading,
        routes;

    $.ajaxSetup({
        headers: {
            Authorization: 'Bearer ' + cookies.get('token')
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

            router.navigate(e.currentTarget.getAttribute('href'), {
                trigger: e.currentTarget.dataset.trigger !== '0',
                replace: e.currentTarget.dataset.replace !== '0'
            });
        }
    });

    $(document).on('click', '.confirmLink__trigger', function(e) {
        e.stopPropagation();

        var confirmLink = $(this).closest('.confirmLink');

        confirmLink.addClass('confirmLink_active');
    });

    $(document).on('click', function(e) {
        var confirmLink_active = $('.confirmLink_active').not($(e.target).closest('.confirmLink_active'));

        confirmLink_active.removeClass('confirmLink_active');
    });

    $(document).on('shown.bs.modal', '.modal', function(){
        $(this).find('[autofocus]').focus();
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