require.config({
    paths: {
        'backbone': 'bower_components/backbone/backbone',
        'moment': 'bower_components/momentjs/moment',
        'cookies': 'bower_components/cookies-js/src/cookies',
        'jquery': 'bower_components/jquery/dist/jquery',
        'form2js': 'bower_components/form2js/src/form2js',
        'numeral': 'bower_components/numeral/numeral',
        'templateCompiler': 'kit/templateCompiler/templateCompiler',
        'amd-loader': 'bower_components/amd-loader/amd-loader',
        'sortable': 'bower_components/sortable/js/sortable',
        'datepicker': 'madmin/vendors/bootstrap-datepicker/js/bootstrap-datepicker',
        'typehead': 'bower_components/typeahead.js/dist/typeahead.bundle',

        //plugins
        'i18n': 'bower_components/requirejs-i18n/i18n',
        'ejs': 'kit/templateLoader/templateLoader'
    },

    map: {
        '*': {
            uri: 'bower_components/uri.js/src/URI',
            router: 'kit/router/router',
            lodash: 'bower_components/lodash/dist/lodash',
            underscore: 'bower_components/lodash/dist/lodash'
        }
    },

    shim: {
        datepicker: ['jquery'],
        typehead: ['jquery']
    }

});