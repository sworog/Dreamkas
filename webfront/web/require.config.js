require.config({
    baseUrl: '/',
    paths: {

        //deprecated libs
        'backbone.syphon': 'libs/backbone/backbone.syphon',
        'jquery.ui': 'libs/jquery-ui/ui/minified/jquery-ui.min',
        'backbone.queryparams': 'kit/libs/backbone/backbone.queryparams',
        'jquery.require': 'kit/libs/jquery/jquery.require',
        'jquery.maskedinput': 'kit/libs/jquery/jquery.maskedinput',

        //libs
        'backbone': 'bower_components/backbone/backbone',
        'moment': 'bower_components/momentjs/moment',
        'numeral': 'bower_components/numeral/numeral',
        'sortable': 'bower_components/sortable/js/sortable',
        'uri': 'bower_components/uri.js/src',
        'when': 'bower_components/when/when',
        'lodash': 'bower_components/lodash/dist/lodash',
        'jquery': 'bower_components/jquery/dist/jquery',
        'templateCompiler': 'kit/utils/templateCompiler',

        //requirejs plugins
        'amd-loader': 'bower_components/amd-loader/amd-loader',
        'tpl': 'kit/utils/templateLoader',
        'i18n': 'bower_components/requirejs-i18n/i18n'
    },
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