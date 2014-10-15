require.config({
    paths: {
        'moment': 'bower_components/momentjs/moment',
        'jquery': 'bower_components/jquery/dist/jquery',
        'select2': 'bower_components/select2/select2',
        'select2_locale_ru': 'bower_components/select2/select2_locale_ru'
    },

    map: {
        '*': {
            'templateCompiler': 'kit/templateCompiler/templateCompiler',
            'uri': 'bower_components/uri.js/src/URI',
            'router': 'kit/router/router',
            'lodash': 'bower_components/lodash/dist/lodash',
            'underscore': 'bower_components/lodash/dist/lodash',
            'rivets': 'bower_components/rivets/dist/rivets',
            'backbone': 'bower_components/backbone/backbone',
            'cookies': 'bower_components/cookies-js/src/cookies',
            'form2js': 'bower_components/form2js/src/form2js',
            'numeral': 'bower_components/numeral/numeral',
            'amd-loader': 'bower_components/amd-loader/amd-loader',
            'sortable': 'bower_components/sortable/js/sortable',
            'datepicker': 'madmin/vendors/bootstrap-datepicker/js/bootstrap-datepicker',
            'typeahead': 'bower_components/typeahead.js/dist/typeahead.bundle',

            //plugins
            'i18n': 'bower_components/requirejs-i18n/i18n',
            'ejs': 'kit/templateLoader/templateLoader'
        }
    },

    shim: {
        'select2_locale_ru': ['select2']
    }

});