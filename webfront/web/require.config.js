require.config({
    paths: {
        'backbone.syphon': 'libs/backbone/backbone.syphon',
        'dictionary': 'dictionary',
        'jquery.ui': 'libs/jquery-ui/ui/minified/jquery-ui.min',
        'backbone': 'kit/libs/backbone/backbone',
        'backbone.queryparams': 'kit/libs/backbone/backbone.queryparams',

        'lodash': 'kit/libs/lodash',

        'jquery': 'kit/libs/jquery/jquery-2.0.3',
        'jquery.require': 'kit/libs/jquery/jquery.require',
        'jquery.maskedinput': 'kit/libs/jquery/jquery.maskedinput',

        'amd-loader': 'kit/libs/require/amd-loader',
        'tpl': 'kit/utils/templateLoader',
        'templateCompiler': 'kit/utils/templateCompiler',

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
        'backbone.queryparams': ['backbone'],
        'jquery.require': ['jquery'],
        'libs/lhAutocomplete': ['jquery.ui'],
        'jquery.ui': ['jquery']
    },
    config: {
        //Set the config for the i18n
        //module ID
        i18n: {
            locale: 'root'
        }
    }
});