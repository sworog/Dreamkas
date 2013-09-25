require({
    baseUrl: '/',
    //urlArgs: 'bust=' +  (new Date()).getTime(),
    paths: {
        'backbone': 'kit/libs/backbone/backbone',
        'backbone.queryparams': 'kit/libs/backbone/backbone.queryparams',

        'lodash': 'kit/libs/lodash',

        'jquery': 'kit/libs/jquery/jquery-2.0.3',
        'jquery.require': 'kit/libs/jquery/jquery.require',
        'jquery.maskedinput': 'kit/libs/jquery/jquery.maskedinput',

        'tpl': 'kit/utils/tpl',
        'templateCompiler': 'kit/utils/template',

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
            deps: ['lodash', 'jquery'],
            exports: 'Backbone'
        },
        'backbone.queryparams': ['backbone']
    }
});