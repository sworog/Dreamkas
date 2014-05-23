require.config({
    paths: {
        //bower_components
        'backbone': 'bower_components/backbone/backbone',
        'moment': 'bower_components/momentjs/moment',
        'cookies': 'bower_components/cookies-js/src/cookies',
        'form2js': 'bower_components/form2js/src/form2js',
        'numeral': 'bower_components/numeral/numeral',
        'sortable': 'bower_components/sortable/js/sortable',
        'uri': 'bower_components/uri.js/src',
        'when': 'bower_components/when/when',
        'lodash': 'kit/lodash/lodash',
        'jquery': 'bower_components/jquery/dist/jquery',
        'jquery.ui': 'bower_components/jqueryui/ui/minified/jquery-ui.min',
        'i18n': 'bower_components/requirejs-i18n/i18n',
        'jquery.maskedinput': 'bower_components/jquery-maskedinput/dist/jquery.maskedinput',

        //kit
        'templateCompiler': 'kit/templateCompiler/templateCompiler',
        'tpl': 'kit/templateLoader/templateLoader'
    },
    shim: {
        backbone: {
            deps: ['lodash', 'jquery'],
            exports: 'Backbone'
        },
        'kit/lhAutocomplete': ['jquery.ui'],
        'jquery.ui': ['jquery'],
        'jquery.maskedinput': ['jquery']
    },
    config: {
        //Set the config for the i18n
        //module ID
        i18n: {
            locale: 'root'
        }
    }
});