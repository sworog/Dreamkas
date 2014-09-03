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

    require('madmin/js/jquery-ui');
    require('blocks/confirmLink/confirmLink');
    require('madmin/vendors/bootstrap/js/bootstrap');
    require('madmin/vendors/bootstrap-hover-dropdown/bootstrap-hover-dropdown');


    //modules setup

    getText.dictionary = require('i18n!nls/main');

    moment.lang('root', require('i18n!nls/moment'));
    moment.lang('root');

    numeral.language('root', require('i18n!nls/numeral'));
    numeral.language('root');

    var posWindowReference = null,
        isStarted,
        loading,
        routesUrl;

    var openPos = function() {
        if (posWindowReference == null || posWindowReference.closed) {
            /* if the pointer to the window object in memory does not exist
             or if such pointer exists but the window was closed */
            posWindowReference = window.open('/pos', 'pos', 'innerWidth=1000, innerHeight=800');
            /* then create it. The new window will be created and
             will be brought on top of any other window. */
        } else {
            posWindowReference.focus();
            /* else the window reference must exist and the window
             is not closed; therefore, we can bring it back on top of any other
             window with the focus() method. There would be no need to re-create
             the window or to reload the referenced resource. */
        }
    };


    //ajax setup

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


    //delegate document global events

    $(document)
        .on('click', '[href]', function(e) {
            e.stopPropagation();

            if (e.currentTarget.dataset.navigate !== '0') {
                e.preventDefault();

                router.navigate(e.currentTarget.getAttribute('href'), {
                    trigger: e.currentTarget.dataset.trigger !== '0',
                    replace: e.currentTarget.dataset.replace == '1'
                });
            }
        })
        .on('shown.bs.modal', '.modal', function() {
            $(this).find('[autofocus]').focus();
        })
        .on('click', '.page__posLink', function(e) {
            e.preventDefault();

            openPos();
        });


    //start app

    loading = currentUserModel.fetch();

    loading.done(function() {
        routesUrl = 'routes/authorized';
    });

    loading.fail(function() {
        routesUrl = 'routes/unauthorized';
    });

    loading.always(function() {
        requirejs([routesUrl], function(routes) {

            isStarted = true;
            _.extend(router.routes, routes);
            router.start();
        });
    });
});