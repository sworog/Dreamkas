require({
    baseUrl: '/',
    //urlArgs: 'bust=' +  (new Date()).getTime(),
    paths: {
        'underscore': 'kit/libs/lodash/lodash',
        'templateEngine': 'kit/utils/template',

        'backbone': 'kit/libs/backbone/backbone',
        'backbone.queryparams': 'kit/libs/backbone/backbone.queryparams',

        'jquery': 'kit/libs/jquery/jquery',
        'jquery.require': 'kit/libs/jquery/jquery.require',

        'tpl': 'kit/utils/tpl',
        'i18n': 'kit/libs/require/i18n'
    },
    packages: [
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
        'backbone.queryparams': ['backbone']
    }
});