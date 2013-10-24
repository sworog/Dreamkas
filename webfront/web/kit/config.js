define(function(require) {
    //requirements
    var cookie = require('./libs/cookie');

    var url = require.toUrl;

    requirejs.config({
        paths: {
            'backbone': url('./libs/backbone/backbone'),
            'backbone.queryparams': url('./libs/backbone/backbone.queryparams'),

            'lodash': url('./libs/lodash'),

            'jquery': url('./libs/jquery/jquery-2.0.3'),
            'jquery.require': url('./libs/jquery/jquery.require'),
            'jquery.maskedinput': url('./libs/jquery/jquery.maskedinput'),

            'amd-loader': url('./libs/require/amd-loader'),
            'tpl': url('./utils/templateLoader'),
            'templateCompiler': url('./utils/templateCompiler'),

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
        },
        config: {
            //Set the config for the i18n
            //module ID
            i18n: {
                locale: cookie.get('locale') || 'root'
            }
        }
    });
});


