require.config({
    paths: {
        'ractive': 'bower_components/ractive/ractive',
        'backbone': 'bower_components/backbone/backbone',
        'moment': 'bower_components/momentjs/moment',
        'cookies': 'bower_components/cookies-js/src/cookies',
        'form2js': 'bower_components/form2js/src/form2js',
        'numeral': 'bower_components/numeral/numeral',
        'sortable': 'bower_components/sortable/js/sortable',
        'when': 'bower_components/when/when',
        'lodash': 'kit/lodash',
        'underscore': 'kit/underscore',
        'router': 'bower_components/router/router',
        'jquery': 'bower_components/jquery/dist/jquery',
        'jquery.ui': 'bower_components/jqueryui/ui/minified/jquery-ui.min',
        'jquery.maskedinput': 'bower_components/jquery-maskedinput/dist/jquery.maskedinput',
        'templateCompiler': 'kit/templateCompiler/templateCompiler',
        'amd-loader': 'bower_components/amd-loader/amd-loader',

        //plugins
        'i18n': 'bower_components/requirejs-i18n/i18n',
        'rv': 'bower_components/requirejs-ractive/rv',
        'tpl': 'kit/templateLoader/templateLoader'
    },
    shim: {
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