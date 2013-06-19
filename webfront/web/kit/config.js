require.config({
    baseUrl: '/',
    paths: {
        'backbone': 'kit/_libs/backbone/backbone',
        'backbone.syphon': 'kit/_libs/backbone/backbone.syphon',
        'underscore': 'kit/_libs/underscore/underscore',

        'jquery': 'kit/_libs/jquery/jquery',
        'jquery.require': 'kit/_libs/jquery/jquery.require',
        'jquery.mod': 'kit/_libs/jquery/jquery.mod',
        'jquery.maskedinput': 'kit/_libs/jquery/jquery.maskedinput',

        'tpl': 'kit/_libs/require/tpl',
        'i18n': 'kit/_libs/require/i18n'
    },
    packages: [
        {
            name: 'kit',
            location: 'kit'
        },
        {
            name: 'moment',
            location: 'kit/_libs/moment',
            main: 'moment'
        }
    ],
    shim: {
        backbone: {
            deps: ['underscore', 'jquery'],
            exports: 'Backbone'
        },
        underscore: {
            exports: '_'
        },
        'jquery.maskedinput': ['jquery'],
        'backbone.syphon': ['backbone']
    }
});

if (window.jQuery) {
    define('jquery', function() {
        return window.jQuery;
    });
}

if (window._) {
    define('underscore', function() {
        return window._;
    });
}

if (window.Backbone) {
    define('backbone', function() {
        return window.Backbone;
    });
}

if (window.moment) {
    define('moment', function() {
        return window.moment;
    });
}
