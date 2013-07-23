require(
    {
        baseUrl: '/',
        paths: {
            'underscore': 'libs/underscore/underscore.min',

            'backbone': 'libs/backbone/backbone',
            'backbone.syphon': 'libs/backbone/backbone.syphon',
            'backbone.queryparams': 'libs/backbone/backbone.queryparams',

            'jquery': 'libs/jquery/jquery.min',
            'jquery-ui': 'libs/jquery-ui/ui/minified/jquery-ui.min',
            'jquery.require': 'libs/jquery/jquery.require',
            'jquery.mod': 'libs/jquery/jquery.mod',
            'jquery.maskedinput': 'libs/jquery/jquery.maskedinput',

            'tpl': 'kit/utils/tpl',
            'i18n': 'libs/require/i18n'
        },
        shim: {
            'backbone': ['underscore', 'jquery'],
            'backbone.queryparams': ['backbone'],
            'backbone.syphon': ['backbone'],

            'jquery-ui': ['jquery'],
            'jquery.require': ['jquery'],
            'jquery.maskedinput': ['jquery'],
            'libs/lhAutocomplete': ['jquery-ui']
        },
        packages: [
            {
                name: 'moment',
                location: 'libs/moment',
                main: 'moment'
            },
            {
                name: 'nls',
                location: 'nls'
            }
        ],
        config: {
            //Set the config for the i18n
            //module ID
            i18n: {
                locale: LH.locale === 'auto' ? undefined : LH.locale
            }
        }
    }, ['libs/libs'], function(){
        require(['app']);
    });