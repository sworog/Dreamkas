window.KIT = {};

require.config({
    baseUrl: '/',
    paths: {
        'backbone': 'kit/libs/backbone/backbone',
        'backbone.syphon': 'kit/libs/backbone/backbone.syphon',
        'underscore': 'kit/libs/underscore/underscore',

        'jquery': 'kit/libs/jquery/jquery',
        'jquery.require': 'kit/libs/jquery/jquery.require',
        'jquery.mod': 'kit/libs/jquery/jquery.mod',
        'jquery.maskedinput': 'kit/libs/jquery/jquery.maskedinput',

        'tpl': 'kit/utils/tpl',
        'i18n': 'kit/libs/require/i18n'
    },
    packages: [
        {
            name: 'nls',
            location: 'kit/nls'
        },
        {
            name: 'kit',
            location: 'kit'
        },
        {
            name: 'moment',
            location: 'kit/libs/moment',
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
