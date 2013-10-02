define(function(require) {

    var url = require.toUrl;

    requirejs.config({
        paths: {
            'backbone': url('./libs/backbone/backbone'),
            'backbone.queryparams': url('./libs/backbone/backbone.queryparams'),

            'lodash': url('./libs/lodash'),

            'jquery': url('./libs/jquery/jquery-2.0.3'),
            'jquery.require': url('./libs/jquery/jquery.require'),
            'jquery.maskedinput': url('./libs/jquery/jquery.maskedinput'),

            'tpl': url('./utils/tpl'),
            'templateCompiler': url('./utils/template'),

            'i18n': url('./libs/require/i18n')
        },
        packages: [
            {
                name: 'moment',
                location: url('./libs/moment'),
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
});


