require.config({
    paths: {
        //deprecated libs
        'backbone.syphon': 'libs/backbone.syphon',
        'jquery.ui': 'libs/jquery-ui/ui/minified/jquery-ui.min',
        'backbone.queryparams': 'libs/backbone.queryparams',
        'jquery.require': 'libs/jquery.require',
        'jquery.maskedinput': 'libs/jquery.maskedinput',

        //bower_components
        'backbone': 'bower_components/backbone/backbone',
        'moment': 'bower_components/momentjs/moment',
        'cookies': 'bower_components/cookies-js/src/cookies',
        'form2js': 'bower_components/form2js/src/form2js',
        'numeral': 'bower_components/numeral/numeral',
        'sortable': 'bower_components/sortable/js/sortable',
        'uri': 'bower_components/uri.js/src',
        'when': 'bower_components/when/when',
        'lodash': 'bower_components/lodash/dist/lodash',
        'jquery': 'bower_components/jquery/dist/jquery',

        //kit
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